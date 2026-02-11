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
        add_action( 'add_meta_boxes', [ $this, 'register_meta_boxes' ] );
        add_action( 'save_post', [ $this, 'save_meta_box_data' ] );
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
            'rewrite'               => [ 'slug' => 'hero', 'with_front' => true ],
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

    /**
     * Register Meta Boxes for Hero
     */
    public function register_meta_boxes() {
        add_meta_box(
            'hero_details',
            __( 'Hero Details', WOS_TEXT_DOMAIN ),
            [ $this, 'hero_meta_box_callback' ],
            'wos_hero',
            'normal',
            'high'
        );
    }

    /**
     * Meta Box Callback
     */
    public function hero_meta_box_callback( $post ) {
        wp_nonce_field( 'wos_save_hero_data', 'wos_hero_meta_box_nonce' );

        $fields = [
            'hero_unlock_day'        => __( 'Unlock Day (from server start)', WOS_TEXT_DOMAIN ),
            'hero_source'            => __( 'Source (e.g. Lucky Wheel)', WOS_TEXT_DOMAIN ),
            'hero_widget_name'       => __( 'Exclusive Widget Name', WOS_TEXT_DOMAIN ),
            'hero_stats_atk'         => __( 'ATK (0-100)', WOS_TEXT_DOMAIN ),
            'hero_stats_def'         => __( 'DEF (0-100)', WOS_TEXT_DOMAIN ),
            'hero_stats_hp'          => __( 'HP (0-100)', WOS_TEXT_DOMAIN ),
            'hero_expedition_skill'  => __( 'Expedition Skill (Description)', WOS_TEXT_DOMAIN ),
            'hero_exploration_skill' => __( 'Exploration Skill (Description)', WOS_TEXT_DOMAIN ),
        ];

        $values = [];
        foreach ( $fields as $key => $label ) {
            $values[ $key ] = get_post_meta( $post->ID, '_' . $key, true );
        }

        echo '<style>
            .wos-meta-row { margin-bottom: 15px; border-bottom: 1px solid #eee; padding-bottom: 10px; }
            .wos-meta-row:last-child { border-bottom: none; }
            .wos-meta-row label { display: block; font-weight: bold; margin-bottom: 5px; }
            .wos-meta-row input[type="text"], .wos-meta-row input[type="number"] { width: 100%; max-width: 400px; }
        </style>';

        foreach ( $fields as $key => $label ) {
            $type = ( strpos( $key, 'stats' ) !== false || strpos( $key, 'day' ) !== false ) ? 'number' : 'text';
            $is_textarea = strpos( $key, 'skill' ) !== false;
            
            echo '<div class="wos-meta-row">';
            echo '<label for="' . esc_attr( $key ) . '">' . esc_html( $label ) . '</label>';
            if ( $is_textarea ) {
                echo '<textarea id="' . esc_attr( $key ) . '" name="' . esc_attr( $key ) . '" rows="3" style="width:100%; max-width:600px;">' . esc_textarea( $values[ $key ] ) . '</textarea>';
            } else {
                echo '<input type="' . $type . '" id="' . esc_attr( $key ) . '" name="' . esc_attr( $key ) . '" value="' . esc_attr( $values[ $key ] ) . '" />';
            }
            echo '</div>';
        }
    }

    /**
     * Save Meta Box Data
     */
    public function save_meta_box_data( $post_id ) {
        if ( ! isset( $_POST['wos_hero_meta_box_nonce'] ) ) {
            return;
        }
        if ( ! wp_verify_nonce( $_POST['wos_hero_meta_box_nonce'], 'wos_save_hero_data' ) ) {
            return;
        }
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
            return;
        }
        if ( ! current_user_can( 'edit_post', $post_id ) ) {
            return;
        }

        $fields = [
            'hero_unlock_day',
            'hero_source',
            'hero_widget_name',
            'hero_stats_atk',
            'hero_stats_def',
            'hero_stats_hp',
            'hero_expedition_skill',
            'hero_exploration_skill',
        ];

        foreach ( $fields as $field ) {
            if ( isset( $_POST[ $field ] ) ) {
                // Use sanitize_textarea_field for skills as they might be longer
                if ( strpos($field, 'skill') !== false ) {
                    update_post_meta( $post_id, '_' . $field, sanitize_textarea_field( $_POST[ $field ] ) );
                } else {
                    update_post_meta( $post_id, '_' . $field, sanitize_text_field( $_POST[ $field ] ) );
                }
            }
        }
    }
}

/**
 * Helper function to get Hero Stats
 */
function wos_get_hero_stats( $post_id ) {
    return [
        'atk' => (int) get_post_meta( $post_id, '_hero_stats_atk', true ),
        'def' => (int) get_post_meta( $post_id, '_hero_stats_def', true ),
        'hp'  => (int) get_post_meta( $post_id, '_hero_stats_hp', true ),
    ];
}

new WoS_Hero_CPT();
