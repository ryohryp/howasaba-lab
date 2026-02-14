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

</body>
</html>
