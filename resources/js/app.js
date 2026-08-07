import './bootstrap';
import Alpine from 'alpinejs';
import collapse from '@alpinejs/collapse';
import focus from '@alpinejs/focus';
import intersect from '@alpinejs/intersect';
import persist from '@alpinejs/persist';
import AOS from 'aos';
import 'aos/dist/aos.css';

// Alpine Plugins
Alpine.plugin(collapse);
Alpine.plugin(focus);
Alpine.plugin(intersect);
Alpine.plugin(persist);

// Alpine Data
Alpine.data('darkMode', () => ({
    dark: Alpine.$persist(false).as('darkMode'),
    
    init() {
        this.updateTheme();
    },
    
    toggle() {
        this.dark = !this.dark;
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

Alpine.data('sidebar', () => ({
    open: Alpine.$persist(true).as('sidebarOpen'),
    
    toggle() {
        this.open = !this.open;
    }
}));

Alpine.data('dropdown', () => ({
    open: false,
    
    toggle() {
        this.open = !this.open;
    },
    
    close() {
        this.open = false;
    }
}));

Alpine.data('modal', () => ({
    open: false,
    
    show() {
        this.open = true;
        document.body.style.overflow = 'hidden';
    },
    
    hide() {
        this.open = false;
        document.body.style.overflow = '';
    }
}));

Alpine.data('toast', () => ({
    messages: [],
    
    show(message, type = 'info', duration = 3000) {
        const id = Date.now();
        this.messages.push({ id, message, type });
        
        setTimeout(() => {
            this.remove(id);
        }, duration);
    },
    
    remove(id) {
        this.messages = this.messages.filter(msg => msg.id !== id);
    }
}));

// Start Alpine
window.Alpine = Alpine;
Alpine.start();

// Initialize AOS
document.addEventListener('DOMContentLoaded', () => {
    AOS.init({
        duration: 800,
        easing: 'ease-in-out',
        once: true,
        mirror: false
    });
});

// Global helpers
window.formatPrice = (amount, currency = 'EUR') => {
    return new Intl.NumberFormat('fr-FR', {
        style: 'currency',
        currency: currency
    }).format(amount);
};

window.formatDate = (date) => {
    return new Intl.DateTimeFormat('fr-FR', {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    }).format(new Date(date));
};

window.copyToClipboard = async (text) => {
    try {
        await navigator.clipboard.writeText(text);
        return true;
    } catch (err) {
        return false;
    }
};
