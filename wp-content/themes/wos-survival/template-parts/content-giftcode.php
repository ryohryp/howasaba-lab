<?php
/**
 * Template part for displaying gift codes
 *
 * @package WOS_Survival
 */

$code_string = get_post_meta( get_the_ID(), '_wos_code_string', true );
$rewards = get_post_meta( get_the_ID(), '_wos_rewards', true );
$expiration = get_post_meta( get_the_ID(), '_wos_expiration_date', true );
$is_expired = wos_is_gift_code_expired( get_the_ID() );

// Class for styling
$card_class = 'gift-code-card';
if ( $is_expired ) {
    $card_class .= ' expired-card';
} else {
    $card_class .= ' active-card';
}

// Check for new arrival (within 24 hours)
$post_date = get_the_date( 'U' );
$current_time = current_time( 'timestamp' );
if ( ! $is_expired && ($current_time - $post_date) < 24 * 60 * 60 ) {
    $card_class .= ' new-arrival';
}
?>

<div class="<?php echo esc_attr( $card_class ); ?>" style="position: relative;">
    
    <?php if ( $is_expired ) : ?>
        <div class="expiration-badge expired">
            <?php esc_html_e( 'Expired', 'wos-survival' ); ?>
        </div>
    <?php else : ?>
        <div class="expiration-badge pulse-animation">
            <?php esc_html_e( 'Active', 'wos-survival' ); ?>
        </div>
    <?php endif; ?>

    <div class="gift-code-content">
        <h3 class="gift-title"><?php the_title(); ?></h3>
        
        <?php if ( $code_string ) : ?>
            <div class="code-display" title="<?php esc_attr_e( 'Click to Copy', 'wos-survival' ); ?>">
                <?php echo esc_html( $code_string ); ?>
            </div>
        <?php endif; ?>

        <?php if ( $rewards ) : ?>
            <div class="rewards-list">
                <strong><?php esc_html_e( 'Rewards:', 'wos-survival' ); ?></strong><br>
                <?php echo nl2br( esc_html( $rewards ) ); ?>
            </div>
        <?php endif; ?>

        <?php if ( $expiration ) : ?>
            <small class="expiration-date">
                <?php printf( esc_html__( 'Expires: %s', 'wos-survival' ), date_i18n( get_option( 'date_format' ), strtotime( $expiration ) ) ); ?>
            </small>
        <?php endif; ?>
    </div>

    <div class="gift-code-actions">
        <?php if ( ! $is_expired && $code_string ) : ?>
            <button class="copy-btn" onclick="navigator.clipboard.writeText('<?php echo esc_js( $code_string ); ?>'); alert('Copied!');">
                <?php esc_html_e( 'Copy Code', 'wos-survival' ); ?>
            </button>
        <?php endif; ?>
    </div>
</div>
