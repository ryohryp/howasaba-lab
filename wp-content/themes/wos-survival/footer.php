    <footer id="colophon" class="site-footer mt-auto border-t border-blue-100/50 bg-blue-50/50 backdrop-blur-sm py-16">
        <div class="container mx-auto px-8 flex flex-col md:flex-row justify-between items-center gap-8 text-center md:text-left">
            <div class="footer-branding flex flex-col gap-2">
                <h2 class="text-xl font-bold text-primary"><?php bloginfo( 'name' ); ?></h2>
                <p class="font-sans text-[10px] uppercase tracking-[0.2em] text-on-surface-variant font-bold">
                    &copy; <?php echo date('Y'); ?> <?php bloginfo( 'name' ); ?>. <?php _e('Not affiliated with Century Games.', 'wos-survival'); ?>
                </p>
            </div>
            
            <nav class="footer-navigation">
                <ul class="flex flex-wrap justify-center md:justify-end gap-x-8 gap-y-4">
                    <li><a href="#" class="font-sans text-[10px] uppercase tracking-widest text-on-surface-variant font-bold hover:text-primary transition-colors"><?php _e('Discord Community', 'wos-survival'); ?></a></li>
                    <li><a href="#" class="font-sans text-[10px] uppercase tracking-widest text-on-surface-variant font-bold hover:text-primary transition-colors"><?php _e('Strategy Wiki', 'wos-survival'); ?></a></li>
                    <li><a href="#" class="font-sans text-[10px] uppercase tracking-widest text-on-surface-variant font-bold hover:text-primary transition-colors"><?php _e('Hero Tier List', 'wos-survival'); ?></a></li>
                    <li><a href="#" class="font-sans text-[10px] uppercase tracking-widest text-on-surface-variant font-bold hover:text-primary transition-colors"><?php _e('Privacy Policy', 'wos-survival'); ?></a></li>
                </ul>
            </nav>
        </div>
    </footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>

<?php 
/**
 * Optional: Bottom Navigation for Mobile
 * Reskinned in the next step to match Pop Style
 */
get_template_part( 'parts/bottom-nav' ); 
?>

</body>
</html>

