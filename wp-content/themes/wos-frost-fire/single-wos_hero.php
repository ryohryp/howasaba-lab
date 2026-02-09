<?php
/**
 * The template for displaying all single hero posts
 *
 * @package WOS_Frost_Fire
 */

get_header();
?>

<main id="primary" class="site-main min-h-screen relative pb-20">
    
    <!-- Hero Background Image (blurred) -->
    <div class="absolute inset-0 z-0 overflow-hidden">
        <?php if ( has_post_thumbnail() ) : ?>
            <div class="absolute inset-0 bg-cover bg-center blur-2xl opacity-30 scale-110" style="background-image: url('<?php echo get_the_post_thumbnail_url( get_the_ID(), 'full' ); ?>');"></div>
        <?php endif; ?>
        <div class="absolute inset-0 bg-deep-freeze/80"></div>
    </div>

    <div class="relative z-10 container mx-auto px-4 py-12">
        
        <?php
        while ( have_posts() ) :
            the_post();
            
            // Get Meta Data
            $stats_atk = get_post_meta( get_the_ID(), 'stats_attack', true );
            $stats_def = get_post_meta( get_the_ID(), 'stats_defense', true );
            $stats_hp  = get_post_meta( get_the_ID(), 'stats_health', true );
            
            $exp_skill = get_post_meta( get_the_ID(), 'expedition_skill', true );
            $expl_skill= get_post_meta( get_the_ID(), 'exploration_skill', true );
            
            $rec_widget= get_post_meta( get_the_ID(), 'recommended_widget', true );
            ?>

            <!-- Breadcrumb / Back Link -->
            <div class="mb-8">
                <a href="<?php echo get_post_type_archive_link('wos_hero'); ?>" class="inline-flex items-center text-ice-blue hover:text-white transition-colors font-bold uppercase tracking-wider text-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Back to Heroes
                </a>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12">
                
                <!-- Left Column: Image & Basic Info -->
                <div class="lg:col-span-5">
                    <div class="glass-panel p-2 rounded-2xl mb-8 relative group overflow-hidden">
                        <div class="aspect-[3/4] rounded-xl overflow-hidden relative">
                            <?php if ( has_post_thumbnail() ) : ?>
                                <?php the_post_thumbnail( 'full', array( 'class' => 'w-full h-full object-cover' ) ); ?>
                            <?php else : ?>
                                <div class="w-full h-full bg-white/10 flex items-center justify-center">No Image</div>
                            <?php endif; ?>
                            
                            <!-- Frost Overlay Effect -->
                            <div class="absolute inset-0 pointer-events-none bg-gradient-to-t from-deep-freeze/80 via-transparent to-transparent opacity-60"></div>
                        </div>
                    </div>

                    <!-- Types & Tags -->
                    <div class="flex flex-wrap gap-2 justify-center lg:justify-start">
                        <?php echo get_the_term_list( get_the_ID(), 'hero_generation', '<span class="px-3 py-1 rounded-full bg-purple-900/40 border border-purple-500/30 text-purple-200 text-sm font-bold uppercase tracking-wider">', '', '</span>' ); ?>
                        <?php echo get_the_term_list( get_the_ID(), 'hero_type', '<span class="px-3 py-1 rounded-full bg-blue-900/40 border border-blue-500/30 text-blue-200 text-sm font-bold uppercase tracking-wider">', '', '</span>' ); ?>
                        <?php echo get_the_term_list( get_the_ID(), 'hero_rarity', '<span class="px-3 py-1 rounded-full bg-yellow-900/40 border border-yellow-500/30 text-yellow-200 text-sm font-bold uppercase tracking-wider">', '', '</span>' ); ?>
                    </div>
                </div>

                <!-- Right Column: Details -->
                <div class="lg:col-span-7">
                    <h1 class="text-5xl lg:text-6xl font-display font-bold text-white mb-2 drop-shadow-lg"><?php the_title(); ?></h1>
                    <div class="w-20 h-1 bg-fire-crystal mb-8"></div>

                    <!-- Content / Bio -->
                    <div class="prose prose-invert prose-lg max-w-none text-blue-50/80 mb-12">
                        <?php the_content(); ?>
                    </div>

                    <!-- Stats Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-12">
                        <!-- Attack -->
                        <div class="glass-panel p-4 rounded-xl text-center">
                            <h3 class="text-xs uppercase tracking-widest text-white/50 mb-1">Attack</h3>
                            <div class="text-2xl font-bold text-fire-crystal"><?php echo esc_html( $stats_atk ?: '---' ); ?></div>
                        </div>
                        <!-- Defense -->
                        <div class="glass-panel p-4 rounded-xl text-center">
                            <h3 class="text-xs uppercase tracking-widest text-white/50 mb-1">Defense</h3>
                            <div class="text-2xl font-bold text-ice-blue"><?php echo esc_html( $stats_def ?: '---' ); ?></div>
                        </div>
                        <!-- Health -->
                        <div class="glass-panel p-4 rounded-xl text-center">
                            <h3 class="text-xs uppercase tracking-widest text-white/50 mb-1">Health</h3>
                            <div class="text-2xl font-bold text-green-400"><?php echo esc_html( $stats_hp ?: '---' ); ?></div>
                        </div>
                    </div>

                    <!-- Skills Section -->
                    <div class="space-y-6">
                        <!-- Expedition Skill -->
                        <div class="glass-panel p-6 rounded-xl" x-data="{ open: true }">
                            <button @click="open = !open" class="flex justify-between items-center w-full text-left">
                                <h3 class="text-xl font-bold text-white flex items-center">
                                    <span class="mr-3 text-2xl">⚔️</span> Expedition Skill
                                </h3>
                                <svg class="w-6 h-6 transform transition-transform" :class="{'rotate-180': open}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                            <div x-show="open" x-collapse class="mt-4 text-white/80 leading-relaxed border-t border-white/10 pt-4">
                                <?php echo wp_kses_post( wpautop( $exp_skill ?: 'No skill data available.' ) ); ?>
                            </div>
                        </div>

                        <!-- Exploration Skill -->
                        <div class="glass-panel p-6 rounded-xl" x-data="{ open: false }">
                            <button @click="open = !open" class="flex justify-between items-center w-full text-left">
                                <h3 class="text-xl font-bold text-white flex items-center">
                                    <span class="mr-3 text-2xl">🗺️</span> Exploration Skill
                                </h3>
                                <svg class="w-6 h-6 transform transition-transform" :class="{'rotate-180': open}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                            <div x-show="open" x-collapse class="mt-4 text-white/80 leading-relaxed border-t border-white/10 pt-4">
                                <?php echo wp_kses_post( wpautop( $expl_skill ?: 'No skill data available.' ) ); ?>
                            </div>
                        </div>
                    </div>

                    <!-- Recommended Widget -->
                    <?php if ( $rec_widget ) : ?>
                        <div class="mt-8 glass-panel p-6 rounded-xl bg-gradient-to-r from-fire-crystal/10 to-transparent border-fire-crystal/30">
                            <h3 class="text-lg font-bold text-fire-crystal mb-2 uppercase tracking-wider">Recommended Widget</h3>
                            <p class="text-white"><?php echo esc_html( $rec_widget ); ?></p>
                        </div>
                    <?php endif; ?>

                </div>
            </div>

        <?php endwhile; // End of the loop. ?>

    </div>
</main>

<?php
get_footer();
