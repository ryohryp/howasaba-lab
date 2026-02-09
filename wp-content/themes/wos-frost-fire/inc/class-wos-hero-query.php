<?php
/**
 * WoS Hero Query Class
 *
 * Wrapper for WP_Query specifically for heroes.
 */
class WoS_Hero_Query {

    /**
     * Get heroes based on arguments.
     *
     * @param array $args Optional. Arguments to pass to WP_Query.
     * @return WP_Query result.
     */
    public static function get_heroes( array $args = [] ): WP_Query {
        $defaults = [
            'post_type'      => 'wos_hero',
            'posts_per_page' => -1,
            'orderby'        => 'title',
            'order'          => 'ASC',
        ];

        $query_args = wp_parse_args( $args, $defaults );

        return new WP_Query( $query_args );
    }

    /**
     * Get heroes by generation.
     *
     * @param string|array $generation Slug(s) of the generation.
     * @param int $limit Number of heroes to return.
     * @return WP_Query
     */
    public static function get_by_generation( $generation, int $limit = -1 ): WP_Query {
        $args = [
            'tax_query' => [
                [
                    'taxonomy' => 'hero_generation',
                    'field'    => 'slug',
                    'terms'    => $generation,
                ],
            ],
            'posts_per_page' => $limit,
        ];

        return self::get_heroes( $args );
    }

     /**
     * Get heroes by type (Infantry, Lancer, Marksman).
     *
     * @param string|array $type Slug(s) of the hero type.
     * @param int $limit Number of heroes to return.
     * @return WP_Query
     */
    public static function get_by_type( $type, int $limit = -1 ): WP_Query {
        $args = [
            'tax_query' => [
                [
                    'taxonomy' => 'hero_type',
                    'field'    => 'slug',
                    'terms'    => $type,
                ],
            ],
            'posts_per_page' => $limit,
        ];

        return self::get_heroes( $args );
    }
}
