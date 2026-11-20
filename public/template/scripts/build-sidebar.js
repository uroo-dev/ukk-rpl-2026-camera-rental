/**
 * Donezo UI — Static Sidebar Generator
 * Single source of truth for the sidebar navigation. Edits here are written
 * into every page as plain HTML (no runtime JS injection) by running:
 *
 *     node scripts/build-sidebar.js
 *
 * The generated markup replaces the inner content of <aside id="sidebar">
 * in: index.html (depth .), components/*.html (depth ..), pages/<sub>/<file>.html
 * (depth ../..). Standalone pages (auth, error, landing) carry no sidebar.
 */

const fs = require('fs');
const path = require('path');

const ROOT = path.join(__dirname, '..');

// ---------------------------------------------------------------------------
// BUILDER HELPERS
// ---------------------------------------------------------------------------

function link(ctx, href, id, icon, label, badge) {
    const { basePath, activeId } = ctx;
    const isActive = activeId === id;
    const state = isActive ? 'bg-brand-50 text-brand-800' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50';
    const bar = isActive ? '<span class="w-1.5 h-6 bg-brand-800 rounded-full absolute left-0 top-1/2 -translate-y-1/2"></span>' : '';
    const iconColor = isActive ? 'text-brand-800' : 'text-gray-500';
    const badgeHTML = badge ? '<span class="bg-brand-800 text-white text-[10px] font-bold px-2 py-0.5 rounded-full">' + badge + '</span>' : '';
    return '<a href="' + basePath + '/' + href + '" class="flex items-center justify-between px-3.5 py-2.5 rounded-2xl text-sm font-semibold transition-all relative ' + state + '">\n            ' + bar + '\n            <div class="flex items-center space-x-3">\n                <i data-lucide="' + icon + '" class="w-4 h-4 ' + iconColor + '"></i>\n                <span>' + label + '</span>\n            </div>\n            ' + badgeHTML + '\n        </a>';
}

function subLink(ctx, href, id, icon, label) {
    const { basePath, activeId } = ctx;
    const state = activeId === id ? 'bg-brand-800 text-white shadow-xs' : 'text-gray-600 hover:text-brand-800 hover:bg-brand-50/60';
    return '<a href="' + basePath + '/' + href + '" class="flex items-center space-x-2.5 px-3 py-2 rounded-xl text-xs font-semibold transition-all ' + state + '">\n            <i data-lucide="' + icon + '" class="w-3.5 h-3.5"></i>\n            <span>' + label + '</span>\n        </a>';
}

function group(id, label, icon, isOpen, items) {
    const activeClass = isOpen ? 'bg-brand-50 text-brand-800' : 'text-gray-700 hover:bg-gray-50';
    const chevron = isOpen ? 'chevron-down' : 'chevron-right';
    return '<div>\n            <button data-toggle-group="' + id + '" class="w-full flex items-center justify-between px-3.5 py-2.5 rounded-2xl text-sm font-semibold transition-all ' + activeClass + '">\n                <div class="flex items-center space-x-3">\n                    <i data-lucide="' + icon + '" class="w-4 h-4 ' + (isOpen ? 'text-brand-700' : 'text-gray-500') + '"></i>\n                    <span>' + label + '</span>\n                </div>\n                <i data-lucide="' + chevron + '" class="w-4 h-4 text-gray-400 transition-transform"></i>\n            </button>\n            <div data-group-content="' + id + '" class="pl-4 pr-1 mt-1.5 space-y-1 border-l-2 border-brand-100 ml-5 ' + (isOpen ? '' : 'hidden') + '">\n                ' + items + '\n            </div>\n        </div>';
}

// ---------------------------------------------------------------------------
// NAV GROUPS — edit these, run the script, every page updates.
// ---------------------------------------------------------------------------

function adminItems(ctx) {
    return [
        link(ctx, 'pages/admin/dashboard.html', 'dashboard', 'layout-grid', 'Dashboard'),
        link(ctx, 'pages/admin/tasks.html', 'tasks', 'check-square', 'Tasks Backlog', '12+'),
        link(ctx, 'pages/admin/team.html', 'team', 'users', 'Team'),
        link(ctx, 'pages/admin/calendar.html', 'calendar', 'calendar', 'Calendar'),
        link(ctx, 'pages/admin/analytics.html', 'analytics', 'trending-up', 'Analytics'),
        link(ctx, 'pages/admin/messages.html', 'messages', 'message-square', 'Messages'),
        link(ctx, 'pages/admin/profile.html', 'profile', 'user', 'Profile'),
        link(ctx, 'pages/admin/settings.html', 'settings', 'settings', 'Settings'),
        link(ctx, 'pages/admin/blank.html', 'blank', 'file', 'Blank Page'),
    ].join('');
}

function componentItems(ctx) {
    return [
        subLink(ctx, 'components/buttons.html', 'comp-buttons', 'mouse-pointer-click', 'Buttons'),
        subLink(ctx, 'components/forms.html', 'comp-forms', 'edit-3', 'Forms & Inputs'),
        subLink(ctx, 'components/alerts.html', 'comp-alerts', 'bell', 'Alerts & Toasts'),
        subLink(ctx, 'components/modals.html', 'comp-modals', 'layers', 'Modals & Dialogs'),
        subLink(ctx, 'components/cards.html', 'comp-cards', 'credit-card', 'Cards & Widgets'),
        subLink(ctx, 'components/tables.html', 'comp-tables', 'table', 'Tables & Lists'),
        subLink(ctx, 'components/badges.html', 'comp-badges', 'tag', 'Badges & Pills'),
        subLink(ctx, 'components/datetime.html', 'comp-datetime', 'clock', 'Date & Time'),
        subLink(ctx, 'components/charts.html', 'comp-charts', 'bar-chart-3', 'Charts'),
        subLink(ctx, 'components/tabs.html', 'comp-tabs', 'panel-top', 'Tabs'),
        subLink(ctx, 'components/dropdowns.html', 'comp-dropdowns', 'chevron-down', 'Dropdowns'),
        subLink(ctx, 'components/pagination.html', 'comp-pagination', 'chevrons-right', 'Pagination'),
        subLink(ctx, 'components/progress.html', 'comp-progress', 'loader', 'Progress'),
        subLink(ctx, 'components/avatars.html', 'comp-avatars', 'user-circle', 'Avatars'),
        subLink(ctx, 'components/timeline.html', 'comp-timeline', 'git-commit', 'Timeline'),
        subLink(ctx, 'components/tooltips.html', 'comp-tooltips', 'message-circle', 'Tooltips'),
        subLink(ctx, 'components/skeleton.html', 'comp-skeleton', 'loader-2', 'Skeleton'),
        subLink(ctx, 'components/icons.html', 'comp-icons', 'star', 'Icon Gallery'),
    ].join('');
}

function ecommerceItems(ctx) {
    return [
        subLink(ctx, 'pages/ecommerce/products.html', 'products', 'shopping-bag', 'Products'),
        subLink(ctx, 'pages/ecommerce/product-detail.html', 'product-detail', 'package', 'Product Detail'),
        subLink(ctx, 'pages/ecommerce/cart.html', 'cart', 'shopping-cart', 'Cart'),
        subLink(ctx, 'pages/ecommerce/checkout.html', 'checkout', 'credit-card', 'Checkout'),
        subLink(ctx, 'pages/ecommerce/orders.html', 'orders', 'package-check', 'Orders'),
        subLink(ctx, 'pages/ecommerce/invoice.html', 'invoice', 'receipt', 'Invoice / Nota'),
        subLink(ctx, 'pages/ecommerce/ecommerce-customers.html', 'ecommerce-customers', 'users', 'Customers'),
        subLink(ctx, 'pages/ecommerce/reviews.html', 'reviews', 'star', 'Reviews'),
    ].join('');
}

function posItems(ctx) {
    return [
        subLink(ctx, 'pages/pos/cashier.html', 'cashier', 'calculator', 'Cashier'),
        subLink(ctx, 'pages/pos/pos-tables.html', 'pos-tables', 'grid-3x3', 'Tables'),
        subLink(ctx, 'pages/pos/kitchen.html', 'kitchen', 'chef-hat', 'Kitchen'),
        subLink(ctx, 'pages/pos/receipt.html', 'receipt', 'printer', 'Receipt'),
    ].join('');
}

function crmItems(ctx) {
    return [
        subLink(ctx, 'pages/crm/customers.html', 'customers', 'contact', 'Customers'),
        subLink(ctx, 'pages/crm/customer-detail.html', 'customer-detail', 'user-search', 'Customer Detail'),
        subLink(ctx, 'pages/crm/leads.html', 'leads', 'target', 'Leads'),
        subLink(ctx, 'pages/crm/deals.html', 'deals', 'handshake', 'Deals'),
        subLink(ctx, 'pages/crm/activities.html', 'activities', 'activity', 'Activities'),
    ].join('');
}

function hrItems(ctx) {
    return [
        subLink(ctx, 'pages/hr/employees.html', 'employees', 'badge-check', 'Employees'),
        subLink(ctx, 'pages/hr/attendance.html', 'attendance', 'fingerprint', 'Attendance'),
        subLink(ctx, 'pages/hr/payroll.html', 'payroll', 'banknote', 'Payroll'),
        subLink(ctx, 'pages/hr/leave.html', 'leave', 'calendar-off', 'Leave'),
        subLink(ctx, 'pages/hr/recruitment.html', 'recruitment', 'user-plus', 'Recruitment'),
    ].join('');
}

function inventoryItems(ctx) {
    return [
        subLink(ctx, 'pages/inventory/stock.html', 'stock', 'boxes', 'Stock'),
        subLink(ctx, 'pages/inventory/movements.html', 'movements', 'arrow-left-right', 'Movements'),
        subLink(ctx, 'pages/inventory/suppliers.html', 'suppliers', 'truck', 'Suppliers'),
        subLink(ctx, 'pages/inventory/warehouses.html', 'warehouses', 'warehouse', 'Warehouses'),
    ].join('');
}

function financeItems(ctx) {
    return [
        subLink(ctx, 'pages/finance/expense.html', 'expense', 'arrow-down-circle', 'Expense'),
        subLink(ctx, 'pages/finance/income.html', 'income', 'arrow-up-circle', 'Income'),
        subLink(ctx, 'pages/finance/transactions.html', 'transactions', 'arrow-left-right', 'Transactions'),
        subLink(ctx, 'pages/finance/tax-report.html', 'tax-report', 'file-text', 'Tax Report'),
    ].join('');
}

function supportItems(ctx) {
    return [
        subLink(ctx, 'pages/support/tickets.html', 'tickets', 'ticket', 'Tickets'),
        subLink(ctx, 'pages/support/ticket-detail.html', 'ticket-detail', 'message-circle', 'Ticket Detail'),
        subLink(ctx, 'pages/support/knowledge-base.html', 'knowledge-base', 'book-open', 'Knowledge Base'),
        subLink(ctx, 'pages/support/faq.html', 'faq', 'help-circle', 'FAQ'),
    ].join('');
}

function projectsItems(ctx) {
    return [
        subLink(ctx, 'pages/projects/kanban.html', 'kanban', 'trello', 'Kanban'),
        subLink(ctx, 'pages/projects/gantt.html', 'gantt', 'bar-chart-2', 'Gantt Chart'),
        subLink(ctx, 'pages/projects/project-detail.html', 'project-detail', 'folder-kanban', 'Project Detail'),
        subLink(ctx, 'pages/projects/milestones.html', 'milestones', 'flag', 'Milestones'),
    ].join('');
}

function healthcareItems(ctx) {
    return [
        subLink(ctx, 'pages/healthcare/patients.html', 'patients', 'heart-pulse', 'Patients'),
        subLink(ctx, 'pages/healthcare/doctors.html', 'doctors', 'stethoscope', 'Doctors'),
        subLink(ctx, 'pages/healthcare/appointments.html', 'appointments', 'calendar-clock', 'Appointments'),
        subLink(ctx, 'pages/healthcare/medical-records.html', 'medical-records', 'file-heart', 'Medical Records'),
    ].join('');
}

function saasItems(ctx) {
    return [
        subLink(ctx, 'pages/saas/pricing.html', 'pricing', 'tag', 'Pricing'),
        subLink(ctx, 'pages/saas/features.html', 'features', 'zap', 'Features'),
        subLink(ctx, 'pages/saas/about.html', 'about', 'info', 'About'),
        subLink(ctx, 'pages/saas/contact.html', 'contact', 'mail', 'Contact'),
    ].join('');
}

function commItems(ctx) {
    return [
        subLink(ctx, 'pages/communication/inbox.html', 'inbox', 'inbox', 'Inbox'),
        subLink(ctx, 'pages/communication/chat.html', 'chat', 'messages-square', 'Chat'),
        subLink(ctx, 'pages/communication/notifications.html', 'notifications', 'bell-ring', 'Notifications'),
    ].join('');
}

function filesItems(ctx) {
    return [
        subLink(ctx, 'pages/files/file-browser.html', 'file-browser', 'folder', 'File Browser'),
        subLink(ctx, 'pages/files/uploader.html', 'uploader', 'upload', 'Uploader'),
    ].join('');
}

function authItems(ctx) {
    return [
        subLink(ctx, 'pages/auth/login.html', 'login', 'log-in', 'Login'),
        subLink(ctx, 'pages/auth/register.html', 'register', 'user-plus', 'Register'),
        subLink(ctx, 'pages/auth/forgot-password.html', 'forgot-password', 'key-round', 'Forgot Password'),
        subLink(ctx, 'pages/auth/lock-screen.html', 'lock-screen', 'lock', 'Lock Screen'),
        subLink(ctx, 'pages/error/404.html', '404', 'frown', '404 Error'),
        subLink(ctx, 'pages/error/500.html', '500', 'server-crash', '500 Error'),
    ].join('');
}

// ---------------------------------------------------------------------------
// NAV ASSEMBLY
// ---------------------------------------------------------------------------

function buildNav(basePath, activeId) {
    const ctx = { basePath, activeId };
    const starts = (prefix) => activeId.startsWith(prefix);

    const inAdmin = ['tasks', 'team', 'calendar', 'analytics', 'messages', 'profile', 'settings', 'blank'].includes(activeId) && !activeId.startsWith('comp-');
    const inEcommerce = starts('products') || starts('product-detail') || starts('cart') || starts('checkout') || starts('orders') || starts('invoice') || starts('ecommerce-customers') || starts('reviews');
    const inPOS = starts('cashier') || starts('pos-tables') || starts('kitchen') || starts('receipt');
    const inCRM = starts('customer') || starts('crm-') || starts('leads') || starts('deals') || starts('activities');
    const inHR = starts('employees') || starts('attendance') || starts('payroll') || starts('leave') || starts('recruitment');
    const inInventory = starts('stock') || starts('movements') || starts('suppliers') || starts('warehouses');
    const inFinance = starts('expense') || starts('income') || starts('transactions') || starts('tax-report');
    const inSupport = starts('ticket') || starts('knowledge-base') || starts('faq');
    const inProjects = starts('kanban') || starts('gantt') || starts('project-detail') || starts('milestones');
    const inHealthcare = starts('patients') || starts('doctors') || starts('appointments') || starts('medical-records');
    const inSaaS = starts('pricing') || starts('features') || starts('about') || starts('contact');
    const inComm = starts('inbox') || starts('chat') || starts('notifications');
    const inFiles = starts('file-browser') || starts('uploader');
    const inComponents = starts('comp-');

    return `
    <!-- Top Brand Logo -->
    <div class="flex items-center justify-between pb-5 border-b border-gray-100 shrink-0">
        <a href="${basePath}/index.html" class="flex items-center space-x-3 group">
            <div class="w-10 h-10 rounded-2xl bg-brand-800 text-white flex items-center justify-center font-bold text-xl shadow-md transition-transform group-hover:scale-105">
                <svg class="w-6 h-6 text-brand-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M12 2a10 10 0 1 0 10 10 4 4 0 0 1-5-5 5 5 0 0 1 5-5"></path>
                    <circle cx="12" cy="12" r="3"></circle>
                </svg>
            </div>
            <div>
                <span class="font-bold text-2xl tracking-tight text-gray-900 leading-none block">Donezo</span>
                <span class="text-[9px] font-bold uppercase tracking-wider text-brand-600 block mt-0.5">Project OS</span>
            </div>
        </a>
        <button id="close-sidebar-btn" class="md:hidden p-1.5 rounded-xl text-gray-400 hover:text-gray-700 hover:bg-gray-100 transition">
            <i data-lucide="x" class="w-5 h-5"></i>
        </button>
    </div>

    <!-- Navigation Scroll Area -->
    <div class="flex-1 overflow-y-auto py-5 space-y-5 pr-1">

        <!-- DASHBOARD -->
        <div>
            <p class="text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-2.5 px-3">Main</p>
            <nav class="space-y-1">
                ${link(ctx, 'index.html', 'index', 'layout-grid', 'Dashboard')}
                ${link(ctx, 'pages/admin/tasks.html', 'tasks', 'check-square', 'Tasks Backlog', '12+')}
                ${link(ctx, 'pages/admin/team.html', 'team', 'users', 'Team')}
                ${link(ctx, 'pages/admin/calendar.html', 'calendar', 'calendar', 'Calendar')}
            </nav>
        </div>

        <!-- ADMIN PAGES -->
        <div>
            <p class="text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-2.5 px-3">Admin</p>
            ${group('admin', 'Admin Pages', 'shield-check', inAdmin, adminItems(ctx))}
        </div>

        <!-- E-COMMERCE -->
        <div>
            <p class="text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-2.5 px-3">Commerce</p>
            ${group('ecommerce', 'E-Commerce', 'shopping-cart', inEcommerce, ecommerceItems(ctx))}
            ${group('pos', 'Point of Sale', 'calculator', inPOS, posItems(ctx))}
        </div>

        <!-- MANAGEMENT -->
        <div>
            <p class="text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-2.5 px-3">Management</p>
            ${group('crm', 'CRM', 'contact', inCRM, crmItems(ctx))}
            ${group('hr', 'Human Resources', 'users-round', inHR, hrItems(ctx))}
            ${group('inventory', 'Inventory', 'boxes', inInventory, inventoryItems(ctx))}
            ${group('finance', 'Finance', 'wallet', inFinance, financeItems(ctx))}
        </div>

        <!-- OPERATIONS -->
        <div>
            <p class="text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-2.5 px-3">Operations</p>
            ${group('support', 'Support', 'life-buoy', inSupport, supportItems(ctx))}
            ${group('projects', 'Projects', 'folder-kanban', inProjects, projectsItems(ctx))}
            ${group('healthcare', 'Healthcare', 'heart-pulse', inHealthcare, healthcareItems(ctx))}
        </div>

        <!-- EXTRAS -->
        <div>
            <p class="text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-2.5 px-3">Extras</p>
            ${group('saas', 'SaaS Pages', 'globe', inSaaS, saasItems(ctx))}
            ${group('communication', 'Communication', 'mail', inComm, commItems(ctx))}
            ${group('files', 'File Manager', 'folder', inFiles, filesItems(ctx))}
            ${group('auth', 'Auth & Errors', 'lock', false, authItems(ctx))}
            <nav class="space-y-1 mt-1">
                ${link(ctx, 'pages/landing/index.html', 'landing-index', 'rocket', 'Landing Page')}
            </nav>
        </div>

        <!-- UI COMPONENTS -->
        <div>
            <div class="flex items-center justify-between px-3 mb-2.5">
                <p class="text-[11px] font-bold text-gray-400 uppercase tracking-wider">UI Toolkit</p>
                <span class="bg-brand-100 text-brand-800 text-[9px] font-extrabold px-1.5 py-0.5 rounded">BLADE</span>
            </div>
            ${group('components', 'UI Components', 'box', inComponents, componentItems(ctx))}
        </div>

        <!-- GENERAL -->
        <div>
            <p class="text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-2.5 px-3">General</p>
            <nav class="space-y-1">
                <button onclick="triggerToast('info', 'Settings', 'Settings dialog ready.')" class="w-full flex items-center space-x-3 px-3.5 py-2.5 rounded-2xl text-gray-600 hover:text-gray-900 hover:bg-gray-50 text-sm font-semibold transition-all text-left">
                    <i data-lucide="settings" class="w-4 h-4 text-gray-500"></i><span>Settings</span>
                </button>
                <button onclick="triggerToast('info', 'Help Center', 'Documentation & support guide opened.')" class="w-full flex items-center space-x-3 px-3.5 py-2.5 rounded-2xl text-gray-600 hover:text-gray-900 hover:bg-gray-50 text-sm font-semibold transition-all text-left">
                    <i data-lucide="help-circle" class="w-4 h-4 text-gray-500"></i><span>Help & Docs</span>
                </button>
                <a href="${basePath}/pages/auth/login.html" class="w-full flex items-center space-x-3 px-3.5 py-2.5 rounded-2xl text-gray-500 hover:text-rose-600 hover:bg-rose-50 text-sm font-semibold transition-all text-left">
                    <i data-lucide="log-out" class="w-4 h-4"></i><span>Logout</span>
                </a>
            </nav>
        </div>
    </div>

    <!-- Bottom Mobile App Promo Card -->
    <div class="relative overflow-hidden bg-brand-dark rounded-2xl p-4 text-white shadow-lg mt-auto shrink-0">
        <div class="absolute -right-4 -bottom-4 opacity-20 pointer-events-none">
            <svg width="120" height="120" viewBox="0 0 100 100" fill="none" stroke="currentColor">
                <circle cx="50" cy="50" r="40" stroke-width="4"></circle>
                <circle cx="50" cy="50" r="25" stroke-width="3"></circle>
            </svg>
        </div>
        <div class="relative z-10">
            <div class="w-8 h-8 rounded-xl bg-white/10 flex items-center justify-center mb-2.5">
                <i data-lucide="smartphone" class="w-4 h-4 text-brand-300"></i>
            </div>
            <h4 class="font-bold text-xs text-white leading-tight">Donezo Mobile App</h4>
            <p class="text-[10px] text-gray-300 mt-0.5 mb-2.5">Available on iOS & Android</p>
            <button onclick="triggerToast('info', 'Download', 'Redirecting to App Store...')" class="w-full py-1.5 bg-brand-800 hover:bg-brand-700 text-white font-semibold text-xs rounded-xl transition shadow border border-brand-600/40">
                Download
            </button>
        </div>
    </div>
    `;
}

// Sidebar interactivity for pages that do not load donezo.js (root index.html)
const INLINE_SIDEBAR_JS = `<script>
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('[data-toggle-group]').forEach(btn => {
        btn.addEventListener('click', () => {
            const groupId = btn.getAttribute('data-toggle-group');
            const content = document.querySelector('[data-group-content="' + groupId + '"]');
            if (!content) return;
            content.classList.toggle('hidden');
            const icon = btn.querySelector('[data-lucide]');
            if (icon) {
                const isHidden = content.classList.contains('hidden');
                icon.setAttribute('data-lucide', isHidden ? 'chevron-right' : 'chevron-down');
                if (typeof lucide !== 'undefined') lucide.createIcons();
            }
        });
    });

    const sidebar = document.getElementById('sidebar');
    const openBtn = document.getElementById('open-sidebar-btn');
    const closeBtn = document.getElementById('close-sidebar-btn');
    const backdrop = document.getElementById('sidebar-backdrop');
    if (openBtn && sidebar) {
        openBtn.addEventListener('click', () => {
            sidebar.classList.remove('-translate-x-full');
            if (backdrop) backdrop.classList.remove('hidden');
        });
    }
    if (closeBtn && sidebar) {
        closeBtn.addEventListener('click', () => {
            sidebar.classList.add('-translate-x-full');
            if (backdrop) backdrop.classList.add('hidden');
        });
    }
    if (backdrop && sidebar) {
        backdrop.addEventListener('click', () => {
            sidebar.classList.add('-translate-x-full');
            backdrop.classList.add('hidden');
        });
    }
});
</script>`;

// ---------------------------------------------------------------------------
// INJECTION
// ---------------------------------------------------------------------------

function depthFor(relDir) {
    if (relDir === '') return 0;
    if (relDir.startsWith('components')) return 1;
    if (relDir.startsWith('pages')) return 2;
    return -1;
}

function targets() {
    const files = [path.join(ROOT, 'index.html')];
    fs.readdirSync(path.join(ROOT, 'components')).filter((f) => f.endsWith('.html')).forEach((f) => {
        files.push(path.join(ROOT, 'components', f));
    });
    fs.readdirSync(path.join(ROOT, 'pages')).forEach((sub) => {
        const subDir = path.join(ROOT, 'pages', sub);
        if (['auth', 'error', 'landing'].includes(sub) || !fs.statSync(subDir).isDirectory()) return;
        fs.readdirSync(subDir).filter((f) => f.endsWith('.html')).forEach((f) => {
            files.push(path.join(subDir, f));
        });
    });
    return files;
}

let updated = 0;
const skipped = [];

targets().forEach((file) => {
    const rel = path.dirname(path.relative(ROOT, file));
    const relDir = (rel === '.' ? '' : String(rel).replace(/\\/g, '/'));
    const depth = depthFor(relDir);
    if (depth < 0) { skipped.push(path.relative(ROOT, file)); return; }

    const basePath = depth === 0 ? '.' : Array(depth).fill('..').join('/');

    // Active id: filename minus extension; component pages carry a "comp-" prefix
    const filename = path.basename(file);
    const activeId = relDir.startsWith('components')
        ? 'comp-' + filename.replace(/\.html$/, '')
        : filename.replace(/\.html$/, '');

    let html = fs.readFileSync(file, 'utf8');

    const asideRe = /(<aside\s+id="sidebar"[^>]*>)[\s\S]*?(<\/aside>)/;
    if (!asideRe.test(html)) { skipped.push(path.relative(ROOT, file)); return; }
    html = html.replace(asideRe, (m, open) => open + '\n' + buildNav(basePath, activeId) + '\n    </aside>');

    // Remove the legacy runtime nav script; root index.html gets an inline interactivity block instead
    const navScriptRe = /[ \t]*<script src="[^"]*donezo-nav\.js"><\/script>\n?/;
    if (navScriptRe.test(html)) {
        html = html.replace(navScriptRe, path.basename(file) === 'index.html' ? INLINE_SIDEBAR_JS + '\n' : '');
    }

    fs.writeFileSync(file, html);
    updated++;
});

console.log('Sidebar injected into ' + updated + ' pages.');
if (skipped.length) console.log('Skipped (no sidebar aside):\n  - ' + skipped.join('\n  - '));
