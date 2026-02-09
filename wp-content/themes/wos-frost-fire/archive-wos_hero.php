<?php
/**
 * The template for displaying hero archives
 *
 * @package WOS_Frost_Fire
 */

get_header();
?>

<main id="primary" class="site-main min-h-screen relative">
    
    <!-- Background -->
    <div class="absolute inset-0 z-0 bg-deep-freeze">
        <div id="particles-js" class="absolute inset-0 opacity-30"></div>
    </div>

    <div class="relative z-10 container mx-auto px-4 py-12">
        
        <header class="page-header mb-12 text-center">
            <h1 class="page-title text-5xl font-display font-bold text-transparent bg-clip-text bg-gradient-to-b from-white to-ice-blue mb-4">
                <?php post_type_archive_title(); ?>
            </h1>
            <p class="text-blue-100 max-w-2xl mx-auto">
                Commanders of the tundra. Assemble your squad and conquer the cold.
            </p>
        </header>

        <!-- Filters (Mockup for now, could be dynamic later) -->
        <div class="flex flex-wrap justify-center gap-4 mb-12">
            <a href="<?php echo get_post_type_archive_link('wos_hero'); ?>" class="px-4 py-2 rounded-full glass-panel hover:bg-white/20 transition-colors text-sm font-bold uppercase tracking-wider text-white">All</a>
            <!-- Note: In a real implementation, you'd loop through taxonomies here -->
            <button class="px-4 py-2 rounded-full border border-white/20 hover:bg-white/10 transition-colors text-sm font-bold uppercase tracking-wider text-white/70">Infantry</button>
            <button class="px-4 py-2 rounded-full border border-white/20 hover:bg-white/10 transition-colors text-sm font-bold uppercase tracking-wider text-white/70">Lancer</button>
            <button class="px-4 py-2 rounded-full border border-white/20 hover:bg-white/10 transition-colors text-sm font-bold uppercase tracking-wider text-white/70">Marksman</button>
        </div>

        <?php if ( have_posts() ) : ?>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <?php
                while ( have_posts() ) :
                    the_post();
                    get_template_part( 'templates/hero-card' );
                endwhile;
                ?>
            </div>

            <div class="mt-12 flex justify-center">
                <?php
                the_posts_pagination( array(
                    'prev_text' => '<span class="screen-reader-text">' . __( 'Previous', 'wos-frost-fire' ) . '</span> &larr;',
                    'next_text' => '<span class="screen-reader-text">' . __( 'Next', 'wos-frost-fire' ) . '</span> &rarr;',
                    'class'     => 'flex gap-2',
                ) );
                ?>
            </div>

        <?php else : ?>
            <div class="text-center py-20">
                <p class="text-xl text-white/50">No heroes found in the archives.</p>
            </div>
        <?php endif; ?>

    </div>
</main>

<?php
get_footer();
