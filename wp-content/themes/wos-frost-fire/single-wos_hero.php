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
$expedition_skill = get_post_meta( $hero_id, '_hero_expedition_skill', true );
$exploration_skill = get_post_meta( $hero_id, '_hero_exploration_skill', true );

// Terms
$generation = get_the_terms( $hero_id, 'hero_generation' );
$type = get_the_terms( $hero_id, 'hero_type' );
$rarity = get_the_terms( $hero_id, 'hero_rarity' );
$gen_name = !empty($generation) ? $generation[0]->name : 'Unknown Gen';
$type_name = !empty($type) ? $type[0]->name : 'Unknown Type';
$rarity_name = !empty($rarity) ? $rarity[0]->name : 'Common';

// Construct JSON-LD
$json_ld = [
    "@context" => "https://schema.org",
    "@type" => "GameCharacter",
    "name" => get_the_title(),
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
?>

<script type="application/ld+json">
<?php echo json_encode($json_ld, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES); ?>
</script>

<main id="primary" class="site-main container mx-auto px-4 py-8">

    <?php while ( have_posts() ) : the_post(); ?>

        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
            
            <!-- Hero Header / Banner -->
            <div class="relative mb-8 rounded-2xl overflow-hidden shadow-2xl border border-white/10 bg-gradient-to-br from-deep-freeze to-midnight-navy">
                <div class="grid md:grid-cols-3 gap-0">
                    <!-- Image Column -->
                    <div class="relative h-96 md:h-auto bg-black/20">
                         <?php if ( has_post_thumbnail() ) : ?>
                            <?php the_post_thumbnail( 'large', array( 'class' => 'h-full w-full object-cover object-top' ) ); ?>
                            <div class="absolute inset-0 bg-gradient-to-t from-deep-freeze to-transparent md:bg-gradient-to-r"></div>
                        <?php endif; ?>
                    </div>

                    <!-- Info Column -->
                    <div class="col-span-2 p-8 md:p-12 flex flex-col justify-center">
                        <div class="mb-4 flex flex-wrap gap-2">
                             <span class="rounded bg-fire-crystal px-3 py-1 text-sm font-bold text-white shadow-lg shadow-fire-crystal/20"><?php echo esc_html( $rarity_name ); ?></span>
                             <span class="rounded bg-white/10 px-3 py-1 text-sm font-bold text-ice-blue border border-white/10"><?php echo esc_html( $gen_name ); ?></span>
                             <span class="rounded bg-white/10 px-3 py-1 text-sm font-bold text-gray-300 border border-white/10"><?php echo esc_html( $type_name ); ?></span>
                        </div>

                        <h1 class="mb-4 text-4xl md:text-6xl font-black text-white uppercase tracking-tight drop-shadow-lg">
                            <?php the_title(); ?>
                        </h1>

                        <div class="prose prose-invert max-w-none mb-8 text-gray-300">
                            <?php the_content(); ?>
                        </div>

                        <!-- Quick Stats Grid -->
                        <div class="grid grid-cols-3 gap-4 border-t border-white/10 pt-6">
                            <div class="text-center">
                                <div class="text-xs uppercase tracking-wider text-gray-500">Attack</div>
                                <div class="text-2xl font-bold text-white"><?php echo esc_html( $stats_attack ?: 'N/A' ); ?></div>
                            </div>
                            <div class="text-center border-l border-white/10">
                                <div class="text-xs uppercase tracking-wider text-gray-500">Defense</div>
                                <div class="text-2xl font-bold text-white"><?php echo esc_html( $stats_defense ?: 'N/A' ); ?></div>
                            </div>
                            <div class="text-center border-l border-white/10">
                                <div class="text-xs uppercase tracking-wider text-gray-500">Health</div>
                                <div class="text-2xl font-bold text-white"><?php echo esc_html( $stats_health ?: 'N/A' ); ?></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Skills Section using Glassmorphism -->
            <div class="grid md:grid-cols-2 gap-8">
                <!-- Expedition Skill -->
                <div class="rounded-xl border border-white/10 bg-white/5 p-6 backdrop-blur-md">
                    <h3 class="mb-4 text-xl font-bold text-ice-blue flex items-center gap-2">
                        <span>⚔️</span> Expedition Skill
                    </h3>
                    <div class="text-gray-300 leading-relaxed">
                        <?php echo wpautop( esc_html( $expedition_skill ?: 'No skill data available.' ) ); ?>
                    </div>
                </div>

                <!-- Exploration Skill -->
                <div class="rounded-xl border border-white/10 bg-white/5 p-6 backdrop-blur-md">
                    <h3 class="mb-4 text-xl font-bold text-ice-blue flex items-center gap-2">
                        <span>🧭</span> Exploration Skill
                    </h3>
                    <div class="text-gray-300 leading-relaxed">
                        <?php echo wpautop( esc_html( $exploration_skill ?: 'No skill data available.' ) ); ?>
                    </div>
                </div>
            </div>

        </article><!-- #post-<?php the_ID(); ?> -->

    <?php endwhile; // End of the loop. ?>

</main><!-- #main -->

<?php
get_footer();
