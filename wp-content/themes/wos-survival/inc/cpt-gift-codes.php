<?php
/**
 * Register Gift Code Custom Post Type
 *
 * @package WOS_Survival
 */

function wos_survival_register_gift_code_cpt() {
    /**
     * Gift Code CPT
     */
    $gift_labels = array(
        'name'          => _x( 'Gift Codes', 'post type general name', 'wos-survival' ),
        'singular_name' => _x( 'Gift Code', 'post type singular name', 'wos-survival' ),
        'menu_name'     => _x( 'Gift Codes', 'admin menu', 'wos-survival' ),
        'menu_icon'     => 'dashicons-tickets-alt',
        'add_new'       => _x( 'Add New', 'gift code', 'wos-survival' ),
        'add_new_item'  => __( 'Add New Gift Code', 'wos-survival' ),
        'new_item'      => __( 'New Gift Code', 'wos-survival' ),
        'edit_item'     => __( 'Edit Gift Code', 'wos-survival' ),
        'view_item'     => __( 'View Gift Code', 'wos-survival' ),
        'all_items'     => __( 'All Gift Codes', 'wos-survival' ),
        'search_items'  => __( 'Search Gift Codes', 'wos-survival' ),
        'not_found'     => __( 'No gift codes found.', 'wos-survival' ),
    );

    register_post_type( 'gift_code', array(
        'labels'        => $gift_labels,
        'public'        => true,
        'show_ui'       => true,
        'supports'      => array( 'title', 'custom-fields' ), // Added custom-fields support as requested
        'show_in_rest'  => true,
        'rewrite'       => array( 'slug' => 'gift-code' ),
        'map_meta_cap'  => true,
    ) );
}
add_action( 'init', 'wos_survival_register_gift_code_cpt' );

/**
 * Custom Fields for Gift Code
 * (Using simple metaboxes for no-plugin dependency Admin UI)
 */
function wos_survival_gift_code_meta_box() {
    add_meta_box(
        'wos_gift_code_details',
        'Gift Code Details',
        'wos_survival_gift_code_meta_box_callback',
        'gift_code'
    );
}
add_action( 'add_meta_boxes', 'wos_survival_gift_code_meta_box' );

function wos_survival_gift_code_meta_box_callback( $post ) {
    wp_nonce_field( 'wos_save_gift_code_data', 'wos_gift_code_nonce' );

    $code = get_post_meta( $post->ID, '_wos_code_string', true );
    // Fallback if saved via API as 'code_string' only
    if ( empty( $code ) ) {
        $code = get_post_meta( $post->ID, 'code_string', true );
    }

    $rewards = get_post_meta( $post->ID, '_wos_rewards', true );
    if ( empty( $rewards ) ) {
        $rewards = get_post_meta( $post->ID, 'rewards', true );
    }

    $expiration = get_post_meta( $post->ID, '_wos_expiration_date', true );
    if ( empty( $expiration ) ) {
        $expiration = get_post_meta( $post->ID, 'expiration_date', true );
    }

    ?>
    <p>
        <label for="wos_code_string">Code String:</label><br>
        <input type="text" id="wos_code_string" name="wos_code_string" value="<?php echo esc_attr( $code ); ?>" style="width:100%;" />
    </p>
    <p>
        <label for="wos_rewards">Rewards:</label><br>
        <textarea id="wos_rewards" name="wos_rewards" style="width:100%;"><?php echo esc_textarea( $rewards ); ?></textarea>
    </p>
    <p>
        <label for="wos_expiration_date">Expiration Date (YYYY-MM-DD):</label><br>
        <input type="date" id="wos_expiration_date" name="wos_expiration_date" value="<?php echo esc_attr( $expiration ); ?>" />
    </p>
    <?php
}

function wos_survival_save_gift_code_data( $post_id ) {
    if ( ! isset( $_POST['wos_gift_code_nonce'] ) ) {
        return;
    }
    if ( ! wp_verify_nonce( $_POST['wos_gift_code_nonce'], 'wos_save_gift_code_data' ) ) {
        return;
    }
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }
    if ( ! current_user_can( 'edit_post', $post_id ) ) {
        return;
    }

    // Save both underscore (protected) and non-underscore (public/api match) versions for compatibility
    if ( isset( $_POST['wos_code_string'] ) ) {
        update_post_meta( $post_id, '_wos_code_string', sanitize_text_field( $_POST['wos_code_string'] ) );
        update_post_meta( $post_id, 'code_string', sanitize_text_field( $_POST['wos_code_string'] ) );
    }
    if ( isset( $_POST['wos_rewards'] ) ) {
        update_post_meta( $post_id, '_wos_rewards', sanitize_textarea_field( $_POST['wos_rewards'] ) );
        update_post_meta( $post_id, 'rewards', sanitize_textarea_field( $_POST['wos_rewards'] ) );
    }
    if ( isset( $_POST['wos_expiration_date'] ) ) {
        update_post_meta( $post_id, '_wos_expiration_date', sanitize_text_field( $_POST['wos_expiration_date'] ) );
        update_post_meta( $post_id, 'expiration_date', sanitize_text_field( $_POST['wos_expiration_date'] ) );
    }
}
add_action( 'save_post', 'wos_survival_save_gift_code_data' );
