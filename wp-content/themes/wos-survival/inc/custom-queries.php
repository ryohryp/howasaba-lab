<?php
/**
 * Custom Queries and Logic
 *
 * @package WOS_Survival
 */

/**
 * Get active gift codes query.
 *
 * @return WP_Query
 */
function wos_get_active_gift_codes() {
    $today = current_time( 'Y-m-d' );

    $args = array(
        'post_type'      => 'gift_code',
        'posts_per_page' => -1,
        'meta_query'     => array(
            'relation' => 'OR',
            array(
                'key'     => '_wos_expiration_date',
                'value'   => $today,
                'compare' => '>=',
                'type'    => 'DATE',
            ),
            array(
                'key'     => '_wos_expiration_date',
                'value'   => '',
                'compare' => '=', // Treat empty expiration as "never expires" or handle separately
            ),
             array(
                'key'     => '_wos_expiration_date',
                'compare' => 'NOT EXISTS',
            ),
        ),
        'orderby'        => 'date',
        'order'          => 'DESC',
    );

    return new WP_Query( $args );
}

/**
 * Check if a gift code is expired.
 *
 * @param int $post_id
 * @return boolean
 */
function wos_is_gift_code_expired( $post_id ) {
    $expiration = get_post_meta( $post_id, '_wos_expiration_date', true );
    
    if ( empty( $expiration ) ) {
        return false; // No expiration date set
    }

    $today = current_time( 'Y-m-d' );
    
    return $expiration < $today;
}
