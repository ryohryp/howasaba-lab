<?php
/**
 * Template Name: Strategy Guides
 *
 * The template for displaying the Strategy Guides page (Blog roll).
 *
 * @package WOS_Frost_Fire
 */

get_header();
?>

<main id="primary" class="site-main">

    <!-- Header -->
    <header class="relative bg-slate-900 py-20 overflow-hidden">
        <div class="absolute inset-0 z-0 opacity-30">
             <?php 
            $hero_bg = get_theme_mod( 'wos_hero_bg', get_template_directory_uri() . '/assets/images/hero-bg.jpg' );
            ?>
            <div class="w-full h-full bg-cover bg-center" style="background-image: url('<?php echo esc_url( $hero_bg ); ?>'); background-color: #0f172a;"></div>
        </div>
        <div class="relative z-10 container mx-auto px-4 text-center">
            <h1 class="text-4xl md:text-5xl font-black text-white uppercase tracking-tight mb-4">
                <?php _e( 'Strategy Guides', 'wos-frost-fire' ); ?>
            </h1>
            <p class="text-xl text-slate-300 max-w-2xl mx-auto">
                <?php _e( 'Master the game with our expert guides and tips.', 'wos-frost-fire' ); ?>
            </p>
        </div>
        <!-- Fade -->
        <div class="absolute bottom-0 left-0 right-0 h-24 bg-gradient-to-t from-deep-freeze to-transparent"></div>
    </header>

    <div class="container mx-auto px-4 py-12">
        <!-- Post Grid -->
        <?php
        $paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
        $args = [
            'post_type'      => 'post',
            'posts_per_page' => 9,
            'paged'          => $paged,
        ];
        $guides_query = new WP_Query( $args );

        if ( $guides_query->have_posts() ) : ?>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <?php while ( $guides_query->have_posts() ) : $guides_query->the_post(); ?>
                    <?php get_template_part( 'parts/article-card' ); ?>
                <?php endwhile; ?>
            </div>

            <!-- Pagination -->
            <div class="mt-12 flex justify-center">
                <?php
                echo paginate_links( [
                    'total'     => $guides_query->max_num_pages,
                    'current'   => $paged,
                    'prev_text' => '&larr;',
                    'next_text' => '&rarr;',
                    'type'      => 'list',
                    'end_size'  => 3,
                    'mid_size'  => 3,
                ] );
                ?>
            </div>
            
            <?php wp_reset_postdata(); ?>

        <?php else : ?>
            <div class="text-center py-20 border border-dashed border-slate-700 rounded-xl bg-slate-800/50">
                <p class="text-xl text-gray-400 mb-4"><?php _e( 'No guides found.', 'wos-frost-fire' ); ?></p>
                <a href="<?php echo home_url('/'); ?>" class="text-ice-blue hover:text-white underline"><?php _e( 'Back to Home', 'wos-frost-fire' ); ?></a>
            </div>
        <?php endif; ?>
    </div>

</main>

<?php
get_footer();
