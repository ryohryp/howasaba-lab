<?php
/**
 * Shortcode: [wos_tier_list] / [hero_tier_list]
 *
 * Data Source: Supabase (primary) → WordPress CPT (fallback)
 *
 * Attributes:
 * - generation (or gen): int (optional) - Generation number to display.
 */
function wos_shortcode_tier_list( $atts ) {
    $atts = shortcode_atts( array(
        'generation' => '',
        'gen'        => '',
    ), $atts, 'hero_tier_list' );

    $generation_num = ! empty( $atts['generation'] ) ? $atts['generation'] : $atts['gen'];

    // Enqueue styles
    wp_enqueue_style( 'wos-tier-list-style' );

    // Try Supabase first
    $heroes = wos_get_tier_list_from_supabase( $generation_num );

    // Fallback to WordPress
    if ( is_wp_error( $heroes ) || $heroes === null ) {
        return wos_tier_list_wp_fallback( $atts );
    }

    if ( empty( $heroes ) ) {
        return '<div class="wos-tier-list-empty"><p>No heroes found for Gen ' . esc_html( $generation_num ) . '.</p></div>';
    }

    // Group by tier
    $heroes_by_tier = array(
        'S+' => [],
        'S'  => [],
        'A'  => [],
        'B'  => [],
        'C'  => [],
    );

    $troop_priority = array(
        'Infantry' => 1,
        'Lancer'   => 2,
        'Marksman' => 3,
    );

    foreach ( $heroes as $hero ) {
        $tier_key = strtoupper( trim( $hero['tier_overall'] ?? '' ) );
        if ( $tier_key === 'S PLUS' ) $tier_key = 'S+';
        if ( ! array_key_exists( $tier_key, $heroes_by_tier ) ) continue;

        $heroes_by_tier[ $tier_key ][] = array(
            'id'    => $hero['id'],
            'name'  => $hero['name'],
            'jp'    => $hero['japanese_name'] ?? '',
            'thumb' => $hero['image_url'] ?? '',
            'gen'   => $hero['generation'],
            'type'  => $hero['troop_type'],
            'roles' => $hero['special_roles'] ?? [],
            'link'  => home_url( '/hero/' . ( $hero['slug'] ?? sanitize_title( $hero['name'] ) ) . '/' ),
        );
    }

    // Sort within each tier: by troop type priority, then by gen descending
    foreach ( $heroes_by_tier as $tier => &$group ) {
        usort( $group, function( $a, $b ) use ( $troop_priority ) {
            $pA = $troop_priority[ $a['type'] ] ?? 99;
            $pB = $troop_priority[ $b['type'] ] ?? 99;
            if ( $pA === $pB ) {
                return $b['gen'] - $a['gen'];
            }
            return $pA - $pB;
        });
    }
    unset( $group );

    // Render HTML (same structure as before)
    return wos_render_tier_list_html( $heroes_by_tier );
}
add_shortcode( 'wos_tier_list', 'wos_shortcode_tier_list' );
add_shortcode( 'hero_tier_list', 'wos_shortcode_tier_list' );

/**
 * Fetch heroes from Supabase for tier list.
 *
 * @param string|int $generation Generation number (optional).
 * @return array|null Heroes array, or null on failure.
 */
function wos_get_tier_list_from_supabase( $generation ): ?array {
    $supabase = new Supabase_Client();

    if ( ! $supabase->is_configured() ) {
        return null;
    }

    $params = [
        'select' => 'id,name,japanese_name,generation,troop_type,tier_overall,special_roles,image_url,slug',
        'order'  => 'generation.asc,name.asc',
        'tier_overall' => 'not.is.null',
    ];

    if ( ! empty( $generation ) ) {
        $params['generation'] = 'eq.' . intval( $generation );
    }

    $result = $supabase->get( 'heroes', $params );

    return $result;
}

/**
 * WordPress CPT Fallback (original logic)
 */
function wos_tier_list_wp_fallback( $atts ) {
    $generation_num = ! empty( $atts['generation'] ) ? $atts['generation'] : $atts['gen'];

    $args = array(
        'post_type'      => 'wos_hero',
        'posts_per_page' => -1,
        'post_status'    => 'publish',
        'meta_query'     => array(),
    );

    if ( ! empty( $generation_num ) ) {
        $args['meta_query'][] = array(
            'key'     => 'generation',
            'value'   => intval( $generation_num ),
            'compare' => '=',
            'type'    => 'NUMERIC',
        );
    }

    $query = new WP_Query( $args );

    if ( ! $query->have_posts() ) {
        return '<div class="wos-tier-list-empty"><p>No heroes found for Gen ' . esc_html( $generation_num ) . '.</p></div>';
    }

    $heroes_by_tier = array(
        'S+' => [], 'S' => [], 'A' => [], 'B' => [], 'C' => [],
    );

    $troop_priority = array(
        'Infantry' => 1, 'Lancer' => 2, 'Marksman' => 3,
    );

    while ( $query->have_posts() ) {
        $query->the_post();
        $hero_id = get_the_ID();

        $tier    = function_exists('get_field') ? get_field( 'overall_tier', $hero_id ) : get_post_meta($hero_id, 'overall_tier', true);
        $gen     = function_exists('get_field') ? get_field( 'generation', $hero_id ) : get_post_meta($hero_id, 'generation', true);
        $type    = function_exists('get_field') ? get_field( 'troop_type', $hero_id ) : get_post_meta($hero_id, 'troop_type', true);
        $jp_name = function_exists('get_field') ? get_field( 'japanese_name', $hero_id ) : get_post_meta($hero_id, 'japanese_name', true);
        $roles   = function_exists('get_field') ? get_field( 'special_role', $hero_id ) : get_post_meta($hero_id, 'special_role', true);
        $roles   = is_array($roles) ? $roles : [];
        if ( is_string($roles) && is_serialized($roles) ) $roles = unserialize($roles);

        $tier_key = strtoupper( trim( $tier ) );
        if ( ! array_key_exists( $tier_key, $heroes_by_tier ) ) {
            if ( $tier_key === 'S PLUS' ) $tier_key = 'S+';
            else continue;
        }

        $heroes_by_tier[$tier_key][] = array(
            'id'    => $hero_id,
            'name'  => get_the_title(),
            'jp'    => $jp_name,
            'thumb' => get_the_post_thumbnail_url( $hero_id, 'thumbnail' ),
            'gen'   => $gen,
            'type'  => $type,
            'roles' => $roles,
            'link'  => get_permalink(),
        );
    }
    wp_reset_postdata();

    foreach ( $heroes_by_tier as $tier => &$heroes ) {
        usort( $heroes, function( $a, $b ) use ( $troop_priority ) {
            $pA = $troop_priority[ $a['type'] ] ?? 99;
            $pB = $troop_priority[ $b['type'] ] ?? 99;
            if ( $pA === $pB ) return $b['gen'] - $a['gen'];
            return $pA - $pB;
        });
    }
    unset($heroes);

    return wos_render_tier_list_html( $heroes_by_tier );
}

/**
 * Render Tier List HTML (shared between Supabase and WP fallback)
 */
function wos_render_tier_list_html( array $heroes_by_tier ): string {
    ob_start();
    ?>
    <div class="wos-tier-list-container">
        <?php foreach ( $heroes_by_tier as $tier_name => $group_heroes ) :
            if ( empty( $group_heroes ) ) continue;

            $tier_clean = strtolower( str_replace( '+', '-plus', $tier_name ) );
            $row_class = 'tier-row-' . $tier_clean;
            if ( $tier_name === 'S+' ) $row_class .= ' fire-crystal-glow';
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
                                        <?php 
                                        $is_ja = get_locale() === 'ja';
                                        $main_name = ( $is_ja && ! empty( $hero['jp'] ) ) ? $hero['jp'] : $hero['name'];
                                        $sub_name  = ( $is_ja ) ? $hero['name'] : $hero['jp'];
                                        ?>
                                        <span class="hero-name"><?php echo esc_html( $main_name ); ?></span>
                                        <?php if ( ! empty( $sub_name ) && $sub_name !== $main_name ) : ?>
                                            <span class="hero-name-jp"><?php echo esc_html( $sub_name ); ?></span>
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
