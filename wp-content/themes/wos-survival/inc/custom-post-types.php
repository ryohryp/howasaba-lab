<?php
/**
 * Register Custom Post Types and Taxonomies
 *
 * @package WOS_Survival
 */

function wos_survival_register_cpts() {
    /**
     * Hero CPT
     */
    $hero_labels = array(
        'name'               => _x( 'Heroes', 'post type general name', 'wos-survival' ),
        'singular_name'      => _x( 'Hero', 'post type singular name', 'wos-survival' ),
        'menu_name'          => _x( 'Heroes', 'admin menu', 'wos-survival' ),
        'name_admin_bar'     => _x( 'Hero', 'add new on admin bar', 'wos-survival' ),
        'add_new'            => _x( 'Add New', 'hero', 'wos-survival' ),
        'add_new_item'       => __( 'Add New Hero', 'wos-survival' ),
        'new_item'           => __( 'New Hero', 'wos-survival' ),
        'edit_item'          => __( 'Edit Hero', 'wos-survival' ),
        'view_item'          => __( 'View Hero', 'wos-survival' ),
        'all_items'          => __( 'All Heroes', 'wos-survival' ),
        'search_items'       => __( 'Search Heroes', 'wos-survival' ),
    );

    register_post_type( 'hero', array(
        'labels'             => $hero_labels,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array( 'slug' => 'hero' ),
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => 5,
        'menu_icon'          => 'dashicons-shield',
        'supports'           => array( 'title', 'editor', 'thumbnail', 'excerpt' ),
        'show_in_rest'       => true,
    ) );

    /**
     * Event CPT
     */
    $event_labels = array(
        'name'          => _x( 'Events', 'post type general name', 'wos-survival' ),
        'singular_name' => _x( 'Event', 'post type singular name', 'wos-survival' ),
        'menu_name'     => _x( 'Events', 'admin menu', 'wos-survival' ),
        'menu_icon'     => 'dashicons-calendar-alt',
    );

    register_post_type( 'event', array(
        'labels'        => $event_labels,
        'public'        => true,
        'show_ui'       => true,
        'supports'      => array( 'title', 'editor', 'thumbnail' ),
        'show_in_rest'  => true,
    ) );

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
add_action( 'init', 'wos_survival_register_cpts' );

/**
 * Register Taxonomies
 */
function wos_survival_register_taxonomies() {
    // Generation
    register_taxonomy( 'generation', 'hero', array(
        'label'        => __( 'Generation', 'wos-survival' ),
        'rewrite'      => array( 'slug' => 'generation' ),
        'hierarchical' => true,
        'show_in_rest' => true,
    ) );

    // Troop Type
    register_taxonomy( 'troop_type', 'hero', array(
        'label'        => __( 'Troop Type', 'wos-survival' ),
        'rewrite'      => array( 'slug' => 'troop-type' ),
        'hierarchical' => true,
        'show_in_rest' => true,
    ) );
}
add_action( 'init', 'wos_survival_register_taxonomies' );

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
    }
    if ( isset( $_POST['wos_rewards'] ) ) {
        update_post_meta( $post_id, '_wos_rewards', sanitize_textarea_field( $_POST['wos_rewards'] ) );
    }
    if ( isset( $_POST['wos_expiration_date'] ) ) {
        update_post_meta( $post_id, '_wos_expiration_date', sanitize_text_field( $_POST['wos_expiration_date'] ) );
    }
}
add_action( 'save_post', 'wos_survival_save_gift_code_data' );
