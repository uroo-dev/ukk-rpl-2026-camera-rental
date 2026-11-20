/**
 * Donezo UI — Lightweight Vanilla JS Utility (Laravel Blade friendly)
 * Zero dependencies, no build steps required.
 */

document.addEventListener('DOMContentLoaded', () => {
    // 1. Initialize Lucide Icons
    if (typeof lucide !== 'undefined') {
        lucide.createIcons();
    }

    // 2. Tab Switcher for Preview / Code Components
    document.querySelectorAll('[data-donezo-component]').forEach(comp => {
        const previewBtn = comp.querySelector('[data-tab-btn="preview"]');
        const codeBtn = comp.querySelector('[data-tab-btn="code"]');
        const previewPane = comp.querySelector('[data-tab-pane="preview"]');
        const codePane = comp.querySelector('[data-tab-pane="code"]');

        if (previewBtn && codeBtn && previewPane && codePane) {
            previewBtn.addEventListener('click', () => {
                previewPane.classList.remove('hidden');
                codePane.classList.add('hidden');
                previewBtn.className = 'px-3 py-1 text-xs font-semibold rounded-lg bg-brand-800 text-white shadow-xs';
                codeBtn.className = 'px-3 py-1 text-xs font-semibold rounded-lg bg-gray-100 text-gray-600 hover:text-gray-900';
            });

            codeBtn.addEventListener('click', () => {
                previewPane.classList.add('hidden');
                codePane.classList.remove('hidden');
                codeBtn.className = 'px-3 py-1 text-xs font-semibold rounded-lg bg-brand-800 text-white shadow-xs';
                previewBtn.className = 'px-3 py-1 text-xs font-semibold rounded-lg bg-gray-100 text-gray-600 hover:text-gray-900';
            });
        }
    });

    // 3. 1-Click Clipboard Copy Handler
    document.querySelectorAll('[data-copy-target]').forEach(btn => {
        btn.addEventListener('click', () => {
            const targetId = btn.getAttribute('data-copy-target');
            const targetElem = document.getElementById(targetId);
            if (targetElem) {
                const text = targetElem.innerText;
                navigator.clipboard.writeText(text).then(() => {
                    triggerToast('success', 'Copied to Clipboard!', 'HTML/Blade markup ready to paste.');
                    const origHtml = btn.innerHTML;
                    btn.innerHTML = `<i data-lucide="check" class="w-3.5 h-3.5 mr-1"></i> Copied!`;
                    if (typeof lucide !== 'undefined') lucide.createIcons();
                    setTimeout(() => {
                        btn.innerHTML = origHtml;
                        if (typeof lucide !== 'undefined') lucide.createIcons();
                    }, 2000);
                }).catch(() => {
                    triggerToast('error', 'Copy Failed', 'Please copy manually.');
                });
            }
        });
    });

    // 4. Collapsible Sidebar NAV Group Toggles (sidebar markup is static HTML per page)
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

    // 5. Mobile Sidebar Drawer Controls
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

// Modal Helpers
function openModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.remove('hidden');
        const box = modal.querySelector('div');
        if (box) {
            requestAnimationFrame(() => {
                box.classList.remove('scale-95');
                box.classList.add('scale-100');
            });
        }
    }
}

function closeModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        const box = modal.querySelector('div');
        if (box) {
            box.classList.remove('scale-100');
            box.classList.add('scale-95');
        }
        setTimeout(() => modal.classList.add('hidden'), 150);
    }
}

// Global Toast Notification Helper
function triggerToast(type, title, message, duration = 3500) {
    let container = document.getElementById('toast-container');
    if (!container) {
        container = document.createElement('div');
        container.id = 'toast-container';
        container.className = 'fixed top-5 right-5 z-[9999] flex flex-col gap-2.5 max-w-sm pointer-events-none';
        document.body.appendChild(container);
    } else {
        container.className = 'fixed top-5 right-5 z-[9999] flex flex-col gap-2.5 max-w-sm pointer-events-none';
    }

    const config = {
        success: { bg: 'bg-emerald-50 border-emerald-200 text-emerald-950', icon: 'check-circle-2', bar: 'bg-emerald-600' },
        warning: { bg: 'bg-amber-50 border-amber-200 text-amber-950', icon: 'alert-triangle', bar: 'bg-amber-600' },
        error: { bg: 'bg-rose-50 border-rose-200 text-rose-950', icon: 'alert-octagon', bar: 'bg-rose-600' },
        info: { bg: 'bg-brand-50 border-brand-200 text-brand-950', icon: 'info', bar: 'bg-brand-800' }
    };

    const theme = config[type] || config.info;
    const toast = document.createElement('div');
    toast.className = `p-4 rounded-2xl ${theme.bg} border shadow-lg flex items-start justify-between gap-3 text-xs pointer-events-auto transform transition-all duration-300 translate-y-2 opacity-0 relative overflow-hidden w-80`;

    toast.innerHTML = `
        <div class="flex items-start gap-2.5">
            <i data-lucide="${theme.icon}" class="w-4 h-4 mt-0.5 flex-shrink-0"></i>
            <div>
                <h4 class="font-bold text-xs">${title}</h4>
                <p class="mt-0.5 leading-relaxed text-[11px] opacity-80">${message}</p>
            </div>
        </div>
        <button class="toast-close text-gray-400 hover:text-gray-700 text-sm font-bold">&times;</button>
        <div class="toast-bar absolute bottom-0 left-0 h-1 ${theme.bar} w-full transition-all duration-linear"></div>
    `;

    container.appendChild(toast);
    if (typeof lucide !== 'undefined') lucide.createIcons();

    requestAnimationFrame(() => {
        toast.classList.remove('translate-y-2', 'opacity-0');
        toast.classList.add('translate-y-0', 'opacity-100');

        const bar = toast.querySelector('.toast-bar');
        if (bar && duration > 0) {
            bar.style.transition = `width ${duration}ms linear`;
            setTimeout(() => { bar.style.width = '0%'; }, 20);
        }
    });

    function removeToast() {
        toast.style.opacity = '0';
        toast.style.transform = 'translateY(-10px) scale(0.95)';
        setTimeout(() => toast.remove(), 250);
    }

    toast.querySelector('.toast-close').addEventListener('click', removeToast);
    if (duration > 0) setTimeout(removeToast, duration);
}
