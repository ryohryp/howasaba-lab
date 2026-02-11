<?php
/**
 * The template for displaying Event Archive pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package WoS_Frost_Fire
 */

get_header();
?>

<main id="primary" class="site-main container mx-auto px-4 py-8">

    <?php
    // Fetch all events for client-side filtering
    $args = array(
        'post_type'      => 'wos_event',
        'posts_per_page' => -1, // Get all events
        'orderby'        => 'meta_value',
        'meta_key'       => '_event_start_date',
        'order'          => 'ASC',
    );
    $event_query = new WP_Query( $args );
    ?>

    <div x-data="{
        search: '',
        filter: 'all', // all, active, upcoming, past
        
        // Helper to check if an event matches current filters
        isVisible(el) {
            const name = el.dataset.name.toLowerCase();
            const status = el.dataset.status; // active, upcoming, past
            
            const matchesSearch = name.includes(this.search.toLowerCase());
            const matchesFilter = this.filter === 'all' || status === this.filter;
            
            return matchesSearch && matchesFilter;
        }
    }" class="w-full">

        <header class="page-header mb-8 text-center">
            <h1 class="page-title mb-4 text-4xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-ice-blue to-white drop-shadow-lg">
                <?php post_type_archive_title(); ?>
            </h1>
            <div class="archive-description mx-auto max-w-2xl text-gray-300 mb-6">
                <!-- <?php the_archive_description(); ?> -->
                <p><?php _e( 'Track upcoming events, plan your strategy, and maximize your rewards.', 'wos-frost-fire' ); ?></p>
            </div>

            <!-- Search Bar -->
            <div class="max-w-md mx-auto relative mb-8">
                <input 
                    type="text" 
                    x-model="search"
                    placeholder="<?php _e( 'Search events...', 'wos-frost-fire' ); ?>" 
                    class="w-full rounded-full border border-white/20 bg-black/40 px-5 py-3 text-white placeholder-gray-400 backdrop-blur-sm focus:border-ice-blue focus:outline-none focus:ring-1 focus:ring-ice-blue transition-all"
                >
                <div class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
            </div>

            <!-- Filters -->
            <div class="flex flex-wrap justify-center gap-2 mb-8">
                <button 
                    @click="filter = 'all'"
                    :class="filter === 'all' ? 'bg-ice-blue text-deep-freeze font-bold' : 'bg-white/5 text-gray-300 hover:bg-white/10'"
                    class="rounded-full px-6 py-2 text-sm transition-all duration-300"
                >
                    <?php _e( 'All Events', 'wos-frost-fire' ); ?>
                </button>
                <button 
                    @click="filter = 'active'"
                    :class="filter === 'active' ? 'bg-fire-crystal text-white shadow-glow' : 'bg-white/5 text-gray-300 hover:bg-white/10'"
                    class="rounded-full px-6 py-2 text-sm transition-all duration-300 flex items-center gap-2"
                >
                    <span class="relative flex h-2 w-2">
                      <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                      <span class="relative inline-flex rounded-full h-2 w-2 bg-red-500"></span>
                    </span>
                    <?php _e( 'Active Now', 'wos-frost-fire' ); ?>
                </button>
                <button 
                    @click="filter = 'upcoming'"
                    :class="filter === 'upcoming' ? 'bg-indigo-500 text-white shadow-glow' : 'bg-white/5 text-gray-300 hover:bg-white/10'"
                    class="rounded-full px-6 py-2 text-sm transition-all duration-300"
                >
                    <?php _e( 'Upcoming', 'wos-frost-fire' ); ?>
                </button>
                <button 
                    @click="filter = 'past'"
                    :class="filter === 'past' ? 'bg-gray-600 text-white' : 'bg-white/5 text-gray-300 hover:bg-white/10'"
                    class="rounded-full px-6 py-2 text-sm transition-all duration-300"
                >
                    <?php _e( 'Past', 'wos-frost-fire' ); ?>
                </button>
            </div>
        </header><!-- .page-header -->

        <?php if ( $event_query->have_posts() ) : ?>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 relative min-h-[200px]">
                <?php
                while ( $event_query->have_posts() ) :
                    $event_query->the_post();
                    $meta = wos_get_event_meta( get_the_ID() );
                    
                    // Determine status logic
                    $start_date = $meta['start_date'];
                    $today = date('Y-m-d');
                    
                    // Simple logic: 
                    // If start > today => upcoming
                    // If start <= today => active (assuming short duration for simplicity, or we can check duration)
                    // Let's refine: start <= today AND end >= today => active.
                    // But duration is a string "3 Days". Need to parse? 
                    // For now, let's keep it simple: 
                    // upcoming: start > today
                    // past: start < today - 7 days (mock)
                    // active: start <= today >= start - 7 days
                    // Better: We'll rely on start date comparison for now.
                    
                    $status = 'active';
                    if ( $start_date > $today ) {
                        $status = 'upcoming';
                    } elseif ( $start_date < date('Y-m-d', strtotime('-3 days')) ) { // Assuming avg 3 days duration
                        $status = 'past';
                    }
                    
                    // Card Styling based on status
                    $status_colors = [
                        'active' => 'border-fire-crystal/50 shadow-fire-crystal/20',
                        'upcoming' => 'border-ice-blue/30',
                        'past' => 'border-white/5 opacity-60',
                    ];
                    $card_border = $status_colors[$status] ?? 'border-white/10';

                    ?>
                    <article 
                        id="post-<?php the_ID(); ?>" 
                        <?php post_class("relative group overflow-hidden rounded-xl border {$card_border} bg-white/5 backdrop-blur-md transition-all duration-300 hover:bg-white/10 hover:shadow-lg"); ?>
                        x-show="isVisible($el)"
                        data-name="<?php echo esc_attr( get_the_title() ); ?>"
                        data-status="<?php echo esc_attr( $status ); ?>"
                    >
                        <a href="<?php the_permalink(); ?>" class="block h-full flex flex-col">
                            <div class="aspect-w-16 aspect-h-9 relative overflow-hidden rounded-t-xl bg-black/40">
                                <?php if ( has_post_thumbnail() ) : ?>
                                    <?php the_post_thumbnail( 'medium', array( 'class' => 'h-full w-full object-cover transition-transform duration-500 group-hover:scale-110' ) ); ?>
                                <?php else : ?>
                                    <div class="flex h-full w-full items-center justify-center text-ice-blue/20">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                <?php endif; ?>
                                
                                <!-- Date Badge -->
                                <div class="absolute top-0 right-0 rounded-bl-xl bg-black/60 px-3 py-1 text-sm font-mono text-ice-blue backdrop-blur-sm border-l border-b border-white/10">
                                    <?php echo esc_html( $start_date ); ?>
                                </div>
                                
                                <!-- Status Badge -->
                                <div class="absolute top-2 left-2">
                                    <?php if ($status === 'active'): ?>
                                        <span class="inline-flex items-center gap-1 rounded-full bg-red-500/80 px-2 py-0.5 text-xs font-bold text-white shadow-lg backdrop-blur-sm">
                                            <span class="h-1.5 w-1.5 rounded-full bg-white animate-pulse"></span>
                                            LIVE
                                        </span>
                                    <?php elseif ($status === 'upcoming'): ?>
                                        <span class="inline-flex items-center gap-1 rounded-full bg-blue-500/80 px-2 py-0.5 text-xs font-bold text-white shadow-lg backdrop-blur-sm">
                                            SOON
                                        </span>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <div class="p-5 flex-grow flex flex-col">
                                <h2 class="mb-2 text-xl font-bold text-white group-hover:text-ice-blue transition-colors">
                                    <?php the_title(); ?>
                                </h2>
                                
                                <div class="mt-auto flex items-center justify-between text-sm text-gray-400">
                                    <div class="flex items-center gap-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <?php echo esc_html( $meta['duration'] ?: 'N/A' ); ?>
                                    </div>
                                    <?php if ( $meta['server_age'] ) : ?>
                                        <div class="flex items-center gap-1" title="Server Age Requirement">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                            </svg>
                                            <?php echo esc_html( $meta['server_age'] ); ?>d+
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </a>
                    </article>
                <?php
                endwhile;
                wp_reset_postdata();
                ?>
                
                <!-- No Results Message -->
                <div x-show="$el.parentElement.querySelectorAll('article[style*=\'display: none\']').length === $el.parentElement.querySelectorAll('article').length" 
                     class="col-span-full hidden text-center py-12"
                     :class="{'block': true, 'hidden': false}" 
                     style="display: none;"
                >
                    <div class="rounded-xl border border-white/10 bg-white/5 p-8 inline-block max-w-md">
                        <div class="text-4xl mb-4">📅</div>
                        <h3 class="text-xl font-bold text-white mb-2"><?php _e( 'No events found', 'wos-frost-fire' ); ?></h3>
                        <p class="text-gray-400 text-sm"><?php _e( 'There are no events matching your filter.', 'wos-frost-fire' ); ?></p>
                        <button @click="search = ''; filter = 'all'" class="mt-4 px-4 py-2 bg-white/10 hover:bg-white/20 rounded-lg text-sm text-ice-blue transition-colors">
                            <?php _e( 'View All Events', 'wos-frost-fire' ); ?>
                        </button>
                    </div>
                </div>
            </div>

        <?php else : ?>

            <div class="rounded-xl border border-white/10 bg-white/5 p-8 text-center text-gray-400">
                <p><?php _e( 'No events scheduled yet. Stay tuned!', 'wos-frost-fire' ); ?></p>
            </div>

        <?php endif; ?>
    
    </div>

<?php
get_footer();
