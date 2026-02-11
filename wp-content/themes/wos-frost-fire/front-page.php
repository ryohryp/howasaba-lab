<?php
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
