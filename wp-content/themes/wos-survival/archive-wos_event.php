<?php
/**
 * The template for displaying Event Archive pages (Command Center - Pop Style)
 *
 * @package WoS_Survival
 */

get_header();
?>

<main id="primary" class="site-main pt-24 pb-12">

    <?php
    // Fetch all events for client-side filtering
    $args = array(
        'post_type'      => 'wos_event',
        'posts_per_page' => -1,
        'orderby'        => 'meta_value',
        'meta_key'       => '_event_start_date',
        'order'          => 'ASC',
    );
    $event_query = new WP_Query( $args );
    ?>

    <div x-data="{
        search: '',
        filter: 'all', // all, active, upcoming, past
        countVisible() {
            return Array.from(document.querySelectorAll('article')).filter(el => el.style.display !== 'none').length;
        },
        isVisible(el) {
            const name = el.dataset.name.toLowerCase();
            const status = el.dataset.status;
            const matchesSearch = name.includes(this.search.toLowerCase());
            const matchesFilter = this.filter === 'all' || status === this.filter;
            return matchesSearch && matchesFilter;
        }
    }" class="container mx-auto px-6">

        <!-- Command Center Header -->
        <header class="page-header mb-12 flex flex-col md:flex-row justify-between items-end gap-6">
            <div class="flex-grow">
                <div class="mb-4 inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-secondary-container/30 text-secondary text-[10px] font-black uppercase tracking-[0.2em] shadow-sm">
                    <span class="material-symbols-outlined text-sm">terminal</span>
                    <?php _e( 'Strategic Hub', 'wos-survival' ); ?>
                </div>
                <h1 class="text-4xl md:text-6xl font-black text-on-surface tracking-tighter leading-none mb-2">
                    <?php _e( 'Command Center', 'wos-survival' ); ?>
                </h1>
                <p class="text-on-surface-variant font-medium">
                    <?php _e( 'Event schedules, resource calculators, and advanced survival tools.', 'wos-survival' ); ?>
                </p>
            </div>
            
            <!-- Quick Tools Navigation -->
            <div class="flex gap-3">
                <a href="#events" class="btn-primary flex items-center gap-2 py-3 px-6">
                    <span class="material-symbols-outlined text-sm">calendar_month</span>
                    <?php _e( 'Events', 'wos-survival' ); ?>
                </a>
                <a href="#" class="btn-secondary flex items-center gap-2 py-3 px-6">
                    <span class="material-symbols-outlined text-sm">calculate</span>
                    <?php _e( 'Tools', 'wos-survival' ); ?>
                </a>
            </div>
        </header>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12">
            
            <!-- Sidebar: Filters & Mini Widgets -->
            <aside class="lg:col-span-3">
                <div class="glass-panel p-6 sticky top-28">
                    <h3 class="text-xs font-black uppercase tracking-widest text-primary mb-6"><?php _e( 'Operations filter', 'wos-survival' ); ?></h3>
                    
                    <div class="flex flex-col gap-3">
                        <button @click="filter = 'all'" 
                                :class="filter === 'all' ? 'bg-primary text-on-primary' : 'hover:bg-primary/10 text-on-surface-variant'" 
                                class="w-full flex items-center justify-between px-4 py-3 rounded-xl font-bold transition-all">
                            <span><?php _e( 'All Operations', 'wos-survival' ); ?></span>
                            <span class="material-symbols-outlined text-sm">list</span>
                        </button>
                        <button @click="filter = 'active'" 
                                :class="filter === 'active' ? 'bg-error-container text-on-error-container' : 'hover:bg-error/10 text-error'" 
                                class="w-full flex items-center justify-between px-4 py-3 rounded-xl font-bold transition-all border border-error/20">
                            <span class="flex items-center gap-2">
                                <span class="relative flex h-2 w-2">
                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-error opacity-75"></span>
                                    <span class="relative inline-flex rounded-full h-2 w-2 bg-error"></span>
                                </span>
                                <?php _e( 'Active Now', 'wos-survival' ); ?>
                            </span>
                            <span class="material-symbols-outlined text-sm">sensors</span>
                        </button>
                        <button @click="filter = 'upcoming'" 
                                :class="filter === 'upcoming' ? 'bg-primary-container text-on-primary-container' : 'hover:bg-primary/10 text-on-surface-variant'" 
                                class="w-full flex items-center justify-between px-4 py-3 rounded-xl font-bold transition-all">
                            <span><?php _e( 'Upcoming', 'wos-survival' ); ?></span>
                            <span class="material-symbols-outlined text-sm">schedule</span>
                        </button>
                        <button @click="filter = 'past'" 
                                :class="filter === 'past' ? 'bg-surface-container-high text-on-surface-variant' : 'hover:bg-white/50 text-on-surface-variant/50'" 
                                class="w-full flex items-center justify-between px-4 py-3 rounded-xl font-bold transition-all">
                            <span><?php _e( 'Declassified', 'wos-survival' ); ?></span>
                            <span class="material-symbols-outlined text-sm">archive</span>
                        </button>
                    </div>

                    <div class="mt-8 pt-8 border-t border-white/50">
                        <h3 class="text-xs font-black uppercase tracking-widest text-on-surface-variant mb-4"><?php _e( 'Search Protocol', 'wos-survival' ); ?></h3>
                        <div class="relative">
                            <input type="text" x-model="search" placeholder="<?php _e( 'Event name...', 'wos-survival' ); ?>" 
                                   class="w-full bg-white/50 border border-white/80 rounded-xl px-4 py-2.5 text-sm font-bold focus:outline-none transition-all">
                            <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 text-on-surface-variant/30">search</span>
                        </div>
                    </div>
                </div>
            </aside>

            <!-- Main Content: Event Grid -->
            <div id="events" class="lg:col-span-9">
                <?php if ( $event_query->have_posts() ) : ?>
                    <div class="grid grid-cols-1 md:grid-cols-2 xxl:grid-cols-3 gap-8">
                        <?php
                        while ( $event_query->have_posts() ) :
                            $event_query->the_post();
                            $meta = wos_get_event_meta( get_the_ID() );
                            $today = date('Y-m-d');
                            $start_date = $meta['start_date'];
                            
                            $status = 'active';
                            if ( $start_date > $today ) {
                                $status = 'upcoming';
                            } elseif ( $start_date < date('Y-m-d', strtotime('-4 days')) ) {
                                $status = 'past';
                            }

                            // Icons based on post title/content (simplified)
                            $icon = 'event';
                            if (stripos(get_the_title(), 'crystal') !== false) $icon = 'diamond';
                            if (stripos(get_the_title(), 'battle') !== false) $icon = 'swords';
                            if (stripos(get_the_title(), 'recru') !== false) $icon = 'group_add';
                            ?>
                            <article 
                                id="post-<?php the_ID(); ?>" 
                                <?php post_class("group relative"); ?>
                                x-show="isVisible($el)"
                                data-name="<?php echo esc_attr( get_the_title() ); ?>"
                                data-status="<?php echo esc_attr( $status ); ?>"
                            >
                                <a href="<?php the_permalink(); ?>" class="glass-card flex flex-col h-full p-2 overflow-hidden">
                                    <!-- Image Header -->
                                    <div class="relative aspect-video rounded-lg overflow-hidden bg-surface-dim">
                                        <?php if ( has_post_thumbnail() ) : ?>
                                            <?php the_post_thumbnail( 'large', array( 'class' => 'h-full w-full object-cover transition-transform duration-700 group-hover:scale-110' ) ); ?>
                                        <?php else : ?>
                                            <div class="h-full w-full flex items-center justify-center text-on-surface-variant/10">
                                                <span class="material-symbols-outlined text-6xl"><?php echo $icon; ?></span>
                                            </div>
                                        <?php endif; ?>

                                        <!-- Status Badge (Overlay) -->
                                        <div class="absolute top-4 left-4">
                                            <?php if ($status === 'active'): ?>
                                                <div class="bg-error text-on-error px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest flex items-center gap-1.5 shadow-xl">
                                                    <span class="h-2 w-2 rounded-full bg-white animate-pulse"></span>
                                                    LIVE
                                                </div>
                                            <?php elseif ($status === 'upcoming'): ?>
                                                <div class="bg-primary text-on-primary px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest shadow-xl">
                                                    INCOMING
                                                </div>
                                            <?php else: ?>
                                                <div class="bg-surface-container-highest/80 backdrop-blur-md text-on-surface-variant px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest">
                                                    ENDED
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>

                                    <!-- Content -->
                                    <div class="p-4 flex-grow flex flex-col">
                                        <div class="flex justify-between items-start gap-2 mb-3">
                                            <h2 class="text-xl font-black text-on-surface leading-tight group-hover:text-primary transition-colors">
                                                <?php the_title(); ?>
                                            </h2>
                                            <span class="material-symbols-outlined text-on-surface-variant/30"><?php echo $icon; ?></span>
                                        </div>

                                        <div class="mt-auto space-y-3">
                                            <!-- Meta Items -->
                                            <div class="flex items-center gap-4 text-[10px] font-black uppercase tracking-widest text-on-surface-variant">
                                                <div class="flex items-center gap-1 border-r border-outline-variant/30 pr-4">
                                                    <span class="material-symbols-outlined text-xs">calendar_today</span>
                                                    <?php echo date('M d', strtotime($start_date)); ?>
                                                </div>
                                                <div class="flex items-center gap-1">
                                                    <span class="material-symbols-outlined text-xs">hourglass_empty</span>
                                                    <?php echo esc_html( $meta['duration'] ?: '3 Days' ); ?>
                                                </div>
                                            </div>

                                            <div class="w-full bg-surface-container-low/50 h-1.5 rounded-full overflow-hidden">
                                                <div class="bg-primary h-full transition-all duration-1000" style="width: <?php echo ($status === 'active' ? '65%' : ($status === 'past' ? '100%' : '5%')); ?>"></div>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </article>
                        <?php endwhile; wp_reset_postdata(); ?>

                        <!-- No Results -->
                        <div x-show="countVisible() === 0" class="col-span-full py-24 text-center hidden" :class="{'block': true, 'hidden': false}">
                            <span class="material-symbols-outlined text-6xl text-on-surface-variant/20 mb-4">search_off</span>
                            <h3 class="text-2xl font-black text-on-surface mb-2"><?php _e( 'No Operations Found', 'wos-survival' ); ?></h3>
                            <button @click="search = ''; filter = 'all'" class="btn-primary mt-4"><?php _e( 'Clear Search', 'wos-survival' ); ?></button>
                        </div>
                    </div>
                <?php else : ?>
                    <div class="glass-panel p-20 text-center">
                        <p class="text-on-surface-variant font-bold"><?php _e( 'Awaiting new orders...', 'wos-survival' ); ?></p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</main>

<?php
get_footer();
