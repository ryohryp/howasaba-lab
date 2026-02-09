<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package WoS_Frost_Fire
 */

get_header();
?>

<main id="primary" class="site-main container mx-auto px-4 py-8">

	<?php
	if ( have_posts() ) :

		if ( is_home() && ! is_front_page() ) :
			?>
			<header>
				<h1 class="page-title screen-reader-text"><?php single_post_title(); ?></h1>
			</header>
			<?php
		endif;

		/* Start the Loop */
		while ( have_posts() ) :
			the_post();
            
            // Allow for a generic content part if we had one, for now just title
            ?>
            <article id="post-<?php the_ID(); ?>" <?php post_class('mb-8 rounded-xl border border-white/10 bg-white/5 p-6 backdrop-blur-md'); ?>>
                <header class="entry-header mb-4">
                    <?php the_title( sprintf( '<h2 class="entry-title text-2xl font-bold text-ice-blue"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
                </header>
                <div class="entry-content text-gray-300">
                    <?php the_excerpt(); ?>
                </div>
            </article>
            <?php

		endwhile;

		the_posts_navigation();

	else :

        ?>
        <section class="no-results not-found">
            <header class="page-header">
                <h1 class="page-title text-2xl font-bold text-white"><?php esc_html_e( 'Nothing Found', 'wos-frost-fire' ); ?></h1>
            </header>
            <div class="page-content text-gray-300">
                <p><?php esc_html_e( 'It seems we can&rsquo;t find what you&rsquo;re looking for. Perhaps searching can help.', 'wos-frost-fire' ); ?></p>
                <?php get_search_form(); ?>
            </div>
        </section>
        <?php

	endif;
	?>

</main><!-- #main -->

<?php
get_footer();
