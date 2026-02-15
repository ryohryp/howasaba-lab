<?php
/**
 * The template for displaying all single Hero posts
 *
 * @package WoS_Frost_Fire
 */

get_header();

// Get Hero Meta Data
$hero_id = get_the_ID();
$stats_attack = get_post_meta( $hero_id, '_hero_stats_atk', true );
$stats_defense = get_post_meta( $hero_id, '_hero_stats_def', true );
$stats_health = get_post_meta( $hero_id, '_hero_stats_hp', true );

// Old Skills (Fallback)
$expedition_skill = get_post_meta( $hero_id, '_hero_expedition_skill', true );
$exploration_skill = get_post_meta( $hero_id, '_hero_exploration_skill', true );

// New Skills (Gen 6+)
$skill_exploration_active = get_post_meta( $hero_id, 'skill_exploration_active', true );
$skill_expedition_1 = get_post_meta( $hero_id, 'skill_expedition_1', true );
$skill_expedition_2 = get_post_meta( $hero_id, 'skill_expedition_2', true );
$skill_expedition_3 = get_post_meta( $hero_id, 'skill_expedition_3', true );

// Japanese Name
$japanese_name = get_post_meta( $hero_id, 'japanese_name', true );

// Terms
$generation = get_the_terms( $hero_id, 'hero_generation' );
$type = get_the_terms( $hero_id, 'hero_type' );
$rarity = get_the_terms( $hero_id, 'hero_rarity' );
$gen_name = !empty($generation) ? $generation[0]->name : 'Unknown Gen';
$type_name = !empty($type) ? $type[0]->name : 'Unknown Type';
$rarity_name = !empty($rarity) ? $rarity[0]->name : 'Common';
// ... (JSON-LD part skipped for brevity in replace, effectively keeping it same if I target correctly)
?>

<script type="application/ld+json">
<?php
// Re-construct JSON-LD to include JP name if needed, but not strictly required.
$json_ld = [
    "@context" => "https://schema.org",
    "@type" => "GameCharacter",
    "name" => get_the_title(),
    "alternateName" => $japanese_name, // Add JP name
    "image" => get_the_post_thumbnail_url( $hero_id, 'full' ),
    "description" => get_the_excerpt(),
    "gameItem" => [
        "@type" => "Thing",
        "name" => "Whiteout Survival Hero"
    ],
    "additionalProperty" => [
        [
            "@type" => "PropertyValue",
            "name" => "Generation",
            "value" => $gen_name
        ],
        [
            "@type" => "PropertyValue",
            "name" => "Type",
            "value" => $type_name
        ],
        [
            "@type" => "PropertyValue",
            "name" => "Rarity",
            "value" => $rarity_name
        ]
    ]
];
echo json_encode($json_ld, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES); 
?>
</script>

<main id="primary" class="site-main container mx-auto px-4 py-8">

    <?php while ( have_posts() ) : the_post(); ?>

        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
            
            <!-- Hero Header / Banner (Flat Version) -->
            <div class="relative mb-8 rounded-xl overflow-hidden shadow-lg bg-slate-800">
                <style>
                    /* Custom styles for this template */
                    .skill-value {
                        color: #f97316; /* Orange-500 */
                        font-weight: bold;
                    }
                    .text-jp-name {
                        font-family: "Noto Sans JP", sans-serif;
                    }
                </style>
                <div class="flex flex-col md:flex-row p-6 md:p-10 gap-6 md:gap-10 bg-slate-800 items-start">
                    <!-- Image Column (Fixed 91x91) -->
                    <div class="flex-shrink-0">
                        <div class="w-[91px] h-[91px] relative rounded-lg overflow-hidden bg-slate-900 border-2 border-slate-600 shadow-lg">
                             <?php if ( has_post_thumbnail() ) : ?>
                                <?php the_post_thumbnail( 'thumbnail', array( 'class' => 'h-full w-full object-cover object-top' ) ); ?>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Info Column -->
                    <div class="flex-grow flex flex-col justify-center">
                        <div class="mb-4 flex flex-wrap gap-2 items-center">
                             <?php 
                                $overall_tier = get_post_meta( $hero_id, 'overall_tier', true );
                                $tier_bg = 'bg-gray-600';
                                if ($overall_tier === 'S+' || $overall_tier === 'S') $tier_bg = 'bg-orange-500';
                                if ($overall_tier === 'A') $tier_bg = 'bg-purple-600';
                                if ($overall_tier === 'B') $tier_bg = 'bg-blue-600';
                             ?>
                             <?php if($overall_tier): ?>
                                <span class="rounded-lg <?php echo $tier_bg; ?> px-3 py-1 text-base font-black text-white shadow-md">Tier <?php echo esc_html( $overall_tier ); ?></span>
                             <?php endif; ?>
                             <span class="rounded-lg bg-slate-700 px-3 py-1 text-sm font-bold text-ice-blue border border-slate-600"><?php echo esc_html( $gen_name ); ?></span>
                             <span class="rounded-lg bg-slate-700 px-3 py-1 text-sm font-bold text-gray-300 border border-slate-600"><?php echo esc_html( $type_name ); ?></span>
                        </div>

                        <h1 class="mb-1 text-3xl md:text-5xl font-black text-white uppercase tracking-tighter leading-none">
                            <?php the_title(); ?>
                        </h1>
                        <?php if ( $japanese_name ) : ?>
                            <h2 class="mb-4 text-xl md:text-2xl font-bold text-slate-400 text-jp-name tracking-widest pl-1">
                                <?php echo esc_html( $japanese_name ); ?>
                            </h2>
                        <?php endif; ?>

                        <div class="prose prose-invert prose-lg max-w-none mb-6 text-slate-300 font-light leading-relaxed">
                            <?php the_content(); ?>
                        </div>

                        <!-- Quick Stats Grid (Flat Blocks) -->
                        <div class="grid grid-cols-3 gap-4 max-w-xl">
                            <div class="text-center bg-slate-700/50 rounded-lg p-3 border border-slate-700">
                                <div class="text-[10px] uppercase tracking-widest text-slate-400 mb-1"><?php _e( 'Attack', 'wos-frost-fire' ); ?></div>
                                <div class="text-xl font-black text-white font-mono"><?php echo esc_html( $stats_attack ?: '-' ); ?></div>
                            </div>
                            <div class="text-center bg-slate-700/50 rounded-lg p-3 border border-slate-700">
                                <div class="text-[10px] uppercase tracking-widest text-slate-400 mb-1"><?php _e( 'Defense', 'wos-frost-fire' ); ?></div>
                                <div class="text-xl font-black text-white font-mono"><?php echo esc_html( $stats_defense ?: '-' ); ?></div>
                            </div>
                            <div class="text-center bg-slate-700/50 rounded-lg p-3 border border-slate-700">
                                <div class="text-[10px] uppercase tracking-widest text-slate-400 mb-1"><?php _e( 'Health', 'wos-frost-fire' ); ?></div>
                                <div class="text-xl font-black text-white font-mono"><?php echo esc_html( $stats_health ?: '-' ); ?></div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Skills Section (Flat Cards) -->
            <div class="grid md:grid-cols-2 gap-6">
                <!-- Exploration Skills -->
                <div class="flat-card p-6 md:p-8">
                    <h3 class="mb-4 text-xl font-bold text-ice-blue flex items-center gap-3 border-b border-slate-700 pb-4">
                        <span class="text-2xl">🧭</span> <?php _e( 'Exploration Skills', 'wos-frost-fire' ); ?>
                    </h3>
                    <div class="text-slate-300 leading-relaxed text-lg space-y-6">
                        <?php 
                        // Active Skill
                        if ( $skill_exploration_active ) {
                            echo '<div>';
                            echo '<strong class="block text-slate-400 text-xs uppercase tracking-wider mb-2">' . __( 'Active Skill', 'wos-frost-fire' ) . '</strong>';
                            echo wp_kses_post( $skill_exploration_active );
                            echo '</div>';
                        } elseif ( $exploration_skill ) {
                             // Fallback to old single field
                             echo wpautop( esc_html( $exploration_skill ) );
                        } else {
                            _e( 'No skill data available.', 'wos-frost-fire' );
                        }

                        // Passive Skills (New)
                        $skill_exploration_passive_1 = get_post_meta( $hero_id, 'skill_exploration_passive_1', true );
                        $skill_exploration_passive_2 = get_post_meta( $hero_id, 'skill_exploration_passive_2', true );

                        if ( $skill_exploration_passive_1 ) {
                            echo '<div>';
                            echo '<strong class="block text-slate-400 text-xs uppercase tracking-wider mb-2">' . __( 'Passive Skill 1', 'wos-frost-fire' ) . '</strong>';
                            echo wp_kses_post( $skill_exploration_passive_1 );
                            echo '</div>';
                        }

                        if ( $skill_exploration_passive_2 ) {
                            echo '<div>';
                            echo '<strong class="block text-slate-400 text-xs uppercase tracking-wider mb-2">' . __( 'Passive Skill 2', 'wos-frost-fire' ) . '</strong>';
                            echo wp_kses_post( $skill_exploration_passive_2 );
                            echo '</div>';
                        }
                        ?>
                    </div>
                </div>

                <!-- Expedition Skills -->
                <div class="flat-card p-6 md:p-8">
                    <h3 class="mb-4 text-xl font-bold text-ice-blue flex items-center gap-3 border-b border-slate-700 pb-4">
                        <span class="text-2xl">⚔️</span> <?php _e( 'Expedition Skills', 'wos-frost-fire' ); ?>
                    </h3>
                    <div class="text-slate-300 leading-relaxed space-y-6">
                        <?php if ( $skill_expedition_1 || $skill_expedition_2 || $skill_expedition_3 ) : ?>
                            <?php if ( $skill_expedition_1 ) : ?>
                                <div>
                                    <strong class="block text-slate-400 text-xs uppercase tracking-wider mb-2"><?php _e( 'Skill 1', 'wos-frost-fire' ); ?></strong>
                                    <div class="text-lg"><?php echo wp_kses_post( $skill_expedition_1 ); ?></div>
                                </div>
                            <?php endif; ?>
                            <?php if ( $skill_expedition_2 ) : ?>
                                <div>
                                    <strong class="block text-slate-400 text-xs uppercase tracking-wider mb-2"><?php _e( 'Skill 2', 'wos-frost-fire' ); ?></strong>
                                    <div class="text-lg"><?php echo wp_kses_post( $skill_expedition_2 ); ?></div>
                                </div>
                            <?php endif; ?>
                            <?php if ( $skill_expedition_3 ) : ?>
                                <div>
                                    <strong class="block text-slate-400 text-xs uppercase tracking-wider mb-2"><?php _e( 'Skill 3', 'wos-frost-fire' ); ?></strong>
                                    <div class="text-lg"><?php echo wp_kses_post( $skill_expedition_3 ); ?></div>
                                </div>
                            <?php endif; ?>
                        <?php elseif ( $expedition_skill ) : ?>
                             <?php echo wpautop( esc_html( $expedition_skill ) ); ?>
                        <?php else : ?>
                            <?php _e( 'No skill data available.', 'wos-frost-fire' ); ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

                </article><!-- #post-<?php the_ID(); ?> -->

                <!-- Exclusive Gear Section -->
                <?php 
                $gear_details = get_post_meta( $hero_id, 'exclusive_gear_details', true );
                $gear_priority = get_post_meta( $hero_id, 'exclusive_gear_priority', true );
                $widget_name = get_post_meta( $hero_id, '_hero_widget_name', true );
                
                if ( $gear_details || $widget_name ) : 
                ?>
                <div class="mt-6 flat-card p-6 md:p-8">
                    <h3 class="mb-4 text-xl font-bold text-ice-blue flex items-center gap-3 border-b border-slate-700 pb-4">
                        <span class="text-2xl">🛡️</span> <?php _e( 'Exclusive Gear', 'wos-frost-fire' ); ?>
                        <?php if($widget_name): ?>
                            <span class="text-white text-base font-normal ml-auto bg-slate-700 px-3 py-1 rounded"><?php echo esc_html($widget_name); ?></span>
                        <?php endif; ?>
                    </h3>
                    <div class="grid md:grid-cols-12 gap-8">
                        <div class="md:col-span-8 text-slate-300 leading-relaxed text-lg">
                            <?php echo wp_kses_post( wpautop($gear_details) ); ?>
                        </div>
                        <?php if($gear_priority): ?>
                        <div class="md:col-span-4 flex flex-col justify-center items-center bg-slate-900/50 rounded-lg p-6 border border-slate-700/50">
                            <div class="text-xs text-slate-400 uppercase tracking-widest mb-3"><?php _e( 'Priority', 'wos-frost-fire' ); ?></div>
                            <div class="inline-block px-6 py-2 rounded-full bg-fire-crystal text-white font-black shadow-lg text-xl transform hover:scale-105 transition-transform">
                                <?php echo esc_html($gear_priority); ?>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Team Recommendation Section -->
                <?php 
                $team_rec = get_post_meta( $hero_id, 'team_recommendation', true );
                if ( $team_rec ) : 
                ?>
                <div class="mt-6 flat-card p-6 md:p-8">
                    <h3 class="mb-4 text-xl font-bold text-ice-blue flex items-center gap-3 border-b border-slate-700 pb-4">
                        <span class="text-2xl">🤝</span> <?php _e( 'Recommended Formation', 'wos-frost-fire' ); ?>
                    </h3>
                    <div class="text-slate-300 leading-relaxed text-lg">
                        <?php echo wp_kses_post( wpautop($team_rec) ); ?>
                    </div>
                </div>
                <?php endif; ?>

        </article><!-- #post-<?php the_ID(); ?> -->

    <?php endwhile; // End of the loop. ?>

</main><!-- #main -->

<?php
get_footer();
