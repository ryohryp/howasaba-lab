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
            
            <!-- Hero Header / Banner -->
            <div class="relative mb-8 rounded-2xl overflow-hidden shadow-2xl border border-white/10 bg-gradient-to-br from-deep-freeze to-midnight-navy">
                <style>
                    /* Custom styles for this template */
                    .skill-value {
                        color: #fbbf24; /* Amber-400 */
                        font-weight: bold;
                    }
                    .text-jp-name {
                        font-family: "Noto Sans JP", sans-serif;
                    }
                </style>
                <div class="grid md:grid-cols-3 gap-0">
                    <!-- Image Column -->
                    <div class="relative h-96 md:h-auto bg-black/20">
                         <?php if ( has_post_thumbnail() ) : ?>
                            <?php the_post_thumbnail( 'large', array( 'class' => 'h-full w-full object-cover object-top' ) ); ?>
                            <div class="absolute inset-0 bg-gradient-to-t from-deep-freeze to-transparent md:bg-gradient-to-r"></div>
                        <?php endif; ?>
                    </div>

                    <!-- Info Column -->
                    <div class="col-span-2 p-6 md:p-12 flex flex-col justify-center">
                        <div class="mb-4 flex flex-wrap gap-2">
                             <?php 
                                $overall_tier = get_post_meta( $hero_id, 'overall_tier', true );
                                $tier_color = 'bg-gray-500';
                                if ($overall_tier === 'S+' || $overall_tier === 'S') $tier_color = 'bg-gradient-to-r from-yellow-400 to-orange-500';
                                if ($overall_tier === 'A') $tier_color = 'bg-purple-500';
                                if ($overall_tier === 'B') $tier_color = 'bg-blue-500';
                             ?>
                             <?php if($overall_tier): ?>
                                <span class="rounded <?php echo $tier_color; ?> px-3 py-1 text-sm font-bold text-white shadow-lg shadow-fire-crystal/20 border border-white/20">Tier <?php echo esc_html( $overall_tier ); ?></span>
                             <?php endif; ?>
                             <span class="rounded bg-white/10 px-3 py-1 text-sm font-bold text-ice-blue border border-white/10"><?php echo esc_html( $gen_name ); ?></span>
                             <span class="rounded bg-white/10 px-3 py-1 text-sm font-bold text-gray-300 border border-white/10"><?php echo esc_html( $type_name ); ?></span>
                        </div>

                        <h1 class="mb-2 text-3xl md:text-6xl font-black text-white uppercase tracking-tight drop-shadow-lg">
                            <?php the_title(); ?>
                        </h1>
                        <?php if ( $japanese_name ) : ?>
                            <h2 class="mb-4 text-2xl md:text-3xl font-bold text-gray-400 text-jp-name">
                                <?php echo esc_html( $japanese_name ); ?>
                            </h2>
                        <?php endif; ?>

                        <div class="prose prose-invert max-w-none mb-8 text-gray-300">
                            <?php the_content(); ?>
                        </div>

                        <!-- Quick Stats Grid -->
                        <div class="grid grid-cols-3 gap-4 border-t border-white/10 pt-6">
                            <div class="text-center">
                                <div class="text-xs uppercase tracking-wider text-gray-500"><?php _e( 'Attack', 'wos-frost-fire' ); ?></div>
                                <div class="text-2xl font-bold text-white"><?php echo esc_html( $stats_attack ?: 'N/A' ); ?></div>
                            </div>
                            <div class="text-center border-l border-white/10">
                                <div class="text-xs uppercase tracking-wider text-gray-500"><?php _e( 'Defense', 'wos-frost-fire' ); ?></div>
                                <div class="text-2xl font-bold text-white"><?php echo esc_html( $stats_defense ?: 'N/A' ); ?></div>
                            </div>
                            <div class="text-center border-l border-white/10">
                                <div class="text-xs uppercase tracking-wider text-gray-500"><?php _e( 'Health', 'wos-frost-fire' ); ?></div>
                                <div class="text-2xl font-bold text-white"><?php echo esc_html( $stats_health ?: 'N/A' ); ?></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Skills Section using Glassmorphism -->
            <div class="grid md:grid-cols-2 gap-8">
                <!-- Exploration Skill -->
                <div class="rounded-xl border border-white/10 bg-white/5 p-6 backdrop-blur-md">
                    <h3 class="mb-4 text-xl font-bold text-ice-blue flex items-center gap-2">
                        <span>🧭</span> <?php _e( 'Exploration Skill (Active)', 'wos-frost-fire' ); ?>
                    </h3>
                    <div class="text-gray-300 leading-relaxed">
                        <?php 
                        if ( $skill_exploration_active ) {
                            echo wp_kses_post( $skill_exploration_active ); 
                        } elseif ( $exploration_skill ) {
                             echo wpautop( esc_html( $exploration_skill ) );
                        } else {
                            _e( 'No skill data available.', 'wos-frost-fire' );
                        }
                        ?>
                    </div>
                </div>

                <!-- Expedition Skills -->
                <div class="rounded-xl border border-white/10 bg-white/5 p-6 backdrop-blur-md">
                    <h3 class="mb-4 text-xl font-bold text-ice-blue flex items-center gap-2">
                        <span>⚔️</span> <?php _e( 'Expedition Skills', 'wos-frost-fire' ); ?>
                    </h3>
                    <div class="text-gray-300 leading-relaxed space-y-4">
                        <?php if ( $skill_expedition_1 || $skill_expedition_2 ) : ?>
                            <?php if ( $skill_expedition_1 ) : ?>
                                <div>
                                    <strong class="block text-gray-200 text-sm mb-1"><?php _e( 'Skill 1', 'wos-frost-fire' ); ?></strong>
                                    <?php echo wp_kses_post( $skill_expedition_1 ); ?>
                                </div>
                            <?php endif; ?>
                            <?php if ( $skill_expedition_2 ) : ?>
                                <div>
                                    <strong class="block text-gray-200 text-sm mb-1"><?php _e( 'Skill 2', 'wos-frost-fire' ); ?></strong>
                                    <?php echo wp_kses_post( $skill_expedition_2 ); ?>
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
                <div class="mt-8 rounded-xl border border-white/10 bg-white/5 p-6 backdrop-blur-md">
                    <h3 class="mb-4 text-xl font-bold text-ice-blue flex items-center gap-2">
                        <span>🛡️</span> <?php _e( 'Exclusive Gear', 'wos-frost-fire' ); ?>
                        <?php if($widget_name): ?>
                            <span class="text-white text-base font-normal ml-2">- <?php echo esc_html($widget_name); ?></span>
                        <?php endif; ?>
                    </h3>
                    <div class="grid md:grid-cols-3 gap-6">
                        <div class="md:col-span-2 text-gray-300 leading-relaxed">
                            <?php echo wp_kses_post( wpautop($gear_details) ); ?>
                        </div>
                        <?php if($gear_priority): ?>
                        <div class="border-t md:border-t-0 md:border-l border-white/10 pt-4 md:pt-0 md:pl-6">
                            <div class="text-sm text-gray-400 uppercase tracking-widest mb-1"><?php _e( 'Priority', 'wos-frost-fire' ); ?></div>
                            <div class="inline-block px-3 py-1 rounded bg-gradient-to-r from-fire-crystal/80 to-red-600/80 text-white font-bold shadow-lg">
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
                <div class="mt-8 rounded-xl border border-white/10 bg-white/5 p-6 backdrop-blur-md">
                    <h3 class="mb-4 text-xl font-bold text-ice-blue flex items-center gap-2">
                        <span>🤝</span> <?php _e( 'Recommended Formation', 'wos-frost-fire' ); ?>
                    </h3>
                    <div class="text-gray-300 leading-relaxed">
                        <?php echo wp_kses_post( wpautop($team_rec) ); ?>
                    </div>
                </div>
                <?php endif; ?>

        </article><!-- #post-<?php the_ID(); ?> -->

    <?php endwhile; // End of the loop. ?>

</main><!-- #main -->

<?php
get_footer();
