<?php
/**
 * Shortcode: [wos_tier_list]
 * Attributes:
 * - gen: int (optional) - Max generation to display.
 */
/**
 * Shortcode: [hero_tier_list] or [wos_tier_list]
 * Attributes:
 * - generation (or gen): int (optional) - Generation number to display (e.g. 6).
 */
function wos_shortcode_tier_list( $atts ) {
    $atts = shortcode_atts( array(
        'generation' => '', // Primary attribute
        'gen'        => '', // Alias/Legacy
    ), $atts, 'hero_tier_list' );

    // Normalize generation
    $generation_num = ! empty( $atts['generation'] ) ? $atts['generation'] : $atts['gen'];

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
    if ( ! empty( $generation_num ) ) {
        // Assuming 'generation' is stored as a number/string in post_meta by ACF
        // We handle both meta key 'generation' and potentially 'hero_generation' taxonomy if complex,
        // but let's stick to meta per requirements.
        $args['meta_query'][] = array(
            'key'     => 'generation',
            'value'   => intval( $generation_num ),
            'compare' => '=', // Strict equality for specific generation
            'type'    => 'NUMERIC',
        );
    }

    $query = new WP_Query( $args );
    
    if ( ! $query->have_posts() ) {
        // Fallback or empty message
        return '<div class="wos-tier-list-empty"><p>No heroes found for Gen ' . esc_html( $generation_num ) . '.</p></div>';
    }

    // Grouping & Sorting Data
    $heroes_by_tier = array(
        'S+' => [],
        'S'  => [],
        'A'  => [],
        'B'  => [],
        'C'  => [], 
    );

    // Troop Type Priority: Shield=1, Spear=2, Bow=3
    // Map internal types (Infantry, Lancer, Marksman)
    $troop_priority = array(
        'Infantry' => 1, // Shield
        'Lancer'   => 2, // Spear
        'Marksman' => 3, // Bow
    );

    while ( $query->have_posts() ) {
        $query->the_post();
        $hero_id = get_the_ID();
        
        // Retrieve ACF fields
        $tier = function_exists('get_field') ? get_field( 'overall_tier', $hero_id ) : get_post_meta($hero_id, 'overall_tier', true);
        $gen  = function_exists('get_field') ? get_field( 'generation', $hero_id ) : get_post_meta($hero_id, 'generation', true);
        $type = function_exists('get_field') ? get_field( 'troop_type', $hero_id ) : get_post_meta($hero_id, 'troop_type', true);
        $jp_name = function_exists('get_field') ? get_field( 'japanese_name', $hero_id ) : get_post_meta($hero_id, 'japanese_name', true);
        $roles = function_exists('get_field') ? get_field( 'special_role', $hero_id ) : get_post_meta($hero_id, 'special_role', true);
        $roles = is_array($roles) ? $roles : [];
        // Handle serialized if raw meta
        if ( is_string($roles) && is_serialized($roles) ) {
            $roles = unserialize($roles);
        }

        // Normalize Tier Key
        $tier_key = strtoupper( trim( $tier ) );
        if ( ! array_key_exists( $tier_key, $heroes_by_tier ) ) {
            // Check for minor variations? e.g. "S PLUS" -> "S+"
            // For now, simple mapping
             if ( $tier_key === 'S PLUS' ) $tier_key = 'S+';
             else continue; // Skip unknown tiers
        }

        $hero_data = array(
            'id'    => $hero_id,
            'name'  => get_the_title(),
            'jp'    => $jp_name,
            'thumb' => get_the_post_thumbnail_url( $hero_id, 'thumbnail' ),
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
            $tier_clean = strtolower( str_replace( '+', '-plus', $tier_name ) );
            $row_class = 'tier-row-' . $tier_clean;
            
            // Add 'fire-crystal-glow' for S+
            if ( $tier_name === 'S+' ) {
                $row_class .= ' fire-crystal-glow';
            }
            ?>
            <div class="wos-tier-row <?php echo esc_attr( $row_class ); ?>">
                <div class="wos-tier-label">
                    <span class="tier-text"><?php echo esc_html( $tier_name ); ?></span>
                </div>
                <div class="wos-tier-heroes">
                    <?php foreach ( $group_heroes as $hero ) : 
                        $type_class = 'type-' . strtolower( $hero['type'] );
                        ?>
                        <div class="wos-hero-card-wrapper">
                            <a href="<?php echo esc_url( $hero['link'] ); ?>" class="wos-hero-card <?php echo esc_attr( $type_class ); ?>">
                                <div class="hero-card-inner">
                                    <div class="hero-image-wrapper">
                                        <?php if ( $hero['thumb'] ) : ?>
                                            <img src="<?php echo esc_url( $hero['thumb'] ); ?>" alt="<?php echo esc_attr( $hero['name'] ); ?>" class="hero-thumb">
                                        <?php else : ?>
                                            <div class="hero-thumb-placeholder"></div>
                                        <?php endif; ?>
                                        <div class="hero-badges">
                                            <span class="hero-gen-badge">G<?php echo esc_html( $hero['gen'] ); ?></span>
                                        </div>
                                        <span class="hero-type-icon icon-<?php echo esc_attr( strtolower( $hero['type'] ) ); ?>"></span>
                                    </div>
                                    <div class="hero-info">
                                        <span class="hero-name"><?php echo esc_html( $hero['name'] ); ?></span>
                                        <?php if ( ! empty( $hero['jp'] ) ) : ?>
                                            <span class="hero-name-jp"><?php echo esc_html( $hero['jp'] ); ?></span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </a>
                            <?php if ( ! empty( $hero['roles'] ) ) : ?>
                                <div class="hero-roles-below">
                                    <?php foreach ( $hero['roles'] as $role ) : ?>
                                        <span class="role-pill-tiny"><?php echo esc_html( $role ); ?></span>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    <?php
    return ob_get_clean();
}
add_shortcode( 'wos_tier_list', 'wos_shortcode_tier_list' );
add_shortcode( 'hero_tier_list', 'wos_shortcode_tier_list' );
