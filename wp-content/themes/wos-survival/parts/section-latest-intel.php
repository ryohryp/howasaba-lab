<?php
/**
 * Template part for the Latest Intel section
 *
 * @package WOS_Frost_Fire
 */
?>
<?php
/**
 * Template part for the Latest Intel section
 *
 * @package WOS_Frost_Fire
 */
?>
<section class="space-y-16">

    <!-- 1. Latest Gift Codes -->
    <div class="gift-codes-section">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-bold text-white flex items-center gap-2 drop-shadow-lg">
                <span class="text-3xl">🎁</span>
                <?php _e( 'Latest Gift Codes', 'wos-frost-fire' ); ?>
            </h2>
            <a href="<?php echo get_post_type_archive_link('gift_code'); ?>" class="text-sm text-ice-blue hover:text-white transition-colors flex items-center gap-1">
                <?php _e( 'View All', 'wos-frost-fire' ); ?> &rarr;
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <?php
            $gift_query = new WP_Query( [
                'post_type'      => 'gift_code',
                'posts_per_page' => 3,
                'orderby'        => 'date',
                'order'          => 'DESC',
            ] );

            if ( $gift_query->have_posts() ) :
                while ( $gift_query->have_posts() ) : $gift_query->the_post();
                    $code = get_post_meta( get_the_ID(), '_wos_code_string', true );
                    $rewards = get_post_meta( get_the_ID(), '_wos_rewards', true );
                    $expire = get_post_meta( get_the_ID(), '_wos_expiration_date', true );
                    $is_expired = $expire && strtotime($expire) < time();
                    ?>
                    <div class="relative group bg-white/5 backdrop-blur-md border border-white/10 rounded-xl p-5 hover:bg-white/10 transition-all duration-300 overflow-hidden">
                        <div class="absolute top-0 right-0 p-2">
                            <?php if($is_expired): ?>
                                <span class="text-xs font-bold text-gray-500 bg-black/50 px-2 py-1 rounded">EXPIRED</span>
                            <?php else: ?>
                                <span class="text-xs font-bold text-green-400 bg-green-400/10 px-2 py-1 rounded animate-pulse">ACTIVE</span>
                            <?php endif; ?>
                        </div>
                        <h3 class="font-mono text-xl font-bold text-ice-blue mb-2 tracking-wider"><?php echo esc_html( $code ?: get_the_title() ); ?></h3>
                        <p class="text-sm text-gray-300 line-clamp-2 mb-3"><?php echo esc_html( $rewards ); ?></p>
                        <div class="text-xs text-gray-500 flex items-center gap-1">
                            <span>⏳</span> <?php echo $expire ? esc_html( date('Y.m.d', strtotime($expire)) ) : 'No Expiry'; ?>
                        </div>
                        
                        <!-- Copy Button Placeholder (Optional JS enhancement) -->
                        <div class="mt-3 opacity-0 group-hover:opacity-100 transition-opacity">
                            <button class="w-full py-1 text-xs font-bold bg-white/10 hover:bg-white/20 rounded text-center text-white" onclick="navigator.clipboard.writeText('<?php echo esc_js($code); ?>'); alert('Code Copied!')">
                                Copy Code
                            </button>
                        </div>
                    </div>
                    <?php
                endwhile;
                wp_reset_postdata();
            else :
                echo '<p class="text-gray-500 col-span-3 text-center">No active codes found.</p>';
            endif;
            ?>
        </div>
    </div>

    <!-- 2. Featured Heroes (S+ Tier) -->
    <div class="featured-heroes-section">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-bold text-white flex items-center gap-2 drop-shadow-lg">
                <span class="text-3xl">❄️</span>
                <?php _e( 'Featured Heroes (Top Tier)', 'wos-frost-fire' ); ?>
            </h2>
            <a href="<?php echo get_post_type_archive_link('wos_hero'); ?>" class="text-sm text-ice-blue hover:text-white transition-colors flex items-center gap-1">
                <?php _e( 'Hero Database', 'wos-frost-fire' ); ?> &rarr;
            </a>
        </div>

        <div class="grid grid-cols-2 lg:grid-cols-4 gap-6">
            <?php
            $hero_query = new WP_Query( [
                'post_type'      => 'wos_hero',
                'posts_per_page' => 4,
                'meta_query'     => [
                    [
                        'key'     => 'overall_tier',
                        'value'   => ['S+', 'S'],
                        'compare' => 'IN'
                    ]
                ],
                'orderby'        => 'date', // Or random? 'rand'
            ] );

            if ( $hero_query->have_posts() ) :
                while ( $hero_query->have_posts() ) : $hero_query->the_post();
                    get_template_part( 'parts/hero-card' );
                endwhile;
                wp_reset_postdata();
            else :
                // Fallback to recent if no S+ found
                 $hero_query = new WP_Query( [ 'post_type' => 'wos_hero', 'posts_per_page' => 4 ] );
                 while ( $hero_query->have_posts() ) : $hero_query->the_post();
                    get_template_part( 'parts/hero-card' );
                 endwhile;
                 wp_reset_postdata();
            endif;
            ?>
        </div>
    </div>

    <!-- 3. Active Events -->
    <div class="active-events-section">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-bold text-white flex items-center gap-2 drop-shadow-lg">
                <span class="text-3xl">⚔️</span>
                <?php _e( 'Upcoming Events', 'wos-frost-fire' ); ?>
            </h2>
            <a href="<?php echo get_post_type_archive_link('wos_event'); ?>" class="text-sm text-ice-blue hover:text-white transition-colors flex items-center gap-1">
                <?php _e( 'Event Calendar', 'wos-frost-fire' ); ?> &rarr;
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <?php
            $event_query = new WP_Query( [
                'post_type'      => 'wos_event',
                'posts_per_page' => 3,
                'meta_key'       => '_event_start_date',
                'orderby'        => 'meta_value',
                'order'          => 'ASC',
                // Filter for future or recent? Let's just show next 3 by start date
            ] );

            if ( $event_query->have_posts() ) :
                while ( $event_query->have_posts() ) : $event_query->the_post();
                    $start = get_post_meta( get_the_ID(), '_event_start_date', true );
                    $duration = get_post_meta( get_the_ID(), '_event_duration', true );
                    $server_age = get_post_meta( get_the_ID(), '_server_age_requirement', true );
                    
                    // Date logic visual
                    $is_today = $start === date('Y-m-d');
                    ?>
                    <article class="relative bg-gradient-to-br from-white/5 to-white/0 border border-white/10 rounded-xl p-5 hover:border-fire-crystal/50 transition-all group">
                         <div class="flex items-center justify-between mb-2">
                            <span class="text-xs font-bold text-gray-400 bg-black/30 px-2 py-1 rounded">
                                Server Day <?php echo esc_html($server_age); ?>+
                            </span>
                            <?php if($is_today): ?>
                                <span class="text-xs font-bold text-fire-crystal animate-pulse">HAPPENING NOW</span>
                            <?php endif; ?>
                         </div>
                         <h3 class="text-lg font-bold text-white mb-2 group-hover:text-fire-crystal transition-colors">
                             <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                         </h3>
                         <div class="text-sm text-gray-300 flex flex-col gap-1">
                             <div class="flex items-center gap-2">
                                 <span>📅</span> 
                                 <span class="<?php echo $is_today ? 'text-fire-crystal font-bold' : ''; ?>">
                                     <?php echo esc_html( date('M j', strtotime($start)) ); ?>
                                 </span>
                             </div>
                             <div class="flex items-center gap-2 text-gray-400">
                                 <span>⏱️</span> <?php echo esc_html($duration); ?>
                             </div>
                         </div>
                    </article>
                    <?php
                endwhile;
                wp_reset_postdata();
            else :
                echo '<p class="text-gray-500 col-span-3 text-center">No upcoming events found.</p>';
            endif;
            ?>
        </div>
    </div>
</section>
