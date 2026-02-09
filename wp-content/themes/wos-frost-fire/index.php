<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package WOS_Frost_Fire
 */

get_header();
?>

<main id="primary" class="site-main min-h-screen relative overflow-hidden">
    <!-- Background Elements -->
    <div class="absolute inset-0 z-0 bg-deep-freeze">
        <div id="particles-js" class="absolute inset-0"></div>
    </div>

    <div class="container mx-auto px-4 py-12 relative z-10">
        <?php
        if ( have_posts() ) :

            if ( is_home() && ! is_front_page() ) :
                ?>
                <header class="mb-8">
                    <h1 class="text-4xl font-bold text-white drop-shadow-lg glass-text items-center justify-center flex">
                        <?php single_post_title(); ?>
                    </h1>
                </header>
                <?php
            endif;

            /* Start the Loop */
            while ( have_posts() ) :
                the_post();

                /*
                 * Include the Post-Type-specific template for the content.
                 * If you want to override this in a child theme, then include a file
                 * called content-___.php (where ___ is the Post Type name) and that will be used instead.
                 */
                 // For now, just a simple output
                ?>
                <article id="post-<?php the_ID(); ?>" <?php post_class('mb-8 p-6 rounded-xl glass-panel text-white'); ?>>
                    <header class="entry-header mb-4">
                        <?php the_title( '<h2 class="entry-title text-2xl font-bold font-display text-ice-blue"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' ); ?>
                    </header>

                    <div class="entry-content text-gray-100">
                        <?php
                        the_content(
                            sprintf(
                                wp_kses(
                                    /* translators: %s: Name of current post. Only visible to screen readers */
                                    __( 'Continue reading<span class="screen-reader-text"> "%s"</span>', 'wos-frost-fire' ),
                                    array(
                                        'span' => array(
                                            'class' => array(),
                                        ),
                                    )
                                ),
                                wp_kses_post( get_the_title() )
                            )
                        );
                        ?>
                    </div>
                </article>
                <?php
            endwhile;

            the_posts_navigation();

        else :

           echo '<p class="text-white text-center">Ready to survive the whiteout? Add some content.</p>';

        endif;
        ?>
    </div>
</main>

<?php
get_footer();
