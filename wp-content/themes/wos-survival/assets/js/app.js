import '../css/app.css';
import Alpine from 'alpinejs';

// Initialize Alpine.js
window.Alpine = Alpine;

document.addEventListener('alpine:init', () => {
    Alpine.data('heroFilter', () => ({
        search: '',
        selectedGen: 'all',
        selectedType: 'all',

        init() {
            // Optional: Initialize from URL params if needed
            console.log('Hero Filter Initialized');
        },

        isVisible(el) {
            if (!el.dataset.name) return true;

            const name = el.dataset.name.toLowerCase();
            const gen = el.dataset.gen;
            const type = el.dataset.type;

            const matchesSearch = this.search === '' || name.includes(this.search.toLowerCase());
            const matchesGen = this.selectedGen === 'all' || gen === this.selectedGen;
            const matchesType = this.selectedType === 'all' || type === this.selectedType;

            return matchesSearch && matchesGen && matchesType;
        },

        setGen(gen) {
            this.selectedGen = gen;
        },

        setType(type) {
            this.selectedType = type;
        }
    }));
});

Alpine.start();

console.log('WOS Frost & Fire Theme Loaded');
