	<footer id="colophon" class="site-footer bg-black/40 backdrop-blur-lg border-t border-white/5 mt-auto text-white/60 py-8 relative z-10">
		<div class="container mx-auto px-4">
            <div class="site-info flex flex-col md:flex-row justify-between items-center gap-4">
                <div class="copyright text-sm">
                    &copy; <?php echo date( 'Y' ); ?> <span class="text-ice-blue font-bold"><?php bloginfo( 'name' ); ?></span>. All rights reserved.
                </div>
                
                <div class="credit text-xs flex gap-4">
                    <a href="#" class="hover:text-white transition-colors">Privacy Policy</a>
                    <a href="#" class="hover:text-white transition-colors">Terms of Service</a>
                </div>
            </div><!-- .site-info -->
		</div>
	</footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
