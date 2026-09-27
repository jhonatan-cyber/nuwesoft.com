import { expect, test, type Page, type Response } from '@playwright/test';

type CspViolation = {
    directive: string;
    blockedURI: string;
    sourceFile: string;
    lineNumber: number;
};

// Páginas públicas que no necesitan contenido previo en la base de datos.
const publicPages = ['/', '/servicios', '/portafolio', '/blog', '/contacto', '/privacidad', '/terminos'];

// Páginas del panel autenticado (rutas GET de routes/web.php bajo auth+admin).
// Cubren los distintos tipos de vista: home, CRUDs, formularios y lectura de logs.
const dashboardPages = [
    '/dashboard',
    '/dashboard/projects',
    '/dashboard/messages',
    '/dashboard/posts',
    '/dashboard/technologies',
    '/dashboard/testimonials',
    '/dashboard/subscribers',
    '/dashboard/settings',
    '/dashboard/logs',
    '/profile',
];

const CSP_REFUSAL = /Content Security Policy|Refused to (execute|apply|load|create)/i;

// Hosts que de verdad sirven scripts: beacon de Cloudflare Web Analytics y los
// assets/extensiones que PostHog carga en diferido. Cualquier otro host externo
// en script-src es un CDN muerto (p. ej. cdn.jsdelivr.net, que solo servía imágenes).
const allowedScriptHosts = new Set([
    'static.cloudflareinsights.com',
    'us-assets.i.posthog.com',
    'eu-assets.i.posthog.com',
]);

function externalScriptHosts(directive: string): string[] {
    return directive
        .split(/\s+/)
        // Los entornos locales añaden hosts con comodín (`http://localhost:*`),
        // solo se auditan hosts reales de producción.
        .filter((token) => /^https?:\/\//.test(token) && !/:\*$/.test(token))
        .map((token) => token.replace(/^https?:\/\//, '').split('/')[0])
        .filter((host) => !/^(localhost|127\.0\.0\.1)(:\d+)?$/.test(host));
}

/**
 * Instala dos trampas antes de que se ejecute cualquier script de la página:
 *  - el evento `securitypolicyviolation` (cubre todos los directivos, no solo consola)
 *  - los mensajes de consola que el navegador emite al bloquear un recurso
 *
 * Devuelve el array de mensajes de consola capturado durante la navegación.
 */
function trapCspViolations(page: Page): string[] {
    const consoleRefusals: string[] = [];

    page.on('console', (message) => {
        if (CSP_REFUSAL.test(message.text())) {
            consoleRefusals.push(message.text());
        }
    });

    page.addInitScript(() => {
        const store: CspViolation[] = [];
        (window as unknown as { __cspViolations: CspViolation[] }).__cspViolations = store;

        document.addEventListener('securitypolicyviolation', (event) => {
            store.push({
                directive: (event as SecurityPolicyViolationEvent).violatedDirective,
                blockedURI: (event as SecurityPolicyViolationEvent).blockedURI,
                sourceFile: (event as SecurityPolicyViolationEvent).sourceFile ?? '',
                lineNumber: (event as SecurityPolicyViolationEvent).lineNumber,
            });
        });
    });

    return consoleRefusals;
}

function directiveValue(csp: string, name: string): string {
    for (const raw of csp.split(';')) {
        const directive = raw.trim();
        if (directive.split(' ')[0] === name) {
            return directive;
        }
    }

    return '';
}

/** Entra en el panel con el usuario administrador usado por la suite E2E. */
async function login(page: Page): Promise<void> {
    await page.goto('/login');
    await page.locator('#email').fill('e2e@nuwesoft.test');
    await page.locator('#password').fill('E2E-password-2026!');
    await page.getByRole('button', { name: /iniciar|ingresar|login/i }).click();
    await expect(page).toHaveURL(/\/dashboard/, { timeout: 90_000 });
}

/** Todas las asertiones de CSP compartidas entre páginas públicas y autenticadas. */
async function assertNoCspBlockage(
    page: Page,
    path: string,
    response: Response | null,
    consoleRefusals: string[],
): Promise<void> {
    expect(response?.status(), `${path} debe responder 200`).toBe(200);

    // La política existe, exige nonce y ya no admite 'unsafe-inline' en script-src.
    const csp = response?.headers()['content-security-policy'] ?? '';
    expect(csp, `Falta la cabecera Content-Security-Policy en ${path}`).not.toBe('');

    const scriptSrc = directiveValue(csp, 'script-src');
    expect(scriptSrc, `script-src ausente en ${path}`).not.toBe('');
    expect(scriptSrc, `script-src sin nonce en ${path}`).toContain("'nonce-");
    expect(scriptSrc, `script-src aún permite 'unsafe-inline' en ${path}`).not.toContain("'unsafe-inline'");

    // Solo hosts de la lista blanca: sin CDNs muertos en script-src.
    const unexpectedHosts = externalScriptHosts(scriptSrc).filter((host) => !allowedScriptHosts.has(host));
    expect(
        unexpectedHosts,
        `script-src permite hosts no autorizados en ${path}: ${unexpectedHosts.join(', ')}`,
    ).toEqual([]);

    // La aplicación arranca de verdad: si un script crítico se bloquea, Vue no monta.
    await expect
        .poll(
            () => page.evaluate(() => document.getElementById('app')?.childElementCount ?? 0),
            { timeout: 30_000, message: `Vue no montó en ${path}` },
        )
        .toBeGreaterThan(0);

    // Al menos un script del documento lleva el nonce (Vite/Ziggy/anti-flash de tema).
    expect(
        await page.evaluate(() => document.querySelectorAll('script[nonce]').length),
        `No hay scripts con nonce en ${path}`,
    ).toBeGreaterThan(0);

    // Deja cargar chunks asíncronos, fuentes e imágenes antes de leer las violaciones.
    // Con tope: una conexión abierta (analytics, websockets) no debe alargar el test.
    await page.waitForLoadState('networkidle', { timeout: 5_000 }).catch(() => undefined);
    await page.waitForTimeout(500);

    const violations = (await page.evaluate(
        () => (window as unknown as { __cspViolations?: CspViolation[] }).__cspViolations ?? [],
    )) as CspViolation[];

    // Prioriza los scripts: es lo que rompería la página, con mensaje directo.
    const scriptViolations = violations.filter((violation) => violation.directive.startsWith('script'));
    expect(
        scriptViolations,
        `CSP bloqueó scripts en ${path}:\n${JSON.stringify(scriptViolations, null, 2)}`,
    ).toEqual([]);

    expect(
        violations,
        `CSP bloqueó recursos en ${path}:\n${JSON.stringify(violations, null, 2)}`,
    ).toEqual([]);

    expect(
        consoleRefusals,
        `El navegador rechazó recursos en ${path}:\n${consoleRefusals.join('\n')}`,
    ).toEqual([]);
}

test.describe('Content Security Policy', () => {
    for (const path of publicPages) {
        test(`no bloquea scripts inline en ${path}`, async ({ page }) => {
            const consoleRefusals = trapCspViolations(page);

            const response = await page.goto(path, { waitUntil: 'load' });
            await assertNoCspBlockage(page, path, response, consoleRefusals);
        });
    }
});

test.describe('Content Security Policy (dashboard autenticado)', () => {
    let consoleRefusals: string[] = [];

    test.beforeEach(async ({ page }) => {
        // La trampa se instala antes del login para no perder ninguna violación.
        consoleRefusals = trapCspViolations(page);
        await login(page);
    });

    for (const path of dashboardPages) {
        test(`no bloquea scripts inline en ${path}`, async ({ page }) => {
            // Solo se audita la página objetivo: se descarta lo ocurrido durante el login.
            consoleRefusals.length = 0;

            const response = await page.goto(path, { waitUntil: 'load' });
            await assertNoCspBlockage(page, path, response, consoleRefusals);
        });
    }
});
