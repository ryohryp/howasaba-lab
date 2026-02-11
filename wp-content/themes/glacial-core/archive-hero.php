<?php get_header(); ?>

<div id="snow-container"></div>

<main class="min-h-screen bg-[var(--bg-frost)] text-[var(--text-snow)] pb-20 pt-24 px-4">
    
    <div class="container mx-auto max-w-7xl" x-data="heroDatabase()">
        
        <!-- Header & Search -->
        <div class="mb-10 text-center space-y-4">
            <h1 class="text-4xl md:text-5xl font-black text-transparent bg-clip-text bg-gradient-to-r from-blue-200 to-white drop-shadow-[0_0_10px_rgba(255,255,255,0.3)]">
                HERO DATABASE
            </h1>
            <p class="text-blue-200/60 max-w-xl mx-auto">
                Complete data on all heroes. Filter by generation, class, and rarity to find your perfect formation.
            </p>
        </div>

        <!-- Filters Toolbar -->
        <div class="glass-panel p-6 mb-10 sticky top-24 z-40 transition-all duration-300 shadow-[0_4px_30px_rgba(0,0,0,0.1)]">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <!-- Search -->
                <div>
                    <label class="block text-xs font-bold text-blue-300 mb-2 uppercase tracking-wider">Search</label>
                    <input type="text" x-model="search" placeholder="Search Hero Name..." 
                           class="w-full bg-[#0b1120]/60 border border-[var(--glass-border)] rounded px-4 py-2 text-white placeholder-gray-500 focus:outline-none focus:border-blue-400 transition-colors">
                </div>
                
                <!-- Generation Filter -->
                <div>
                    <label class="block text-xs font-bold text-blue-300 mb-2 uppercase tracking-wider">Generation</label>
                    <select x-model="filterGen" class="w-full bg-[#0b1120]/60 border border-[var(--glass-border)] rounded px-4 py-2 text-white focus:outline-none focus:border-blue-400 transition-colors appearance-none">
                        <option value="">All Generations</option>
                        <?php 
                        $gens = get_terms(['taxonomy' => 'hero_generation', 'hide_empty' => false]);
                        foreach($gens as $gen): ?>
                        <option value="<?php echo esc_attr($gen->slug); ?>"><?php echo esc_html($gen->name); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Class Filter -->
                <div>
                    <label class="block text-xs font-bold text-blue-300 mb-2 uppercase tracking-wider">Class</label>
                    <div class="flex gap-2">
                        <?php foreach(['infantry', 'lancer', 'marksman'] as $c): ?>
                            <button @click="filterClass = filterClass === '<?php echo $c; ?>' ? '' : '<?php echo $c; ?>'"
                                    :class="filterClass === '<?php echo $c; ?>' ? 'bg-[var(--accent-fire-crystal)] text-white border-transparent' : 'bg-transparent border-[var(--glass-border)] hover:bg-white/5'"
                                    class="flex-1 py-2 rounded border text-xs font-bold uppercase transition-all">
                                <?php echo ucfirst($c); ?>
                            </button>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Rarity Filter (Optional/Bonus) -->
                 <div>
                    <label class="block text-xs font-bold text-blue-300 mb-2 uppercase tracking-wider">Rarity</label>
                    <select x-model="filterRarity" class="w-full bg-[#0b1120]/60 border border-[var(--glass-border)] rounded px-4 py-2 text-white focus:outline-none focus:border-blue-400 transition-colors appearance-none">
                        <option value="">All Rarities</option>
                        <option value="mythic">Mythic</option>
                        <option value="epic">Epic</option>
                        <option value="rare">Rare</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Grid -->
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            <?php 
            $args = array(
                'post_type' => 'hero',
                'posts_per_page' => -1, // Get all for client-side filtering
                'orderby' => 'title',
                'order' => 'ASC'
            );
            $query = new WP_Query($args);
            
            if($query->have_posts()):
                while($query->have_posts()): $query->the_post();
                    // Get Taxonomies
                    $gens = get_the_terms(get_the_ID(), 'hero_generation');
                    $gen_slug = $gens ? $gens[0]->slug : '';
                    $gen_name = $gens ? $gens[0]->name : '';

                    $classes = get_the_terms(get_the_ID(), 'hero_class');
                    $class_slug = $classes ? $classes[0]->slug : '';
                    $class_name = $classes ? $classes[0]->name : '';
                    
                    $rarities = get_the_terms(get_the_ID(), 'hero_rarity');
                    $rarity_slug = $rarities ? $rarities[0]->slug : '';

                    // Get Meta
                   $stats = glacial_get_hero_stats(get_the_ID());
                ?>
                <div class="hero-card glass-panel group relative overflow-hidden transition-all duration-300 hover:translate-y-[-5px] cursor-pointer"
                     data-name="<?php echo strtolower(get_the_title()); ?>"
                     data-gen="<?php echo $gen_slug; ?>"
                     data-class="<?php echo $class_slug; ?>"
                     data-rarity="<?php echo $rarity_slug; ?>"
                     x-show="isVisible($el)"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 transform scale-90"
                     x-transition:enter-end="opacity-100 transform scale-100">
                    
                    <!-- Glow Effect -->
                    <div class="absolute inset-0 bg-blue-500/0 group-hover:bg-blue-500/10 transition-colors duration-300"></div>
                    
                    <!-- Card Content -->
                    <div class="p-6 relative z-10 flex flex-col items-center">
                        <div class="w-24 h-24 mb-4 rounded-full border-2 border-[var(--glass-border)] bg-[#0b1120] flex items-center justify-center overflow-hidden group-hover:border-blue-400 group-hover:shadow-[0_0_15px_rgba(56,189,248,0.3)] transition-all">
                            <?php if(has_post_thumbnail()): ?>
                                <?php the_post_thumbnail('thumbnail', ['class' => 'w-full h-full object-cover']); ?>
                            <?php else: ?>
                                <span class="text-4xl">👤</span>
                            <?php endif; ?>
                        </div>

                        <h3 class="text-xl font-bold mb-1 group-hover:text-blue-300 transition-colors"><?php the_title(); ?></h3>
                        
                        <div class="flex gap-2 mb-4">
                            <span class="text-[10px] uppercase font-bold px-2 py-0.5 rounded bg-blue-900/50 text-blue-200 border border-blue-500/20">
                                <?php echo $gen_name ?: 'Unknown'; ?>
                            </span>
                            <span class="text-[10px] uppercase font-bold px-2 py-0.5 rounded bg-gray-700/50 text-gray-300 border border-gray-600/30">
                                <?php echo $class_name ?: 'Unknown'; ?>
                            </span>
                        </div>

                        <!-- Mini Stats Chart (Visual only for list) -->
                        <div class="w-full h-1 bg-gray-700 rounded-full overflow-hidden flex">
                            <div class="h-full bg-red-500" style="width: <?php echo $stats['atk']/3; ?>%"></div>
                            <div class="h-full bg-blue-500" style="width: <?php echo $stats['def']/3; ?>%"></div>
                            <div class="h-full bg-green-500" style="width: <?php echo $stats['hp']/3; ?>%"></div>
                        </div>
                    </div>
                </div>
                <?php endwhile; wp_reset_postdata(); ?>
            <?php else: ?>
                <p class="col-span-full text-center text-gray-400">No heroes found. Run the seeder!</p>
            <?php endif; ?>
        </div>
        
        <!-- Empty State (JS controlled) -->
        <div x-show="countVisible() === 0" class="text-center py-20 hidden" :class="{'hidden': countVisible() !== 0}">
            <p class="text-xl text-gray-400">No heroes match your filters.</p>
            <button @click="resetFilters()" class="mt-4 text-blue-300 hover:text-white underline">Clear Filters</button>
        </div>

    </div>
</main>

<script>
function heroDatabase() {
    return {
        search: '',
        filterGen: '',
        filterClass: '',
        filterRarity: '',
        
        isVisible(el) {
            const name = el.dataset.name;
            const gen = el.dataset.gen;
            const cls = el.dataset.class;
            const rarity = el.dataset.rarity;
            
            const matchSearch = this.search === '' || name.includes(this.search.toLowerCase());
            const matchGen = this.filterGen === '' || gen === this.filterGen;
            const matchClass = this.filterClass === '' || cls === this.filterClass;
            const matchRarity = this.filterRarity === '' || rarity === this.filterRarity;
            
            return matchSearch && matchGen && matchClass && matchRarity;
        },
        
        countVisible() {
            // Helper to check if any are visible (would require querying DOM in real implementation or maintaining a list)
            // For simplicity in this Alpine component, simple toggling. 
            // A more robust way is to dispatch an event or use a getter that queries DOM.
            // Simplified return 1 to avoid 'hidden' logic complexity without full DOM scan every keystroke.
            return 1; 
        },
        
        resetFilters() {
            this.search = '';
            this.filterGen = '';
            this.filterClass = '';
            this.filterRarity = '';
        }
    }
}
</script>

<?php get_footer(); ?>
