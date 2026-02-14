    <footer id="colophon" class="site-footer mt-auto border-t border-white/5 bg-midnight-navy py-12 text-sm text-gray-400">
        <div class="container mx-auto px-4">
            <div class="grid gap-8 sm:grid-cols-2 lg:grid-cols-4">
                <div class="footer-branding">
                    <h2 class="mb-4 text-lg font-bold text-ice-blue"><?php bloginfo( 'name' ); ?></h2>
                    <p class="mb-4 text-xs"><?php bloginfo( 'description' ); ?></p>
                </div>
                
                <!-- Example Footer Widgets Areas (Static for now) -->
                <div>
                    <h3 class="mb-3 font-semibold text-white">Resources</h3>
                    <ul class="space-y-2">
                        <li><a href="#" class="hover:text-fire-crystal transition-colors">Hero Tier List</a></li>
                        <li><a href="#" class="hover:text-fire-crystal transition-colors">Event Calendar</a></li>
                        <li><a href="#" class="hover:text-fire-crystal transition-colors">State Transfer</a></li>
                    </ul>
                </div>

                <div>
                    <h3 class="mb-3 font-semibold text-white">Tools</h3>
                    <ul class="space-y-2">
                        <li><a href="#" class="hover:text-fire-crystal transition-colors">Power Calculator</a></li>
                        <li><a href="#" class="hover:text-fire-crystal transition-colors">Server Age Checker</a></li>
                    </ul>
                </div>
            </div>

            <div class="mt-12 border-t border-white/5 pt-8 text-center text-xs">
                <a href="<?php echo esc_url( __( 'https://wordpress.org/', 'wos-frost-fire' ) ); ?>">
                    <?php
                    /* translators: %s: CMS name, i.e. WordPress. */
                    printf( esc_html__( 'Proudly powered by %s', 'wos-frost-fire' ), 'WordPress' );
                    ?>
                </a>
                <span class="mx-2">|</span>
                <?php
                /* translators: 1: Theme name, 2: Theme author. */
                printf( esc_html__( 'Theme: %1$s by %2$s.', 'wos-frost-fire' ), 'WoS Frost & Fire', '<a href="#">Antigravity</a>' );
                ?>
            </div>
        </div>
    </footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>

<!-- Mobile Menu Overlay -->
<div 
    x-show="mobileMenuOpen" 
    class="fixed inset-0 z-[60] bg-black/80 backdrop-blur-sm transition-opacity"
    x-transition:enter="ease-out duration-300"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="ease-in duration-200"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    @click="mobileMenuOpen = false"
    style="display: none;"
></div>

<!-- Mobile Menu Drawer -->
<div 
    x-show="mobileMenuOpen" 
    class="fixed right-0 top-0 bottom-0 z-[70] w-64 bg-deep-freeze border-l border-white/10 shadow-2xl p-6 overflow-y-auto"
    x-transition:enter="transform transition ease-out duration-300"
    x-transition:enter-start="translate-x-full"
    x-transition:enter-end="translate-x-0"
    x-transition:leave="transform transition ease-in duration-200"
    x-transition:leave-start="translate-x-0"
    x-transition:leave-end="translate-x-full"
    style="display: none;"
>
    <div class="flex justify-between items-center mb-8">
        <h2 class="text-xl font-bold text-ice-blue">MENU</h2>
        <button @click="mobileMenuOpen = false" class="text-gray-400 hover:text-white">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
        </button>
    </div>

    <!-- Navigation Links -->
    <nav class="space-y-4">
        <a href="<?php echo home_url('/'); ?>" class="block py-2 text-gray-300 hover:text-white border-b border-white/5"><?php _e('Home', 'wos-frost-fire'); ?></a>
        <a href="<?php echo get_post_type_archive_link('wos_hero'); ?>" class="block py-2 text-gray-300 hover:text-white border-b border-white/5"><?php _e('Heroes Database', 'wos-frost-fire'); ?></a>
        <a href="<?php echo get_post_type_archive_link('wos_event'); ?>" class="block py-2 text-gray-300 hover:text-white border-b border-white/5"><?php _e('Event Calendar', 'wos-frost-fire'); ?></a>
        <a href="<?php echo get_post_type_archive_link('gift_code'); ?>" class="block py-2 text-gray-300 hover:text-white border-b border-white/5"><?php _e('Gift Codes', 'wos-frost-fire'); ?></a>
        <!-- Add more links as needed -->
    </nav>
    
    <div class="mt-8 pt-8 border-t border-white/10">
        <p class="text-xs text-gray-500 text-center">&copy; <?php echo date('Y'); ?> <?php bloginfo('name'); ?></p>
    </div>
</div>

<!-- Bottom Navigation for Mobile -->
<?php get_template_part( 'parts/bottom-nav' ); ?>

</body>
</html>
