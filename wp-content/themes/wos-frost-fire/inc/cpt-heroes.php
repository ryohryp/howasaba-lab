<?php
/**
 * Register Hero Custom Post Type and Taxonomies
 */
class WoS_Hero_CPT {

    /**
     * Constructor.
     */
    public function __construct() {
        add_action( 'init', [ $this, 'register_cpt' ], 0 );
        add_action( 'init', [ $this, 'register_taxonomies' ], 0 );
    }

    /**
     * Register Custom Post Type.
     */
    public function register_cpt() {
        $labels = [
            'name'                  => _x( 'Heroes', 'Post Type General Name', WOS_TEXT_DOMAIN ),
            'singular_name'         => _x( 'Hero', 'Post Type Singular Name', WOS_TEXT_DOMAIN ),
            'menu_name'             => __( 'Heroes', WOS_TEXT_DOMAIN ),
            'name_admin_bar'        => __( 'Hero', WOS_TEXT_DOMAIN ),
            'archives'              => __( 'Hero Archives', WOS_TEXT_DOMAIN ),
            'attributes'            => __( 'Hero Attributes', WOS_TEXT_DOMAIN ),
            'parent_item_colon'     => __( 'Parent Hero:', WOS_TEXT_DOMAIN ),
            'all_items'             => __( 'All Heroes', WOS_TEXT_DOMAIN ),
            'add_new_item'          => __( 'Add New Hero', WOS_TEXT_DOMAIN ),
            'add_new'               => __( 'Add New', WOS_TEXT_DOMAIN ),
            'new_item'              => __( 'New Hero', WOS_TEXT_DOMAIN ),
            'edit_item'             => __( 'Edit Hero', WOS_TEXT_DOMAIN ),
            'update_item'           => __( 'Update Hero', WOS_TEXT_DOMAIN ),
            'view_item'             => __( 'View Hero', WOS_TEXT_DOMAIN ),
            'view_items'            => __( 'View Heroes', WOS_TEXT_DOMAIN ),
            'search_items'          => __( 'Search Hero', WOS_TEXT_DOMAIN ),
            'not_found'             => __( 'Not found', WOS_TEXT_DOMAIN ),
            'not_found_in_trash'    => __( 'Not found in Trash', WOS_TEXT_DOMAIN ),
            'featured_image'        => __( 'Hero Image', WOS_TEXT_DOMAIN ),
            'set_featured_image'    => __( 'Set hero image', WOS_TEXT_DOMAIN ),
            'remove_featured_image' => __( 'Remove hero image', WOS_TEXT_DOMAIN ),
            'use_featured_image'    => __( 'Use as hero image', WOS_TEXT_DOMAIN ),
            'insert_into_item'      => __( 'Insert into hero', WOS_TEXT_DOMAIN ),
            'uploaded_to_this_item' => __( 'Uploaded to this hero', WOS_TEXT_DOMAIN ),
            'items_list'            => __( 'Heroes list', WOS_TEXT_DOMAIN ),
            'items_list_navigation' => __( 'Heroes list navigation', WOS_TEXT_DOMAIN ),
            'filter_items_list'     => __( 'Filter heroes list', WOS_TEXT_DOMAIN ),
        ];

        $args = [
            'label'                 => __( 'Hero', WOS_TEXT_DOMAIN ),
            'description'           => __( 'Whiteout Survival Heroes', WOS_TEXT_DOMAIN ),
            'labels'                => $labels,
            'supports'              => [ 'title', 'editor', 'thumbnail', 'custom-fields', 'excerpt' ],
            'taxonomies'            => [ 'hero_generation', 'hero_type', 'hero_rarity' ],
            'hierarchical'          => false,
            'public'                => true,
            'show_ui'               => true,
            'show_in_menu'          => true,
            'menu_position'         => 5,
            'menu_icon'             => 'dashicons-shield',
            'show_in_admin_bar'     => true,
            'show_in_nav_menus'     => true,
            'can_export'            => true,
            'has_archive'           => true,
            'exclude_from_search'   => false,
            'publicly_queryable'    => true,
            'capability_type'       => 'post',
            'show_in_rest'          => true, // Key for Gutenberg editor support
        ];
        register_post_type( 'wos_hero', $args );
    }

    /**
     * Register Taxonomies.
     */
    public function register_taxonomies() {
        // Generation
        register_taxonomy( 'hero_generation', [ 'wos_hero' ], [
            'hierarchical'      => true,
            'labels'            => [
                'name'              => _x( 'Generations', 'taxonomy general name', WOS_TEXT_DOMAIN ),
                'singular_name'     => _x( 'Generation', 'taxonomy singular name', WOS_TEXT_DOMAIN ),
                'menu_name'         => __( 'Generation', WOS_TEXT_DOMAIN ),
            ],
            'show_ui'           => true,
            'show_admin_column' => true,
            'query_var'         => true,
            'rewrite'           => [ 'slug' => 'generation' ],
            'show_in_rest'      => true,
        ]);

        // Type (Infantry, Lancer, etc.)
        register_taxonomy( 'hero_type', [ 'wos_hero' ], [
            'hierarchical'      => true,
            'labels'            => [
                'name'              => _x( 'Types', 'taxonomy general name', WOS_TEXT_DOMAIN ),
                'singular_name'     => _x( 'Type', 'taxonomy singular name', WOS_TEXT_DOMAIN ),
                'menu_name'         => __( 'Type', WOS_TEXT_DOMAIN ),
            ],
            'show_ui'           => true,
            'show_admin_column' => true,
            'query_var'         => true,
            'rewrite'           => [ 'slug' => 'type' ],
            'show_in_rest'      => true,
        ]);

        // Rarity
        register_taxonomy( 'hero_rarity', [ 'wos_hero' ], [
            'hierarchical'      => true,
            'labels'            => [
                'name'              => _x( 'Rarities', 'taxonomy general name', WOS_TEXT_DOMAIN ),
                'singular_name'     => _x( 'Rarity', 'taxonomy singular name', WOS_TEXT_DOMAIN ),
                'menu_name'         => __( 'Rarity', WOS_TEXT_DOMAIN ),
            ],
            'show_ui'           => true,
            'show_admin_column' => true,
            'query_var'         => true,
            'rewrite'           => [ 'slug' => 'rarity' ],
            'show_in_rest'      => true,
        ]);
    }
}

new WoS_Hero_CPT();
