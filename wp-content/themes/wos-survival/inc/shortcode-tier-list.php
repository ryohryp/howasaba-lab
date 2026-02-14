<?php
/**
 * Shortcode: [wos_tier_list]
 * Attributes:
 * - gen: int (optional) - Max generation to display.
 */
function wos_shortcode_tier_list( $atts ) {
    $atts = shortcode_atts( array(
        'gen' => '', // Empty means all
    ), $atts, 'wos_tier_list' );

    // Enqueue styles
    wp_enqueue_style( 'wos-tier-list-style' );

    // Build Query
    $args = array(
        'post_type'      => 'wos_hero',
        'posts_per_page' => -1,
        'post_status'    => 'publish',
        'meta_query'     => array(),
    );

    // Filter by Generation if provided
    if ( ! empty( $atts['gen'] ) ) {
        // Assuming 'generation' is stored as a number in post_meta by ACF
        $args['meta_query'][] = array(
            'key'     => 'generation',
            'value'   => intval( $atts['gen'] ),
            'compare' => '<=',
            'type'    => 'NUMERIC',
        );
    }

    $query = new WP_Query( $args );
    
    if ( ! $query->have_posts() ) {
        return '<p>No heroes found.</p>';
    }

    // Grouping & Sorting Data
    $heroes_by_tier = array(
        'S+' => [],
        'S'  => [],
        'A'  => [],
        'B'  => [],
        'C'  => [], // Add more if needed
    );

    // Troop Type Priority for Sorting: Infantry=1, Lancer=2, Marksman=3
    $troop_priority = array(
        'Infantry' => 1,
        'Lancer'   => 2,
        'Marksman' => 3,
    );

    while ( $query->have_posts() ) {
        $query->the_post();
        $hero_id = get_the_ID();
        
        // Retrieve ACF fields
        // Use get_field if ACF is active, otherwise fallback to get_post_meta
        $tier = function_exists('get_field') ? get_field( 'overall_tier', $hero_id ) : get_post_meta($hero_id, 'overall_tier', true);
        $gen  = function_exists('get_field') ? get_field( 'generation', $hero_id ) : get_post_meta($hero_id, 'generation', true);
        $type = function_exists('get_field') ? get_field( 'troop_type', $hero_id ) : get_post_meta($hero_id, 'troop_type', true);
        $roles = function_exists('get_field') ? get_field( 'special_role', $hero_id ) : get_post_meta($hero_id, 'special_role', true);
        $roles = is_array($roles) ? $roles : [];

        // Normalize Tier Key (handle potential case sensitivity or spaces)
        $tier_key = strtoupper( trim( $tier ) );
        if ( ! array_key_exists( $tier_key, $heroes_by_tier ) ) {
            // If Tier is undefined (e.g. empty), maybe put in 'C' or skip
            // For now, let's create a partial bucket or skip
            // $heroes_by_tier[$tier_key] = []; // Uncomment to allow dynamic tiers
            continue; 
        }

        $hero_data = array(
            'id'    => $hero_id,
            'name'  => get_the_title(),
            'thumb' => get_the_post_thumbnail_url( $hero_id, 'thumbnail' ), // Use medium or thumbnail
            'gen'   => $gen,
            'type'  => $type,
            'roles' => $roles,
            'link'  => get_permalink(),
        );

        $heroes_by_tier[$tier_key][] = $hero_data;
    }
    wp_reset_postdata();

    // Sort within each Tier
    foreach ( $heroes_by_tier as $tier => &$heroes ) {
        usort( $heroes, function( $a, $b ) use ( $troop_priority ) {
            $pA = $troop_priority[ $a['type'] ] ?? 99;
            $pB = $troop_priority[ $b['type'] ] ?? 99;
            
            if ( $pA === $pB ) {
                // Secondary sort by Generation (High to Low? Low to High?) 
                // Let's say Higher Gen first
                return $b['gen'] - $a['gen'];
            }
            return $pA - $pB;
        });
    }
    unset($heroes);

    // Output HTML
    ob_start();
    ?>
    <div class="wos-tier-list-container">
        <?php foreach ( $heroes_by_tier as $tier_name => $group_heroes ) : 
            if ( empty( $group_heroes ) ) continue;
            
            // Determine row style class based on Tier
            $row_class = 'tier-row-' . strtolower( str_replace( '+', '-plus', $tier_name ) );
            ?>
            <div class="wos-tier-row <?php echo esc_attr( $row_class ); ?>">
                <div class="wos-tier-label">
                    <span class="tier-text"><?php echo esc_html( $tier_name ); ?></span>
                </div>
                <div class="wos-tier-heroes">
                    <?php foreach ( $group_heroes as $hero ) : 
                        $type_class = 'type-' . strtolower( $hero['type'] );
                        ?>
                        <a href="<?php echo esc_url( $hero['link'] ); ?>" class="wos-hero-card <?php echo esc_attr( $type_class ); ?>">
                            <div class="hero-card-inner">
                                <div class="hero-image-wrapper">
                                    <?php if ( $hero['thumb'] ) : ?>
                                        <img src="<?php echo esc_url( $hero['thumb'] ); ?>" alt="<?php echo esc_attr( $hero['name'] ); ?>" class="hero-thumb">
                                    <?php else : ?>
                                        <div class="hero-thumb-placeholder"></div>
                                    <?php endif; ?>
                                    <span class="hero-gen-badge">Gen <?php echo esc_html( $hero['gen'] ); ?></span>
                                    <span class="hero-type-icon icon-<?php echo esc_attr( strtolower( $hero['type'] ) ); ?>"></span>
                                </div>
                                <div class="hero-info">
                                    <span class="hero-name"><?php echo esc_html( $hero['name'] ); ?></span>
                                    <?php if ( ! empty( $hero['roles'] ) ) : ?>
                                        <div class="hero-roles">
                                            <?php foreach ( $hero['roles'] as $role ) : ?>
                                                <span class="role-pill"><?php echo esc_html( $role ); ?></span>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    <?php
    return ob_get_clean();
}
add_shortcode( 'wos_tier_list', 'wos_shortcode_tier_list' );
