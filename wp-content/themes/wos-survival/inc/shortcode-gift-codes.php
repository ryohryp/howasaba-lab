<?php
/**
 * Shortcode for displaying Gift Codes
 *
 * Data Source: Supabase (primary) → WordPress CPT (fallback)
 *
 * @package WOS_Survival
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register Shortcode
 */
function wos_register_gift_code_shortcode() {
	add_shortcode( 'wos_gift_codes', 'wos_gift_code_list_callback' );
}
add_action( 'init', 'wos_register_gift_code_shortcode' );

/**
 * Shortcode Callback
 */
function wos_gift_code_list_callback( $atts ) {
	$atts = shortcode_atts( array(
		'limit' => 20,
	), $atts, 'wos_gift_codes' );

	// Enqueue styles and scripts ONLY when shortcode is used
	wp_enqueue_style( 'wos-gift-code-list', get_template_directory_uri() . '/assets/css/gift-code-list.css', array(), WOS_THEME_VERSION );
	wp_enqueue_script( 'wos-gift-code-list-js', get_template_directory_uri() . '/assets/js/gift-code-list.js', array(), WOS_THEME_VERSION, true );

	// Try Supabase first
	$codes = wos_get_gift_codes_from_supabase( intval( $atts['limit'] ) );

	// Fallback to WordPress CPT
	if ( $codes === null ) {
		return wos_gift_code_list_wp_fallback( $atts );
	}

	if ( empty( $codes ) ) {
		return '<p class="wos-no-codes">現在、有効なギフトコードは検知されていません。</p>';
	}

	ob_start();
	?>
	<div class="wos-gift-code-list">
		<?php foreach ( $codes as $code_data ) :
			$code       = $code_data['code'] ?? '';
			$rewards    = $code_data['rewards'] ?? '';
			$expiration = $code_data['expiration_date'] ?? '';
			$created_at = $code_data['created_at'] ?? '';

			if ( empty( $code ) ) continue;

			// Check "New" (within 24 hours)
			$is_new = false;
			if ( $created_at ) {
				$created_time = strtotime( $created_at );
				$is_new = $created_time && ( time() - $created_time ) < 24 * 60 * 60;
			}

			// Check "Expired"
			$is_expired = false;
			if ( $expiration ) {
				$expiry_time = strtotime( $expiration );
				$is_expired = $expiry_time && $expiry_time < time();
			}

			$card_classes = array( 'wos-gift-card' );
			if ( $is_new && ! $is_expired ) $card_classes[] = 'new-flare';
			if ( $is_expired ) $card_classes[] = 'frozen-code';
			?>
			<div class="<?php echo esc_attr( implode( ' ', $card_classes ) ); ?>">
				<div class="wos-gift-code-row">
					<span class="wos-code-string"><?php echo esc_html( $code ); ?></span>
					<?php if ( ! $is_expired ) : ?>
						<button class="wos-copy-btn" data-code="<?php echo esc_attr( $code ); ?>">COPY</button>
					<?php endif; ?>
				</div>

				<div class="wos-gift-meta">
					<?php if ( ! empty( $expiration ) ) : ?>
						<div class="wos-meta-row">
							<span class="wos-meta-label">EXP:</span>
							<span><?php echo esc_html( $expiration ); ?></span>
						</div>
					<?php endif; ?>
					
					<?php if ( ! empty( $rewards ) ) : ?>
						<div class="wos-meta-row" style="display:block;">
							<div class="wos-meta-label">REWARDS:</div>
							<div style="font-size: 0.85rem; opacity: 0.9; margin-top:2px;">
								<?php echo nl2br( esc_html( $rewards ) ); ?>
							</div>
						</div>
					<?php endif; ?>

                    <div style="margin-top: 0.5rem; font-size: 0.7rem; opacity: 0.5; text-align: right;">
                        <?php
						if ( $created_at ) {
							echo esc_html( date( 'Y-m-d H:i', strtotime( $created_at ) ) );
						}
						?>
                    </div>
				</div>
			</div>
		<?php endforeach; ?>
	</div>
	<?php
	return ob_get_clean();
}

/**
 * Fetch gift codes from Supabase.
 *
 * @param int $limit Max codes to return.
 * @return array|null Array of codes, or null on connection failure (triggers WP fallback).
 */
function wos_get_gift_codes_from_supabase( int $limit = 20 ): ?array {
	$supabase = new Supabase_Client();

	if ( ! $supabase->is_configured() ) {
		return null;
	}

	$result = $supabase->get( 'gift_codes', [
		'select'    => 'code,rewards,expiration_date,created_at',
		'is_active' => 'eq.true',
		'order'     => 'created_at.desc',
		'limit'     => $limit,
	] );

	if ( is_wp_error( $result ) ) {
		return null; // Trigger WP fallback
	}

	return $result;
}

/**
 * WordPress CPT Fallback (original logic)
 */
function wos_gift_code_list_wp_fallback( $atts ) {
	$args = array(
		'post_type'      => 'gift_code',
		'posts_per_page' => intval( $atts['limit'] ),
		'post_status'    => 'publish',
		'orderby'        => 'date',
		'order'          => 'DESC',
	);

	$query = new WP_Query( $args );

	if ( ! $query->have_posts() ) {
		return '<p class="wos-no-codes">現在、有効なギフトコードは検知されていません。</p>';
	}

	ob_start();
	?>
	<div class="wos-gift-code-list">
		<?php
		while ( $query->have_posts() ) :
			$query->the_post();
			$post_id = get_the_ID();

			$code = get_post_meta( $post_id, '_wos_code_string', true );
            if ( empty( $code ) ) $code = get_post_meta( $post_id, 'code_string', true );

			$rewards = get_post_meta( $post_id, '_wos_rewards', true );
            if ( empty( $rewards ) ) $rewards = get_post_meta( $post_id, 'rewards', true );

			$expiration = get_post_meta( $post_id, '_wos_expiration_date', true );
            if ( empty( $expiration ) ) $expiration = get_post_meta( $post_id, 'expiration_date', true );

            if ( empty( $code ) ) continue;

			$is_new = ( time() - get_the_date( 'U' ) ) < 24 * 60 * 60;
			$is_expired = ! empty( $expiration ) && strtotime( $expiration ) && strtotime( $expiration ) < time();

			$card_classes = array( 'wos-gift-card' );
			if ( $is_new && ! $is_expired ) $card_classes[] = 'new-flare';
			if ( $is_expired ) $card_classes[] = 'frozen-code';
			?>
			<div class="<?php echo esc_attr( implode( ' ', $card_classes ) ); ?>">
				<div class="wos-gift-code-row">
					<span class="wos-code-string"><?php echo esc_html( $code ); ?></span>
					<?php if ( ! $is_expired ) : ?>
						<button class="wos-copy-btn" data-code="<?php echo esc_attr( $code ); ?>">COPY</button>
					<?php endif; ?>
				</div>
				<div class="wos-gift-meta">
					<?php if ( ! empty( $expiration ) ) : ?>
						<div class="wos-meta-row">
							<span class="wos-meta-label">EXP:</span>
							<span><?php echo esc_html( $expiration ); ?></span>
						</div>
					<?php endif; ?>
					<?php if ( ! empty( $rewards ) ) : ?>
						<div class="wos-meta-row" style="display:block;">
							<div class="wos-meta-label">REWARDS:</div>
							<div style="font-size: 0.85rem; opacity: 0.9; margin-top:2px;">
								<?php echo nl2br( esc_html( $rewards ) ); ?>
							</div>
						</div>
					<?php endif; ?>
                    <div style="margin-top: 0.5rem; font-size: 0.7rem; opacity: 0.5; text-align: right;">
                        <?php echo get_the_date( 'Y-m-d H:i' ); ?>
                    </div>
				</div>
			</div>
		<?php
		endwhile;
		wp_reset_postdata();
		?>
	</div>
	<?php
	return ob_get_clean();
}
