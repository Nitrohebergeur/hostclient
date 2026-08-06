import './bootstrap';
import Alpine from 'alpinejs';
import { Chart, registerables } from 'chart.js';
import * as lucide from 'lucide';

// Initialize Alpine.js
window.Alpine = Alpine;
Alpine.start();

// Initialize Chart.js
Chart.register(...registerables);
window.Chart = Chart;

// Initialize Lucide icons
lucide.createIcons();

// Dark mode toggle
document.addEventListener('alpine:init', () => {
    Alpine.data('darkMode', () => ({
        dark: localStorage.getItem('theme') === 'dark' || 
              (!localStorage.getItem('theme') && window.matchMedia('(prefers-color-scheme: dark)').matches),
        
        toggle() {
            this.dark = !this.dark;
            localStorage.setItem('theme', this.dark ? 'dark' : 'light');
            this.updateTheme();
        },
        
        init() {
            this.updateTheme();
        },
        
        updateTheme() {
            if (this.dark) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        }
    }));

    // Dropdown component
    Alpine.data('dropdown', () => ({
        open: false,
        toggle() {
            this.open = !this.open;
        },
        close() {
            this.open = false;
        }
    }));

    // Modal component
    Alpine.data('modal', () => ({
        show: false,
        open() {
            this.show = true;
            document.body.style.overflow = 'hidden';
        },
        close() {
            this.show = false;
            document.body.style.overflow = 'auto';
        }
    }));

    // Notification component
    Alpine.data('notification', () => ({
        notifications: [],
        add(type, message, duration = 5000) {
            const id = Date.now();
            this.notifications.push({ id, type, message });
            
            if (duration > 0) {
                setTimeout(() => this.remove(id), duration);
            }
        },
        remove(id) {
            this.notifications = this.notifications.filter(n => n.id !== id);
        }
    }));
});

// Livewire hooks
document.addEventListener('livewire:navigated', () => {
    lucide.createIcons();
});

// Auto-dismiss alerts
document.addEventListener('DOMContentLoaded', () => {
    const alerts = document.querySelectorAll('[data-auto-dismiss]');
    alerts.forEach(alert => {
        const duration = parseInt(alert.dataset.autoDismiss) || 5000;
        setTimeout(() => {
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 300);
        }, duration);
    });
});

// AJAX CSRF token
const token = document.head.querySelector('meta[name="csrf-token"]');
if (token) {
    window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
}

// Service Worker for offline support (optional)
if ('serviceWorker' in navigator && import.meta.env.PROD) {
    navigator.serviceWorker.register('/sw.js');
}

console.log('HostClient initialized ✨');
