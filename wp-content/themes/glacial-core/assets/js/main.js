console.log('GlacialCore Theme Loaded');

// Snow Effect Implementation
document.addEventListener('DOMContentLoaded', () => {
    const snowContainer = document.getElementById('snow-container');
    if (!snowContainer) return;

    // Create canvas for snow
    const canvas = document.createElement('canvas');
    canvas.width = window.innerWidth;
    canvas.height = window.innerHeight;
    snowContainer.appendChild(canvas);
    
    const ctx = canvas.getContext('2d');
    const snowflakes = [];
    
    class Snowflake {
        constructor() {
            this.x = Math.random() * canvas.width;
            this.y = Math.random() * canvas.height;
            this.size = Math.random() * 3 + 1;
            this.speed = Math.random() * 1 + 0.5;
            this.opacity = Math.random() * 0.5 + 0.3;
        }
        
        update() {
            this.y += this.speed;
            if (this.y > canvas.height) {
                this.y = 0;
                this.x = Math.random() * canvas.width;
            }
        }
        
        draw() {
            ctx.fillStyle = `rgba(255, 255, 255, ${this.opacity})`;
            ctx.beginPath();
            ctx.arc(this.x, this.y, this.size, 0, Math.PI * 2);
            ctx.fill();
        }
    }
    
    function initSnow() {
        for (let i = 0; i < 50; i++) {
            snowflakes.push(new Snowflake());
        }
    }
    
    function animate() {
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        snowflakes.forEach(flake => {
            flake.update();
            flake.draw();
        });
        requestAnimationFrame(animate);
    }
    
    initSnow();
    animate();
    
    // Resize handler
    window.addEventListener('resize', () => {
        canvas.width = window.innerWidth;
        canvas.height = window.innerHeight;
    });
});
