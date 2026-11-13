import './bootstrap';

window.lucide?.createIcons();

const panel = document.getElementById('menu-panel');
const card = panel?.querySelector('[data-menu-card]');

function openMenu() {
    if (!panel) return;
    panel.classList.remove('hidden');
    document.querySelectorAll('[data-menu-open]').forEach((b) => b.setAttribute('aria-expanded', 'true'));
    requestAnimationFrame(() => card?.classList.remove('translate-x-full'));
}

function closeMenu() {
    if (!panel) return;
    card?.classList.add('translate-x-full');
    document.querySelectorAll('[data-menu-open]').forEach((b) => b.setAttribute('aria-expanded', 'false'));
    setTimeout(() => panel.classList.add('hidden'), 220);
}

document.querySelectorAll('[data-menu-open]').forEach((btn) => btn.addEventListener('click', openMenu));
document.querySelectorAll('[data-menu-close]').forEach((btn) => btn.addEventListener('click', closeMenu));
document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') closeMenu();
});

const progress = document.querySelector('[data-ai-progress]');
if (progress) {
    const steps = [...progress.querySelectorAll('[data-step]')];
    let current = 0;
    const advance = () => {
        if (current < steps.length) {
            const dot = steps[current].querySelector('[data-dot]');
            dot?.classList.remove('bg-gray-fill');
            dot?.classList.add('bg-lime-dark');
            current += 1;
            setTimeout(advance, 900);
        } else {
            document.getElementById('ai-form')?.submit();
        }
    };
    setTimeout(advance, 500);
}

document.querySelectorAll('[data-option]').forEach((label) => {
    label.addEventListener('click', () => {
        const group = label.closest('[data-option-group]');
        group?.querySelectorAll('[data-option]').forEach((o) => {
            o.classList.remove('border-primary', 'bg-primary-soft');
            o.querySelector('[data-dot]')?.classList.add('opacity-0');
        });
        label.classList.add('border-primary', 'bg-primary-soft');
        label.querySelector('[data-dot]')?.classList.remove('opacity-0');
    });
});
