<?php
/**
 * Template part for the Latest Intel section
 *
 * @package WOS_Frost_Fire
 */
?>
<section class="space-y-20">

    <!-- 1. Latest Gift Codes -->
    <div class="gift-codes-section">
        <div class="flex items-center justify-between mb-8 pb-4 border-b border-white/10">
            <h2 class="text-3xl font-black text-white flex items-center gap-3">
                <span class="text-fire-crystal">🎁</span>
                <?php _e( 'Latest Gift Codes', 'wos-frost-fire' ); ?>
            </h2>
            <a href="<?php echo get_post_type_archive_link('gift_code'); ?>" class="text-sm font-bold text-ice-blue hover:text-white transition-colors uppercase tracking-wider"><?php _e( 'View All', 'wos-frost-fire' ); ?> &rarr;</a>
        </div>
        
        <?php
        $latest_codes = new WP_Query([
            'post_type'      => 'gift_code',
            'posts_per_page' => 3,
            'orderby'        => 'date',
            'order'          => 'DESC',
        ]);
        
        if ($latest_codes->have_posts()) : ?>
            <div class="grid gap-4 md:grid-cols-3">
                <?php while ($latest_codes->have_posts()) : $latest_codes->the_post(); 
                    $code = get_post_meta(get_the_ID(), '_wos_code_string', true) ?: get_post_meta(get_the_ID(), 'code_string', true);
                    $rewards = get_post_meta(get_the_ID(), '_wos_rewards', true) ?: get_post_meta(get_the_ID(), 'rewards', true);
                    $expires = get_post_meta(get_the_ID(), '_wos_expiration_date', true) ?: get_post_meta(get_the_ID(), 'expiration_date', true);
                ?>
                    <div class="flat-card p-6 flex flex-col justify-between border-l-4 border-l-fire-crystal">
                        <div>
                            <div class="flex justify-between items-start mb-2">
                                <span class="text-xs font-bold text-fire-crystal uppercase tracking-wider">Active Code</span>
                                <?php if($expires): ?>
                                    <span class="text-[10px] text-slate-400 bg-slate-900 px-2 py-1 rounded"><?php echo esc_html(date('M j', strtotime($expires))); ?></span>
                                <?php endif; ?>
                            </div>
                            <div class="bg-slate-900 rounded p-3 mb-3 font-mono text-xl text-center text-white tracking-widest border border-slate-700 select-all">
                                <?php echo esc_html($code); ?>
                            </div>
                            <p class="text-sm text-slate-400 line-clamp-2 mb-4"><?php echo esc_html($rewards); ?></p>
                        </div>
                        <button 
                            onclick="navigator.clipboard.writeText('<?php echo esc_js($code); ?>'); this.innerText='Copied!';"
                            class="w-full py-2 bg-slate-700 hover:bg-slate-600 text-white text-sm font-bold rounded transition-colors"
                        >
                            Copy Code
                        </button>
                    </div>
                <?php endwhile; wp_reset_postdata(); ?>
            </div>
        <?php else : ?>
            <p class="text-slate-500 italic text-center py-8">No active codes found.</p>
        <?php endif; ?>
    </div>

    <!-- 2. Featured Heroes (Grid) -->
    <div class="featured-heroes-section">
        <div class="flex items-center justify-between mb-8 pb-4 border-b border-white/10">
            <h2 class="text-3xl font-black text-white flex items-center gap-3">
                <span class="text-ice-blue">👑</span>
                <?php _e( 'Featured Heroes (Top Tier)', 'wos-frost-fire' ); ?>
            </h2>
            <a href="<?php echo get_post_type_archive_link('wos_hero'); ?>" class="text-sm font-bold text-ice-blue hover:text-white transition-colors uppercase tracking-wider"><?php _e( 'Hero Database', 'wos-frost-fire' ); ?> &rarr;</a>
        </div>

        <?php
        $featured_heroes = new WP_Query([
            'post_type'      => 'wos_hero',
            'posts_per_page' => 4,
            'orderby'        => 'date',
            'order'          => 'DESC',
        ]);
        
        if ($featured_heroes->have_posts()) : ?>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                <?php while ($featured_heroes->have_posts()) : $featured_heroes->the_post(); ?>
                    <?php get_template_part('parts/hero-card'); ?>
                <?php endwhile; wp_reset_postdata(); ?>
            </div>
        <?php endif; ?>
    </div>

    <!-- 3. Active Events -->
    <div class="events-section">
         <div class="flex items-center justify-between mb-8 pb-4 border-b border-white/10">
            <h2 class="text-3xl font-black text-white flex items-center gap-3">
                <span class="text-white">📅</span>
                <?php _e( 'Upcoming Events', 'wos-frost-fire' ); ?>
            </h2>
            <a href="<?php echo get_post_type_archive_link('wos_event'); ?>" class="text-sm font-bold text-ice-blue hover:text-white transition-colors uppercase tracking-wider"><?php _e( 'Event Calendar', 'wos-frost-fire' ); ?> &rarr;</a>
        </div>

        <?php
        $events = new WP_Query([
            'post_type'      => 'wos_event',
            'posts_per_page' => 3,
            'orderby'        => 'date',
            'order'          => 'DESC',
        ]);
        
        if ($events->have_posts()) : ?>
            <div class="space-y-4">
                <?php while ($events->have_posts()) : $events->the_post(); 
                    $start = get_post_meta(get_the_ID(), 'event_start_date', true);
                    $end = get_post_meta(get_the_ID(), 'event_end_date', true);
                    $type = get_the_terms(get_the_ID(), 'event_type');
                    $type_name = $type ? $type[0]->name : 'Event';
                ?>
                    <a href="<?php the_permalink(); ?>" class="flat-card block p-6 hover:border-l-4 hover:border-l-ice-blue transition-all group">
                        <div class="flex items-center gap-6">
                            <!-- Date Box -->
                            <div class="flex-shrink-0 flex flex-col items-center justify-center w-16 h-16 bg-slate-900 rounded-lg border border-slate-700 group-hover:border-ice-blue/30 transition-colors">
                                <span class="text-xs text-slate-400 uppercase"><?php echo date('M', strtotime($start)); ?></span>
                                <span class="text-2xl font-black text-white"><?php echo date('d', strtotime($start)); ?></span>
                            </div>
                            
                            <!-- Info -->
                            <div>
                                <span class="inline-block px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider bg-slate-700 text-ice-blue mb-1">
                                    <?php echo esc_html($type_name); ?>
                                </span>
                                <h3 class="text-xl font-bold text-white group-hover:text-ice-blue transition-colors"><?php the_title(); ?></h3>
                                <p class="text-sm text-slate-400">
                                    <?php echo date('Y/m/d', strtotime($start)); ?> - <?php echo date('m/d', strtotime($end)); ?>
                                </p>
                            </div>
                        </div>
                    </a>
                <?php endwhile; wp_reset_postdata(); ?>
            </div>
        <?php endif; ?>
    </div>

</section>
