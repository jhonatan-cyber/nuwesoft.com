---
name: web-design-guidelines
description: Review UI code for Web Interface Guidelines compliance. Use when asked to "review my UI", "check accessibility", "audit design", "review UX", or "check my site against best practices".
metadata:
  author: vercel
  version: "1.0.0"
  argument-hint: <file-or-pattern>
---

# Web Interface Guidelines

Review files for compliance with Web Interface Guidelines.

## How It Works

1. Fetch the latest guidelines from the source URL below
2. Read the specified files (or prompt user for files/pattern)
3. Check against all rules in the fetched guidelines
4. Output findings in the terse `file:line` format

## Guidelines Source

Fetch fresh guidelines before each review:

```
https://raw.githubusercontent.com/vercel-labs/web-interface-guidelines/main/command.md
```

Use WebFetch to retrieve the latest rules. The fetched content contains all the rules and output format instructions.

## Usage

When a user provides a file or pattern argument:
1. Fetch guidelines from the source URL above
2. Read the specified files
3. Apply all rules from the fetched guidelines
4. Output findings using the format specified in the guidelines

If no files specified, ask the user which files to review.

---

## Cache & Local Fallback Guidelines

If the dynamic network request to retrieve the guidelines from GitHub fails (e.g. due to sandbox network limitations or GitHub API downtime), the agent MUST use this local copy of the **Vercel Web Interface Guidelines** fallback:

### 1. Typography & Contrast
* **WCAG Compliance**: All text must meet WCAG AA contrast requirements (≥4.5:1 for normal text, ≥3:1 for large text 18px+ or bold 14px+).
* **Relative Units**: Use relative font sizing (`rem`, `em`) rather than hardcoded pixel values to allow user agent zoom.
* **Readable Measures**: Keep text container widths within a readable line length (45–75 characters per line).

### 2. Interaction & Focus States
* **Visible Focus**: All interactive elements (buttons, inputs, links) must show a highly visible `:focus-visible` ring. Never suppress focus outlines (`outline: none`) without styling a high-contrast replacement.
* **Semantic Elements**: Use `<button>` for actions, `<a>` for navigation, and `<input>`/`<select>` for forms. Do not add click handlers to generic `<div>` or `<span>` elements unless you add full keyboard compatibility (`tabindex="0"` and key listeners for Space/Enter) and appropriate ARIA roles.
* **Touch Targets**: All interactive controls must have a minimum interactive size of `44x44px` (or `48x48px` on mobile layouts) to ensure touch usability.

### 3. Keyboard Accessibility & Modals
* **Sequential Navigation**: Users must be able to navigate the page linearly using only the Tab key.
* **Modal Trap**: When an overlay (modal, drawer, dialog) is open, focus must be trapped within the overlay. Pressing the Escape key must close the overlay.
* **Dynamic Overlays**: Ensure dropdowns and tooltips do not clip under `overflow: hidden` containers. Use the native HTML `<dialog>` element or CSS `position: fixed` when escape-harnesses are needed.

### 4. Forms & Input States
* **Labels**: Every form input must have a programmatically linked `<label>` using matching `for` and `id` attributes. Never use the `placeholder` attribute as the sole identifier for an input.
* **Validation & Errors**: Clear validation states must be shown. Error text must be placed under the input and linked programmatically using `aria-describedby` pointing to the error message ID.

### 5. Icons & Media
* **Icon Hiding**: Icons that are decorative must have `aria-hidden="true"` to hide them from screen readers.
* **Screen Reader Labels**: Interactive buttons containing only an icon must provide a text equivalent using `sr-only` classes or `aria-label`.

### Fallback Output Format
If fallback mode is active, output findings using the identical `file:line: column - rule - description` format.
