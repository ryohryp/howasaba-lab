<?php
/**
 * The template for displaying the front page (Pop Style)
 *
 * @package WoS_Survival
 */

get_header();
?>

<main id="primary" class="site-main">

    <!-- Hero Section (Pop Style) -->
    <section class="relative min-h-[90vh] flex items-center pt-24 pb-20 overflow-hidden bg-surface">
        <!-- Abstract Decorative Background -->
        <div class="absolute inset-0 z-0 overflow-hidden pointer-events-none">
            <div class="absolute -top-[20%] -right-[10%] w-[70%] h-[70%] rounded-full bg-primary/5 blur-[120px]"></div>
            <div class="absolute -bottom-[10%] -left-[5%] w-[50%] h-[50%] rounded-full bg-secondary/5 blur-[100px]"></div>
            <div class="absolute inset-0 bg-[radial-gradient(#e5e7eb_1px,transparent_1px)] [background-size:40px_40px] opacity-[0.15]"></div>
        </div>

        <div class="container mx-auto px-6 relative z-10">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
                <!-- Text Content -->
                <div class="max-w-3xl">
                    <div class="mb-8 inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-secondary-container/30 text-secondary text-[10px] font-black uppercase tracking-[0.2em] shadow-sm animate-fade-in">
                        <span class="material-symbols-outlined text-sm">bolt</span>
                        <?php _e( 'Advanced Survival Protocol', 'wos-survival' ); ?>
                    </div>
                    
                    <h1 class="text-6xl md:text-8xl font-black text-on-surface tracking-tighter leading-[0.9] mb-8 animate-slide-up">
                        Whiteout<br>
                        <span class="text-primary"><?php _e( 'Survival', 'wos-survival' ); ?></span><br>
                        Intelligence
                    </h1>
                    
                    <p class="text-xl md:text-2xl text-on-surface-variant font-medium mb-10 leading-relaxed max-w-xl animate-slide-up animation-delay-200">
                        <?php _e( 'The definitive database for hero optimization, event strategy, and alliance warfare.', 'wos-survival' ); ?>
                    </p>
                    
                    <div class="flex flex-wrap gap-4 animate-slide-up animation-delay-400">
                        <a href="<?php echo get_post_type_archive_link('wos_hero'); ?>" class="btn-primary group flex items-center gap-3 py-4 px-8 text-lg">
                            <span class="material-symbols-outlined">database</span>
                            <?php _e( 'Explore Heroes', 'wos-survival' ); ?>
                        </a>
                        <a href="<?php echo get_post_type_archive_link('wos_event'); ?>" class="btn-secondary group flex items-center gap-3 py-4 px-8 text-lg">
                            <span class="material-symbols-outlined">terminal</span>
                            <?php _e( 'Command Center', 'wos-survival' ); ?>
                        </a>
                    </div>
                </div>

                <!-- Visual Element / Featured Card -->
                <div class="hidden lg:block relative animate-fade-in animation-delay-600">
                    <div class="glass-panel p-2 transform rotate-2 hover:rotate-0 transition-transform duration-700">
                        <div class="aspect-[4/5] rounded-xl overflow-hidden relative">
                             <?php 
                             $hero_bg = get_theme_mod( 'wos_hero_bg', get_template_directory_uri() . '/assets/images/hero-bg.jpg' );
                             ?>
                             <img src="<?php echo esc_url( $hero_bg ); ?>" class="w-full h-full object-cover grayscale-[0.2] contrast-[1.1]" alt="Survival Hero">
                             <div class="absolute inset-0 bg-gradient-to-t from-primary/40 to-transparent"></div>
                             
                             <!-- UI Overlay Elements -->
                             <div class="absolute bottom-8 left-8 right-8 glass-panel p-6 border-white/40">
                                 <div class="flex items-center gap-4 mb-3">
                                     <div class="w-10 h-10 rounded-full bg-primary flex items-center justify-center text-on-primary">
                                         <span class="material-symbols-outlined">verified</span>
                                     </div>
                                     <div>
                                         <div class="text-[10px] font-black uppercase tracking-widest text-on-surface/60"><?php _e( 'System Status', 'wos-survival' ); ?></div>
                                         <div class="text-sm font-black text-on-surface uppercase"><?php _e( 'Operational', 'wos-survival' ); ?></div>
                                     </div>
                                 </div>
                                 <div class="h-1 w-full bg-surface-container-high rounded-full overflow-hidden">
                                     <div class="h-full bg-primary w-[88%]"></div>
                                 </div>
                             </div>
                        </div>
                    </div>
                    <!-- Decorative Circles -->
                    <div class="absolute -top-12 -left-12 w-24 h-24 rounded-full border-[12px] border-primary/10"></div>
                    <div class="absolute -bottom-8 -right-8 w-16 h-16 rounded-full bg-secondary/20 blur-xl"></div>
                </div>
            </div>
        </div>
    </section>

    <!-- Main Content Hub -->
    <div class="container mx-auto px-6 py-24 space-y-32">
        
        <!-- Modules Grid -->
        <section>
            <div class="mb-12 flex flex-col md:flex-row md:items-end justify-between gap-6">
                <div>
                    <h2 class="text-[10px] font-black uppercase tracking-[0.3em] text-primary mb-4"><?php _e( 'Intelligence core', 'wos-survival' ); ?></h2>
                    <h3 class="text-4xl md:text-5xl font-black text-on-surface tracking-tighter leading-none"><?php _e( 'Survival Modules', 'wos-survival' ); ?></h3>
                </div>
                <p class="max-w-sm text-on-surface-variant font-medium text-sm">
                    <?php _e( 'High-performance tools specifically engineered to optimize your progression through the frost.', 'wos-survival' ); ?>
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Tool -->
                <?php
                get_template_part( 'parts/card-feature', null, [
                    'href'        => home_url( '/tools' ),
                    'icon'        => 'calculate',
                    'title'       => __( 'Strategic Tools', 'wos-survival' ),
                    'description' => __( 'Resource calculators, speed-up optimizers, and server-age deployment guides.', 'wos-survival' ),
                    'color'       => 'tertiary',
                ] );
                ?>

                <!-- Hero Database -->
                <?php
                get_template_part( 'parts/card-feature', null, [
                    'href'        => get_post_type_archive_link( 'wos_hero' ),
                    'icon'        => 'database',
                    'title'       => __( 'Hero Database', 'wos-survival' ),
                    'description' => __( 'Full breakdown of hero generations, skill synergies, and optimal gear configurations.', 'wos-survival' ),
                    'color'       => 'primary',
                ] );
                ?>

                <!-- Guide -->
                <?php
                get_template_part( 'parts/card-feature', null, [
                    'href'        => home_url( '/guide' ),
                    'icon'        => 'auto_stories',
                    'title'       => __( 'Analysis Logs', 'wos-survival' ),
                    'description' => __( 'Deep-dive analysis on game mechanics, seasonal strategies, and warfare tactics.', 'wos-survival' ),
                    'color'       => 'secondary',
                ] );
                ?>
            </div>
        </section>

        <!-- Strategy Intelligence Logs -->
        <section>
            <div class="glass-panel p-12 overflow-hidden relative">
                <!-- Decorative BG for Section -->
                <div class="absolute top-0 right-0 w-64 h-64 bg-primary/5 rounded-full blur-[80px] -mr-32 -mt-32"></div>

                <div class="flex flex-col md:flex-row items-center justify-between mb-16 gap-8 relative z-10">
                    <div>
                        <h2 class="text-3xl md:text-5xl font-black text-on-surface tracking-tighter leading-none mb-4"><?php _e( 'Latest Intelligence', 'wos-survival' ); ?></h2>
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-1 bg-primary rounded-full"></div>
                            <p class="text-on-surface-variant font-black text-[10px] uppercase tracking-widest"><?php _e( 'Data Feed: LIVE', 'wos-survival' ); ?></p>
                        </div>
                    </div>
                    <a href="<?php echo home_url('/guide'); ?>" class="btn-secondary flex items-center gap-3 py-3 px-6">
                        <span><?php _e( 'Access All Logs', 'wos-survival' ); ?></span>
                        <span class="material-symbols-outlined text-sm">arrow_right_alt</span>
                    </a>
                </div>

                <?php
                $guides_query = new WP_Query([
                    'post_type'      => 'post',
                    'posts_per_page' => 3,
                    'orderby'        => 'date',
                    'order'          => 'DESC',
                ]);

                if ( $guides_query->have_posts() ) : ?>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 relative z-10">
                        <?php while ( $guides_query->have_posts() ) : $guides_query->the_post(); ?>
                            <?php get_template_part( 'parts/article-card' ); ?>
                        <?php endwhile; wp_reset_postdata(); ?>
                    </div>
                <?php else : ?>
                    <div class="py-24 text-center border-2 border-dashed border-outline-variant/30 rounded-2xl relative z-10">
                        <span class="material-symbols-outlined text-6xl text-on-surface-variant/20 mb-6">dynamic_feed</span>
                        <p class="text-on-surface-variant font-bold uppercase tracking-widest text-xs"><?php _e( 'No intelligence logs currently detected.', 'wos-survival' ); ?></p>
                    </div>
                <?php endif; ?>
            </div>
        </section>

        <!-- Latest Intel Section -->
        <?php get_template_part( 'parts/section-latest-intel' ); ?>
    </div>
</main>

<?php
get_footer();
