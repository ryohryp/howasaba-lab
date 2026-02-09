/**
 * The template for displaying the front page
 *
 * @package WOS_Frost_Fire
 */

get_header();
?>

<main id="primary" class="site-main">

    <!-- Hero Section: COMMAND CENTER -->
    <section class="relative min-h-screen flex flex-col justify-center overflow-hidden py-20">
        <!-- Background Video/Image Placeholder -->
        <div class="absolute inset-0 z-0 bg-deep-freeze">
            <div id="particles-js" class="absolute inset-0 opacity-20"></div>
            <!-- Gradient Overlay -->
            <div class="absolute inset-0 bg-gradient-to-t from-deep-freeze via-deep-freeze/80 to-transparent"></div>
        </div>

        <!-- Content -->
        <div class="relative z-10 container mx-auto px-4 space-y-12">
            
            <!-- Header -->
            <div class="text-center space-y-4" x-data="{ show: false }" x-init="setTimeout(() => show = true, 100)">
                <h1 
                    class="text-4xl md:text-6xl font-display font-bold text-transparent bg-clip-text bg-gradient-to-r from-white via-ice-blue to-blue-400 opacity-0 transition-all duration-700 transform translate-y-5"
                    :class="{ 'opacity-100 translate-y-0': show }"
                >
                    COMMAND CENTER
                </h1>
                <p 
                    class="text-blue-200/60 text-lg max-w-2xl mx-auto opacity-0 transition-opacity duration-700 delay-200"
                    :class="{ 'opacity-100': show }"
                >
                    <?php _e( 'Hub for Whiteout Survival analysis, tools, and strategy intel.', 'wos-frost-fire' ); ?>
                </p>
            </div>

            <!-- Features Grid -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Tool -->
                <div class="group backdrop-blur-xl bg-white/5 border border-white/10 shadow-[0_8px_32px_0_rgba(0,0,0,0.37)] rounded-2xl p-6 hover:border-blue-200/30 transition-colors duration-300">
                    <a href="<?php echo home_url( '/tools' ); ?>" class="flex flex-col h-full">
                        <div class="w-12 h-12 rounded-xl bg-cyan-400/10 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform duration-300">
                            <!-- Wrench Icon (SVG) -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-cyan-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/></svg>
                        </div>
                        <h3 class="text-xl font-bold text-blue-100 mb-2 group-hover:text-white transition-colors"><?php _e( 'Tools', 'wos-frost-fire' ); ?></h3>
                        <p class="text-blue-200/60 text-sm mb-6 flex-1">
                            <?php _e( 'Access WOS-Navi, bear hunt calculator, and other custom tools.', 'wos-frost-fire' ); ?>
                        </p>
                        <div class="flex items-center text-sm font-medium text-blue-200 group-hover:text-cyan-400 transition-colors mt-auto">
                            <span><?php _e( 'Access Modules', 'wos-frost-fire' ); ?></span>
                            <!-- ArrowRight Icon -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 ml-2 group-hover:translate-x-1 transition-transform" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                        </div>
                    </a>
                </div>

                <!-- Hero Database -->
                <div class="group backdrop-blur-xl bg-white/5 border border-white/10 shadow-[0_8px_32px_0_rgba(0,0,0,0.37)] rounded-2xl p-6 hover:border-blue-200/30 transition-colors duration-300">
                    <a href="<?php echo get_post_type_archive_link( 'wos_hero' ); ?>" class="flex flex-col h-full">
                        <div class="w-12 h-12 rounded-xl bg-blue-200/10 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform duration-300">
                            <!-- Shield Icon -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-blue-200" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z"/></svg>
                        </div>
                        <h3 class="text-xl font-bold text-blue-100 mb-2 group-hover:text-white transition-colors"><?php _e( 'Hero Database', 'wos-frost-fire' ); ?></h3>
                        <p class="text-blue-200/60 text-sm mb-6 flex-1">
                            <?php _e( 'Detailed analysis of hero skills, generations, and optimal builds.', 'wos-frost-fire' ); ?>
                        </p>
                        <div class="flex items-center text-sm font-medium text-blue-200 group-hover:text-cyan-400 transition-colors mt-auto">
                            <span><?php _e( 'Access Modules', 'wos-frost-fire' ); ?></span>
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 ml-2 group-hover:translate-x-1 transition-transform" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                        </div>
                    </a>
                </div>

                <!-- Guide -->
                <div class="group backdrop-blur-xl bg-white/5 border border-white/10 shadow-[0_8px_32px_0_rgba(0,0,0,0.37)] rounded-2xl p-6 hover:border-blue-200/30 transition-colors duration-300">
                    <a href="<?php echo home_url( '/guide' ); ?>" class="flex flex-col h-full">
                        <div class="w-12 h-12 rounded-xl bg-emerald-400/10 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform duration-300">
                            <!-- FileText Icon -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-emerald-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z"/><path d="M14 2v4a2 2 0 0 0 2 2h4"/><path d="M10 9H8"/><path d="M16 13H8"/><path d="M16 17H8"/></svg>
                        </div>
                        <h3 class="text-xl font-bold text-blue-100 mb-2 group-hover:text-white transition-colors"><?php _e( 'Strategy Guides', 'wos-frost-fire' ); ?></h3>
                        <p class="text-blue-200/60 text-sm mb-6 flex-1">
                            <?php _e( 'Comprehensive guides on events, warfare, and city management.', 'wos-frost-fire' ); ?>
                        </p>
                        <div class="flex items-center text-sm font-medium text-blue-200 group-hover:text-cyan-400 transition-colors mt-auto">
                            <span><?php _e( 'Access Modules', 'wos-frost-fire' ); ?></span>
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 ml-2 group-hover:translate-x-1 transition-transform" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                        </div>
                    </a>
                </div>
            </div>

            <!-- Latest Info Section -->
            <section>
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-2xl font-bold text-blue-100 flex items-center gap-2">
                        <!-- Swords Icon -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-blue-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="14.5 17.5 3 6 3 3 6 3 17.5 14.5"/><line x1="13" x2="19" y1="19" y2="13"/><line x1="16" x2="20" y1="16" y2="20"/><line x1="19" x2="21" y1="21" y2="19"/></svg>
                        <?php _e( 'Latest Intel', 'wos-frost-fire' ); ?>
                    </h2>
                    <a href="<?php echo home_url( '/guide' ); ?>" class="text-sm text-cyan-400 hover:underline"><?php _e( 'View All', 'wos-frost-fire' ); ?></a>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <?php
                    // Query for latest posts (assuming 'post' type for guides for now, or use 'wos_guide' if CPT exists)
                    $latest_guides = new WP_Query( [
                        'post_type'      => 'post', // Adjust if you have a specific CPT for guides
                        'posts_per_page' => 2,
                        'ignore_sticky_posts' => 1,
                    ] );

                    if ( $latest_guides->have_posts() ) :
                        while ( $latest_guides->have_posts() ) : $latest_guides->the_post();
                            ?>
                            <div class="group backdrop-blur-xl bg-white/5 border border-white/10 shadow-lg rounded-2xl p-4 hover:bg-white/10 hover:border-blue-200/30 cursor-pointer transition-all duration-300">
                                <a href="<?php the_permalink(); ?>" class="block">
                                    <div class="flex justify-between items-start mb-2">
                                        <span class="text-xs font-mono text-cyan-400 bg-cyan-400/10 px-2 py-1 rounded">GUIDE</span>
                                        <span class="text-xs text-blue-200/40"><?php echo get_the_date( 'Y.m.d' ); ?></span>
                                    </div>
                                    <h3 class="font-bold text-lg text-blue-100 mb-1 group-hover:text-white transition-colors"><?php the_title(); ?></h3>
                                    <div class="text-sm text-blue-200/60 line-clamp-2">
                                        <?php the_excerpt(); ?>
                                    </div>
                                </a>
                            </div>
                            <?php
                        endwhile;
                        wp_reset_postdata();
                    else :
                        ?>
                        <div class="col-span-full text-center py-8 text-blue-200/50">
                            <?php _e( 'No recent intel found.', 'wos-frost-fire' ); ?>
                        </div>
                        <?php
                    endif;
                    ?>
                </div>
            </section>
        </div>
    </section>

</main>

<?php
get_footer();
