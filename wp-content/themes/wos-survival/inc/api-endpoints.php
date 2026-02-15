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

/**
 * Handle POST request to add a post
 *
 * @param WP_REST_Request $request The request object.
 * @return WP_REST_Response The response object.
 */
function wos_handle_create_post( WP_REST_Request $request ) {
    $title   = $request->get_param( 'title' );
    $content = $request->get_param( 'content' );
    $status  = $request->get_param( 'status' );
    $slug    = $request->get_param( 'slug' );

    // Default status to draft if not provided
    if ( empty( $status ) ) {
        $status = 'draft';
    }

    $post_data = array(
        'post_title'   => $title,
        'post_content' => $content,
        'post_status'  => $status,
        'post_type'    => 'post',
    );
    
    if ( ! empty( $slug ) ) {
        $post_data['post_name'] = $slug;
    }

    $post_id = wp_insert_post( $post_data );

    if ( is_wp_error( $post_id ) ) {
        return new WP_REST_Response( array(
            'code'    => 'create_failed',
            'message' => $post_id->get_error_message(),
            'data'    => array( 'status' => 500 ),
        ), 500 );
    }

    return new WP_REST_Response( array(
        'message' => '記事が作成されました',
        'post_id' => $post_id,
        'title'   => $title,
    ), 201 );
}

/**
 * Register REST API routes for post updates
 */
function wos_register_post_update_routes() {
    register_rest_route( 'wos-radar/v1', '/create-post', array(
        'methods'             => 'POST',
        'callback'            => 'wos_handle_create_post',
        'permission_callback' => function ( WP_REST_Request $request ) {
            $token = $request->get_header( 'x-radar-token' );
            $secret = 'WosRadarSecret2026_Operation!';

            if ( $token === $secret ) {
                return true;
            }

            return new WP_Error( 
                'invalid_token', 
                'Auth Token Invalid', 
                array( 'status' => 401 ) 
            );
        },
        'args'                => array(
            'title'           => array(
                'required'          => true,
                'validate_callback' => function( $param, $request, $key ) {
                    return is_string( $param ) && ! empty( $param );
                },
                'sanitize_callback' => 'sanitize_text_field',
            ),
            'content'         => array(
                'required'          => true,
                'validate_callback' => function( $param, $request, $key ) {
                    return is_string( $param );
                },
            ),
            'status'          => array(
                'required'          => false,
                'validate_callback' => function( $param, $request, $key ) {
                    return in_array( $param, array( 'publish', 'draft', 'pending' ), true );
                },
                'default'           => 'draft',
            ),
            'slug'            => array(
                'required'          => false,
                'sanitize_callback' => 'sanitize_title',
            ),
        ),
    ) );

    register_rest_route( 'wos-radar/v1', '/update-post', array(
        'methods'             => 'POST',
        'callback'            => 'wos_handle_update_post',
        'permission_callback' => function ( WP_REST_Request $request ) {
            $token = $request->get_header( 'x-radar-token' );
            // TODO: Move secret to wp-config.php or options for better security
            $secret = 'WosRadarSecret2026_Operation!'; 

            if ( $token === $secret ) {
                return true;
            }

            return new WP_Error( 
                'invalid_token', 
                'Auth Token Invalid', 
                array( 'status' => 401 ) 
            );
        },
        'args'                => array(
            'slug'            => array(
                'required'          => true,
                'validate_callback' => function( $param, $request, $key ) {
                    return is_string( $param ) && ! empty( $param );
                },
                'sanitize_callback' => 'sanitize_title',
            ),
            'content'         => array(
                'required'          => false, // Content might not always change
                'validate_callback' => function( $param, $request, $key ) {
                    return is_string( $param );
                },
                // Intentionally NOT using sanitize_text_field to allow HTML tags
                // We rely on the authentication header for security here
            ),
            'title'           => array(
                'required'          => false,
                'validate_callback' => function( $param, $request, $key ) {
                    return is_string( $param );
                },
                'sanitize_callback' => 'sanitize_text_field',
            ),
             'meta'            => array(
                'required'          => false,
                'validate_callback' => function( $param, $request, $key ) {
                    return is_array( $param );
                },
            ),
        ),
    ) );
}
add_action( 'rest_api_init', 'wos_register_post_update_routes' );

/**
 * Handle POST request to update a post
 *
 * @param WP_REST_Request $request The request object.
 * @return WP_REST_Response The response object.
 */
function wos_handle_update_post( WP_REST_Request $request ) {
    $slug    = $request->get_param( 'slug' );
    $content = $request->get_param( 'content' );
    $title   = $request->get_param( 'title' );
    $meta    = $request->get_param( 'meta' );

    // 1. Find the post by slug
    $args = array(
        'name'        => $slug,
        'post_type'   => 'post', // Adjust if custom post type needed
        'post_status' => 'any',
        'numberposts' => 1,
    );
    
    // Check custom post types if needed, e.g. wos_hero
    // For now assuming standard 'post' or we can make post_type a parameter
    $posts = get_posts( $args );
    
    if ( ! $posts ) {
         // Try checking pages or custom post types if not found in posts
         $args['post_type'] = array('post', 'page', 'wos_hero', 'wos_event');
         $posts = get_posts( $args );
    }

    if ( ! $posts ) {
        return new WP_REST_Response( array(
            'code'    => 'post_not_found',
            'message' => '指定されたSlugの記事が見つかりません: ' . $slug,
            'data'    => array( 'status' => 404 ),
        ), 404 );
    }

    $post_id = $posts[0]->ID;
    $post_data = array(
        'ID' => $post_id,
    );

    if ( ! empty( $content ) ) {
        // Allow specific HTML tags for our theme
        // Ideally we trust the authenticated source, but let's be careful or just raw update
        // Since we are authenticated with a secret token, we can assume it's safe to update content directly
        $post_data['post_content'] = $content;
    }

    if ( ! empty( $title ) ) {
        $post_data['post_title'] = $title;
    }

    // Update the post
    $updated_post_id = wp_update_post( $post_data, true );

    if ( is_wp_error( $updated_post_id ) ) {
        return new WP_REST_Response( array(
            'code'    => 'update_failed',
            'message' => $updated_post_id->get_error_message(),
            'data'    => array( 'status' => 500 ),
        ), 500 );
    }

    // Update Meta Data
    if ( ! empty( $meta ) && is_array( $meta ) ) {
        foreach ( $meta as $key => $value ) {
            update_post_meta( $post_id, $key, $value );
        }
    }

    return new WP_REST_Response( array(
        'message' => '記事が更新されました',
        'post_id' => $post_id,
        'slug'    => $slug,
    ), 200 );
}
