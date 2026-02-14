import '../css/app.css';
import Alpine from 'alpinejs';
import { tsParticles } from "tsparticles-engine";
import { loadSlim } from "tsparticles-slim";

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

// Initialize Particles
document.addEventListener('DOMContentLoaded', async () => {
    const particlesContainer = document.getElementById('particles-js');
    if (particlesContainer) {
        await loadSlim(tsParticles);

        await tsParticles.load("particles-js", {
            particles: {
                color: {
                    value: "#ffffff",
                },
                move: {
                    direction: "bottom",
                    enable: true,
                    outModes: {
                        default: "out",
                    },
                    random: true,
                    speed: 2,
                    straight: false,
                },
                number: {
                    density: {
                        enable: true,
                        area: 800,
                    },
                    value: 80,
                },
                opacity: {
                    value: 0.5,
                },
                shape: {
                    type: "circle",
                },
                size: {
                    value: { min: 1, max: 3 },
                },
            },
        });
    }
});

console.log('WOS Frost & Fire Theme Loaded');
