import { expect, test } from '@playwright/test';
import type { Page } from '@playwright/test';

// The form normalizes names to title case on blur.
const projectName = `E2e Project ${Date.now()}`;
const updatedName = `${projectName} Updated`;

const responsiveWidths = [390, 768, 1440];

async function expectResponsiveProjectGrid(page: Page) {
    for (const width of responsiveWidths) {
        await page.setViewportSize({ width, height: 900 });
        const grid = page.getByTestId('projects-grid');
        await expect(grid).toBeVisible();

        const columns = await grid.evaluate(element =>
            getComputedStyle(element).gridTemplateColumns.split(' ').filter(Boolean).length
        );
        const expectedMaximum = width >= 1280 ? 3 : width >= 768 ? 2 : 1;
        expect(columns).toBeLessThanOrEqual(expectedMaximum);
        await expect(page.getByRole('button', { name: `Editar ${projectName}` })).toBeVisible();
        await expect(page.getByRole('button', { name: `Eliminar ${projectName}` })).toBeVisible();
        expect(await page.evaluate(() => document.documentElement.scrollWidth <= window.innerWidth)).toBe(true);
    }
}

test.beforeEach(async ({ page }) => {
    await page.goto('/login');
    await page.locator('#email').fill('e2e@nuwesoft.test');
    await page.locator('#password').fill('E2E-password-2026!');
    await page.getByRole('button', { name: /iniciar|ingresar|login/i }).click();
    await expect(page).toHaveURL(/\/dashboard/, { timeout: 90_000 });
});

test('admin can create, edit, change status and delete a project', async ({ page }) => {
    await page.goto('/dashboard/projects');
    await page.getByRole('button', { name: /crear.*proyecto|nuevo.*proyecto/i }).click();

    await page.locator('#name').fill(projectName);
    await page.locator('#category').selectOption('web');
    await page.locator('#desc').fill('Proyecto creado por la prueba E2E.');
    await page.getByRole('button', { name: /guardar/i }).click();
    await expect(page.locator('#name')).toBeHidden();
    await page.reload();
    await expect(page.getByText(projectName, { exact: true })).toBeVisible();
    await expectResponsiveProjectGrid(page);
    await page.setViewportSize({ width: 1440, height: 900 });

    await page.getByRole('button', { name: `Editar ${projectName}` }).click();
    await page.locator('#name').fill(updatedName);
    await page.getByRole('button', { name: /guardar/i }).click();
    await expect(page.locator('#name')).toBeHidden();
    await page.reload();
    await expect(page.getByText(updatedName, { exact: true })).toBeVisible();

    await page.getByRole('button', { name: 'Desactivar proyecto' }).click();
    await page.getByRole('button', { name: /sí, desactivar/i }).click();
    await expect(page.getByText(/inactivo/i).first()).toBeVisible();

    await page.getByRole('button', { name: `Eliminar ${updatedName}` }).click();
    await page.getByRole('button', { name: /sí, eliminar/i }).click();
    await expect(page.getByText(updatedName, { exact: true })).toHaveCount(0);
});
