<?php
/**
 * Hero Card Component
 * 
 * Displays a hero card with Pop Style Glassmorphism.
 * Based on Stitch "Hero Database (Pop Style)"
 */

// Check if filtering is enabled (default: false)
$use_filtering = $args['use_filtering'] ?? false;

// Check if raw hero data is passed from Supabase
$hero_data = $args['hero_data'] ?? null;

if ( $hero_data ) {
    $hero_id     = $hero_data['id'] ?? 0;
    $gen_name    = 'Gen ' . ( $hero_data['generation'] ?? '' );
    $type_name   = $hero_data['troop_type'] ?? ''; // Infantry, Marksman, Lancer
    $rarity_name = $hero_data['rarity'] ?? 'SSR';
    $gen_slug    = $hero_data['generation'] ?? '';
    $type_slug   = strtolower( $type_name );
    $permalink   = home_url( '/hero/' . ( $hero_data['slug'] ?? sanitize_title( $hero_data['name'] ) ) . '/' );
    $is_ja       = get_locale() === 'ja';
    $title       = ( $is_ja && ! empty( $hero_data['japanese_name'] ) ) ? $hero_data['japanese_name'] : ( $hero_data['name'] ?? '' );
    $image_url   = $hero_data['image_url'] ?? '';
} else {
    // WP Loop fallback
    $hero_id    = get_the_ID();
    $generation = get_the_terms( $hero_id, 'hero_generation' );
    $type       = get_the_terms( $hero_id, 'hero_type' );
    $rarity     = get_the_terms( $hero_id, 'hero_rarity' );
    
    $gen_name    = !empty($generation) && !is_wp_error($generation) ? $generation[0]->name : '';
    $type_name   = !empty($type) && !is_wp_error($type) ? $type[0]->name : '';
    $rarity_name = !empty($rarity) && !is_wp_error($rarity) ? $rarity[0]->name : 'SSR';
    
    $gen_slug  = !empty($generation) && !is_wp_error($generation) ? $generation[0]->slug : '';
    $type_slug = !empty($type) && !is_wp_error($type) ? $type[0]->slug : '';
    $permalink = get_permalink();
    $title     = get_the_title();
    $image_url = get_the_post_thumbnail_url($hero_id, 'large');
}

// Map Class to Material Symbols
$class_icon = 'person';
if (strpos($type_slug, 'infantry') !== false || strpos($type_slug, 'shield') !== false) $class_icon = 'shield';
if (strpos($type_slug, 'marksman') !== false || strpos($type_slug, 'bow') !== false) $class_icon = 'ads_click';
if (strpos($type_slug, 'lancer') !== false || strpos($type_slug, 'spear') !== false) $class_icon = 'directions_run';

// Rarity Color
$rarity_class = 'text-primary';
if ($rarity_name === 'SSR') $rarity_class = 'text-orange-500';

?>

<article 
    id="post-<?php echo esc_attr( $hero_id ); ?>" 
    <?php post_class('relative group'); ?>
    data-name="<?php echo esc_attr( $title ); ?>"
    data-gen="<?php echo esc_attr( preg_replace('/[^0-9]/', '', $gen_slug) ); ?>"
    data-type="<?php echo esc_attr( $type_slug ); ?>"
    <?php if ( $use_filtering ) : ?>
        x-show="isVisible($el)"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 scale-90"
        x-transition:enter-end="opacity-100 scale-100"
    <?php endif; ?>
>
    <a href="<?php echo esc_url( $permalink ); ?>" class="glass-card block p-6 flex flex-col items-center gap-5">
        
        <!-- Gen Badge (Top Left) -->
        <div class="absolute top-4 left-4 bg-surface-container-high/80 px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest text-on-surface-variant backdrop-blur-md">
            <?php echo esc_html( $gen_name ); ?>
        </div>

        <!-- Level/SSR Badge (Top Right) -->
        <div class="absolute top-4 right-4 bg-tertiary-container text-on-tertiary-container px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest shadow-sm">
            <?php echo esc_html( $rarity_name ); ?>
        </div>

        <!-- Hero Image -->
        <div class="relative w-36 h-36 md:w-44 md:h-44 rounded-3xl overflow-hidden border-4 border-white shadow-2xl bg-surface-dim">
            <?php if ( $image_url ) : ?>
                <img src="<?php echo esc_url( $image_url ); ?>" alt="<?php echo esc_attr( $title ); ?>" class="h-full w-full object-cover object-top transition-transform duration-500 group-hover:scale-110">
            <?php else : ?>
                <div class="h-full w-full flex items-center justify-center text-on-surface-variant/20">
                    <span class="material-symbols-outlined text-6xl">person</span>
                </div>
            <?php endif; ?>
        </div>

        <!-- Hero Name & Class Info -->
        <div class="flex flex-col items-center gap-1 text-center">
            <h3 class="text-xl md:text-2xl font-black text-on-surface leading-none group-hover:text-primary transition-colors">
                <?php echo esc_html( $title ); ?>
            </h3>
            
            <div class="flex items-center gap-2 <?php echo esc_attr($rarity_class); ?>">
                 <span class="material-symbols-outlined text-sm"><?php echo esc_html($class_icon); ?></span>
                 <span class="text-[10px] font-bold uppercase tracking-widest"><?php printf( __( '%s Specialist', 'wos-survival' ), esc_html($type_name) ); ?></span>
            </div>
        </div>

        <!-- Card Footer (Tags/Actions) -->
        <div class="flex gap-2">
            <span class="px-3 py-1 bg-surface-container-low rounded-full text-[9px] font-bold text-on-surface-variant uppercase tracking-tighter"><?php _e( 'View Build', 'wos-survival' ); ?></span>
        </div>

    </a>
</article>

