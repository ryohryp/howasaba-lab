<?php
/**
 * Template part for the Latest Intel section (Pop Style)
 *
 * @package WoS_Survival
 */
?>
<section class="space-y-24">

    <!-- 1. Latest Gift Codes -->
    <div class="gift-codes-section">
        <div class="flex flex-col md:flex-row md:items-end justify-between mb-12 gap-6 pb-6 border-b border-outline-variant/30">
            <div>
                <h2 class="text-[10px] font-black uppercase tracking-[0.3em] text-tertiary mb-3"><?php _e( 'Resources supply', 'wos-survival' ); ?></h2>
                <h3 class="text-3xl md:text-5xl font-black text-on-surface tracking-tighter leading-none flex items-center gap-4">
                    <span class="material-symbols-outlined text-4xl text-tertiary">featured_seasonal_and_gifts</span>
                    <?php _e( 'Latest Gift Codes', 'wos-survival' ); ?>
                </h3>
            </div>
            <a href="<?php echo get_post_type_archive_link('gift_code'); ?>" class="btn-secondary flex items-center gap-2 py-3 px-6 text-xs">
                <span><?php _e( 'Archive', 'wos-survival' ); ?></span>
                <span class="material-symbols-outlined text-sm">history</span>
            </a>
        </div>
        
        <?php
        $latest_codes = new WP_Query([
            'post_type'      => 'gift_code',
            'posts_per_page' => 3,
            'orderby'        => 'date',
            'order'          => 'DESC',
        ]);
        
        if ($latest_codes->have_posts()) : ?>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <?php while ($latest_codes->have_posts()) : $latest_codes->the_post(); 
                    $code = get_post_meta(get_the_ID(), '_wos_code_string', true) ?: get_post_meta(get_the_ID(), 'code_string', true);
                    $rewards = get_post_meta(get_the_ID(), '_wos_rewards', true) ?: get_post_meta(get_the_ID(), 'rewards', true);
                    $expires = get_post_meta(get_the_ID(), '_wos_expiration_date', true) ?: get_post_meta(get_the_ID(), 'expiration_date', true);
                ?>
                    <div class="glass-card p-6 flex flex-col justify-between group overflow-hidden relative">
                        <!-- Decorative element -->
                        <div class="absolute -top-6 -right-6 w-12 h-12 bg-tertiary/10 rounded-full blur-xl group-hover:scale-150 transition-transform duration-700"></div>

                        <div>
                            <div class="flex justify-between items-start mb-4">
                                <span class="bg-tertiary-container/30 text-tertiary text-[10px] font-black uppercase tracking-widest px-3 py-1 rounded-full border border-tertiary/10"><?php _e( 'Active', 'wos-survival' ); ?></span>
                                <?php if($expires): ?>
                                    <div class="flex items-center gap-1.5 text-on-surface-variant/40 text-[10px] font-black uppercase">
                                        <span class="material-symbols-outlined text-xs">timer</span>
                                        <?php echo esc_html(date('M d', strtotime($expires))); ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                            
                            <div class="mb-6">
                                <div class="text-[10px] font-black uppercase text-on-surface-variant/40 mb-2"><?php _e( 'Encrypted Key', 'wos-survival' ); ?></div>
                                <div class="bg-surface-container-high rounded-xl p-4 font-black text-2xl text-center text-on-surface tracking-[0.2em] border-2 border-dashed border-outline-variant/50 select-all group-hover:border-tertiary/30 transition-colors">
                                    <?php echo esc_html($code); ?>
                                </div>
                            </div>
                            
                            <div class="space-y-4 mb-8">
                                <div class="text-[10px] font-black uppercase text-on-surface-variant/40 mb-2"><?php _e( 'Estimated Yield', 'wos-survival' ); ?></div>
                                <p class="text-sm font-bold text-on-surface-variant leading-relaxed"><?php echo esc_html($rewards); ?></p>
                            </div>
                        </div>

                        <button 
                            onclick="navigator.clipboard.writeText('<?php echo esc_js($code); ?>'); this.querySelector('.btn-text').innerText='COPIED'; this.classList.add('bg-tertiary'); this.classList.remove('btn-secondary'); setTimeout(() => { this.querySelector('.btn-text').innerText='DEPLOY CODE'; this.classList.remove('bg-tertiary'); this.classList.add('btn-secondary'); }, 2000);"
                            class="btn-secondary w-full py-4 flex items-center justify-center gap-3 transition-all duration-300"
                        >
                            <span class="material-symbols-outlined text-sm">content_copy</span>
                            <span class="btn-text"><?php _e( 'DEPLOY CODE', 'wos-survival' ); ?></span>
                        </button>
                    </div>
                <?php endwhile; wp_reset_postdata(); ?>
            </div>
        <?php else : ?>
            <div class="glass-panel p-16 text-center">
                <p class="text-on-surface-variant font-black text-sm uppercase tracking-widest"><?php _e( 'No active codes detected by intelligence.', 'wos-survival' ); ?></p>
            </div>
        <?php endif; ?>
    </div>

    <!-- 2. Featured Heroes (Grid) -->
    <div class="featured-heroes-section">
        <div class="flex flex-col md:flex-row md:items-end justify-between mb-12 gap-6 pb-6 border-b border-outline-variant/30">
            <div>
                <h2 class="text-[10px] font-black uppercase tracking-[0.3em] text-primary mb-3"><?php _e( 'Combat assets', 'wos-survival' ); ?></h2>
                <h3 class="text-3xl md:text-5xl font-black text-on-surface tracking-tighter leading-none flex items-center gap-4">
                    <span class="material-symbols-outlined text-4xl text-primary">military_tech</span>
                    <?php _e( 'Elite Heroes', 'wos-survival' ); ?>
                </h3>
            </div>
            <a href="<?php echo get_post_type_archive_link('wos_hero'); ?>" class="btn-secondary flex items-center gap-2 py-3 px-6 text-xs">
                <span><?php _e( 'Hero Intel', 'wos-survival' ); ?></span>
                <span class="material-symbols-outlined text-sm">database</span>
            </a>
        </div>

        <?php
        $featured_heroes = new WP_Query([
            'post_type'      => 'wos_hero',
            'posts_per_page' => 4,
            'orderby'        => 'date',
            'order'          => 'DESC',
        ]);
        
        if ($featured_heroes->have_posts()) : ?>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                <?php while ($featured_heroes->have_posts()) : $featured_heroes->the_post(); ?>
                    <?php get_template_part('parts/hero-card'); ?>
                <?php endwhile; wp_reset_postdata(); ?>
            </div>
        <?php endif; ?>
    </div>

    <!-- 3. Active Events -->
    <div class="events-section">
        <div class="flex flex-col md:flex-row md:items-end justify-between mb-12 gap-6 pb-6 border-b border-outline-variant/30">
            <div>
                <h2 class="text-[10px] font-black uppercase tracking-[0.3em] text-secondary mb-3"><?php _e( 'Temporal operations', 'wos-survival' ); ?></h2>
                <h3 class="text-3xl md:text-5xl font-black text-on-surface tracking-tighter leading-none flex items-center gap-4">
                    <span class="material-symbols-outlined text-4xl text-secondary">calendar_today</span>
                    <?php _e( 'Upcoming Events', 'wos-survival' ); ?>
                </h3>
            </div>
            <a href="<?php echo get_post_type_archive_link('wos_event'); ?>" class="btn-secondary flex items-center gap-2 py-3 px-6 text-xs">
                <span><?php _e( 'Full Schedule', 'wos-survival' ); ?></span>
                <span class="material-symbols-outlined text-sm">schedule</span>
            </a>
        </div>

        <?php
        $events = new WP_Query([
            'post_type'      => 'wos_event',
            'posts_per_page' => 4,
            'orderby'        => 'date',
            'order'          => 'DESC',
        ]);
        
        if ($events->have_posts()) : ?>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <?php while ($events->have_posts()) : $events->the_post(); 
                    $start = get_post_meta(get_the_ID(), '_event_start_date', true);
                    $end = get_post_meta(get_the_ID(), '_event_end_date', true);
                    if (!$start) $start = get_the_date('Y-m-d');
                    
                    $type = get_the_terms(get_the_ID(), 'event_type');
                    $type_name = ( ! is_wp_error($type) && ! empty($type) ) ? $type[0]->name : 'Event';
                ?>
                    <a href="<?php the_permalink(); ?>" class="glass-card p-6 flex items-center justify-between group overflow-hidden">
                        <div class="flex items-center gap-8">
                            <!-- Date Block -->
                            <div class="flex-shrink-0 flex flex-col items-center justify-center p-3 rounded-2xl bg-secondary-container/30 text-secondary border border-secondary/10 w-20 h-20 shadow-sm group-hover:scale-105 transition-transform duration-500">
                                <span class="text-[10px] font-black uppercase tracking-widest text-secondary/60"><?php echo date('M', strtotime($start)); ?></span>
                                <span class="text-3xl font-black leading-none"><?php echo date('d', strtotime($start)); ?></span>
                            </div>
                            
                            <!-- Content -->
                            <div>
                                <div class="inline-flex items-center gap-1.5 bg-secondary-container/20 text-secondary text-[9px] font-black uppercase tracking-widest px-2.5 py-1 rounded-full mb-3">
                                    <span class="material-symbols-outlined text-[12px]">tags</span>
                                    <?php echo esc_html($type_name); ?>
                                </div>
                                <h4 class="text-xl font-black text-on-surface mb-1 group-hover:text-primary transition-colors"><?php the_title(); ?></h4>
                                <div class="flex items-center gap-1.5 text-on-surface-variant/40 text-[10px] font-black uppercase">
                                    <span class="material-symbols-outlined text-[12px]">schedule</span>
                                    <?php echo date('Y/m/d', strtotime($start)); ?> - <?php echo $end ? date('m/d', strtotime($end)) : 'TBA'; ?>
                                </div>
                            </div>
                        </div>
                        
                        <div class="w-10 h-10 rounded-full bg-surface-container-high flex items-center justify-center text-on-surface-variant/20 group-hover:bg-primary group-hover:text-on-primary transition-all duration-500 transform group-hover:translate-x-2">
                             <span class="material-symbols-outlined">chevron_right</span>
                        </div>
                    </a>
                <?php endwhile; wp_reset_postdata(); ?>
            </div>
        <?php else : ?>
            <div class="glass-panel p-16 text-center">
                <p class="text-on-surface-variant font-black text-sm uppercase tracking-widest"><?php _e( 'No upcoming operations scheduled.', 'wos-survival' ); ?></p>
            </div>
        <?php endif; ?>
    </div>

</section>
