<?php
/**
 * Shortcode for displaying Gift Codes
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
		'limit' => 10,
	), $atts, 'wos_gift_codes' );

	// Enqueue styles and scripts ONLY when shortcode is used
	wp_enqueue_style( 'wos-gift-code-list', get_template_directory_uri() . '/assets/css/gift-code-list.css', array(), WOS_THEME_VERSION );
	wp_enqueue_script( 'wos-gift-code-list-js', get_template_directory_uri() . '/assets/js/gift-code-list.js', array(), WOS_THEME_VERSION, true );

	// Query Gift Codes
	$args = array(
		'post_type'      => 'gift_code',
		'posts_per_page' => intval( $atts['limit'] ),
		'post_status'    => 'publish',
		'orderby'        => 'date',
		'order'          => 'DESC',
	);

	$query = new WP_Query( $args );

	if ( ! $query->have_posts() ) {
		return '<p>現在、利用可能なギフトコードはありません。</p>';
	}

	ob_start();
	?>
	<div class="wos-gift-code-list">
		<?php
		while ( $query->have_posts() ) :
			$query->the_post();
			$post_id = get_the_ID();
            
            // Meta Data
            // Support both key formats (API uses 'code_string', manual save uses '_wos_code_string')
            // Priority: _wos_code_string (Manual) -> code_string (API)
			$code = get_post_meta( $post_id, '_wos_code_string', true );
            if ( empty( $code ) ) {
                $code = get_post_meta( $post_id, 'code_string', true );
            }
            
			$rewards = get_post_meta( $post_id, '_wos_rewards', true );
            if ( empty( $rewards ) ) {
                $rewards = get_post_meta( $post_id, 'rewards', true );
            }
            
			$expiration = get_post_meta( $post_id, '_wos_expiration_date', true );
            if ( empty( $expiration ) ) {
                $expiration = get_post_meta( $post_id, 'expiration_date', true );
            }


			// Logic for classes
			$is_new = false;
			$is_expired = false;

			// Check "New" (within 24 hours)
			$post_date = get_the_date( 'U' );
			if ( ( time() - $post_date ) < 24 * 60 * 60 ) {
				$is_new = true;
			}

			// Check "Expired"
			if ( ! empty( $expiration ) ) {
				$expiry_time = strtotime( $expiration );
				if ( $expiry_time && $expiry_time < time() ) {
					$is_expired = true;
				}
			}

			$card_classes = array( 'wos-gift-card' );
			if ( $is_new && ! $is_expired ) {
				$card_classes[] = 'new-flare';
			}
			if ( $is_expired ) {
				$card_classes[] = 'frozen-code';
			}
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
