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
        ];
        register_post_type( 'wos_event', $args );
    }
}

new WoS_Event_CPT();
