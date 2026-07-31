<?php
/**
 * Shortcode: [wos_tier_list] / [hero_tier_list]
 *
 * Data source: Supabase (primary) → WordPress CPT (fallback).
 *
 * Attributes:
 * - generation (or gen): int (optional) - Generation number to display.
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Render the hero tier list.
 *
 * @param array $atts Shortcode attributes.
 * @return string
 */
function wos_shortcode_tier_list( $atts ) {
    $atts = shortcode_atts(
        array(
            'generation' => '',
            'gen'        => '',
        ),
        $atts,
        'hero_tier_list'
    );

    $generation = wos_tier_list_get_generation( $atts );

    wp_enqueue_style( 'wos-tier-list-style' );

    $rows = wos_get_tier_list_from_supabase( $generation );

    if ( null === $rows || is_wp_error( $rows ) ) {
        return wos_tier_list_wp_fallback( $atts );
    }

    $heroes = wos_tier_list_normalize_supabase_rows( $rows );

    if ( empty( $heroes ) ) {
        return wos_tier_list_render_empty( $generation );
    }

    return wos_render_tier_list_html( wos_tier_list_group_heroes( $heroes ) );
}
add_shortcode( 'wos_tier_list', 'wos_shortcode_tier_list' );
add_shortcode( 'hero_tier_list', 'wos_shortcode_tier_list' );

/**
 * Resolve and sanitize the generation attribute.
 *
 * @param array $atts Shortcode attributes.
 * @return int|string Positive generation number, or an empty string.
 */
function wos_tier_list_get_generation( array $atts ) {
    $value      = ! empty( $atts['generation'] ) ? $atts['generation'] : $atts['gen'];
    $generation = absint( $value );

    return $generation > 0 ? $generation : '';
}

/**
 * Fetch tier-list rows from Supabase.
 *
 * The client can return WP_Error, so this function intentionally has no
 * native ?array return type. Keeping the error object lets the shortcode
 * fall back to WordPress without causing a fatal TypeError.
 *
 * @param int|string $generation Generation number, or an empty string.
 * @return array|WP_Error|null
 */
function wos_get_tier_list_from_supabase( $generation ) {
    $supabase = new Supabase_Client();

    if ( ! $supabase->is_configured() ) {
        return null;
    }

    $params = array(
        'select'       => 'id,name,japanese_name,generation,troop_type,tier_overall,special_roles,image_url,slug',
        'order'        => 'generation.asc,name.asc',
        'tier_overall' => 'not.is.null',
    );

    if ( '' !== $generation ) {
        $params['generation'] = 'eq.' . $generation;
    }

    $result = $supabase->get( 'heroes', $params );

    if ( is_wp_error( $result ) ) {
        return $result;
    }

    if ( ! is_array( $result ) ) {
        return new WP_Error(
            'wos_invalid_supabase_tier_list',
            'Supabase returned an invalid tier-list response.'
        );
    }

    return $result;
}

/**
 * Normalize Supabase rows into the shared hero-card shape.
 *
 * @param array $rows Supabase rows.
 * @return array
 */
function wos_tier_list_normalize_supabase_rows( array $rows ): array {
    $heroes = array();

    foreach ( $rows as $row ) {
        if ( ! is_array( $row ) || empty( $row['name'] ) ) {
            continue;
        }

        $name = (string) $row['name'];
        $slug = ! empty( $row['slug'] ) ? (string) $row['slug'] : sanitize_title( $name );
        $hero = wos_tier_list_build_hero(
            array(
                'id'    => isset( $row['id'] ) ? $row['id'] : 0,
                'name'  => $name,
                'jp'    => isset( $row['japanese_name'] ) ? $row['japanese_name'] : '',
                'thumb' => isset( $row['image_url'] ) ? $row['image_url'] : '',
                'gen'   => isset( $row['generation'] ) ? $row['generation'] : 0,
                'type'  => isset( $row['troop_type'] ) ? $row['troop_type'] : '',
                'tier'  => isset( $row['tier_overall'] ) ? $row['tier_overall'] : '',
                'roles' => isset( $row['special_roles'] ) ? $row['special_roles'] : array(),
                'link'  => home_url( '/hero/' . $slug . '/' ),
            )
        );

        if ( null !== $hero ) {
            $heroes[] = $hero;
        }
    }

    return $heroes;
}

/**
 * Render the WordPress CPT fallback.
 *
 * @param array $atts Shortcode attributes.
 * @return string
 */
function wos_tier_list_wp_fallback( $atts ) {
    $generation = wos_tier_list_get_generation( (array) $atts );
    $args       = array(
        'post_type'      => 'wos_hero',
        'posts_per_page' => -1,
        'post_status'    => 'publish',
        'meta_query'     => array(),
    );

    if ( '' !== $generation ) {
        $args['meta_query'][] = array(
            'key'     => 'generation',
            'value'   => $generation,
            'compare' => '=',
            'type'    => 'NUMERIC',
        );
    }

    $query  = new WP_Query( $args );
    $heroes = array();

    while ( $query->have_posts() ) {
        $query->the_post();

        $hero_id = get_the_ID();
        $hero    = wos_tier_list_build_hero(
            array(
                'id'    => $hero_id,
                'name'  => get_the_title( $hero_id ),
                'jp'    => wos_tier_list_get_hero_field( 'japanese_name', $hero_id ),
                'thumb' => get_the_post_thumbnail_url( $hero_id, 'thumbnail' ),
                'gen'   => wos_tier_list_get_hero_field( 'generation', $hero_id ),
                'type'  => wos_tier_list_get_hero_field( 'troop_type', $hero_id ),
                'tier'  => wos_tier_list_get_hero_field( 'overall_tier', $hero_id ),
                'roles' => maybe_unserialize( wos_tier_list_get_hero_field( 'special_role', $hero_id ) ),
                'link'  => get_permalink( $hero_id ),
            )
        );

        if ( null !== $hero ) {
            $heroes[] = $hero;
        }
    }

    wp_reset_postdata();

    if ( empty( $heroes ) ) {
        return wos_tier_list_render_empty( $generation );
    }

    return wos_render_tier_list_html( wos_tier_list_group_heroes( $heroes ) );
}

/**
 * Read a hero field through ACF when available, otherwise post meta.
 *
 * @param string $field_name Field name.
 * @param int    $hero_id    Hero post ID.
 * @return mixed
 */
function wos_tier_list_get_hero_field( string $field_name, int $hero_id ) {
    if ( function_exists( 'get_field' ) ) {
        return get_field( $field_name, $hero_id );
    }

    return get_post_meta( $hero_id, $field_name, true );
}

/**
 * Build and validate the shared hero-card shape.
 *
 * @param array $hero Hero data.
 * @return array|null
 */
function wos_tier_list_build_hero( array $hero ): ?array {
    $tier = wos_tier_list_normalize_tier( isset( $hero['tier'] ) ? $hero['tier'] : '' );
    $name = isset( $hero['name'] ) ? trim( (string) $hero['name'] ) : '';

    if ( '' === $tier || '' === $name ) {
        return null;
    }

    return array(
        'id'    => isset( $hero['id'] ) ? $hero['id'] : 0,
        'name'  => $name,
        'jp'    => isset( $hero['jp'] ) ? trim( (string) $hero['jp'] ) : '',
        'thumb' => isset( $hero['thumb'] ) ? (string) $hero['thumb'] : '',
        'gen'   => isset( $hero['gen'] ) ? absint( $hero['gen'] ) : 0,
        'type'  => wos_tier_list_normalize_type( isset( $hero['type'] ) ? $hero['type'] : '' ),
        'tier'  => $tier,
        'roles' => wos_tier_list_normalize_roles( isset( $hero['roles'] ) ? $hero['roles'] : array() ),
        'link'  => isset( $hero['link'] ) ? (string) $hero['link'] : '',
    );
}

/**
 * Normalize supported tier labels.
 *
 * @param mixed $tier Tier label.
 * @return string
 */
function wos_tier_list_normalize_tier( $tier ): string {
    $tier = strtoupper( trim( (string) $tier ) );
    $tier = str_replace( array( 'Ｓ', '＋' ), array( 'S', '+' ), $tier );

    if ( in_array( $tier, array( 'S PLUS', 'SPLUS', 'S-PLUS' ), true ) ) {
        return 'S+';
    }

    return in_array( $tier, array( 'S+', 'S', 'A', 'B', 'C' ), true ) ? $tier : '';
}

/**
 * Normalize troop types used by sorting and CSS classes.
 *
 * @param mixed $type Troop type.
 * @return string
 */
function wos_tier_list_normalize_type( $type ): string {
    $type  = strtolower( trim( (string) $type ) );
    $types = array(
        'infantry' => 'Infantry',
        'lancer'   => 'Lancer',
        'marksman' => 'Marksman',
    );

    return isset( $types[ $type ] ) ? $types[ $type ] : ucfirst( $type );
}

/**
 * Normalize roles into a flat string array.
 *
 * @param mixed $roles Roles value.
 * @return array
 */
function wos_tier_list_normalize_roles( $roles ): array {
    if ( ! is_array( $roles ) ) {
        return array();
    }

    $normalized = array();

    foreach ( $roles as $role ) {
        if ( is_scalar( $role ) && '' !== trim( (string) $role ) ) {
            $normalized[] = trim( (string) $role );
        }
    }

    return $normalized;
}

/**
 * Group heroes by tier and apply display order.
 *
 * @param array $heroes Normalized heroes.
 * @return array
 */
function wos_tier_list_group_heroes( array $heroes ): array {
    $groups = array(
        'S+' => array(),
        'S'  => array(),
        'A'  => array(),
        'B'  => array(),
        'C'  => array(),
    );

    foreach ( $heroes as $hero ) {
        if ( is_array( $hero ) && isset( $groups[ $hero['tier'] ] ) ) {
            $groups[ $hero['tier'] ][] = $hero;
        }
    }

    foreach ( $groups as &$group ) {
        usort( $group, 'wos_tier_list_compare_heroes' );
    }
    unset( $group );

    return $groups;
}

/**
 * Sort by troop priority, generation descending, then name.
 *
 * @param array $left  First hero.
 * @param array $right Second hero.
 * @return int
 */
function wos_tier_list_compare_heroes( array $left, array $right ): int {
    $priority = array(
        'Infantry' => 1,
        'Lancer'   => 2,
        'Marksman' => 3,
    );
    $left_priority  = isset( $priority[ $left['type'] ] ) ? $priority[ $left['type'] ] : 99;
    $right_priority = isset( $priority[ $right['type'] ] ) ? $priority[ $right['type'] ] : 99;

    if ( $left_priority !== $right_priority ) {
        return $left_priority <=> $right_priority;
    }

    if ( (int) $left['gen'] !== (int) $right['gen'] ) {
        return (int) $right['gen'] <=> (int) $left['gen'];
    }

    return strcasecmp( (string) $left['name'], (string) $right['name'] );
}

/**
 * Render an empty-state message.
 *
 * @param int|string $generation Generation number, or an empty string.
 * @return string
 */
function wos_tier_list_render_empty( $generation ): string {
    $message = '' !== $generation
        ? sprintf( 'No heroes found for Gen %d.', $generation )
        : 'No heroes found.';

    return '<div class="wos-tier-list-empty"><p>' . esc_html( $message ) . '</p></div>';
}

/**
 * Render tier-list HTML shared by Supabase and WordPress data.
 *
 * @param array $heroes_by_tier Heroes grouped by tier.
 * @return string
 */
function wos_render_tier_list_html( array $heroes_by_tier ): string {
    ob_start();
    ?>
    <div class="wos-tier-list-container">
        <?php foreach ( $heroes_by_tier as $tier_name => $group_heroes ) : ?>
            <?php if ( empty( $group_heroes ) ) : ?>
                <?php continue; ?>
            <?php endif; ?>

            <?php
            $tier_clean = sanitize_html_class( strtolower( str_replace( '+', '-plus', $tier_name ) ) );
            $row_class  = 'tier-row-' . $tier_clean;

            if ( 'S+' === $tier_name ) {
                $row_class .= ' fire-crystal-glow';
            }
            ?>
            <div class="wos-tier-row <?php echo esc_attr( $row_class ); ?>">
                <div class="wos-tier-label">
                    <span class="tier-text"><?php echo esc_html( $tier_name ); ?></span>
                </div>
                <div class="wos-tier-heroes">
                    <?php foreach ( $group_heroes as $hero ) : ?>
                        <?php
                        $type_slug = sanitize_html_class( strtolower( $hero['type'] ) );
                        $is_ja     = 'ja' === get_locale();
                        $main_name = $is_ja && '' !== $hero['jp'] ? $hero['jp'] : $hero['name'];
                        $sub_name  = $is_ja ? $hero['name'] : $hero['jp'];
                        ?>
                        <div class="wos-hero-card-wrapper">
                            <a href="<?php echo esc_url( $hero['link'] ); ?>" class="wos-hero-card type-<?php echo esc_attr( $type_slug ); ?>">
                                <div class="hero-card-inner">
                                    <div class="hero-image-wrapper">
                                        <?php if ( '' !== $hero['thumb'] ) : ?>
                                            <img src="<?php echo esc_url( $hero['thumb'] ); ?>" alt="<?php echo esc_attr( $hero['name'] ); ?>" class="hero-thumb" loading="lazy">
                                        <?php else : ?>
                                            <div class="hero-thumb-placeholder"></div>
                                        <?php endif; ?>
                                        <div class="hero-badges">
                                            <span class="hero-gen-badge">G<?php echo esc_html( $hero['gen'] ); ?></span>
                                        </div>
                                        <span class="hero-type-icon icon-<?php echo esc_attr( $type_slug ); ?>"></span>
                                    </div>
                                    <div class="hero-info">
                                        <span class="hero-name"><?php echo esc_html( $main_name ); ?></span>
                                        <?php if ( '' !== $sub_name && $sub_name !== $main_name ) : ?>
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

    return (string) ob_get_clean();
}
