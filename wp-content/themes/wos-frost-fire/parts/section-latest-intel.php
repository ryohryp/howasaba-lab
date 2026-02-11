<?php
/**
 * Template part for the Latest Intel section
 *
 * @package WOS_Frost_Fire
 */
?>
<section>
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-bold text-blue-100 flex items-center gap-2">
            <?php get_template_part( 'parts/icons/swords' ); ?>
            <?php _e( 'Latest Intel', 'wos-frost-fire' ); ?>
        </h2>
        <a href="<?php echo home_url( '/guide' ); ?>" class="text-sm text-cyan-400 hover:underline"><?php _e( 'View All', 'wos-frost-fire' ); ?></a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <?php
        $latest_guides = new WP_Query( [
            'post_type'      => 'post',
            'posts_per_page' => 2,
            'ignore_sticky_posts' => 1,
        ] );

        if ( $latest_guides->have_posts() ) :
            while ( $latest_guides->have_posts() ) : $latest_guides->the_post();
                ?>
                <div class="group backdrop-blur-xl bg-white/5 border border-white/10 shadow-lg rounded-2xl p-4 hover:bg-white/10 hover:border-blue-200/30 cursor-pointer transition-all duration-300">
                    <a href="<?php the_permalink(); ?>" class="block">
                        <div class="flex justify-between items-start mb-2">
                            <span class="text-xs font-mono text-cyan-400 bg-cyan-400/10 px-2 py-1 rounded">GUIDE</span>
                            <span class="text-xs text-blue-200/40"><?php echo get_the_date( 'Y.m.d' ); ?></span>
                        </div>
                        <h3 class="font-bold text-lg text-blue-100 mb-1 group-hover:text-white transition-colors"><?php the_title(); ?></h3>
                        <div class="text-sm text-blue-200/60 line-clamp-2">
                            <?php the_excerpt(); ?>
                        </div>
                    </a>
                </div>
                <?php
            endwhile;
            wp_reset_postdata();
        else :
            ?>
            <div class="col-span-full text-center py-8 text-blue-200/50">
                <?php _e( 'No recent intel found.', 'wos-frost-fire' ); ?>
            </div>
            <?php
        endif;
        ?>
    </div>
</section>
