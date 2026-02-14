import '../css/app.css';
import Alpine from 'alpinejs';
import { tsParticles } from "tsparticles-engine";
import { loadSlim } from "tsparticles-slim";

// Initialize Alpine.js
window.Alpine = Alpine;
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
