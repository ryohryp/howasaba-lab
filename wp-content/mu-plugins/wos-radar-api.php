<?php
/**
 * Plugin Name: WOS Radar API (Force Load)
 * Description: Forces registration of the Gift Code API endpoint via mu-plugins to bypass theme issues.
 * Version: 1.0.0
 * Author: WOS Survival
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register REST API routes (Force Mode)
 */
function wos_mu_register_gift_code_api() {
    register_rest_route( 'wos-radar/v1', '/add-code', array(
        'methods'             => WP_REST_Server::ALL, // GET & POST
        'callback'            => 'wos_mu_handle_gift_code_debug',
        'permission_callback' => '__return_true',     // Public access for debugging
    ) );
}
add_action( 'rest_api_init', 'wos_mu_register_gift_code_api' );

/**
 * Debug Callback
 */
function wos_mu_handle_gift_code_debug( WP_REST_Request $request ) {
    return new WP_REST_Response( array( 
        'message' => '大雪原レーダー、mu-plugins経由で強制起動完了！',
        'method'  => $request->get_method(),
        'source'  => 'mu-plugins/wos-radar-api.php'
    ), 200 );
}
