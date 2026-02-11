<?php
/**
 * Register Event Custom Post Type
 */
class WoS_Event_CPT {

    /**
     * Constructor.
     */
    public function __construct() {
        add_action( 'init', [ $this, 'register_cpt' ], 0 );
        add_action( 'add_meta_boxes', [ $this, 'register_meta_boxes' ] );
        add_action( 'save_post', [ $this, 'save_meta_box_data' ] );
    }

    /**
     * Register Custom Post Type.
     */
    public function register_cpt() {
        $labels = [
            'name'                  => _x( 'Events', 'Post Type General Name', WOS_TEXT_DOMAIN ),
            'singular_name'         => _x( 'Event', 'Post Type Singular Name', WOS_TEXT_DOMAIN ),
            'menu_name'             => __( 'Events', WOS_TEXT_DOMAIN ),
            'name_admin_bar'        => __( 'Event', WOS_TEXT_DOMAIN ),
            'archives'              => __( 'Event Archives', WOS_TEXT_DOMAIN ),
            'attributes'            => __( 'Event Attributes', WOS_TEXT_DOMAIN ),
            'parent_item_colon'     => __( 'Parent Event:', WOS_TEXT_DOMAIN ),
            'all_items'             => __( 'All Events', WOS_TEXT_DOMAIN ),
            'add_new_item'          => __( 'Add New Event', WOS_TEXT_DOMAIN ),
            'add_new'               => __( 'Add New', WOS_TEXT_DOMAIN ),
            'new_item'              => __( 'New Event', WOS_TEXT_DOMAIN ),
            'edit_item'             => __( 'Edit Event', WOS_TEXT_DOMAIN ),
            'update_item'           => __( 'Update Event', WOS_TEXT_DOMAIN ),
            'view_item'             => __( 'View Event', WOS_TEXT_DOMAIN ),
            'view_items'            => __( 'View Events', WOS_TEXT_DOMAIN ),
            'search_items'          => __( 'Search Event', WOS_TEXT_DOMAIN ),
            'not_found'             => __( 'Not found', WOS_TEXT_DOMAIN ),
            'not_found_in_trash'    => __( 'Not found in Trash', WOS_TEXT_DOMAIN ),
            'featured_image'        => __( 'Event Image', WOS_TEXT_DOMAIN ),
            'set_featured_image'    => __( 'Set event image', WOS_TEXT_DOMAIN ),
            'remove_featured_image' => __( 'Remove event image', WOS_TEXT_DOMAIN ),
            'use_featured_image'    => __( 'Use as event image', WOS_TEXT_DOMAIN ),
            'insert_into_item'      => __( 'Insert into event', WOS_TEXT_DOMAIN ),
            'uploaded_to_this_item' => __( 'Uploaded to this event', WOS_TEXT_DOMAIN ),
            'items_list'            => __( 'Events list', WOS_TEXT_DOMAIN ),
            'items_list_navigation' => __( 'Events list navigation', WOS_TEXT_DOMAIN ),
            'filter_items_list'     => __( 'Filter events list', WOS_TEXT_DOMAIN ),
        ];

        $args = [
            'label'                 => __( 'Event', WOS_TEXT_DOMAIN ),
            'description'           => __( 'Whiteout Survival Events', WOS_TEXT_DOMAIN ),
            'labels'                => $labels,
            'supports'              => [ 'title', 'editor', 'thumbnail', 'custom-fields', 'excerpt' ],
            'hierarchical'          => false,
            'public'                => true,
            'show_ui'               => true,
            'show_in_menu'          => true,
            'menu_position'         => 6,
            'menu_icon'             => 'dashicons-calendar-alt',
            'show_in_admin_bar'     => true,
            'show_in_nav_menus'     => true,
            'can_export'            => true,
            'has_archive'           => true,
            'exclude_from_search'   => false,
            'publicly_queryable'    => true,
            'capability_type'       => 'post',
            'show_in_rest'          => true,
            'rewrite'               => [ 'slug' => 'event', 'with_front' => true ],
        ];
        register_post_type( 'wos_event', $args );
    }

    /**
     * Register Meta Boxes for Event
     */
    public function register_meta_boxes() {
        add_meta_box(
            'event_details',
            __( 'Event Details', WOS_TEXT_DOMAIN ),
            [ $this, 'event_meta_box_callback' ],
            'wos_event',
            'normal',
            'high'
        );
    }

    /**
     * Meta Box Callback
     */
    public function event_meta_box_callback( $post ) {
        wp_nonce_field( 'wos_save_event_data', 'wos_event_meta_box_nonce' );

        $fields = [
            'event_start_date'       => __( 'Start Date (YYYY-MM-DD)', WOS_TEXT_DOMAIN ),
            'event_duration'         => __( 'Duration (e.g. 3 Days)', WOS_TEXT_DOMAIN ),
            'server_age_requirement' => __( 'Server Age Requirement (Days)', WOS_TEXT_DOMAIN ),
        ];

        $values = [];
        foreach ( $fields as $key => $label ) {
            $values[ $key ] = get_post_meta( $post->ID, '_' . $key, true );
        }

        echo '<style>
            .wos-meta-row { margin-bottom: 15px; border-bottom: 1px solid #eee; padding-bottom: 10px; }
            .wos-meta-row:last-child { border-bottom: none; }
            .wos-meta-row label { display: block; font-weight: bold; margin-bottom: 5px; }
            .wos-meta-row input[type="text"], .wos-meta-row input[type="number"], .wos-meta-row input[type="date"] { width: 100%; max-width: 400px; }
        </style>';

        foreach ( $fields as $key => $label ) {
            $type = ( strpos( $key, 'date' ) !== false ) ? 'date' : ( ( strpos( $key, 'age' ) !== false ) ? 'number' : 'text' );
            echo '<div class="wos-meta-row">';
            echo '<label for="' . esc_attr( $key ) . '">' . esc_html( $label ) . '</label>';
            echo '<input type="' . $type . '" id="' . esc_attr( $key ) . '" name="' . esc_attr( $key ) . '" value="' . esc_attr( $values[ $key ] ) . '" />';
            echo '</div>';
        }
    }

    /**
     * Save Meta Box Data
     */
    public function save_meta_box_data( $post_id ) {
        if ( ! isset( $_POST['wos_event_meta_box_nonce'] ) ) {
            return;
        }
        if ( ! wp_verify_nonce( $_POST['wos_event_meta_box_nonce'], 'wos_save_event_data' ) ) {
            return;
        }
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
            return;
        }
        if ( ! current_user_can( 'edit_post', $post_id ) ) {
            return;
        }

        $fields = [
            'event_start_date',
            'event_duration',
            'server_age_requirement',
        ];

        foreach ( $fields as $field ) {
            if ( isset( $_POST[ $field ] ) ) {
                update_post_meta( $post_id, '_' . $field, sanitize_text_field( $_POST[ $field ] ) );
            }
        }
    }
}

/**
 * Helper function to get Event Meta
 */
function wos_get_event_meta( $post_id ) {
    return [
        'start_date' => get_post_meta( $post_id, '_event_start_date', true ),
        'duration'   => get_post_meta( $post_id, '_event_duration', true ),
        'server_age' => (int) get_post_meta( $post_id, '_server_age_requirement', true ),
    ];
}



new WoS_Event_CPT();
