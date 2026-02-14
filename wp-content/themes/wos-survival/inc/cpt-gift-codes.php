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
    );

    register_post_type( 'gift_code', array(
        'labels'        => $gift_labels,
        'public'        => true,
        'show_ui'       => true,
        'supports'      => array( 'title' ), // Title used for internal admin ID, actual code in meta
        'show_in_rest'  => true,
    ) );
}
add_action( 'init', 'wos_survival_register_gift_code_cpt' );

/**
 * Custom Fields for Gift Code
 * (Using simple metaboxes for no-plugin dependency)
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
    $rewards = get_post_meta( $post->ID, '_wos_rewards', true );
    $expiration = get_post_meta( $post->ID, '_wos_expiration_date', true );

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

    if ( isset( $_POST['wos_code_string'] ) ) {
        update_post_meta( $post_id, '_wos_code_string', sanitize_text_field( $_POST['wos_code_string'] ) );
        
        // API duplication check relies on 'code_string' key (without underscore) in some contexts, 
        // ensuring compatibility by saving both or standardizing.
        // The API implementation uses 'code_string' (no underscore) in meta_query check but saves '_wos_code_string' in meta_input.
        // Let's align on what the API uses.
        // API implementation:
        // Check: key='code_string', value=$code
        // Save: '_wos_code_string' => $code
        // This seems inconsistent in the API code I wrote earlier. 
        // Checking API code...
        // API check uses 'code_string'. API save uses 'code_string' (no underscore) in meta_input for the duplicate check key?
        // Wait, looking at API code I wrote in step 25/39:
        // Check: key => 'code_string'
        // Save: 'code_string' => $code_string (in meta_input)
        // AND '_wos_code_string' => $code_string
        
        // So we should save 'code_string' as well to match API duplicate check logic if we want manual edits to be catchy.
        update_post_meta( $post_id, 'code_string', sanitize_text_field( $_POST['wos_code_string'] ) );
    }
    if ( isset( $_POST['wos_rewards'] ) ) {
        update_post_meta( $post_id, '_wos_rewards', sanitize_textarea_field( $_POST['wos_rewards'] ) );
    }
    if ( isset( $_POST['wos_expiration_date'] ) ) {
        update_post_meta( $post_id, '_wos_expiration_date', sanitize_text_field( $_POST['wos_expiration_date'] ) );
    }
}
add_action( 'save_post', 'wos_survival_save_gift_code_data' );
