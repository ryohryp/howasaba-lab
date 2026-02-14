<?php
/**
 * Mobile Bottom Navigation Template Part
 * 
 * Fixed navigation bar for mobile devices.
 * 
 * @package WOS_Frost_Fire
 */

// Define menu items
$menu_items = [
    [
        'label' => __( 'Home', 'wos-frost-fire' ),
        'url'   => home_url('/'),
        'icon'  => '<path d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>',
        'active_check' => is_front_page(),
    ],
    [
        'label' => __( 'Heroes', 'wos-frost-fire' ),
        'url'   => get_post_type_archive_link('wos_hero'),
        'icon'  => '<path d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />',
        'active_check' => is_post_type_archive('wos_hero') || is_singular('wos_hero'),
    ],
    [
        'label' => __( 'Guides', 'wos-frost-fire' ), // Changed from Events to Guide generic or Events specifically
        'url'   => home_url('/guide'), // Assuming /guide page exists or category
        'icon'  => '<path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />',
        'active_check' => is_page('guide') || is_singular('post'),
    ],
    [
        'label' => __( 'Tools', 'wos-frost-fire' ),
        'url'   => home_url('/tools'),
        'icon'  => '<path d="M11 4a2 2 0 114 0v1a1 1 0 001 1h3a1 1 0 011 1v3a1 1 0 01-1 1h-1a2 2 0 100 4h1a1 1 0 011 1v3a1 1 0 01-1 1h-3a1 1 0 01-1-1v-1a2 2 0 10-4 0v1a1 1 0 01-1 1H7a1 1 0 01-1-1v-3a1 1 0 00-1-1H4a2 2 0 110-4h1a1 1 0 001-1V7a1 1 0 011-1h3a1 1 0 001-1V4z" />',
        'active_check' => is_page('tools') || is_archive('wos_event'), // Events can be under tools or separate
    ],
];
?>

<div class="fixed bottom-0 left-0 right-0 z-50 bg-deep-freeze/95 backdrop-blur-lg border-t border-white/10 md:hidden pb-safe">
    <div class="flex justify-around items-center h-16">
        <?php foreach ($menu_items as $item): 
            $active_class = $item['active_check'] ? 'text-fire-crystal' : 'text-gray-400 hover:text-white';
        ?>
            <a href="<?php echo esc_url($item['url']); ?>" class="flex flex-col items-center justify-center w-full h-full space-y-1 active:scale-90 transition-transform duration-200 <?php echo $active_class; ?>">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <?php echo $item['icon']; // SVG content ?>
                </svg>
                <span class="text-[10px] font-medium leading-none"><?php echo esc_html($item['label']); ?></span>
            </a>
        <?php endforeach; ?>
        
        <!-- Toggle Mobile Menu (Existing logic integration) -->
        <button 
            @click="mobileMenuOpen = !mobileMenuOpen" 
            class="flex flex-col items-center justify-center w-full h-full space-y-1 text-gray-400 hover:text-white active:scale-90 transition-transform duration-200"
        >
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
            <span class="text-[10px] font-medium leading-none"><?php _e('Menu', 'wos-frost-fire'); ?></span>
        </button>
    </div>
</div>
