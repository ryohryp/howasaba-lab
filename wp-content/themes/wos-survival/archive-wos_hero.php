<?php
/**
 * The template for displaying Hero Archive pages (Pop Style)
 *
 * @package WoS_Survival
 */

get_header();
?>

<main id="primary" class="site-main pt-24 pb-12">

    <?php
    // Fetch all heroes for client-side filtering
    $supabase_client = new Supabase_Client();
    $supabase_heroes = $supabase_client->is_configured() 
        ? $supabase_client->get('heroes', [
            'select' => 'id,name,japanese_name,generation,troop_type,rarity,slug,image_url', 
            'order' => 'generation.desc,name.asc'
        ]) 
        : null;

    $use_supabase = !is_wp_error($supabase_heroes) && is_array($supabase_heroes);
    
    // Fallback to WP_Query if Supabase fails
    $hero_query = null;
    if ( ! $use_supabase ) {
        $args = array(
            'post_type'      => 'wos_hero',
            'posts_per_page' => -1,
            'orderby'        => 'title',
            'order'          => 'ASC',
        );
        $hero_query = new WP_Query( $args );
    }

    // Get taxonomies for filter buttons
    $generations = get_terms( array(
        'taxonomy'   => 'hero_generation',
        'hide_empty' => true,
        'orderby'    => 'slug',
        'order'      => 'DESC',
    ) );
    $types = get_terms( array(
        'taxonomy'   => 'hero_type',
        'hide_empty' => true,
    ) );
    ?>

    <div x-data="heroFilter" class="container mx-auto px-6">

        <!-- Hero Header Section -->
        <header class="page-header mb-12 flex flex-col items-center text-center">
            <div class="mb-4 inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-primary/10 text-primary text-[10px] font-black uppercase tracking-[0.2em] shadow-sm">
                <span class="material-symbols-outlined text-sm">database</span>
                <?php _e( 'Hero Records', 'wos-survival' ); ?>
            </div>
            <h1 class="text-4xl md:text-6xl font-black text-on-surface tracking-tighter mb-4 leading-none">
                <?php post_type_archive_title(); ?>
            </h1>
            <p class="max-w-2xl text-on-surface-variant font-medium leading-relaxed">
                <?php _e( 'Analyze statistics, skills, and generation cycles of Whiteout Survival heroes. From legendary Gen 1 survivors to the latest meta-defining warriors.', 'wos-survival' ); ?>
            </p>
        </header>

        <!-- Search & Control Panel -->
        <div class="glass-panel p-6 md:p-8 mb-12 flex flex-col lg:flex-row gap-8 items-center justify-between">
            
            <!-- Search Bar -->
            <div class="w-full lg:w-1/3 relative">
                <input 
                    type="text" 
                    x-model="search"
                    placeholder="<?php _e( 'Search by name...', 'wos-survival' ); ?>" 
                    class="w-full h-14 pl-14 pr-6 rounded-2xl bg-white/50 border border-white/80 text-on-surface font-bold placeholder-on-surface-variant/50 focus:outline-none focus:ring-2 focus:ring-primary/20 transition-all shadow-inner"
                >
                <div class="absolute left-6 top-1/2 -translate-y-1/2 text-on-surface-variant">
                    <span class="material-symbols-outlined">search</span>
                </div>
            </div>

            <!-- Filter Chips Group -->
            <div class="flex flex-wrap items-center justify-center gap-2 lg:justify-end">
                <div class="flex gap-2 p-1.5 bg-white/30 rounded-2xl border border-white/50">
                    <button 
                        @click="setGen('all')"
                        :class="selectedGen === 'all' ? 'bg-primary text-on-primary shadow-lg shadow-primary/20' : 'text-on-surface-variant hover:bg-white/50'"
                        class="px-5 py-2.5 rounded-xl font-bold text-xs transition-all active:scale-95" 
                    >
                        <?php _e( 'All Gens', 'wos-survival' ); ?>
                    </button>
                    <?php foreach ( $generations as $gen ) : ?>
                    <button 
                        @click="setGen('<?php echo esc_attr( $gen->slug ); ?>')"
                        :class="selectedGen === '<?php echo esc_attr( $gen->slug ); ?>' ? 'bg-primary text-on-primary shadow-lg shadow-primary/20' : 'text-on-surface-variant hover:bg-white/50'"
                        class="px-5 py-2.5 rounded-xl font-bold text-xs transition-all active:scale-95"
                    >
                        <?php echo esc_html( $gen->name ); ?>
                    </button>
                    <?php endforeach; ?>
                </div>

                <div class="hidden sm:block h-8 w-[1px] bg-white/50 mx-2"></div>

                <div class="flex gap-2">
                    <button 
                        @click="setType('all')"
                        :class="selectedType === 'all' ? 'bg-secondary-container text-on-secondary-container shadow-md' : 'bg-white/30 text-on-surface-variant border-white/50'"
                        class="px-4 py-2.5 rounded-xl font-bold text-xs border transition-all active:scale-95"
                    >
                        <?php _e( 'All Classes', 'wos-survival' ); ?>
                    </button>
                    <?php foreach ( $types as $type ) : ?>
                    <button 
                        @click="setType('<?php echo esc_attr( $type->slug ); ?>')"
                        :class="selectedType === '<?php echo esc_attr( $type->slug ); ?>' ? 'bg-secondary-container text-on-secondary-container shadow-md' : 'bg-white/30 text-on-surface-variant border-white/50'"
                        class="px-4 py-2.5 rounded-xl font-bold text-xs border transition-all active:scale-95"
                    >
                        <?php echo esc_html( $type->name ); ?>
                    </button>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <!-- Sort & Settings Panel (Mobile Friendly) -->
        <div class="flex justify-between items-center mb-8 px-2">
            <div class="flex items-center gap-2">
                <span class="material-symbols-outlined text-primary">sort</span>
                <select 
                    x-model="sortBy"
                    class="bg-transparent text-sm font-black text-on-surface uppercase tracking-widest focus:outline-none cursor-pointer"
                >
                    <option value="gen-desc"><?php _e( 'Generation (Newest)', 'wos-survival' ); ?></option>
                    <option value="gen-asc"><?php _e( 'Generation (Oldest)', 'wos-survival' ); ?></option>
                    <option value="name-asc"><?php _e( 'A - Z', 'wos-survival' ); ?></option>
                </select>
            </div>
            <div class="text-[10px] font-black text-on-surface-variant/50 uppercase tracking-widest">
                <span x-text="countVisible()"></span> <?php _e( 'Heroes showing', 'wos-survival' ); ?>
            </div>
        </div>

        <?php if ( $use_supabase ? !empty($supabase_heroes) : $hero_query->have_posts() ) : ?>

            <div class="grid grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 xxl:grid-cols-5 gap-6 md:gap-8 min-h-[400px]">
                <?php
                if ( $use_supabase ) {
                    foreach ( $supabase_heroes as $hero ) {
                        get_template_part( 'parts/hero-card', null, ['use_filtering' => true, 'hero_data' => $hero] );
                    }
                } else {
                    while ( $hero_query->have_posts() ) :
                        $hero_query->the_post();
                        get_template_part( 'parts/hero-card', null, ['use_filtering' => true] );
                    endwhile;
                    wp_reset_postdata();
                }
                ?>
                
                <!-- No Results Message -->
                <div x-show="countVisible() === 0" 
                     class="col-span-full hidden text-center py-24"
                     :class="{'block': true, 'hidden': false}" 
                     style="display: none;"
                >
                    <div class="glass-panel p-12 inline-flex flex-col items-center max-w-md">
                        <span class="material-symbols-outlined text-6xl text-on-surface-variant/30 mb-4 scale-150">person_off</span>
                        <h3 class="text-2xl font-black text-on-surface mb-2 tracking-tight"><?php _e( 'No heroes detected', 'wos-survival' ); ?></h3>
                        <p class="text-on-surface-variant font-medium mb-6"><?php _e( 'The blizzard has obscured your search. Try different filters or terms.', 'wos-survival' ); ?></p>
                        <button @click="resetFilters()" class="btn-primary">
                            <?php _e( 'Reset Surveillance', 'wos-survival' ); ?>
                        </button>
                    </div>
                </div>
            </div>

        <?php else : ?>

            <div class="glass-panel p-16 text-center">
                <span class="material-symbols-outlined text-6xl text-error mb-4">error_meditation</span>
                <p class="text-xl font-bold text-on-surface"><?php _e( 'Intelligence data currently unavailable.', 'wos-survival' ); ?></p>
            </div>

        <?php endif; ?>
    
    </div>

</main>

<script>
/**
 * Hero Filter Helper
 * Ensure helper functions like resetFilters are available
 */
document.addEventListener('alpine:init', () => {
    // Add resetFilters if not already in the main heroFilter object
    // Note: Assuming heroFilter is defined in a separate JS file as seen in previous tasks.
});

// Function to find how many elements are visible (Alpine helper)
function countVisible() {
    return Array.from(document.querySelectorAll('article')).filter(el => el.style.display !== 'none').length;
}
</script>

<?php
get_footer();
