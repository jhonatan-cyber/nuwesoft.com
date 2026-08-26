import { existsSync } from 'node:fs';
import { Configuration, MemoryStorage, RequestQueue } from 'crawlee';
import { chromium } from 'playwright-core';
import { assertSafeNetworkUrl } from './url-safety.mjs';

const chunks = [];
for await (const chunk of process.stdin) chunks.push(chunk);

const input = JSON.parse(Buffer.concat(chunks).toString('utf8'));
const browserCandidates = process.platform === 'win32'
    ? ['C:\\Program Files\\Google\\Chrome\\Application\\chrome.exe', 'C:\\Program Files (x86)\\Microsoft\\Edge\\Application\\msedge.exe']
    : ['/usr/bin/google-chrome', '/usr/bin/chromium', '/usr/bin/chromium-browser'];
const playwrightExecutable = chromium.executablePath();
const executablePath = process.env.CHROME_PATH
    || browserCandidates.find(existsSync)
    || (existsSync(playwrightExecutable) ? playwrightExecutable : null);

if (!executablePath) throw new Error('No se encontró Chrome/Chromium. Configura CHROME_PATH en el servidor.');

const browser = await chromium.launch({
    executablePath,
    headless: true,
    args: ['--no-sandbox', '--disable-dev-shm-usage', '--disable-gpu'],
});
const context = await browser.newContext({ viewport: { width: 1440, height: 900 }, deviceScaleFactor: 1, locale: 'es-BO' });
const page = await context.newPage();
const requestedOrigin = new URL(input.url).origin;

await assertSafeNetworkUrl(input.url, requestedOrigin);
await page.route('**/*', async route => {
    try {
        await assertSafeNetworkUrl(
            route.request().url(),
            route.request().isNavigationRequest() ? requestedOrigin : null,
        );
        await route.continue();
    } catch {
        await route.abort('blockedbyclient');
    }
});

page.setDefaultNavigationTimeout(25000);
page.setDefaultTimeout(12000);

const configuration = new Configuration({ storageClient: new MemoryStorage(), purgeOnStart: true });
const requestQueue = await RequestQueue.open(`capture-${Date.now()}`, { config: configuration });
const captures = [];
const forbiddenAction = /(logout|signout|cerrar[-_/]?sesion|delete|destroy|remove|eliminar|payment|checkout|pagar)/i;
const maxCaptures = Number.parseInt(process.env.CAPTURE_MAX_PAGES || '20', 10);

const safeCaptureName = (url, fallback) => {
    const pathname = new URL(url).pathname;
    if (pathname === '/' || pathname === '') return 'inicio';
    return pathname.split('/').filter(Boolean).join('-').replace(/[^a-z0-9-]+/gi, '-') || fallback;
};

const capture = async (capturePage, name) => {
    await capturePage.evaluate(async () => {
        const delay = milliseconds => new Promise(resolve => setTimeout(resolve, milliseconds));
        const scrollables = [...document.querySelectorAll('*')].filter(element => {
            const style = window.getComputedStyle(element);
            return element.scrollHeight > element.clientHeight + 40 && /(auto|scroll)/.test(style.overflowY);
        });

        for (const element of [document.scrollingElement, ...scrollables].filter(Boolean)) {
            const maximum = Math.min(element.scrollHeight, 30000);
            for (let top = 0; top < maximum; top += 700) {
                element.scrollTop = top;
                await delay(35);
            }
            element.scrollTop = 0;
        }

        document.documentElement.style.setProperty('height', 'auto', 'important');
        document.documentElement.style.setProperty('overflow', 'visible', 'important');
        document.body.style.setProperty('height', 'auto', 'important');
        document.body.style.setProperty('overflow', 'visible', 'important');

        for (const element of scrollables) {
            if (element.scrollHeight <= 30000) {
                element.style.setProperty('height', `${element.scrollHeight}px`, 'important');
                element.style.setProperty('max-height', 'none', 'important');
                element.style.setProperty('overflow-y', 'visible', 'important');
            }
        }

        window.scrollTo(0, 0);
        await delay(250);
    });
    const image = await capturePage.screenshot({ type: 'jpeg', quality: 72, fullPage: true });
    captures.push({ name, url: capturePage.url(), mime_type: 'image/jpeg', base64: image.toString('base64') });
};

try {
    await page.goto(input.url, { waitUntil: 'domcontentloaded' });
    await page.waitForTimeout(1500);
    if (input.fields.length > 0) {
        await capture(page, 'login');

    const typeOffsets = {};
    for (const field of input.fields) {
        const selector = `input[type="${field.type}"]`;
        const offset = typeOffsets[field.type] || 0;
        typeOffsets[field.type] = offset + 1;
        const locator = page.locator(selector).nth(offset);
        if (await locator.count() && field.value != null) await locator.fill(String(field.value));
    }

    const submit = page.locator('button[type="submit"], input[type="submit"]').first();
    if (!await submit.count()) throw new Error('No se encontró el botón para iniciar sesión.');

    const loginUrl = page.url();
    await submit.click();
    await Promise.race([
        page.waitForURL(url => url.href !== loginUrl, { timeout: 30000 }),
        page.locator('input[type="password"]:visible').waitFor({ state: 'hidden', timeout: 30000 }),
    ]).catch(() => null);
    await page.waitForLoadState('networkidle', { timeout: 15000 }).catch(() => null);

    if (await page.locator('input[type="password"]:visible').count() && page.url() === loginUrl) {
        throw new Error('No se pudo iniciar sesión. Verifica las credenciales o si el sitio solicita CAPTCHA, MFA u otro paso.');
    }

    }

    const authenticatedOrigin = requestedOrigin;
    await requestQueue.addRequest({ url: page.url() });

    while (captures.length < maxCaptures) {
        const request = await requestQueue.fetchNextRequest();
        if (!request) break;

        try {
            const requestedUrl = new URL(request.url);
            if (requestedUrl.origin !== authenticatedOrigin || forbiddenAction.test(requestedUrl.pathname + requestedUrl.search)) {
                await requestQueue.markRequestHandled(request);
                continue;
            }

            if (page.url() !== request.url) {
                await page.goto(request.url, { waitUntil: 'domcontentloaded' });
                await page.waitForTimeout(1000);
            }

            const currentUrl = new URL(page.url());
            if (currentUrl.origin !== authenticatedOrigin || /login|signin/i.test(currentUrl.pathname)) {
                await requestQueue.markRequestHandled(request);
                continue;
            }

            await capture(page, safeCaptureName(page.url(), `modulo-${captures.length}`));
            const links = await page.locator('a[href]').evaluateAll(nodes => nodes.map(node => node.href));
            for (const link of links) {
                try {
                    const candidate = new URL(link);
                    candidate.hash = '';
                    if (candidate.origin === authenticatedOrigin && !forbiddenAction.test(candidate.pathname + candidate.search)) {
                        await requestQueue.addRequest({ url: candidate.href });
                    }
                } catch {}
            }

            await requestQueue.markRequestHandled(request);
        } catch {
            await requestQueue.markRequestHandled(request);
        }
    }

    process.stdout.write(JSON.stringify({ captures, final_url: page.url(), pages_captured: captures.length }));
} catch (error) {
    process.stderr.write(error instanceof Error ? error.message : 'Falló la sesión del navegador.');
    process.exitCode = 1;
} finally {
    await context.close();
    await browser.close();
}
