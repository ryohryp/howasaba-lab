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
	register_rest_route( 'wos/v1', '/gift-code', array(
		'methods'  => 'POST',
		'callback' => 'wos_handle_gift_code_submission',
		'permission_callback' => function () {
            // アプリケーションパスワード認証等が通っていれば true
            // より厳密には current_user_can('publish_posts') 等をチェック
            // ここでは簡易的に、認証済みユーザーかつ編集権限を持つことを確認
            return current_user_can( 'edit_posts' );
        },
        'args' => array(
            'code' => array(
                'required' => true,
                'validate_callback' => function($param, $request, $key) {
                    return is_string($param) && !empty($param);
                }
            ),
            'rewards' => array(
                'required' => false, // 必須ではないが推奨
                'validate_callback' => function($param, $request, $key) {
                    return is_string($param);
                },
                'default' => ''
            ),
            'expiry' => array(
                'required' => false,
                'validate_callback' => function($param, $request, $key) {
                    // YYYY-MM-DD 形式などを期待するが、文字列として受け取る
                    return is_string($param);
                },
                'default' => ''
            )
        )
	) );
}
add_action( 'rest_api_init', 'wos_register_gift_code_api_routes' );

/**
 * Handle POST request to create a gift code
 *
 * @param WP_REST_Request $request
 * @return WP_REST_Response
 */
function wos_handle_gift_code_submission( WP_REST_Request $request ) {
    $code_string = sanitize_text_field( $request->get_param( 'code' ) );
    $rewards     = sanitize_text_field( $request->get_param( 'rewards' ) ); // 将来的にはリッチテキスト対応を考慮
    $expiry      = sanitize_text_field( $request->get_param( 'expiry' ) );

    // 1. 重複チェック
    // カスタムフィールド 'gift_code_string' で検索して既存のコードがあるか確認
    $args = array(
        'post_type'  => 'gift_code',
        'meta_query' => array(
            array(
                'key'     => '_wos_code_string',
                'value'   => $code_string,
                'compare' => '='
            )
        ),
        'posts_per_page' => 1,
        'post_status'    => 'any' // 公開済み、下書き、ゴミ箱などすべてチェック
    );
    
    $query = new WP_Query( $args );

    if ( $query->have_posts() ) {
        // 既に存在する
        return new WP_REST_Response( array(
            'status'  => 'skipped',
            'message' => 'Gift code already exists.',
            'code'    => $code_string
        ), 200 );
    }

    // 2. 新規投稿の作成
    $post_title = 'ギフトコード: ' . $code_string; // タイトルはシンプルに
    
    // expiryから有効期限の日時を設定（パースが必要な場合はここで行う）
    // とりあえず今回はテキストのまま保存し、別途表示時に処理するか、メタデータとして保存
    
    $post_data = array(
        'post_title'   => $post_title,
        'post_content' => $rewards, // 本文に報酬内容を入れる
        'post_status'  => 'publish', // 即時公開
        'post_type'    => 'gift_code',
        'meta_input'   => array(
            '_wos_code_string' => $code_string,
            '_wos_rewards'     => $rewards,
            '_wos_expiration_date'  => $expiry, // キー名を修正
            '_wos_source'      => 'auto-collector'
        )
    );

    $post_id = wp_insert_post( $post_data );

    if ( is_wp_error( $post_id ) ) {
        return new WP_REST_Response( array(
            'status'  => 'error',
            'message' => $post_id->get_error_message()
        ), 500 );
    }

    return new WP_REST_Response( array(
        'status'  => 'created',
        'message' => 'Gift code created successfully.',
        'post_id' => $post_id,
        'code'    => $code_string
    ), 201 );
}
