import '../css/app.css';
import Alpine from 'alpinejs';

// Initialize Alpine.js
window.Alpine = Alpine;

document.addEventListener('alpine:init', () => {
    Alpine.data('heroFilter', () => ({
        search: '',
        selectedGen: 'all',
        selectedType: 'all',
        sortBy: 'gen-desc', // Default sort

        init() {
            console.log('Hero Filter Initialized');
            // Initial sort
            this.$nextTick(() => {
                this.sortItems();
            });

            // Watch for sortBy changes
            this.$watch('sortBy', () => {
                this.sortItems();
            });
        },

        isVisible(el) {
            // If no data attributes, show by default (safer fallback)
            if (!el.dataset.name && !el.dataset.gen && !el.dataset.type) return true;

            const name = el.dataset.name ? el.dataset.name.toLowerCase() : '';
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
        },

        sortItems() {
            const container = this.$el.querySelector('.grid');
            if (!container) return;

            const items = Array.from(container.querySelectorAll('article'));
            
            items.sort((a, b) => {
                if (this.sortBy === 'gen-desc') {
                    const genA = parseInt(a.dataset.gen) || 0;
                    const genB = parseInt(b.dataset.gen) || 0;
                    if (genB !== genA) return genB - genA;
                    return a.dataset.name.localeCompare(b.dataset.name);
                } else if (this.sortBy === 'gen-asc') {
                    const genA = parseInt(a.dataset.gen) || 0;
                    const genB = parseInt(b.dataset.gen) || 0;
                    if (genA !== genB) return genA - genB;
                    return a.dataset.name.localeCompare(b.dataset.name);
                } else if (this.sortBy === 'name-asc') {
                    return a.dataset.name.localeCompare(b.dataset.name);
                }
                return 0;
            });

            // Re-append items in new order
            items.forEach(item => container.appendChild(item));
        }
    }));
});

Alpine.start();

console.log('WOS Frost & Fire Theme Loaded');
