# AGENTS.md — Donezo Dashboard

Static HTML admin/dashboard template (no framework, no bundler, no runtime). Everything an agent needs to work here safely.

## Commands

```bash
npm install                 # tailwindcss CLI + chart.js + lucide + fontsource packages
npm run build               # build:css then copy vendor libs into assets/vendor/
npm run build:css           # compile + minify assets/css/tailwind-input.css -> assets/css/tailwind.css
npm run watch:css           # same, watching (npm run dev is an alias)
node scripts/build-sidebar.js  # regenerate static sidebar markup into all pages after nav edits
```

There is **no test suite, no linter, no formatter, and no git repository** in this directory. Verification is manual: open pages in a browser and check the console. Pages also work fully offline via `file://` (double-click to open).

## Non-obvious gotchas

- **Tailwind output is committed and must be rebuilt.** `assets/css/tailwind.css` is a generated artifact that every page links to. Any new utility class you write does nothing until `npm run build:css` runs. Content globs live in `tailwind.config.js` (`index.html`, `components/**/*.html`, `pages/**/*.html`, `assets/js/**/*.js`) — files outside those paths are not scanned, so classes in `assets/css/*` or new top-level folders will be purged.
- **The sidebar is static HTML in every page, synced by a generator.** `<aside id="sidebar">` carries the full nav markup (no runtime injection). `scripts/build-sidebar.js` is the single source of truth: edit the NAV groups there, run `node scripts/build-sidebar.js`, and all 79 sidebar-carrying pages are rewritten. Depth is derived from file location: root `./`, `components/*.html` → `../`, `pages/<sub>/<file>.html` → `../..` — no dependency on the project folder name, works via `file://`. Component pages get the active id as `comp-<name>`. Editing a page's sidebar by hand will be silently overwritten on the next generator run; never hand-edit it.
- **Two stylesheets contain the same component classes.** `assets/css/tailwind-input.css` (Tailwind source, includes `@layer utilities`, `@font-face`, and the `.donezo-*` classes) and `assets/css/donezo.css` (a standalone duplicate of the `.donezo-*` classes). Both are linked on every page. Editing one without the other silently produces divergent styling; change both, or treat `donezo.css` as the Blade-portable copy.
- **No CDN anywhere in the markup.** Chart.js and Lucide are vendored into `assets/vendor/` by `scripts/copy-vendor.js`, and fonts are local woff2 files declared in `tailwind-input.css`. The README still mentions "Lucide via unpkg CDN" and three legacy admin pages used to load `cdn.tailwindcss.com`; those were converted to local assets. Do not reintroduce CDN tags. The only remaining external requests are Unsplash placeholder images.
- **`index.html` does not load `assets/js/donezo.js`.** It carries its own inline copies of `openModal`, `closeModal`, and `triggerToast`. Changing a helper in `donezo.js` will not affect the root dashboard page; update both.
- **Icons must be re-rendered after injecting HTML.** Markup uses `<i data-lucide="name">` placeholders resolved by `lucide.createIcons()`. Any dynamic insertion (toasts, sidebar, tab switches) needs a follow-up `createIcons()` call, as the existing helpers do.
- **Lucide is pinned to 0.469; some legacy names are gone.** `x-circle` and `plus-minus` do not exist in this version (use `circle-x`, `arrow-up-down`). Validate new names against `node_modules/lucide/dist/esm/icons/` before using them — an unknown name renders as an empty box, not an error.
- **`shadow-xs`, `backdrop-blur-xs`, and `h-5.5` are not Tailwind v3 defaults.** They are defined via `tailwind.config.js` (`boxShadow.xs`, `backdropBlur.xs`, `spacing['5.5']`) because the existing pages already used them.
- **`.cat-pill`, `.comp-card`, `.settings-tab`, `.view-toggle-btn` have no CSS.** They are JS selector hooks; active state is applied by swapping Tailwind classes in inline scripts (`filterComponentTab` in `index.html`, tab logic in `pages/admin/settings.html`). `animate-in fade-in zoom-in-95` only appears inside escaped code samples, so `tailwindcss-animate` is intentionally absent.
- **Dark mode is configured but unused.** `darkMode: 'class'` is set; no page ships `dark:` variants.

## Architecture and page shell

Every dashboard page is a self-contained file with an identical shell. Copy it rather than inventing a new one:

```
<head>  -> ../../assets/css/tailwind.css, ../../assets/css/donezo.css,
           ../../assets/vendor/lucide.min.js (+ chart.min.js only when the page draws charts)
<body>  -> #toast-container (fixed top-right anchor for triggerToast)
        -> flex h-screen wrapper
              #sidebar (full static sidebar markup, output of scripts/build-sidebar.js)
             #sidebar-backdrop (mobile overlay)
             main column: sticky <header> (#open-sidebar-btn, title, subtitle, actions) + <main class="space-y-6 pb-12">
        -> donezo.js (icons, group toggles, drawer, tabs, copy), then any inline <script> for charts
```

Relative depth is fixed per location: `.` at the root (`index.html`), `..` for `components/*.html`, `../..` for `pages/<section>/*.html`.

Runtime flow: the sidebar HTML is static per page (correct depth prefix, active item classes baked in — component ids use a `comp-` prefix). `donezo.js` handles everything interactive: `lucide.createIcons()`, collapsible groups via `[data-toggle-group]` / `[data-group-content]`, the mobile drawer (`#open-sidebar-btn` / `#close-sidebar-btn` / `#sidebar-backdrop`), `[data-donezo-component]` preview/code tab switching, and `[data-copy-target]` clipboard copy. `index.html` (root) does not load `donezo.js`; it ships the same group-toggle/drawer logic as an inline `<script>` block.

Auth, error, and landing pages (`pages/auth/*`, `pages/error/*`, `pages/landing/index.html`) are **standalone**: no sidebar, no header — only `donezo.js` for icons and toasts.

### Directory map (86 HTML files)

| Path | Contents |
| --- | --- |
| `index.html` | Root dashboard + component playground, inline JS helpers |
| `components/` | 18 UI reference pages (buttons, forms, alerts, modals, cards, tables, badges, datetime, charts, tabs, dropdowns, pagination, progress, avatars, timeline, tooltips, skeleton, icons) |
| `pages/admin/` | dashboard, tasks, team, calendar, analytics, messages, profile, settings, blank (9) |
| `pages/ecommerce/` | products, product-detail, cart, checkout, orders, invoice, ecommerce-customers, reviews (8) |
| `pages/pos/` | cashier, pos-tables, kitchen, receipt (4) |
| `pages/crm/` | customers, customer-detail, leads, deals, activities (5) |
| `pages/hr/` | employees, attendance, payroll, leave, recruitment (5) |
| `pages/inventory/` | stock, movements, suppliers, warehouses (4) |
| `pages/finance/` | expense, income, transactions, tax-report (4) |
| `pages/support/` | tickets, ticket-detail, knowledge-base, faq (4) |
| `pages/projects/` | kanban, gantt, project-detail, milestones (4) |
| `pages/healthcare/` | patients, doctors, appointments, medical-records (4) |
| `pages/saas/` | pricing, features, about, contact (4) |
| `pages/communication/` | inbox, chat, notifications (3) |
| `pages/files/` | file-browser, uploader (2) |
| `pages/auth/` | login, register, forgot-password, lock-screen (4) |
| `pages/error/` | 404, 500 (2) |
| `pages/landing/` | index (1) |
| `assets/js/` | `donezo.js` (icons, sidebar toggles/drawer, tabs, copy, toast/modal helpers) |
| `assets/css/` | `tailwind-input.css` (source), `tailwind.css` (built), `donezo.css` (portable duplicate) |
| `assets/vendor/` | `chart.min.js`, `lucide.min.js` (generated by `scripts/copy-vendor.js`) |
| `assets/fonts/` | `plus-jakarta-sans/`, `jetbrains-mono/` woff2 files |
| `scripts/` | `copy-vendor.js`, `build-sidebar.js` (regenerates static sidebars) |
| `assets/icons/` | empty |

## Adding a page

1. Copy the shell from a sibling page in the same folder (depth matters).
2. Add the entry to `scripts/build-sidebar.js` (the relevant items function), run `node scripts/build-sidebar.js` so the static sidebar in all pages updates, and copy the updated sidebar block into the new page too (`node scripts/build-sidebar.js` handles it if the page sits in a mapped folder).
3. Cross-link from related pages (detail pages link back to their list; the nav is not the only entry point).
4. Run `npm run build:css` and check the page in a browser: sidebar renders, active item is highlighted, icons appear, no 404s in the network tab.

## Conventions

- **Design language**: deep green `brand-800 #14422b` primary, `bg-[#f3f5f4]` page surface, `rounded-2xl`/`rounded-3xl` corners, `shadow-card`, Plus Jakarta Sans for UI and JetBrains Mono for IDs/numbers.
- **Micro typography is deliberate**: section labels `text-[10px] font-bold uppercase tracking-wider text-gray-400`, helper text `text-[11px] text-gray-400`, body `text-xs`, card titles `text-sm font-bold`. Match these instead of defaulting to `text-sm` everywhere.
- **Reusable classes** (defined in `tailwind-input.css` and `donezo.css`): `.donezo-card`, `.donezo-btn` + `-primary|-secondary|-soft|-ghost|-danger`, `.donezo-input`, `.code-viewer`, `.pattern-striped`, `.pattern-striped-green`, `.gauge-clip`.
- **Status pills**: `text-[10px] font-bold px-2 py-1 rounded-full` with semantic pairs — emerald (paid/active/delivered), amber (pending/low stock/at risk), rose (failed/stockout/critical), blue (processing/shipped), brand (VIP/in progress), gray (draft/neutral).
- **Interactive placeholders** call the global helpers instead of `alert()`: `onclick="triggerToast('success','Title','Message')"`, `openModal('id')`, `closeModal('id')`. Toast types are `success | warning | error | info`.
- **Copied snippets must be self-contained.** Component pages embed the JS a snippet needs directly in the escaped code sample, so pasted code works instantly. Pattern: `components/alerts.html` ships a standalone `showToast(type, title, message, duration)` (markup + `<script>` in the code tab), and its preview buttons call that same local function. Prefer this pattern for new interactive component pages over pointing at the global `donezo.js` helpers.
- **Component reference pages** follow one pattern: a `.donezo-card[data-donezo-component]` containing `[data-tab-btn="preview"|"code"]`, `[data-tab-pane="preview"|"code"]`, and a `.code-viewer > pre > code#<id>` with HTML-escaped markup that `[data-copy-target="<id>"]` copies. Keep the escaped sample in sync with the live preview above it.
- **Charts**: inline `<script>` at the bottom of the page, wrapped in `DOMContentLoaded` plus a `typeof Chart === 'undefined'` guard, `responsive: true, maintainAspectRatio: false`, and a fixed-height parent (`h-44` … `h-64`). Palette: `#14422b`, `#41ab75`, `#b2e2ca`, `#e5e7eb`; danger `#f43f5e`/`#f59e0b`. Legends sit at `position: 'bottom'` with `boxWidth: 10` and 10px font.
- **Sample data** is consistent across modules (same people — Alya Pratama, Dimas Saputra, Sarah Wijaya, Maya Kusuma, Bagas Nugroho — and the same companies: Nordwind, Kopi Logi, Gudangku, BukuGuru, Medika, Retailku, Tani Labs, SekolahPlus). Reuse them so cross-page links tell one coherent story.
