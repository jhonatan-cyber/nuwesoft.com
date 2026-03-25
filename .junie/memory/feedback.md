[2026-03-24 13:31] - Updated by Junie
{
    "TYPE": "correction",
    "CATEGORY": "package manager choice",
    "EXPECTATION": "Use Bun instead of npm for installs and script runs in this project.",
    "NEW INSTRUCTION": "WHEN giving JS install or run commands THEN use Bun (bun install, bun run)."
}

[2026-03-24 13:37] - Updated by Junie
{
    "TYPE": "correction",
    "CATEGORY": "root route output",
    "EXPECTATION": "The root path (/) should display the custom landing page, not the default Laravel + Vite starter page.",
    "NEW INSTRUCTION": "WHEN root shows Laravel/Vite starter THEN render landing via Inertia and remove welcome.blade.php."
}

[2026-03-24 13:43] - Updated by Junie
{
    "TYPE": "correction",
    "CATEGORY": "root route output",
    "EXPECTATION": "The root path must display the custom landing page via Inertia, not the default Laravel/Vite starter.",
    "NEW INSTRUCTION": "WHEN \"/\" shows Laravel/Vite starter THEN delete welcome.blade.php and configure root route to Inertia::render('Welcome')."
}

[2026-03-24 14:46] - Updated by Junie
{
    "TYPE": "correction",
    "CATEGORY": "missing logo",
    "EXPECTATION": "The n8n card should display its official logo/icon correctly in the stack.",
    "NEW INSTRUCTION": "WHEN n8n logo not visible THEN fix asset URL and add fallback icon."
}

[2026-03-24 14:49] - Updated by Junie
{
    "TYPE": "correction",
    "CATEGORY": "n8n logo source",
    "EXPECTATION": "Use the n8n icon from the provided LobeHub icons link instead of other sources.",
    "NEW INSTRUCTION": "WHEN displaying n8n logo in UI THEN use LobeHub icons link as primary and DevIcon as fallback."
}

[2026-03-24 15:03] - Updated by Junie
{
    "TYPE": "correction",
    "CATEGORY": "backend logos display",
    "EXPECTATION": "Show official logos for backend technologies, similar to how Laravel’s logo is shown.",
    "NEW INSTRUCTION": "WHEN adding backend technologies to UI THEN display their official logos beside the names."
}

[2026-03-24 15:05] - Updated by Junie
{
    "TYPE": "correction",
    "CATEGORY": "card logo placement",
    "EXPECTATION": "Keep the descriptive text in the card body and move the technology logos to the card header.",
    "NEW INSTRUCTION": "WHEN editing service/technology cards UI THEN place logos in card header; keep body text unchanged."
}

[2026-03-24 15:06] - Updated by Junie
{
    "TYPE": "correction",
    "CATEGORY": "tech logo grouping",
    "EXPECTATION": "Add more logos per technology but keep each card showing only its own category logos without mixing categories.",
    "NEW INSTRUCTION": "WHEN editing service/technology cards UI THEN display only logos matching the card category; avoid cross-category mixing"
}

[2026-03-24 15:13] - Updated by Junie
{
    "TYPE": "correction",
    "CATEGORY": "tooltips and shadcn",
    "EXPECTATION": "Tooltips should be visible and the project should use shadcn/ui for system components.",
    "NEW INSTRUCTION": "WHEN building UI components or tooltips THEN use shadcn/ui Tooltip and components."
}

[2026-03-24 15:46] - Updated by Junie
{
    "TYPE": "negative",
    "CATEGORY": "redesign request",
    "EXPECTATION": "User is dissatisfied with the current design and wants a full system redesign using @.agents\\skills\\find-skills.",
    "NEW INSTRUCTION": "WHEN a redesign of the system is requested THEN use @.agents\\skills\\find-skills to drive the redesign plan."
}

[2026-03-24 16:28] - Updated by Junie
{
    "TYPE": "correction",
    "CATEGORY": "dark mode text contrast",
    "EXPECTATION": "Text content must be readable in Dark mode with proper contrast.",
    "NEW INSTRUCTION": "WHEN dark mode is active THEN apply dark:text-white or high-contrast text classes."
}

[2026-03-24 16:37] - Updated by Junie
{
    "TYPE": "correction",
    "CATEGORY": "language consistency i18n",
    "EXPECTATION": "The UI should not mix English and Spanish; provide a language system to view the site fully in Spanish or English.",
    "NEW INSTRUCTION": "WHEN adding or updating UI text THEN use i18n locale files (es,en) and avoid hardcoding."
}

[2026-03-24 17:15] - Updated by Junie
{
    "TYPE": "correction",
    "CATEGORY": "navbar translation",
    "EXPECTATION": "Navbar menu items should translate via i18n using the updated root keys, not show untranslated text.",
    "NEW INSTRUCTION": "WHEN i18n keys are renamed or moved THEN update Navbar to use new keys and verify translations."
}

[2026-03-24 17:58] - Updated by Junie
{
    "TYPE": "correction",
    "CATEGORY": "hero title casing",
    "EXPECTATION": "The Hero headline in Spanish should be exactly \"forjamos impacto alpha\" in lowercase.",
    "NEW INSTRUCTION": "WHEN updating ES hero headline THEN set to \"forjamos impacto alpha\" lowercase exactly."
}

[2026-03-24 18:00] - Updated by Junie
{
    "TYPE": "correction",
    "CATEGORY": "hero slogan content",
    "EXPECTATION": "The homepage slogan must not include the word \"alpha\".",
    "NEW INSTRUCTION": "WHEN setting or updating Hero slogan THEN avoid the word \"alpha\" in all locales."
}

[2026-03-24 18:00] - Updated by Junie
{
    "TYPE": "correction",
    "CATEGORY": "hero slogan content",
    "EXPECTATION": "The homepage slogan must not include the word \"alpha\" in any locale.",
    "NEW INSTRUCTION": "WHEN updating the Hero slogan text THEN remove any occurrence of the word \"alpha\"."
}

[2026-03-24 18:54] - Updated by Junie
{
    "TYPE": "negative",
    "CATEGORY": "scroll/click lock",
    "EXPECTATION": "The UI must remain interactive; no persistent overlay or body lock should block scroll or clicks.",
    "NEW INSTRUCTION": "WHEN UI is unscrollable or clicks blocked THEN remove body overflow-hidden and any overlay; enable pointer-events."
}

[2026-03-24 21:21] - Updated by Junie
{
    "TYPE": "negative",
    "CATEGORY": "dashboard blocked UI",
    "EXPECTATION": "The dashboard must remain fully interactive with no overlays or locks blocking actions.",
    "NEW INSTRUCTION": "WHEN dashboard is unclickable or unscrollable THEN remove overlays and body locks; restore pointer-events and scrolling."
}

[2026-03-25 00:39] - Updated by Junie
{
    "TYPE": "correction",
    "CATEGORY": "project card images",
    "EXPECTATION": "Project management cards should display the uploaded project images (at least the first/cover image) instead of appearing blank.",
    "NEW INSTRUCTION": "WHEN project card renders THEN display first project.images image_url as thumbnail; add placeholder fallback."
}

[2026-03-25 00:42] - Updated by Junie
{
    "TYPE": "correction",
    "CATEGORY": "dashboard redesign requirements",
    "EXPECTATION": "User wants to see all project images (not just the first) and requests a redesigned dashboard using @.agents with a floating navbar and floating sidebar.",
    "NEW INSTRUCTION": "WHEN redesigning dashboard THEN show all project images and add floating navbar and sidebar."
}

[2026-03-25 00:49] - Updated by Junie
{
    "TYPE": "negative",
    "CATEGORY": "dashboard redesign dissatisfaction",
    "EXPECTATION": "User did not like the current dashboard redesign and wants a different design applied.",
    "NEW INSTRUCTION": "WHEN user rejects a dashboard design THEN propose 2-3 dashboard options and use @.agents\\skills\\find-skills."
}

[2026-03-25 00:59] - Updated by Junie
{
    "TYPE": "correction",
    "CATEGORY": "dashboard layout width",
    "EXPECTATION": "Dashboard modules/cards should occupy the full available width without narrow containers.",
    "NEW INSTRUCTION": "WHEN updating dashboard modules layout THEN make modules full-width and remove max-w constraints."
}

[2026-03-25 01:01] - Updated by Junie
{
    "TYPE": "correction",
    "CATEGORY": "theme/lang controls styling",
    "EXPECTATION": "Theme and language toggle buttons should match the new floating dashboard design and integrate into the navbar.",
    "NEW INSTRUCTION": "WHEN editing theme or language controls in dashboard THEN use shadcn/ui buttons matching floating navbar glass style."
}

[2026-03-25 01:46] - Updated by Junie
{
    "TYPE": "correction",
    "CATEGORY": "i18n translation key",
    "EXPECTATION": "The Edit button should show the translated

[2026-03-25 02:10] - Updated by Junie
{
    "TYPE": "correction",
    "CATEGORY": "modal dark-mode inputs",
    "EXPECTATION": "Modal form inputs must be readable and high-contrast in Dark mode.",
    "NEW INSTRUCTION": "WHEN styling modal form fields in dark mode THEN apply shadcn dark variants with high-contrast bg, border, and text."
}

[2026-03-25 02:25] - Updated by Junie
{
    "TYPE": "correction",
    "CATEGORY": "missing technologies migrations",
    "EXPECTATION": "The Technologies module must include database tables so Project->technologies queries work without errors.",
    "NEW INSTRUCTION": "WHEN defining Project-Technology belongsToMany THEN create technologies and project_technology migrations and migrate."
}

[2026-03-25 02:28] - Updated by Junie
{
    "TYPE": "correction",
    "CATEGORY": "i18n sidebar label",
    "EXPECTATION": "The Sidebar should display the translated Technologies label, not the raw key.",
    "NEW INSTRUCTION": "WHEN a sidebar label shows an i18n key THEN fix the key usage and add missing translations."
}

[2026-03-25 02:40] - Updated by Junie
{
    "TYPE": "correction",
    "CATEGORY": "missing logos",
    "EXPECTATION": "Logos for Solid, n8n, and AIAGENT must be shown correctly in the UI.",
    "NEW INSTRUCTION": "WHEN Solid, n8n, or AIAGENT logos absent THEN add official logos; n8n use LobeHub."
}

[2026-03-25 03:35] - Updated by Junie
{
    "TYPE": "correction",
    "CATEGORY": "missing tech logos",
    "EXPECTATION": "Logos for n8n, Solid, and AIAGENT must load and display correctly.",
    "NEW INSTRUCTION": "WHEN n8n, Solid, or AIAGENT logo missing/broken THEN use official URL with fallback sources."
}

[2026-03-25 03:38] - Updated by Junie
{
    "TYPE": "negative",
    "CATEGORY": "technologies list missing",
    "EXPECTATION": "The Technologies module in the dashboard should display the list of technologies.",
    "NEW INSTRUCTION": "WHEN Technologies dashboard shows empty THEN query technologies and render list via Inertia."
}

[2026-03-25 03:42] - Updated by Junie
{
    "TYPE": "correction",
    "CATEGORY": "missing technologies/logos",
    "EXPECTATION": "Add n8n and AIAGENT to the Technologies module with their official logos displayed.",
    "NEW INSTRUCTION": "WHEN updating Technologies list THEN add n8n and AIAGENT with correct logos and fallbacks."
}

[2026-03-25 04:27] - Updated by Junie
{
    "TYPE": "correction",
    "CATEGORY": "technologies actions translation",
    "EXPECTATION": "Edit and Delete buttons in Technologies should show translated labels, not raw keys.",
    "NEW INSTRUCTION": "WHEN Technologies action buttons show raw keys THEN use t(actions.edit/actions.delete) and add locales."
}

[2026-03-25 04:34] - Updated by Junie
{
    "TYPE": "correction",
    "CATEGORY": "technologies modal buttons i18n",
    "EXPECTATION": "The Create/Edit Technology modal should display translated button labels (Save, Cancel), not raw i18n keys.",
    "NEW INSTRUCTION": "WHEN Technologies modal buttons show raw keys THEN use t(actions.save/actions.cancel) and add locales."
}

