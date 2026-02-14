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

<article 
    id="post-<?php the_ID(); ?>" 
    <?php post_class('relative group overflow-hidden rounded-xl bg-slate-800 shadow-lg transition-all duration-300 hover:-translate-y-1 hover:shadow-xl border border-white/5 hover:border-ice-blue/30'); ?>
    x-show="isVisible($el)"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0 scale-90"
    x-transition:enter-end="opacity-100 scale-100"
>
    <a href="<?php the_permalink(); ?>" class="hero-card group block relative overflow-hidden rounded-xl bg-slate-800 shadow-lg transition-all duration-300 hover:-translate-y-1 hover:shadow-xl border border-white/5 hover:border-ice-blue/30"
   data-gen="<?php echo esc_attr( $gen_slug ); ?>"
   data-type="<?php echo esc_attr( $type_slug ); ?>">
    
    <!-- Hero Image Area (Top) -->
    <div class="relative h-48 w-full overflow-hidden bg-slate-900">
        <?php if ( has_post_thumbnail() ) : ?>
            <?php the_post_thumbnail( 'medium', array( 'class' => 'h-full w-full object-cover object-top transition-transform duration-500 group-hover:scale-110' ) ); ?>
        <?php else : ?>
            <div class="h-full w-full flex items-center justify-center bg-slate-800 text-slate-700">
                <span class="text-4xl">?</span>
            </div>
        <?php endif; ?>
        
        <!-- Gen Badge (Top Left) -->
        <div class="absolute top-2 left-2">
            <span class="inline-block px-2 py-1 bg-slate-900/90 text-ice-blue text-xs font-bold rounded border border-white/10 shadow-sm backdrop-blur-none">
                <?php echo esc_html( $gen_name ); ?>
            </div>
        </div>
        
        <!-- Hover interaction: Ice crack effect overlay (CSS handled/Optional) -->
        <div class="absolute inset-0 pointer-events-none opacity-0 group-hover:opacity-100 transition-opacity bg-gradient-to-t from-ice-blue/10 to-transparent"></div>
    </a>
</article>
