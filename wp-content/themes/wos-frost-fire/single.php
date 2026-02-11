<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
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
                <!-- Date and Category -->
                <div class="flex items-center gap-4 text-sm text-gray-400 mb-4">
                    <span class="flex items-center gap-1">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <?php echo get_the_date(); ?>
                    </span>
                    <span class="text-gray-600">|</span>
                    <?php 
                    $categories = get_the_category();
                    if ( ! empty( $categories ) ) {
                        echo '<a href="' . esc_url( get_category_link( $categories[0]->term_id ) ) . '" class="text-cyan-400 hover:text-cyan-300 transition-colors">' . esc_html( $categories[0]->name ) . '</a>';
                    }
                    ?>
                </div>

                <?php the_title( '<h1 class="entry-title text-3xl md:text-4xl font-bold text-white mb-6 leading-tight">', '</h1>' ); ?>

                <?php if ( has_post_thumbnail() ) : ?>
                    <div class="post-thumbnail mb-8 rounded-xl overflow-hidden shadow-lg border border-white/10">
                        <?php the_post_thumbnail( 'full', array( 'class' => 'w-full h-auto object-cover' ) ); ?>
                    </div>
                <?php endif; ?>

            </header><!-- .entry-header -->

            <div class="entry-content text-gray-300 prose prose-invert prose-lg max-w-none prose-headings:text-ice-blue prose-a:text-cyan-400 hover:prose-a:text-cyan-300 prose-blockquote:border-l-ice-blue prose-blockquote:bg-white/5 prose-blockquote:py-2 prose-blockquote:px-4 prose-code:text-pink-300">
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

            <footer class="entry-footer mt-12 pt-8 border-t border-white/10">
                
                <!-- Tags -->
                <?php
                $tags_list = get_the_tag_list( '', ' ' );
                if ( $tags_list ) {
                    printf( '<div class="tags-links flex flex-wrap gap-2 mb-6">%s</div>', $tags_list ); // Note: Ensure your CSS styles tags appropriately
                }
                ?>

                <!-- Prev/Next Navigation -->
                <div class="post-navigation border-t border-white/5 pt-8">
                    <?php
                    the_post_navigation(
                        array(
                            'prev_text' => '<span class="nav-subtitle text-xs text-gray-400 uppercase tracking-wider mb-1 block">' . esc_html__( 'Previous:', 'wos-frost-fire' ) . '</span> <span class="nav-title text-ice-blue hover:text-white transition-colors">%title</span>',
                            'next_text' => '<span class="nav-subtitle text-xs text-gray-400 uppercase tracking-wider mb-1 block">' . esc_html__( 'Next:', 'wos-frost-fire' ) . '</span> <span class="nav-title text-ice-blue hover:text-white transition-colors">%title</span>',
                            'class'     => 'grid grid-cols-1 md:grid-cols-2 gap-8',
                        )
                    );
                    ?>
                </div>

            </footer><!-- .entry-footer -->

        </article><!-- #post-<?php the_ID(); ?> -->
        <?php

		// If comments are open or we have at least one comment, load up the comment template.
		if ( comments_open() || get_comments_number() ) :
            ?>
            <div class="bg-deep-freeze/50 backdrop-blur-md rounded-xl p-6 border border-white/10 shadow-lg">
			    <?php comments_template(); ?>
            </div>
            <?php
		endif;

	endwhile; // End of the loop.
	?>

</main><!-- #main -->

<?php
get_footer();
