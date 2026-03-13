<?php
/**
 * Supabase REST API Client for WordPress
 *
 * Supabase の PostgREST API を呼び出すヘルパークラス。
 * wp-config.php に以下の定数を定義してください：
 *   define( 'SUPABASE_URL', 'https://xxx.supabase.co' );
 *   define( 'SUPABASE_ANON_KEY', 'eyJ...' );
 *
 * @package WoS_Frost_Fire
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Supabase_Client {

    /**
     * @var string Supabase project URL
     */
    private string $url;

    /**
     * @var string Supabase anon/publishable key
     */
    private string $key;

    /**
     * @var int Cache duration in seconds (default: 5 minutes)
     */
    private int $cache_ttl;

    /**
     * Constructor.
     *
     * @param int $cache_ttl Cache TTL in seconds.
     */
    public function __construct( int $cache_ttl = 300 ) {
        $this->url       = defined( 'SUPABASE_URL' ) ? SUPABASE_URL : '';
        $this->key       = defined( 'SUPABASE_ANON_KEY' ) ? SUPABASE_ANON_KEY : '';
        $this->cache_ttl = $cache_ttl;
    }

    /**
     * Check if the client is configured.
     */
    public function is_configured(): bool {
        return ! empty( $this->url ) && ! empty( $this->key );
    }

    /**
     * Make a GET request to Supabase PostgREST.
     *
     * @param string $table   Table name.
     * @param array  $params  Query parameters (PostgREST syntax).
     *                        e.g. ['select' => '*', 'order' => 'generation.asc', 'troop_type' => 'eq.Infantry']
     * @return array|WP_Error Decoded JSON array or WP_Error.
     */
    public function get( string $table, array $params = [] ) {
        if ( ! $this->is_configured() ) {
            return new WP_Error( 'supabase_not_configured', 'Supabase URL or key is not defined.' );
        }

        // Build cache key
        $cache_key = 'sb_' . md5( $table . serialize( $params ) );
        $cached    = get_transient( $cache_key );

        if ( false !== $cached ) {
            return $cached;
        }

        // Build URL
        $endpoint = trailingslashit( $this->url ) . 'rest/v1/' . $table;

        if ( ! empty( $params ) ) {
            $endpoint = add_query_arg( $params, $endpoint );
        }

        $response = wp_remote_get( $endpoint, [
            'headers' => [
                'apikey'        => $this->key,
                'Authorization' => 'Bearer ' . $this->key,
                'Accept'        => 'application/json',
            ],
            'timeout' => 10,
        ] );

        if ( is_wp_error( $response ) ) {
            return $response;
        }

        $status = wp_remote_retrieve_response_code( $response );
        $body   = wp_remote_retrieve_body( $response );
        $data   = json_decode( $body, true );

        if ( $status !== 200 ) {
            return new WP_Error(
                'supabase_api_error',
                'Supabase API returned status ' . $status,
                $data
            );
        }

        // Cache the result
        set_transient( $cache_key, $data, $this->cache_ttl );

        return $data;
    }

    /**
     * Get all heroes, optionally filtered.
     *
     * @param array $filters PostgREST filters. e.g. ['generation' => 'eq.6']
     * @param string $order  Order string. e.g. 'generation.asc,name.asc'
     * @return array|WP_Error
     */
    public function get_heroes( array $filters = [], string $order = 'generation.asc,name.asc' ) {
        $params = array_merge(
            [ 'select' => '*', 'order' => $order ],
            $filters
        );
        return $this->get( 'heroes', $params );
    }

    /**
     * Get a single hero by name.
     *
     * @param string $name Hero name (English).
     * @return array|null|WP_Error
     */
    public function get_hero_by_name( string $name ) {
        $result = $this->get( 'heroes', [
            'select' => '*',
            'name'   => 'eq.' . $name,
        ] );

        if ( is_wp_error( $result ) ) {
            return $result;
        }

        return ! empty( $result ) ? $result[0] : null;
    }

    /**
     * Get a single hero by slug.
     *
     * @param string $slug Hero slug.
     * @return array|null|WP_Error
     */
    public function get_hero_by_slug( string $slug ) {
        $result = $this->get( 'heroes', [
            'select' => '*',
            'slug'   => 'eq.' . $slug,
        ] );

        if ( is_wp_error( $result ) ) {
            return $result;
        }

        return ! empty( $result ) ? $result[0] : null;
    }

    /**
     * Get active gift codes.
     *
     * @return array|WP_Error
     */
    public function get_gift_codes() {
        return $this->get( 'gift_codes', [
            'select'    => '*',
            'is_active' => 'eq.true',
            'order'     => 'created_at.desc',
        ] );
    }

    /**
     * Get events, optionally filtered by date.
     *
     * @param string|null $after Only return events starting after this date (YYYY-MM-DD).
     * @return array|WP_Error
     */
    public function get_events( ?string $after = null ) {
        $params = [
            'select' => '*',
            'order'  => 'start_date.desc',
        ];

        if ( $after ) {
            $params['start_date'] = 'gte.' . $after;
        }

        return $this->get( 'events', $params );
    }

    /**
     * Clear all Supabase transient caches.
     */
    public static function flush_cache() {
        global $wpdb;
        $wpdb->query(
            "DELETE FROM {$wpdb->options} WHERE option_name LIKE '_transient_sb_%' OR option_name LIKE '_transient_timeout_sb_%'"
        );
    }
}
