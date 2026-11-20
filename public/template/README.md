# Donezo UI — Admin & Dashboard Template

A production-ready, modular admin dashboard template styled with the **Donezo Green Design Language** and structured on the **Uroo UI architecture**. Every asset is local: no CDN requests, no bundler, no framework. Pages are plain HTML that drops straight into a Laravel Blade layout or any static host.

---

## 🎨 Design System & Palette

- **Primary Signature Green (`brand-800`)**: `#14422b`
- **Brand Accents**:
  - `brand-50`: `#eef8f3`
  - `brand-200`: `#b2e2ca`
  - `brand-400`: `#41ab75`
  - `brand-dark`: `#0c2619`
- **Surface / Background**: `#f3f5f4`
- **Typography**: Plus Jakarta Sans (UI) & JetBrains Mono (IDs, numbers), shipped as local woff2 files
- **Icons**: [Lucide](https://lucide.dev), vendored locally in `assets/vendor/`
- **Charts**: [Chart.js](https://www.chartjs.org/), vendored locally in `assets/vendor/`

Reusable component classes: `.donezo-card`, `.donezo-btn` (`-primary`, `-secondary`, `-soft`, `-ghost`, `-danger`), `.donezo-input`, `.code-viewer`, `.pattern-striped`, `.gauge-clip`.

---

## 🛠️ Build

```bash
npm install          # tailwindcss CLI, chart.js, lucide, fontsource packages
npm run build        # compile Tailwind + copy Chart.js/Lucide into assets/vendor/
npm run build:css    # assets/css/tailwind-input.css -> assets/css/tailwind.css (minified)
npm run watch:css    # same, in watch mode (alias: npm run dev)
node scripts/build-sidebar.js   # regenerate the static sidebar markup into all pages
```

`assets/css/tailwind.css` is a **generated file that is kept in the project**, so pages work without running a build. Re-run `npm run build:css` after adding new utility classes, otherwise the new classes are missing from the stylesheet.

---

## 🚀 Quick Start

**Fully offline — no server needed.** Double-click any page (`index.html`, `pages/ecommerce/invoice.html`, …); the sidebar, icons, charts, and all component previews work over `file://`. The nav engine reads its own relative `<script src>` depth, so the folder can be renamed or moved anywhere.

A local server is optional, just nicer to preview:

```bash
npx serve .            # or: python3 -m http.server 8000
```

---

## 📁 Directory Structure

```text
donezo-dashboard/
├── index.html              # Root dashboard + UI playground (self-contained inline JS)
├── AGENTS.md               # Conventions and gotchas for AI agents / contributors
├── components/             # 18 UI reference pages with Preview / Code tabs + copy button
├── pages/                  # 67 modular view pages (see table below)
├── assets/
│   ├── css/                # tailwind-input.css (source), tailwind.css (built), donezo.css
│   ├── js/                 # donezo.js (icons, sidebar toggles, drawer, tabs, copy, toasts)
│   ├── vendor/             # chart.min.js, lucide.min.js (copied by scripts/copy-vendor.js)
│   └── fonts/              # plus-jakarta-sans/, jetbrains-mono/ (woff2)
├── scripts/
│   ├── build-sidebar.js    # Single source of truth: regenerates static sidebars into all pages
│   └── copy-vendor.js      # Copies third-party libs out of node_modules
└── tailwind.config.js      # Brand palette, fonts, shadows, extra utilities
```

### Page inventory

| Module | Pages |
| --- | --- |
| Admin | dashboard, tasks, team, calendar, analytics, messages, profile, settings, blank |
| E-Commerce | products, product-detail, cart, checkout, orders, invoice, customers, reviews |
| Point of Sale | cashier, tables, kitchen display, receipt |
| CRM | customers, customer-detail, leads, deals, activities |
| Human Resources | employees, attendance, payroll, leave, recruitment |
| Inventory | stock, movements, suppliers, warehouses |
| Finance | expense, income, transactions, tax report |
| Support | tickets, ticket-detail, knowledge base, FAQ |
| Projects | kanban, gantt, project-detail, milestones |
| Healthcare | patients, doctors, appointments, medical records |
| SaaS | pricing, features, about, contact |
| Communication | inbox, chat, notifications |
| File Manager | file browser, uploader |
| Auth & Errors | login, register, forgot password, lock screen, 404, 500 |
| Marketing | landing page |
| UI Toolkit | 18 component reference pages |

---

## 🧩 How Pages Fit Together

1. Each page carries the **full sidebar as static HTML** inside `<aside id="sidebar">` — correct relative depth prefix and active-item highlighting baked in, so pages render instantly with zero runtime JS. To edit the navigation, change `scripts/build-sidebar.js` and run `node scripts/build-sidebar.js`; it rewrites all 79 sidebar pages at once.
2. `assets/js/donezo.js` wires everything interactive: collapsible nav groups, the mobile drawer, `triggerToast(type, title, message)`, `openModal(id)` / `closeModal(id)`, Lucide icon rendering, Preview/Code tab switching for component pages, and one-click clipboard copy.
3. Auth, error, and landing pages are standalone (no sidebar) and only load `donezo.js`.
4. Charts are initialised by an inline `<script>` at the bottom of the page, guarded by a `typeof Chart` check.
5. Alert/toast snippets ship with their own JavaScript: the Code tab of `components/alerts.html` includes a standalone `showToast()` function, so copied code works immediately in any project.

---

## 🐘 Using with Laravel Blade

- Convert the shared shell (head, sidebar aside, header, scripts) into `resources/views/layouts/dashboard.blade.php` and keep each `<main>` body as a view — the sidebar being static HTML makes this a copy-paste job.
- Publish `assets/` to `public/` and reference it with `asset('assets/css/tailwind.css')` instead of relative paths.
- Replace the relative sidebar `href` values with `route()` calls, or keep the generated markup as-is for static prototyping.
- Toasts, modals, and tab logic are plain vanilla JS with no template syntax, so they work unchanged inside Blade.

---

## ✨ Feature Highlights

- Floating responsive sidebar with badge counters and a mobile drawer
- Global search with keyboard shortcut hint, KPI stat cards, striped bar charts, semi-donut gauges, and a live time tracker on the root dashboard
- Component playground with live Preview/Code switching and one-click HTML copy
- Modals, form controls, switches, pagination, and toast notifications with countdown progress bars
- Operational modules covering commerce, POS, CRM, HR, inventory, finance, support, projects, and healthcare
- Consistent sample data (same people and companies) across modules so cross-page navigation reads as one product
