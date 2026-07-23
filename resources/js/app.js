

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

// Material Design ripple effect for any element with the `md-ripple` class.
document.addEventListener('click', (event) => {
    const target = event.target.closest('.md-ripple');
    if (!target) return;

    const existing = target.querySelector(':scope > .md-ripple-effect');
    if (existing) existing.remove();

    const rect = target.getBoundingClientRect();
    const diameter = Math.max(rect.width, rect.height);
    const radius = diameter / 2;

    const circle = document.createElement('span');
    circle.className = 'md-ripple-effect';
    circle.style.width = circle.style.height = `${diameter}px`;
    circle.style.left = `${event.clientX - rect.left - radius}px`;
    circle.style.top = `${event.clientY - rect.top - radius}px`;

    target.appendChild(circle);
    circle.addEventListener('animationend', () => circle.remove());
});
