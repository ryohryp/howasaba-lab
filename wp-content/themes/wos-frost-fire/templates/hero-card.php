<?php
/**
 * Template part for displaying hero card
 *
 * @package WOS_Frost_Fire
 */

$hero_id = get_the_ID();
$generation = get_the_terms( $hero_id, 'hero_generation' );
$types = get_the_terms( $hero_id, 'hero_type' );
$rarity = get_the_terms( $hero_id, 'hero_rarity' );
?>

<article id="hero-<?php the_ID(); ?>" <?php post_class('glass-panel p-6 rounded-2xl transition-all duration-300 hover:scale-105 hover:shadow-[0_0_20px_rgba(224,247,250,0.3)] flex flex-col h-full group'); ?>>
    
    <!-- Hero Image -->
    <div class="relative aspect-[3/4] mb-4 rounded-xl overflow-hidden bg-black/20">
        <?php if ( has_post_thumbnail() ) : ?>
            <?php the_post_thumbnail( 'large', array( 'class' => 'w-full h-full object-cover transition-transform duration-700 group-hover:scale-110' ) ); ?>
        <?php else : ?>
            <div class="w-full h-full flex items-center justify-center text-white/20 bg-gradient-to-br from-white/5 to-transparent">
                <span class="text-4xl">❄️</span>
            </div>
        <?php endif; ?>

        <!-- Rarity Badge -->
        <?php if ( ! empty( $rarity ) && ! is_wp_error( $rarity ) ) : ?>
            <div class="absolute top-2 right-2 px-2 py-1 rounded bg-black/60 backdrop-blur-sm border border-white/10 text-xs font-bold text-yellow-400 uppercase">
                <?php echo esc_html( $rarity[0]->name ); ?>
            </div>
        <?php endif; ?>
    </div>

    <!-- Header -->
    <header class="mb-auto">
        <h2 class="text-xl font-bold font-display text-white mb-2 group-hover:text-ice-blue transition-colors"><?php the_title(); ?></h2>
        
        <div class="flex flex-wrap gap-2 mb-4">
            <!-- Type -->
            <?php if ( ! empty( $types ) && ! is_wp_error( $types ) ) : ?>
                <span class="text-xs font-bold uppercase tracking-wider px-2 py-1 rounded bg-blue-900/50 text-blue-200 border border-blue-500/30">
                    <?php echo esc_html( $types[0]->name ); ?>
                </span>
            <?php endif; ?>

            <!-- Generation -->
            <?php if ( ! empty( $generation ) && ! is_wp_error( $generation ) ) : ?>
                <span class="text-xs font-bold uppercase tracking-wider px-2 py-1 rounded bg-purple-900/50 text-purple-200 border border-purple-500/30">
                    <?php echo esc_html( $generation[0]->name ); ?>
                </span>
            <?php endif; ?>
        </div>
    </header>

    <!-- Footer / Action -->
    <div class="mt-4 pt-4 border-t border-white/10 flex justify-between items-center opacity-0 group-hover:opacity-100 transition-opacity duration-300">
        <span class="text-xs text-white/60">View Details</span>
        <a href="<?php the_permalink(); ?>" class="text-fire-crystal hover:text-white transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd" />
            </svg>
        </a>
    </div>

</article>
