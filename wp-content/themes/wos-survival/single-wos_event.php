<?php
/**
 * The template for displaying all single Event posts
 *
 * @package WoS_Frost_Fire
 */

get_header();

// Get Event Meta Data
$event_id = get_the_ID();
$meta = wos_get_event_meta( $event_id );
$start_date = $meta['start_date'];
$duration = $meta['duration'];
$server_age = $meta['server_age'];

// Determine status logic (Reusing logic from archive for consistency)
$today = date('Y-m-d');
$status = 'active';
if ( $start_date > $today ) {
    $status = 'upcoming';
} elseif ( $start_date < date('Y-m-d', strtotime('-3 days')) ) { 
    $status = 'past';
}

$status_labels = [
    'active'   => [ 'label' => 'LIVE', 'color' => 'bg-red-500/80' ],
    'upcoming' => [ 'label' => 'SOON', 'color' => 'bg-blue-500/80' ],
    'past'     => [ 'label' => 'ENDED', 'color' => 'bg-gray-500/80' ],
];
$current_status = $status_labels[$status];
?>

<main id="primary" class="site-main container mx-auto px-4 py-8">

    <?php while ( have_posts() ) : the_post(); ?>

        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
            
            <!-- Event Hero Section -->
            <div class="relative mb-8 rounded-2xl overflow-hidden shadow-2xl border border-white/10 bg-gradient-to-br from-deep-freeze to-midnight-navy">
                <div class="grid md:grid-cols-2 gap-0 relative z-10">
                    <!-- Image/Visual Column -->
                    <div class="relative h-64 md:h-auto min-h-[300px] bg-black/40 flex items-center justify-center overflow-hidden">
                         <?php if ( has_post_thumbnail() ) : ?>
                            <?php the_post_thumbnail( 'large', array( 'class' => 'absolute inset-0 h-full w-full object-cover opacity-80' ) ); ?>
                        <?php else : ?>
                            <div class="text-6xl text-white/20">📅</div>
                        <?php endif; ?>
                        
                        <!-- Status Badge -->
                        <div class="absolute top-4 left-4">
                             <span class="inline-flex items-center gap-2 rounded-full <?php echo $current_status['color']; ?> px-4 py-1 text-sm font-bold text-white shadow-lg backdrop-blur-sm border border-white/20">
                                <?php if ($status === 'active'): ?><span class="h-2 w-2 rounded-full bg-white animate-pulse"></span><?php endif; ?>
                                <?php echo $current_status['label']; ?>
                            </span>
                        </div>
                    </div>

                    <!-- Info Column -->
                    <div class="p-8 md:p-12 flex flex-col justify-center relative bg-black/20 backdrop-blur-sm">
                        <h1 class="mb-4 text-3xl md:text-5xl font-black text-white leading-tight drop-shadow-lg">
                            <?php the_title(); ?>
                        </h1>

                        <div class="flex flex-col gap-4 text-gray-200 mb-6">
                            <div class="flex items-center gap-3 text-lg">
                                <span class="flex items-center justify-center w-8 h-8 rounded-full bg-white/10">🗓️</span>
                                <div>
                                    <div class="text-xs text-ice-blue uppercase tracking-wider font-bold">Start Date</div>
                                    <div class="font-bold"><?php echo esc_html( $start_date ?: 'TBA' ); ?></div>
                                </div>
                            </div>
                            <div class="flex items-center gap-3 text-lg">
                                <span class="flex items-center justify-center w-8 h-8 rounded-full bg-white/10">⏱️</span>
                                <div>
                                    <div class="text-xs text-ice-blue uppercase tracking-wider font-bold">Duration</div>
                                    <div class="font-bold"><?php echo esc_html( $duration ?: 'Unknown' ); ?></div>
                                </div>
                            </div>
                            <?php if ( $server_age ) : ?>
                            <div class="flex items-center gap-3 text-lg">
                                <span class="flex items-center justify-center w-8 h-8 rounded-full bg-white/10">🌍</span>
                                <div>
                                    <div class="text-xs text-ice-blue uppercase tracking-wider font-bold">Requirement</div>
                                    <div class="font-bold">Server Age <?php echo esc_html( $server_age ); ?> Days+</div>
                                </div>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                
                <!-- Background decoration -->
                <div class="absolute inset-0 bg-gradient-to-r from-deep-freeze/20 to-fire-crystal/10 mix-blend-overlay pointer-events-none"></div>
            </div>

            <!-- Content Section -->
            <div class="rounded-xl border border-white/10 bg-white/5 p-8 backdrop-blur-md">
                <h3 class="mb-6 text-2xl font-bold text-ice-blue border-b border-white/10 pb-4">Event Details</h3>
                <div class="prose prose-invert prose-lg max-w-none text-gray-300">
                    <?php the_content(); ?>
                </div>
            </div>
            
            <!-- Navigation -->
            <div class="mt-8 flex justify-between">
                <a href="<?php echo get_post_type_archive_link('wos_event'); ?>" class="inline-flex items-center gap-2 px-6 py-3 rounded-full bg-white/5 hover:bg-white/10 border border-white/10 text-ice-blue transition-colors">
                    ← Back to Events
                </a>
            </div>

        </article><!-- #post-<?php the_ID(); ?> -->

    <?php endwhile; // End of the loop. ?>

</main><!-- #main -->

<?php
get_footer();
