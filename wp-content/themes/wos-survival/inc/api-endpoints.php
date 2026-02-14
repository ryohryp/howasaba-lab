<?php
/**
 * REST API Endpoints for Gift Codes (Debug Mode)
 *
 * @package WOS_Survival
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register REST API routes
 */
function wos_register_gift_code_api_routes_debug() {
    register_rest_route( 'wos-radar/v1', '/add-code', array(
        'methods'             => WP_REST_Server::ALL, // GET, POST, etc.
        'callback'            => 'wos_handle_gift_code_debug',
        'permission_callback' => '__return_true',     // Bypass auth completely
    ) );
}
add_action( 'rest_api_init', 'wos_register_gift_code_api_routes_debug' );

/**
 * Debug Callback
 */
function wos_handle_gift_code_debug( WP_REST_Request $request ) {
    return new WP_REST_Response( array( 'message' => '大雪原レーダー、受信良好！' ), 200 );
}
