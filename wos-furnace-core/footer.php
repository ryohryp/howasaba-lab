	</main><!-- #content -->

	<footer id="colophon" class="site-footer bg-secondary text-text/60 py-8 border-t border-accent/10">
		<div class="container mx-auto px-4 text-center">
			<div class="site-info">
				<a href="<?php echo esc_url( __( 'https://wordpress.org/', 'wos-furnace-core' ) ); ?>" class="hover:text-accent transition-colors">
					<?php
					/* translators: %s: CMS name, i.e. WordPress. */
					printf( esc_html__( 'Proudly powered by %s', 'wos-furnace-core' ), 'WordPress' );
					?>
				</a>
				<span class="sep"> | </span>
				<?php
				/* translators: 1: Theme name, 2: Theme author. */
				printf( esc_html__( 'Theme: %1$s by %2$s.', 'wos-furnace-core' ), 'wos-furnace-core', '<a href="https://howasaba-code.com" class="hover:text-accent transition-colors">Antigravity</a>' );
				?>
			</div><!-- .site-info -->
            <div class="mt-4 text-xs font-mono text-accent/40">
                SYSTEM STATUS: ONLINE
            </div>
		</div>
	</footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
