<?php
/**
 * Template part for displaying event list item
 *
 * @package WOS_Frost_Fire
 */

$start_date = get_post_meta( get_the_ID(), 'event_start_date', true );
$end_date   = get_post_meta( get_the_ID(), 'event_end_date', true );
$requirement= get_post_meta( get_the_ID(), 'server_age_requirement', true );

// Date formatting
$formatted_date = 'TBA';
if ( $start_date ) {
    $formatted_date = date( 'M j', strtotime( $start_date ) );
    if ( $end_date ) {
        $formatted_date .= ' - ' . date( 'M j', strtotime( $end_date ) );
    }
}
?>

<div id="event-<?php the_ID(); ?>" <?php post_class('glass-panel p-4 mb-4 flex flex-col md:flex-row gap-4 items-center hover:bg-white/5 transition-colors'); ?>>
    
    <!-- Date Badge -->
    <div class="flex-shrink-0 w-full md:w-24 text-center">
        <div class="text-xs text-white/50 uppercase tracking-widest mb-1">Date</div>
        <div class="font-bold text-ice-blue"><?php echo esc_html( $formatted_date ); ?></div>
    </div>

    <!-- Content -->
    <div class="flex-grow text-center md:text-left">
        <h3 class="text-xl font-bold text-white mb-1">
            <a href="<?php the_permalink(); ?>" class="hover:text-fire-crystal transition-colors"><?php the_title(); ?></a>
        </h3>
        <div class="text-sm text-white/70">
            <?php echo wp_trim_words( get_the_excerpt(), 15 ); ?>
        </div>
    </div>

    <!-- Requirement Badge -->
    <?php if ( $requirement ) : ?>
        <div class="flex-shrink-0">
            <span class="inline-block px-3 py-1 bg-red-900/40 border border-red-500/30 text-red-200 text-xs font-bold rounded-full uppercase tracking-wider">
                Server Day <?php echo esc_html( $requirement ); ?>+
            </span>
        </div>
    <?php endif; ?>

    <!-- Action -->
    <div class="flex-shrink-0">
         <a href="<?php the_permalink(); ?>" class="inline-block px-4 py-2 border border-white/20 rounded hover:bg-white/10 text-sm font-bold transition-colors">Details</a>
    </div>

</div>
