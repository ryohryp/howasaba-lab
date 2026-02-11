<?php
/**
 * Register Event Custom Post Type
 */
function glacial_register_event_cpt() {
    $labels = array(
        'name'               => 'Events',
        'singular_name'      => 'Event',
        'menu_name'          => 'Events',
        'add_new'            => 'Add New',
        'add_new_item'       => 'Add New Event',
        'edit_item'          => 'Edit Event',
        'new_item'           => 'New Event',
        'view_item'          => 'View Event',
        'search_items'       => 'Search Events',
        'not_found'          => 'No events found',
        'not_found_in_trash' => 'No events found in Trash',
    );

    $args = array(
        'labels'              => $labels,
        'public'              => true,
        'has_archive'         => true,
        'menu_icon'           => 'dashicons-calendar-alt',
        'supports'            => array( 'title', 'editor', 'thumbnail' ),
        'rewrite'             => array( 'slug' => 'event' ),
        'show_in_rest'        => true,
    );

    register_post_type( 'event', $args );
}
add_action( 'init', 'glacial_register_event_cpt' );

/**
 * Register Meta Boxes for Event
 */
function glacial_event_add_meta_boxes() {
    add_meta_box(
        'event_details',
        'Event Details',
        'glacial_event_meta_box_callback',
        'event',
        'normal',
        'high'
    );
}
add_action( 'add_meta_boxes', 'glacial_event_add_meta_boxes' );

/**
 * Meta Box Callback
 */
function glacial_event_meta_box_callback( $post ) {
    wp_nonce_field( 'glacial_save_event_data', 'glacial_event_meta_box_nonce' );

    $event_start_date = get_post_meta( $post->ID, '_event_start_date', true );
    $event_duration = get_post_meta( $post->ID, '_event_duration', true );
    $server_age_requirement = get_post_meta( $post->ID, '_server_age_requirement', true );

    ?>
    <style>
        .glacial-meta-row { margin-bottom: 10px; }
        .glacial-meta-row label { display: block; font-weight: bold; margin-bottom: 5px; }
        .glacial-meta-row input[type="text"], .glacial-meta-row textarea, .glacial-meta-row select, .glacial-meta-row input[type="date"] { width: 100%; }
    </style>
    <div class="glacial-meta-row">
        <label for="event_start_date">Start Date</label>
        <input type="date" id="event_start_date" name="event_start_date" value="<?php echo esc_attr( $event_start_date ); ?>" />
    </div>
    <div class="glacial-meta-row">
        <label for="event_duration">Duration (e.g., "7 Days")</label>
        <input type="text" id="event_duration" name="event_duration" value="<?php echo esc_attr( $event_duration ); ?>" />
    </div>
    <div class="glacial-meta-row">
        <label for="server_age_requirement">Server Age Requirement (Days)</label>
        <input type="number" id="server_age_requirement" name="server_age_requirement" value="<?php echo esc_attr( $server_age_requirement ); ?>" />
    </div>
    <?php
}

/**
 * Save Meta Box Data
 */
function glacial_save_event_data( $post_id ) {
    if ( ! isset( $_POST['glacial_event_meta_box_nonce'] ) ) {
        return;
    }
    if ( ! wp_verify_nonce( $_POST['glacial_event_meta_box_nonce'], 'glacial_save_event_data' ) ) {
        return;
    }
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }
    if ( ! current_user_can( 'edit_post', $post_id ) ) {
        return;
    }

    $fields = array( 'event_start_date', 'event_duration', 'server_age_requirement' );

    foreach ( $fields as $field ) {
        if ( isset( $_POST[ $field ] ) ) {
            update_post_meta( $post_id, '_' . $field, sanitize_text_field( $_POST[ $field ] ) );
        }
    }
}
