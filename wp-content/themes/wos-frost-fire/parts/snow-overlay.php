<?php
/**
 * Snow Overlay Component
 * 
 * Uses CSS for pure CSS snow or a lightweight JS solution if preferred.
 * Here we use a CSS-only approach with multiple layers for depth.
 */
?>
<div class="pointer-events-none fixed inset-0 z-50 overflow-hidden" aria-hidden="true">
    <div class="snow-layer layer-1"></div>
    <div class="snow-layer layer-2"></div>
    <div class="snow-layer layer-3"></div>
</div>

<style>
/* Snow keyframes */
@keyframes snowfall {
    0% {
        transform: translateY(-100vh) translateX(0);
        opacity: 0.8;
    }
    100% {
        transform: translateY(100vh) translateX(20px);
        opacity: 0.2;
    }
}

.snow-layer {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-image: 
        radial-gradient(4px 4px at 100px 50px, #fff, transparent), 
        radial-gradient(6px 6px at 200px 150px, #fff, transparent), 
        radial-gradient(3px 3px at 300px 250px, #fff, transparent), 
        radial-gradient(4px 4px at 400px 350px, #fff, transparent),
        radial-gradient(6px 6px at 500px 100px, #fff, transparent), 
        radial-gradient(3px 3px at 50px 200px, #fff, transparent), 
        radial-gradient(4px 4px at 150px 300px, #fff, transparent), 
        radial-gradient(6px 6px at 250px 400px, #fff, transparent), 
        radial-gradient(3px 3px at 350px 500px, #fff, transparent);
    background-size: 550px 550px;
    animation: snowfall 10s linear infinite;
}

.layer-2 {
    animation-duration: 15s;
    background-size: 400px 400px;
    filter: blur(1px);
    opacity: 0.6;
}

.layer-3 {
    animation-duration: 20s;
    background-size: 300px 300px;
    filter: blur(2px);
    opacity: 0.4;
}
</style>
