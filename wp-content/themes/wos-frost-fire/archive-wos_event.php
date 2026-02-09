<?php
/**
 * The template for displaying event archives
 *
 * @package WOS_Frost_Fire
 */

get_header();
?>

<main id="primary" class="site-main min-h-screen relative">
    
    <!-- Background -->
    <div class="absolute inset-0 z-0 bg-deep-freeze">
        <div id="particles-js" class="absolute inset-0 opacity-20"></div>
    </div>

    <div class="relative z-10 container mx-auto px-4 py-12">
        
        <header class="page-header mb-12 text-center">
            <h1 class="page-title text-4xl font-display font-bold text-white mb-2">
                <span class="text-fire-crystal">Events</span> Calendar
            </h1>
            <p class="text-blue-100/70">
                Prepare for the challenges ahead.
            </p>
        </header>

        <div class="max-w-4xl mx-auto">
            <?php if ( have_posts() ) : ?>
                <div class="space-y-4">
                    <?php
                    while ( have_posts() ) :
                        the_post();
                        get_template_part( 'templates/event-list' );
                    endwhile;
                    ?>
                </div>

                <div class="mt-8 flex justify-center">
                    <?php
                    the_posts_pagination( array(
                        'prev_text' => '&larr;',
                        'next_text' => '&rarr;',
                        'class'     => 'flex gap-4 text-white font-bold',
                    ) );
                    ?>
                </div>

            <?php else : ?>
                <div class="text-center py-20 glass-panel rounded-xl">
                    <p class="text-xl text-white/50">No upcoming events found.</p>
                </div>
            <?php endif; ?>
        </div>

    </div>
</main>

<?php
get_footer();
