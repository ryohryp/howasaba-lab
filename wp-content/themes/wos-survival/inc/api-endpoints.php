<?php
/**
 * REST API Endpoints for Gift Codes
 *
 * @package WOS_Survival
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Register REST API routes
 */
function wos_register_gift_code_api_routes() {
	register_rest_route( 'wos-radar/v1', '/add-code', array(
		'methods'             => 'POST',
		'callback'            => 'wos_handle_add_gift_code',
		'permission_callback' => function () {
            // WordPress Application Passwords authentication is handled by core.
            // If the request is authenticated via App Password, current_user_can() will work for that user.
			return current_user_can( 'edit_posts' );
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
                'sanitize_callback' => 'sanitize_text_field', // Use wp_kses_post if rich text is needed, currently text field for safety
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
	$args = array(
		'post_type'      => 'gift_code',
		'post_status'    => 'any', // Check all statuses
		'posts_per_page' => 1,
		'meta_query'     => array(
			array(
				'key'     => 'code_string', // Checking custom field directly
				'value'   => $code_string,
				'compare' => '=',
			),
		),
	);

    // Fallback: Also check if title matches exactly, just in case
    // Note: This is an OR condition logic if implemented separately, but here we prioritize Meta Query
    // as titles might change but the code string is the unique identifier.
    
	$query = new WP_Query( $args );

	if ( $query->have_posts() ) {
		return new WP_REST_Response( array(
			'code'    => 'gift_code_exists',
			'message' => '既に登録済みのコードです',
			'data'    => array( 'status' => 409, 'existing_id' => $query->posts[0]->ID ),
		), 409 );
	}

	// 2. Create new post
    // Titles are important for list tables in Admin, so we use the code string
	$post_title = 'ギフトコード: ' . $code_string;

	$post_data = array(
		'post_title'   => $post_title,
		'post_content' => $rewards, // Optional: put rewards in content or just meta
		'post_status'  => $status,
		'post_type'    => 'gift_code',
        'meta_input'   => array(
            'code_string'     => $code_string,
            'rewards'         => $rewards,
            'expiration_date' => $expiry,
            '_wos_source'     => 'api', // internal tracking
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
