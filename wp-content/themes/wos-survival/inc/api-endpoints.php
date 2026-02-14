<?php
/**
 * REST API Endpoints for Gift Codes
 *
 * @package WOS_Survival
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register REST API routes
 */
function wos_register_gift_code_api_routes() {
	register_rest_route( 'wos-radar/v1', '/add-code', array(
		'methods'             => 'POST', // Only POST is allowed for creation
		'callback'            => 'wos_handle_add_gift_code',
		'permission_callback' => function ( WP_REST_Request $request ) {
			$token = $request->get_header( 'x-radar-token' );
			$secret = 'WosRadarSecret2026_Operation!';

			if ( $token === $secret ) {
				return true;
			}

			return new WP_Error( 
				'invalid_token', 
				'レーダートークンが無効、または届いていません。', 
				array( 'status' => 401 ) 
			);
		},
		'args'                => array(
			'code_string'     => array(
				'required'          => true,
				'validate_callback' => function( $param, $request, $key ) {
					return is_string( $param ) && ! empty( $param );
				},
                'sanitize_callback' => 'sanitize_text_field',
			),
			'rewards'         => array(
				'required'          => false,
				'validate_callback' => function( $param, $request, $key ) {
					return is_string( $param );
				},
				'default'           => '',
                'sanitize_callback' => 'sanitize_text_field',
			),
			'expiration_date' => array(
				'required'          => false,
				'validate_callback' => function( $param, $request, $key ) {
					return is_string( $param );
				},
				'default'           => '',
                'sanitize_callback' => 'sanitize_text_field',
			),
            'status'          => array(
                'required'          => false,
                'validate_callback' => function( $param, $request, $key ) {
                    return in_array( $param, array( 'publish', 'draft', 'pending' ), true );
                },
                'default'           => 'publish',
            ),
		),
	) );
}
add_action( 'rest_api_init', 'wos_register_gift_code_api_routes' );

/**
 * Handle POST request to add a gift code
 *
 * @param WP_REST_Request $request The request object.
 * @return WP_REST_Response The response object.
 */
function wos_handle_add_gift_code( WP_REST_Request $request ) {
	$code_string = $request->get_param( 'code_string' );
	$rewards     = $request->get_param( 'rewards' );
	$expiry      = $request->get_param( 'expiration_date' );
    $status      = $request->get_param( 'status' );

	// 1. 重複チェック
    // Check if a post with the same 'code_string' meta value exists
    // We check both '_wos_code_string' (admin) and 'code_string' (api) for safety
	$args = array(
		'post_type'      => 'gift_code',
		'post_status'    => 'any', // Check all statuses
		'posts_per_page' => 1,
		'meta_query'     => array(
            'relation' => 'OR',
			array(
				'key'     => 'code_string',
				'value'   => $code_string,
				'compare' => '=',
			),
            array(
				'key'     => '_wos_code_string',
				'value'   => $code_string,
				'compare' => '=',
			),
		),
	);

	$query = new WP_Query( $args );

	if ( $query->have_posts() ) {
        // Return 200 OK (not error) so GitHub Actions doesn't fail
		return new WP_REST_Response( array(
			'code'    => 'gift_code_exists',
			'message' => '既に登録済みのコードです (Skipped)',
			'data'    => array( 'status' => 200, 'existing_id' => $query->posts[0]->ID ),
		), 200 );
	}

    // Check by title as well for extra safety
    $existing_post_by_title = get_page_by_title( 'ギフトコード: ' . $code_string, OBJECT, 'gift_code' );
    if ( $existing_post_by_title ) {
        return new WP_REST_Response( array(
			'code'    => 'gift_code_exists_title',
			'message' => '既に登録済みのコードです (Skipped by Title)',
			'data'    => array( 'status' => 200, 'existing_id' => $existing_post_by_title->ID ),
		), 200 );
    }

    // Default Expiration: 30 days from now if not provided
    if ( empty( $expiry ) ) {
        $expiry = date( 'Y-m-d', strtotime( '+30 days' ) );
    }

	// 2. Create new post
	$post_title = 'ギフトコード: ' . $code_string;

	$post_data = array(
		'post_title'   => $post_title,
		'post_content' => $rewards, // Optional: put rewards in content
		'post_status'  => $status,
		'post_type'    => 'gift_code',
        'meta_input'   => array(
            // Save both key formats to ensure compatibility with Admin UI and API checks
            'code_string'          => $code_string,
            '_wos_code_string'     => $code_string,
            
            'rewards'              => $rewards,
            '_wos_rewards'         => $rewards,
            
            'expiration_date'      => $expiry,
            '_wos_expiration_date' => $expiry,
            
            '_wos_source'          => 'api', // internal tracking
        ),
	);

	$post_id = wp_insert_post( $post_data );

	if ( is_wp_error( $post_id ) ) {
		return new WP_REST_Response( array(
            'code'    => 'gift_code_create_failed',
			'message' => $post_id->get_error_message(),
            'data'    => array( 'status' => 500 ),
		), 500 );
	}

	return new WP_REST_Response( array(
		'message' => 'ギフトコードが作成されました',
		'post_id' => $post_id,
        'code'    => $code_string,
	), 201 );
}
