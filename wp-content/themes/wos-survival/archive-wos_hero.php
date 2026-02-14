<?php
/**
 * The template for displaying Hero Archive pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package WoS_Frost_Fire
 */

get_header();
?>

<main id="primary" class="site-main container mx-auto px-4 py-8">

    <?php
    // Fetch all heroes for client-side filtering
    $args = array(
        'post_type'      => 'wos_hero',
        'posts_per_page' => -1, // Get all heroes
        'orderby'        => 'title',
        'order'          => 'ASC',
    );
    $hero_query = new WP_Query( $args );

    // Get taxonomies for filter buttons
    $generations = get_terms( array(
        'taxonomy'   => 'hero_generation',
        'hide_empty' => true,
    ) );
    $types = get_terms( array(
        'taxonomy'   => 'hero_type',
        'hide_empty' => true,
    ) );
    ?>

    <div x-data="heroFilter" class="w-full">

        <header class="page-header mb-8 text-center">
            <h1 class="page-title mb-4 text-4xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-ice-blue to-white drop-shadow-lg">
                <?php post_type_archive_title(); ?>
            </h1>
            <div class="archive-description mx-auto max-w-2xl text-gray-300 mb-6">
                <!-- <?php the_archive_description(); ?> -->
                <p><?php _e( 'Explore the heroes of the frozen apocalypse. Check their stats, skills, and generation availability.', 'wos-frost-fire' ); ?></p>
            </div>

            <!-- Search Bar -->
            <div class="max-w-md mx-auto relative mb-8">
                <input 
                    type="text" 
                    x-model="search"
                    placeholder="<?php _e( 'Search heroes...', 'wos-frost-fire' ); ?>" 
                    class="w-full rounded-full border border-white/20 bg-black/40 px-5 py-3 text-white placeholder-gray-400 backdrop-blur-sm focus:border-ice-blue focus:outline-none focus:ring-1 focus:ring-ice-blue transition-all"
                >
                <div class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
            </div>

            <!-- Filters -->
            <div class="flex flex-col gap-6 items-center">
                <!-- Generation Filters -->
                <div class="flex flex-wrap justify-center gap-2">
                    <button 
                        @click="setGen('all')"
                        :class="selectedGen === 'all' ? 'active' : ''"
                        class="fire-crystal-btn text-base py-3 px-5" 
                    >
                        <?php _e( 'All Gens', 'wos-frost-fire' ); ?>
                    </button>
                    
                    <?php foreach ( $generations as $gen ) : ?>
                        <button 
                            @click="setGen('<?php echo esc_attr( $gen->slug ); ?>')"
                            :class="selectedGen === '<?php echo esc_attr( $gen->slug ); ?>' ? 'active' : ''"
                            class="fire-crystal-btn text-base py-3 px-5"
                        >
                            <?php echo esc_html( $gen->name ); ?>
                        </button>
                    <?php endforeach; ?>
                </div>

                <!-- Type Filters -->
                <div class="flex flex-wrap justify-center gap-2">
                    <button 
                        @click="setType('all')"
                        :class="selectedType === 'all' ? 'bg-ice-blue/80 text-black font-bold shadow-lg shadow-ice-blue/30' : 'bg-white/5 text-gray-300 hover:bg-white/10'"
                        class="rounded-lg px-5 py-3 text-base transition-all duration-300 border border-white/10"
                    >
                        <?php _e( 'All Types', 'wos-frost-fire' ); ?>
                    </button>
                    
                    <?php foreach ( $types as $type ) : ?>
                        <button 
                            @click="setType('<?php echo esc_attr( $type->slug ); ?>')"
                            :class="selectedType === '<?php echo esc_attr( $type->slug ); ?>' ? 'bg-ice-blue/80 text-black font-bold shadow-lg shadow-ice-blue/30' : 'bg-white/5 text-gray-300 hover:bg-white/10'"
                            class="rounded-lg px-5 py-3 text-base transition-all duration-300 border border-white/10"
                        >
                            <?php echo esc_html( $type->name ); ?>
                        </button>
                    <?php endforeach; ?>
                </div>
            </div>
        </header><!-- .page-header -->

        <!-- Server Age Calculator Widget -->
        <section class="mb-12 mx-auto max-w-md">
            <?php get_template_part( 'parts/calculator-server-age' ); ?>
        </section>

        <?php if ( $hero_query->have_posts() ) : ?>

            <!-- Mobile: 2 cols with smaller gap, Tablet: 3 cols, Desktop: 4/5 cols -->
            <!-- Using gap-3 for mobile to save space -->
            <div class="grid grid-cols-2 gap-3 sm:gap-6 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 relative min-h-[200px]">
                <?php
                while ( $hero_query->have_posts() ) :
                    $hero_query->the_post();
                    get_template_part( 'parts/hero-card' );
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
                        <div class="text-4xl mb-4">❄️</div>
                        <h3 class="text-xl font-bold text-white mb-2"><?php _e( 'No heroes found', 'wos-frost-fire' ); ?></h3>
                        <p class="text-gray-400 text-sm"><?php _e( 'Try adjusting your search or filters.', 'wos-frost-fire' ); ?></p>
                        <button @click="search = ''; selectedGen = 'all'; selectedType = 'all'" class="mt-4 px-4 py-2 bg-white/10 hover:bg-white/20 rounded-lg text-sm text-ice-blue transition-colors">
                            <?php _e( 'Clear Filters', 'wos-frost-fire' ); ?>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Pre-rendered pagination is removed because we are loading all items -->

        <?php else : ?>

            <div class="rounded-xl border border-white/10 bg-white/5 p-8 text-center text-gray-400">
                <p><?php _e( 'No heroes found. The tundra is empty...', 'wos-frost-fire' ); ?></p>
            </div>

        <?php endif; ?>
    
    </div>

<?php
get_footer();
