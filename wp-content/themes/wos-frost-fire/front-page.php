<?php
/**
 * The template for displaying the front page
 *
 * @package WOS_Frost_Fire
 */

get_header();
?>

<main id="primary" class="site-main">

    <!-- Hero Section: Immersive Frost Effect -->
    <section class="relative min-h-screen flex items-center justify-center overflow-hidden">
        <!-- Background Video/Image Placeholder -->
        <div class="absolute inset-0 z-0 bg-deep-freeze">
            <div id="particles-js" class="absolute inset-0 opacity-50"></div>
            <!-- Gradient Overlay -->
            <div class="absolute inset-0 bg-gradient-to-t from-deep-freeze via-transparent to-transparent"></div>
        </div>

        <!-- Content -->
        <div class="relative z-10 container mx-auto px-4 text-center" x-data="{ hover: false }">
            <h1 class="text-6xl md:text-8xl font-display font-bold text-transparent bg-clip-text bg-gradient-to-b from-white to-ice-blue mb-6 drop-shadow-[0_0_15px_rgba(224,247,250,0.5)] transform transition-transform duration-700 hover:scale-105">
                SURVIVE THE<br><span class="text-ice-blue">WHITEOUT</span>
            </h1>
            
            <p class="text-xl md:text-2xl text-blue-100 mb-10 max-w-2xl mx-auto font-light tracking-wide">
                Built for the frozen apocalypse. <br>Design your legacy in the ice.
            </p>

            <div class="flex flex-col md:flex-row gap-6 justify-center items-center">
                <a href="#" class="glass-btn text-white text-lg">
                    <span>Enter the Furnace</span>
                </a>
                
                <a href="#" class="px-6 py-2 rounded-full border border-white/30 text-white hover:bg-white/10 transition-colors uppercase tracking-widest text-sm font-bold">
                    View Heroes
                </a>
            </div>
        </div>
    </section>

    <!-- Latest Heroes Section (Glassmorphism Cards) -->
    <section class="py-20 relative z-10 bg-gradient-to-b from-deep-freeze to-midnight">
        <div class="container mx-auto px-4">
            <div class="flex justify-between items-end mb-12">
                <h2 class="text-4xl font-display font-bold text-white">Latest <span class="text-fire-crystal">Heroes</span></h2>
                <a href="<?php echo get_post_type_archive_link('wos_hero'); ?>" class="text-ice-blue hover:text-white transition-colors">View All &rarr;</a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <?php
                $hero_query = new WP_Query( array(
                    'post_type'      => 'wos_hero',
                    'posts_per_page' => 3,
                ) );

                if ( $hero_query->have_posts() ) :
                    while ( $hero_query->have_posts() ) : $hero_query->the_post();
                        ?>
                        <div class="glass-panel p-6 rounded-2xl hover:-translate-y-2 transition-transform duration-300 group">
                            <div class="aspect-w-3 aspect-h-4 mb-4 rounded-xl overflow-hidden bg-black/20">
                                <?php if ( has_post_thumbnail() ) : ?>
                                    <?php the_post_thumbnail( 'large', array( 'class' => 'w-full h-full object-cover group-hover:scale-110 transition-transform duration-500' ) ); ?>
                                <?php else : ?>
                                    <div class="w-full h-full flex items-center justify-center text-white/20">No Image</div>
                                <?php endif; ?>
                            </div>
                            
                            <h3 class="text-2xl font-bold text-white mb-2"><?php the_title(); ?></h3>
                            
                            <div class="flex gap-2 mb-4">
                                <?php
                                $types = get_the_terms( get_the_ID(), 'hero_type' );
                                if ( $types && ! is_wp_error( $types ) ) :
                                    foreach ( $types as $type ) :
                                        ?>
                                        <span class="text-xs font-bold uppercase tracking-wider px-2 py-1 rounded bg-white/10 text-ice-blue"><?php echo esc_html( $type->name ); ?></span>
                                        <?php
                                    endforeach;
                                endif;
                                ?>
                            </div>
                            
                            <a href="<?php the_permalink(); ?>" class="inline-block text-sm font-bold text-fire-crystal hover:text-white transition-colors uppercase tracking-widest">
                                View Data
                            </a>
                        </div>
                        <?php
                    endwhile;
                    wp_reset_postdata();
                else :
                    echo '<p class="text-white/50">No heroes found.</p>';
                endif;
                ?>
            </div>
        </div>
    </section>

</main>

<?php
get_footer();
