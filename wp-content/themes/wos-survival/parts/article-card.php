<?php
/**
 * Template part for displaying posts in a card layout (Pop Style)
 *
 * @package WoS_Survival
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class('group relative'); ?>>
    <a href="<?php the_permalink(); ?>" class="glass-card flex flex-col h-full p-2 overflow-hidden">
        <!-- Thumbnail -->
        <div class="relative aspect-video rounded-lg overflow-hidden bg-surface-dim">
            <?php if ( has_post_thumbnail() ) : ?>
                <?php the_post_thumbnail( 'medium_large', ['class' => 'w-full h-full object-cover transition-transform duration-700 group-hover:scale-110'] ); ?>
            <?php else : ?>
                <div class="w-full h-full flex items-center justify-center text-on-surface-variant/20">
                    <span class="material-symbols-outlined text-5xl">description</span>
                </div>
            <?php endif; ?>
            
            <!-- Category Badge -->
            <div class="absolute top-3 left-3">
                <?php
                $categories = get_the_category();
                if ( ! empty( $categories ) ) {
                    echo '<span class="px-3 py-1 text-[10px] font-black uppercase tracking-widest bg-primary text-on-primary rounded-full shadow-lg">' . esc_html( $categories[0]->name ) . '</span>';
                }
                ?>
            </div>
        </div>

        <!-- Content -->
        <div class="p-5 flex-grow flex flex-col">
            <div class="mb-3 flex items-center gap-3 text-[10px] font-black uppercase tracking-widest text-on-surface-variant/60">
                <div class="flex items-center gap-1">
                    <span class="material-symbols-outlined text-xs">calendar_today</span>
                    <?php echo get_the_date(); ?>
                </div>
                <div class="w-1 h-1 rounded-full bg-outline-variant/50"></div>
                <div class="flex items-center gap-1">
                    <span class="material-symbols-outlined text-xs">person</span>
                    <?php the_author(); ?>
                </div>
            </div>
            
            <h3 class="text-xl font-black text-on-surface mb-3 leading-tight group-hover:text-primary transition-colors">
                <?php the_title(); ?>
            </h3>
            
            <div class="text-on-surface-variant font-medium text-sm mb-6 line-clamp-2 leading-relaxed">
                <?php echo wp_trim_words( get_the_excerpt(), 20 ); ?>
            </div>
            
            <div class="mt-auto pt-6 border-t border-outline-variant/30 flex justify-between items-center">
                <div class="flex -space-x-2">
                    <div class="w-6 h-6 rounded-full bg-primary/10 border border-white flex items-center justify-center text-[10px] font-black self-center text-primary">
                        W
                    </div>
                </div>
                <div class="flex items-center gap-1.5 text-[10px] font-black uppercase tracking-widest text-primary group-hover:translate-x-1 transition-transform">
                    <span><?php _e( 'Read Analysis', 'wos-survival' ); ?></span>
                    <span class="material-symbols-outlined text-xs">arrow_forward</span>
                </div>
            </div>
        </div>
    </a>
</article>
