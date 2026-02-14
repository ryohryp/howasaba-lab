<?php
/**
 * Template part for displaying posts in a card layout
 *
 * @package WOS_Frost_Fire
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class('flat-card group flex flex-col h-full overflow-hidden hover:border-ice-blue/50 transition-colors'); ?>>
    <!-- Thumbnail -->
    <a href="<?php the_permalink(); ?>" class="block relative h-48 overflow-hidden">
        <?php if ( has_post_thumbnail() ) : ?>
            <?php the_post_thumbnail( 'medium_large', ['class' => 'w-full h-full object-cover transition-transform duration-500 group-hover:scale-105'] ); ?>
        <?php else : ?>
            <div class="w-full h-full bg-slate-800 flex items-center justify-center text-slate-600">
                <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path></svg>
            </div>
        <?php endif; ?>
        
        <!-- Category Badge -->
        <div class="absolute top-3 left-3">
            <?php
            $categories = get_the_category();
            if ( ! empty( $categories ) ) {
                echo '<span class="px-2 py-1 text-xs font-bold bg-fire-crystal text-white rounded shadow-sm">' . esc_html( $categories[0]->name ) . '</span>';
            }
            ?>
        </div>
    </a>

    <!-- Content -->
    <div class="p-5 flex-1 flex flex-col">
        <div class="mb-2 text-xs text-gray-400 flex items-center gap-2">
            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
            <?php echo get_the_date(); ?>
        </div>
        
        <h3 class="text-lg font-bold text-white mb-3 leading-snug group-hover:text-ice-blue transition-colors">
            <a href="<?php the_permalink(); ?>">
                <?php the_title(); ?>
            </a>
        </h3>
        
        <div class="text-sm text-gray-400 mb-4 line-clamp-3">
            <?php the_excerpt(); ?>
        </div>
        
        <div class="mt-auto pt-4 border-t border-white/5 flex justify-between items-center">
            <span class="text-xs text-slate-500 font-mono">ID: <?php the_ID(); ?></span>
            <a href="<?php the_permalink(); ?>" class="text-xs font-bold text-ice-blue hover:text-white transition-colors flex items-center gap-1">
                READ MORE 
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
            </a>
        </div>
    </div>
</article>
