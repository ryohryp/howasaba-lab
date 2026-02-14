<?php
/**
 * The template for displaying the front page
 *
 * @package WOS_Frost_Fire
 */

get_header();
?>

<main id="primary" class="site-main">

    <!-- Hero Section (Flat) -->
    <section class="relative min-h-[60vh] flex items-center justify-center overflow-hidden bg-slate-900 pt-20 pb-20">
        <!-- Background Image -->
        <div class="absolute inset-0 z-0 opacity-40 mix-blend-overlay">
            <?php 
            $hero_bg = get_theme_mod( 'wos_hero_bg', get_template_directory_uri() . '/assets/images/hero-bg.jpg' );
            ?>
            <img src="<?php echo esc_url( $hero_bg ); ?>" alt="Background" class="w-full h-full object-cover">
        </div>
        
        <!-- Content -->
        <div class="relative z-10 container mx-auto px-4 text-center">
            <div class="inline-block mb-6 px-4 py-1 rounded-full bg-slate-800/80 border border-slate-700 text-ice-blue text-sm font-bold tracking-widest uppercase shadow-lg backdrop-blur-sm">
                <?php _e( 'Frost & Fire', 'wos-frost-fire' ); ?>
            </div>
            <h1 class="mb-6 text-5xl md:text-7xl font-black text-white uppercase tracking-tighter drop-shadow-2xl leading-none">
                Survive the <span class="text-ice-blue">Whiteout</span><br>
                Conquer the <span class="text-fire-crystal">Cold</span>
            </h1>
            <p class="mb-10 text-xl md:text-2xl text-slate-300 max-w-2xl mx-auto font-light">
                <?php _e( 'The ultimate database and strategy guide for Whiteout Survival.', 'wos-frost-fire' ); ?>
            </p>
            
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="<?php echo get_post_type_archive_link('wos_hero'); ?>" class="fire-crystal-btn text-lg px-8 py-4">
                    <?php _e( 'Find Heroes', 'wos-frost-fire' ); ?>
                </a>
                <a href="<?php echo home_url('/guide'); ?>" class="px-8 py-4 rounded-lg font-bold text-white bg-slate-700 hover:bg-slate-600 transition-colors border border-slate-600">
                    <?php _e( 'Read Guides', 'wos-frost-fire' ); ?>
                </a>
            </div>
        </div>
        
        <!-- Bottom Fade to Solid Color -->
        <div class="absolute bottom-0 left-0 right-0 h-32 bg-gradient-to-t from-slate-900 to-transparent"></div>
    </section>

    <!-- Command Center / Dynamic Sections -->
    <div class="container mx-auto px-4 py-16 space-y-24">
            <!-- Features Grid -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Tool -->
                <?php
                get_template_part( 'parts/card-feature', null, [
                    'href'          => home_url( '/tools' ),
                    'icon_bg_class' => 'bg-cyan-400/10',
                    'icon_path'     => 'parts/icons/wrench',
                    'title'         => __( 'Tools', 'wos-frost-fire' ),
                    'description'   => __( 'Access WOS-Navi, bear hunt calculator, and other custom tools.', 'wos-frost-fire' ),
                ] );
                ?>

                <!-- Hero Database -->
                <?php
                get_template_part( 'parts/card-feature', null, [
                    'href'          => get_post_type_archive_link( 'wos_hero' ),
                    'icon_bg_class' => 'bg-blue-200/10',
                    'icon_path'     => 'parts/icons/shield',
                    'title'         => __( 'Hero Database', 'wos-frost-fire' ),
                    'description'   => __( 'Detailed analysis of hero skills, generations, and optimal builds.', 'wos-frost-fire' ),
                ] );
                ?>

                <!-- Guide -->
                <?php
                get_template_part( 'parts/card-feature', null, [
                    'href'          => home_url( '/guide' ),
                    'icon_bg_class' => 'bg-emerald-400/10',
                    'icon_path'     => 'parts/icons/file-text',
                    'title'         => __( 'Strategy Guides', 'wos-frost-fire' ),
                    'description'   => __( 'Comprehensive guides on events, warfare, and city management.', 'wos-frost-fire' ),
                ] );
                ?>
            </div>

            <!-- Latest Info Section -->
            <?php get_template_part( 'parts/section-latest-intel' ); ?>
        </div>
    </section>

</main>

<?php
get_footer();
