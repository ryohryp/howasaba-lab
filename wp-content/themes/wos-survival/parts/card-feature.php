<?php
/**
 * Template part for displaying a feature card (Pop Style)
 *
 * @package WoS_Survival
 *
 * @param array $args {
 *     @type string $href          Link URL.
 *     @type string $icon          Material Symbol name.
 *     @type string $title         Card title.
 *     @type string $description   Card description.
 *     @type string $color         Accent color (primary, secondary, tertiary, etc.)
 * }
 */

$href        = $args['href'] ?? '#';
$icon        = $args['icon'] ?? 'star';
$title       = $args['title'] ?? '';
$description = $args['description'] ?? '';
$color       = $args['color'] ?? 'primary';
?>

<a href="<?php echo esc_url( $href ); ?>" class="glass-card group flex flex-col h-full p-8 hover:-translate-y-1 transition-all duration-500">
    <div class="mb-8 flex items-center justify-between">
        <div class="w-14 h-14 rounded-2xl bg-<?php echo esc_attr($color); ?>-container/30 flex items-center justify-center text-<?php echo esc_attr($color); ?> group-hover:scale-110 group-hover:bg-<?php echo esc_attr($color); ?>-container transition-all duration-500 shadow-sm">
            <span class="material-symbols-outlined text-3xl"><?php echo esc_html($icon); ?></span>
        </div>
        <div class="w-8 h-8 rounded-full border border-secondary-container flex items-center justify-center text-secondary-container group-hover:text-primary group-hover:border-primary transition-colors">
            <span class="material-symbols-outlined text-sm">arrow_outward</span>
        </div>
    </div>
    
    <h3 class="text-2xl font-black text-on-surface mb-3 tracking-tight group-hover:text-primary transition-colors">
        <?php echo esc_html( $title ); ?>
    </h3>
    
    <p class="text-on-surface-variant font-medium text-sm leading-relaxed flex-grow">
        <?php echo esc_html( $description ); ?>
    </p>
    
    <div class="mt-8 flex items-center gap-2 text-[10px] font-black uppercase tracking-[0.2em] text-on-surface-variant/40 group-hover:text-primary transition-colors">
        <span><?php _e( 'Deploy Module', 'wos-survival' ); ?></span>
        <div class="h-px flex-grow bg-on-surface-variant/10 group-hover:bg-primary/20 transition-colors"></div>
    </div>
</a>
