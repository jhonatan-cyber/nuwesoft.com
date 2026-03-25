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

