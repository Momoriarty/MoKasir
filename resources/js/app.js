// resources/js/app.js
import './bootstrap'; // bootstrap JS & axios
import '../css/app.css'; // Tailwind CSS
import Alpine from 'alpinejs';

window.Alpine = Alpine;
Alpine.start();

document.addEventListener('DOMContentLoaded', () => {
    const toggle = document.getElementById('theme-toggle');
    const html = document.documentElement;

    // set awal mode dark/light sesuai localStorage
    if (localStorage.getItem('theme') === 'dark') {
        html.classList.add('dark');
    } else {
        html.classList.remove('dark');
    }

    // toggle dark mode saat tombol ditekan
    if (toggle) {
        toggle.addEventListener('click', () => {
            html.classList.toggle('dark');
            localStorage.setItem('theme', html.classList.contains('dark') ? 'dark' : 'light');
        });
    }
});
