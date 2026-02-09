<?php
/**
 * The template for displaying all pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package WoS_Frost_Fire
 */

get_header();
?>

<main id="primary" class="site-main container mx-auto px-4 py-8">

	<?php
	while ( have_posts() ) :
		the_post();

        ?>
        <article id="post-<?php the_ID(); ?>" <?php post_class('bg-deep-freeze/50 backdrop-blur-md rounded-xl p-6 border border-white/10 shadow-lg mb-8'); ?>>
            <header class="entry-header mb-6">
                <?php the_title( '<h1 class="entry-title text-3xl md:text-4xl font-bold text-white mb-2">', '</h1>' ); ?>
            </header><!-- .entry-header -->

            <div class="entry-content text-gray-300 prose prose-invert max-w-none">
                <?php
                the_content();

                wp_link_pages(
                    array(
                        'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'wos-frost-fire' ),
                        'after'  => '</div>',
                    )
                );
                ?>
            </div><!-- .entry-content -->

            <?php if ( get_edit_post_link() ) : ?>
                <footer class="entry-footer mt-8 pt-4 border-t border-white/10">
                    <?php
                    edit_post_link(
                        sprintf(
                            wp_kses(
                                /* translators: %s: Name of current post. Only visible to screen readers */
                                __( 'Edit <span class="screen-reader-text">%s</span>', 'wos-frost-fire' ),
                                array(
                                    'span' => array(
                                        'class' => array(),
                                    ),
                                )
                            ),
                            wp_kses_post( get_the_title() )
                        ),
                        '<span class="edit-link text-sm text-gray-400 hover:text-white transition-colors">',
                        '</span>'
                    );
                    ?>
                </footer><!-- .entry-footer -->
            <?php endif; ?>
        </article><!-- #post-<?php the_ID(); ?> -->
        <?php

		// If comments are open or we have at least one comment, load up the comment template.
		if ( comments_open() || get_comments_number() ) :
			comments_template();
		endif;

	endwhile; // End of the loop.
	?>

</main><!-- #main -->

<?php
get_footer();
