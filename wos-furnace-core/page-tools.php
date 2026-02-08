<?php
/**
 * Template Name: Tools Page
 *
 * @package wos-furnace-core
 */

get_header();
?>

<!-- Full Screen Iframe Container -->
<div class="relative w-full overflow-hidden" style="height: calc(100vh - 4rem);">
    <!-- Loading Indicator -->
    <div id="loading-indicator" class="absolute inset-0 flex flex-col items-center justify-center bg-primary z-0">
        <div class="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-accent mb-4"></div>
        <div class="text-accent font-mono text-sm tracking-widest animate-pulse">ESTABLISHING CONNECTION...</div>
    </div>

    <!-- The Iframe -->
    <iframe 
        id="tools-frame"
        src="https://wos-navi.vercel.app/" 
        class="w-full h-full border-none opacity-0 transition-opacity duration-500 relative z-10"
        title="WOS Tools"
        allowfullscreen
    ></iframe>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const iframe = document.getElementById('tools-frame');
        const loading = document.getElementById('loading-indicator');

        // Show iframe when loaded
        iframe.onload = function() {
            iframe.classList.remove('opacity-0');
            // Optional: Hide loading indicator via opacity if you want a smooth fade out
            // For now, the iframe covers it because of z-index, but we can also hide it.
            setTimeout(() => {
                loading.style.display = 'none';
            }, 500);
        };
        
        // Note: Cross-origin restrictions usually prevent reading height from the iframe content directly 
        // unless postMessage is implemented on both ends.
        // Since we are doing a "full screen" style embed (minus header), 
        // the CSS `height: calc(100vh - 4rem)` (4rem = header height) is the most robust solution.
    });
</script>

<?php
get_footer();
