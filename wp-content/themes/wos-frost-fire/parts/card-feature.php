<?php
/**
 * Template part for displaying a feature card
 *
 * @package WOS_Frost_Fire
 *
 * @param array $args {
 *     @type string $href          Link URL.
 *     @type string $icon_bg_class Tailwind class for icon background (e.g., 'bg-cyan-400/10').
 *     @type string $icon_path     Path to the icon file (relative to theme root, e.g., 'parts/icons/wrench').
 *     @type string $title         Card title.
 *     @type string $description   Card description.
 * }
 */

$href          = $args['href'] ?? '#';
$icon_bg_class = $args['icon_bg_class'] ?? 'bg-white/10';
$icon_path     = $args['icon_path'] ?? '';
$title         = $args['title'] ?? '';
$description   = $args['description'] ?? '';
?>

<div class="group backdrop-blur-xl bg-white/5 border border-white/10 shadow-[0_8px_32px_0_rgba(0,0,0,0.37)] rounded-2xl p-6 hover:border-blue-200/30 transition-colors duration-300">
    <a href="<?php echo esc_url( $href ); ?>" class="flex flex-col h-full">
        <div class="w-12 h-12 rounded-xl <?php echo esc_attr( $icon_bg_class ); ?> flex items-center justify-center mb-4 group-hover:scale-110 transition-transform duration-300">
            <?php if ( $icon_path ) : ?>
                <?php get_template_part( $icon_path ); ?>
            <?php endif; ?>
        </div>
        <h3 class="text-xl font-bold text-blue-100 mb-2 group-hover:text-white transition-colors"><?php echo esc_html( $title ); ?></h3>
        <p class="text-blue-200/60 text-sm mb-6 flex-1">
            <?php echo esc_html( $description ); ?>
        </p>
        <div class="flex items-center text-sm font-medium text-blue-200 group-hover:text-cyan-400 transition-colors mt-auto">
            <span><?php _e( 'Access Modules', 'wos-frost-fire' ); ?></span>
            <?php get_template_part( 'parts/icons/arrow-right' ); ?>
        </div>
    </a>
</div>
