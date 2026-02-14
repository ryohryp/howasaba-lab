/**
 * Lightweight Snowstorm Animation
 * Renders a blizzard effect on a canvas overlay.
 */
document.addEventListener('DOMContentLoaded', function() {
    const canvas = document.getElementById('snow-canvas');
    if (!canvas) return;

    const ctx = canvas.getContext('2d');
    let width = window.innerWidth;
    let height = window.innerHeight;

    canvas.width = width;
    canvas.height = height;

    const particles = [];
    const particleCount = 150; // Adjust for density
    
    // Resize handler
    window.addEventListener('resize', function() {
        width = window.innerWidth;
        height = window.innerHeight;
        canvas.width = width;
        canvas.height = height;
    });

    class SnowParticle {
        constructor() {
            this.reset();
        }

        reset() {
            this.x = Math.random() * width;
            this.y = Math.random() * -height; // Start above screen
            this.size = Math.random() * 3 + 1;
            this.speedX = Math.random() * 3 - 1.5; // Horizontal drift (wind)
            this.speedY = Math.random() * 3 + 2;   // Falling speed (blizzard fast)
            this.opacity = Math.random() * 0.5 + 0.3;
        }

        update() {
            this.x += this.speedX + 2; // Strong wind to the right
            this.y += this.speedY;

            // Wrap around
            if (this.y > height) {
                this.y = -10;
                this.x = Math.random() * width - width/2; // Allow entering from left side properly
            }
            if (this.x > width) {
                this.x = 0;
                // If exiting right, random Y reset to keep flow
                if (Math.random() > 0.9) {
                     this.y = Math.random() * height;
                }
            }
        }

        draw() {
            ctx.fillStyle = `rgba(255, 255, 255, ${this.opacity})`;
            ctx.beginPath();
            ctx.arc(this.x, this.y, this.size, 0, Math.PI * 2);
            ctx.fill();
        }
    }

    // Initialize particles
    for (let i = 0; i < particleCount; i++) {
        particles.push(new SnowParticle());
        // Pre-warm the system so snow is already falling
        particles[i].y = Math.random() * height;
    }

    function animate() {
        ctx.clearRect(0, 0, width, height);
        
        particles.forEach(p => {
            p.update();
            p.draw();
        });

        requestAnimationFrame(animate);
    }

    animate();
});
