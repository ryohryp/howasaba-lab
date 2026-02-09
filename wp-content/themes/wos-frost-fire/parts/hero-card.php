<?php
/**
 * Hero Card Component
 * 
 * Displays a hero card with Glassmorphism and hover effects.
 * Expects to be used within The Loop or with a $post object.
 */

$hero_id = get_the_ID();
$generation = get_the_terms( $hero_id, 'hero_generation' );
$type = get_the_terms( $hero_id, 'hero_type' );
$rarity = get_the_terms( $hero_id, 'hero_rarity' );
$gen_name = !empty($generation) && !is_wp_error($generation) ? $generation[0]->name : '';
$type_name = !empty($type) && !is_wp_error($type) ? $type[0]->name : '';
$rarity_name = !empty($rarity) && !is_wp_error($rarity) ? $rarity[0]->name : '';

// Rarity color logic (simplified)
$rarity_class = 'text-gray-200';
if ($rarity_name === 'SSR') $rarity_class = 'text-yellow-400';
if ($rarity_name === 'SR') $rarity_class = 'text-purple-400';
if ($rarity_name === 'R') $rarity_class = 'text-blue-400';

?>

<article id="post-<?php the_ID(); ?>" <?php post_class('relative group overflow-hidden rounded-xl border border-white/10 bg-white/5 backdrop-blur-md transition-all duration-300 hover:scale-[1.02] hover:bg-white/10 hover:shadow-lg hover:shadow-ice-blue/20'); ?>>
    <a href="<?php the_permalink(); ?>" class="block h-full">
        <div class="aspect-w-3 aspect-h-4 relative overflow-hidden rounded-t-xl">
            <?php if ( has_post_thumbnail() ) : ?>
                <?php the_post_thumbnail( 'medium', array( 'class' => 'h-full w-full object-cover transition-transform duration-500 group-hover:scale-110' ) ); ?>
            <?php else : ?>
                <div class="flex h-full w-full items-center justify-center bg-midnight-navy/50 text-ice-blue/30">
                    <span class="text-4xl">❄️</span>
                </div>
            <?php endif; ?>
            
            <!-- Generation Badge -->
            <div class="absolute top-2 left-2 rounded-full bg-black/60 px-2 py-0.5 text-xs font-bold text-ice-blue backdrop-blur-sm">
                <?php echo esc_html( $gen_name ); ?>
            </div>
        </div>

        <div class="p-4">
            <h2 class="mb-1 text-xl font-bold text-white group-hover:text-ice-blue transition-colors">
                <?php the_title(); ?>
            </h2>
            
            <div class="flex flex-wrap gap-2 text-sm">
                <?php if ($type_name): ?>
                    <span class="inline-flex items-center gap-1 text-gray-300">
                        <!-- Icon placeholder -->
                        <span class="h-1.5 w-1.5 rounded-full bg-fire-crystal"></span>
                        <?php echo esc_html( $type_name ); ?>
                    </span>
                <?php endif; ?>

                <?php if ($rarity_name): ?>
                    <span class="<?php echo esc_attr( $rarity_class ); ?> font-semibold">
                        <?php echo esc_html( $rarity_name ); ?>
                    </span>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Hover interaction: Ice crack effect overlay (CSS handled/Optional) -->
        <div class="absolute inset-0 pointer-events-none opacity-0 group-hover:opacity-100 transition-opacity bg-gradient-to-t from-ice-blue/10 to-transparent"></div>
    </a>
</article>
