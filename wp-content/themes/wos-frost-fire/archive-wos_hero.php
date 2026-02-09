<?php
/**
 * The template for displaying Hero Archive pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package WoS_Frost_Fire
 */

get_header();
?>

<main id="primary" class="site-main container mx-auto px-4 py-8">

    <header class="page-header mb-12 text-center">
        <h1 class="page-title mb-4 text-4xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-ice-blue to-white drop-shadow-lg">
            <?php post_type_archive_title(); ?>
        </h1>
        <div class="archive-description mx-auto max-w-2xl text-gray-300">
            <?php the_archive_description(); ?>
            <p><?php _e( 'Explore the heroes of the frozen apocalypse. Check their stats, skills, and generation availability.', 'wos-frost-fire' ); ?></p>
        </div>
    </header><!-- .page-header -->

    <!-- Server Age Calculator Widget -->
    <section class="mb-12 mx-auto max-w-md">
        <?php get_template_part( 'parts/calculator-server-age' ); ?>
    </section>

    <!-- Filters (Placeholder for now, could be Alpine-driven later) -->
    <div class="mb-8 flex flex-wrap justify-center gap-4">
        <!-- Example static filter buttons -->
        <button class="rounded-full bg-white/10 px-4 py-2 text-sm text-white hover:bg-fire-crystal hover:text-white transition-colors">All Gens</button>
        <button class="rounded-full bg-white/5 px-4 py-2 text-sm text-gray-300 hover:bg-fire-crystal hover:text-white transition-colors">Gen 1</button>
        <button class="rounded-full bg-white/5 px-4 py-2 text-sm text-gray-300 hover:bg-fire-crystal hover:text-white transition-colors">Infantry</button>
        <button class="rounded-full bg-white/5 px-4 py-2 text-sm text-gray-300 hover:bg-fire-crystal hover:text-white transition-colors">Lancer</button>
        <button class="rounded-full bg-white/5 px-4 py-2 text-sm text-gray-300 hover:bg-fire-crystal hover:text-white transition-colors">Marksman</button>
    </div>

    <?php if ( have_posts() ) : ?>

        <div class="grid grid-cols-2 gap-6 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5">
            <?php
            /* Start the Loop */
            while ( have_posts() ) :
                the_post();

                /*
                 * Include the Post-Type-specific template for the content.
                 * If you want to override this in a child theme, then include a file
                 * called content-wos_hero.php and that will be used instead.
                 */
                get_template_part( 'parts/hero-card' );

            endwhile;
            ?>
        </div>

        <div class="mt-12 text-center">
            <?php
            the_posts_navigation( array(
                'prev_text' => __( '← Previous Heroes', 'wos-frost-fire' ),
                'next_text' => __( 'Next Heroes →', 'wos-frost-fire' ),
                'class'     => 'pagination flex justify-center gap-4',
            ) );
            ?>
        </div>

    <?php else : ?>

        <div class="rounded-xl border border-white/10 bg-white/5 p-8 text-center text-gray-400">
            <p><?php _e( 'No heroes found. The tundra is empty...', 'wos-frost-fire' ); ?></p>
        </div>

    <?php endif; ?>

</main><!-- #main -->

<?php
get_footer();
