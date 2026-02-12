	</main><!-- #content -->

	<footer id="colophon" class="site-footer bg-[#0D1B2A] text-white/40 py-12 border-t border-white/5">
		<div class="container mx-auto px-6 text-center">
			<div class="site-info text-sm flex flex-col md:flex-row items-center justify-center gap-4">
				<a href="<?php echo esc_url( __( 'https://wordpress.org/', 'wos-furnace-core' ) ); ?>" class="hover:text-white transition-colors">
					<?php
					/* translators: %s: CMS name, i.e. WordPress. */
					printf( esc_html__( 'Proudly powered by %s', 'wos-furnace-core' ), 'WordPress' );
					?>
				</a>
				<span class="hidden md:inline">|</span>
				<?php
				/* translators: 1: Theme name, 2: Theme author. */
				printf( esc_html__( 'Theme: %1$s by %2$s.', 'wos-furnace-core' ), 'wos-furnace-core', '<a href="https://howasaba-code.com" class="hover:text-white transition-colors">Antigravity</a>' );
				?>
			</div><!-- .site-info -->
            <div class="mt-8 text-xs text-white/20">
                &copy; <?php echo date( 'Y' ); ?> <?php bloginfo( 'name' ); ?>. All rights reserved.
            </div>
		</div>
	</footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
