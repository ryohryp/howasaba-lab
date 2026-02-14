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
		'methods'             => array( 'GET', 'POST' ), // Allow GET for debugging
		'callback'            => 'wos_handle_add_gift_code',
		'permission_callback' => function ( WP_REST_Request $request ) {
            // Debug: Allow public access for GET requests
            if ( 'GET' === $request->get_method() ) {
                return true;
            }

            // Standard auth check for POST
			return current_user_can( 'edit_posts' );
		},
		'args'                => array(
			'code_string'     => array(
				// POSTの場合のみ必須にするための条件分岐が必要だが、
                // argsのvalidate_callbackはメソッドに関わらず走る可能性があるため、
                // GET時は検証をスキップするか、必須を外してcallback内でチェックする。
                // 簡略化のため、ここでは required => false にし、callback内でPOST時の必須チェックを行う。
				'required'          => false,
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
 * Handle request to add a gift code (POST) or check status (GET)
 *
 * @param WP_REST_Request $request The request object.
 * @return WP_REST_Response The response object.
 */
function wos_handle_add_gift_code( WP_REST_Request $request ) {
    // 1. GETリクエストの場合 (デバッグ用)
    if ( 'GET' === $request->get_method() ) {
        return new WP_REST_Response( array(
            'message' => '大雪原レーダー受信機は正常に稼働しています',
            'status'  => 'active'
        ), 200 );
    }

    // 2. POSTリクエストの場合 (コード登録)
    
    // パラメータ取得
	$code_string = $request->get_param( 'code_string' );
	$rewards     = $request->get_param( 'rewards' );
	$expiry      = $request->get_param( 'expiration_date' );
    $status      = $request->get_param( 'status' );

    // マニュアルで必須チェック (argsでrequired=falseにしたため)
    if ( empty( $code_string ) ) {
        return new WP_REST_Response( array( 
            'code' => 'missing_param', 
            'message' => 'code_string parameter is required.' 
        ), 400 );
    }

	// 重複チェック
	$args = array(
		'post_type'      => 'gift_code',
		'post_status'    => 'any',
		'posts_per_page' => 1,
		'meta_query'     => array(
			array(
				'key'     => 'code_string',
				'value'   => $code_string,
				'compare' => '=',
			),
		),
	);
    
	$query = new WP_Query( $args );

	if ( $query->have_posts() ) {
		return new WP_REST_Response( array(
			'code'    => 'gift_code_exists',
			'message' => '既に登録済みのコードです',
			'data'    => array( 'status' => 409, 'existing_id' => $query->posts[0]->ID ),
		), 409 );
	}

	// 新規投稿の作成
	$post_title = 'ギフトコード: ' . $code_string;

	$post_data = array(
		'post_title'   => $post_title,
		'post_content' => $rewards,
		'post_status'  => $status,
		'post_type'    => 'gift_code',
        'meta_input'   => array(
            'code_string'     => $code_string,
            'rewards'         => $rewards,
            'expiration_date' => $expiry,
            '_wos_source'     => 'api',
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
