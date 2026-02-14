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

// Check if filtering is enabled (default: false)
$use_filtering = $args['use_filtering'] ?? false;

// Define slugs for data attributes
$gen_slug = !empty($generation) && !is_wp_error($generation) ? $generation[0]->slug : '';
$type_slug = !empty($type) && !is_wp_error($type) ? $type[0]->slug : '';

?>

<article 
    id="post-<?php the_ID(); ?>" 
    <?php post_class('relative group overflow-hidden rounded-xl bg-slate-800 shadow-lg transition-all duration-300 hover:-translate-y-1 hover:shadow-xl border border-white/5 hover:border-ice-blue/30'); ?>
    data-name="<?php the_title(); ?>"
    data-gen="<?php echo esc_attr( $gen_slug ); ?>"
    data-type="<?php echo esc_attr( $type_slug ); ?>"
    <?php if ( $use_filtering ) : ?>
        x-show="isVisible($el)"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 scale-90"
        x-transition:enter-end="opacity-100 scale-100"
    <?php endif; ?>
>
    <a href="<?php the_permalink(); ?>" class="hero-card group block relative overflow-hidden rounded-xl bg-slate-800 shadow-lg transition-all duration-300 hover:-translate-y-1 hover:shadow-xl border border-white/5 hover:border-ice-blue/30">
    
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
            <div class="absolute top-2 left-2 flex gap-1">
                <span class="inline-block px-2 py-1 bg-slate-900/90 text-ice-blue text-xs font-bold rounded border border-white/10 shadow-sm backdrop-blur-none">
                    <?php echo esc_html( $gen_name ); ?>
                </span>
                
                <!-- Type Icon -->
                <?php if ( $type_slug ) : ?>
                    <div class="flex items-center justify-center w-6 h-6 bg-slate-900/90 rounded border border-white/10 shadow-sm backdrop-blur-none text-white" title="<?php echo esc_attr( $type_name ); ?>">
                        <?php if ( in_array( $type_slug, ['infantry', 'shield'] ) ) : ?>
                            <!-- Shield Icon (Infantry) -->
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4 text-blue-400">
                                <path fill-rule="evenodd" d="M12.516 2.17a.75.75 0 00-1.032 0 11.209 11.209 0 01-7.877 3.08.75.75 0 00-.722.515A12.74 12.74 0 002.25 9.75c0 5.942 4.064 10.933 9.563 12.348a.749.749 0 00.374 0c5.499-1.415 9.563-6.406 9.563-12.348 0-1.352-.272-2.636-.759-3.807a.754.754 0 00-.581-.37C16.954 6 13.79 3.635 12.516 2.17z" clip-rule="evenodd" />
                            </svg>
                        <?php elseif ( in_array( $type_slug, ['lancer', 'spear'] ) ) : ?>
                             <!-- Spear Icon (Lancer) - Placeholder/Generic -->
                             <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4 text-red-400">
                                <path fill-rule="evenodd" d="M12 2.25c.195 0 .385.063.538.17l8.47 5.928a.75.75 0 01.32.72c-.22 9.074-3.763 13.3-9.066 14.887a.75.75 0 01-.43 0c-5.302-1.587-8.845-5.813-9.065-14.887a.75.75 0 01.32-.72L11.462 2.42A.749.749 0 0112 2.25z" clip-rule="evenodd" />
                            </svg>
                        <?php elseif ( in_array( $type_slug, ['marksman', 'bow', 'archer'] ) ) : ?>
                             <!-- Bow Icon (Marksman) - Placeholder/Generic -->
                             <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4 text-green-400">
                                <path fill-rule="evenodd" d="M12 2.25c.195 0 .385.063.538.17l8.47 5.928a.75.75 0 01.32.72c-.22 9.074-3.763 13.3-9.066 14.887a.75.75 0 01-.43 0c-5.302-1.587-8.845-5.813-9.065-14.887a.75.75 0 01.32-.72L11.462 2.42A.749.749 0 0112 2.25z" clip-rule="evenodd" />
                            </svg>
                        <?php else : ?>
                            <span class="text-xs font-bold"><?php echo esc_html( substr( $type_name, 0, 1 ) ); ?></span>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
            
            <!-- Hover interaction: Ice crack effect overlay -->
            <div class="absolute inset-0 pointer-events-none opacity-0 group-hover:opacity-100 transition-opacity bg-gradient-to-t from-ice-blue/10 to-transparent"></div>
        </div>
    </a>
</article>
