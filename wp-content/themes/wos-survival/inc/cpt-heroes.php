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
        add_action( 'quick_edit_custom_box', [ $this, 'display_quick_edit_custom_box' ], 10, 2 );
        add_action( 'admin_footer-edit.php', [ $this, 'quick_edit_javascript' ] );
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
            'japanese_name'          => __( 'Japanese Name', WOS_TEXT_DOMAIN ),
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
            // japanese_name doesn't use underscore prefix in existing data
            $meta_key = ($key === 'japanese_name') ? $key : '_' . $key;
            $values[ $key ] = get_post_meta( $post->ID, $meta_key, true );
        }

        echo '<style>
            .wos-meta-row { margin-bottom: 15px; border-bottom: 1px solid #eee; padding-bottom: 10px; }
            .wos-meta-row:last-child { border-bottom: none; }
            .wos-meta-row label { display: block; font-weight: bold; margin-bottom: 5px; }
            .wos-meta-row input[type="text"], .wos-meta-row input[type="number"] { width: 100%; max-width: 400px; }
            .wos-image-search-link { display: inline-block; margin-bottom: 10px; font-weight: bold; text-decoration: none; color: #0073aa; }
            .wos-image-search-link:hover { color: #005177; }
        </style>';

        // --- Image Helpers ---
        // Google Image Search Link
        $search_query = urlencode( 'Whiteout Survival ' . get_the_title( $post->ID ) . ' icon' );
        echo '<div class="wos-meta-row" style="background: #f0f6fc; border-left: 4px solid #72aee6; padding: 10px;">';
        echo '<a href="https://www.google.com/search?tbm=isch&q=' . $search_query . '" target="_blank" class="wos-image-search-link">';
        echo '<span class="dashicons dashicons-search" style="vertical-align: text-bottom;"></span> ' . __( 'Search Hero Image on Google', WOS_TEXT_DOMAIN );
        echo '</a>';
        
        // Sideload Input
        echo '<div style="margin-top: 5px;">';
        echo '<label for="hero_sideload_image_url" style="display:inline-block; margin-right:10px;">' . __( 'Set Featured Image from URL:', WOS_TEXT_DOMAIN ) . '</label>';
        echo '<input type="text" id="hero_sideload_image_url" name="hero_sideload_image_url" value="" placeholder="https://example.com/image.png" style="width: 100%; max-width: 500px;" />';
        echo '<p class="description">' . __( 'Paste an image URL here and save to automatically download and set it as the Featured Image.', WOS_TEXT_DOMAIN ) . '</p>';
        echo '</div>';
        echo '</div>';

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
    /**
     * Add Quick Edit Field
     */
    public function display_quick_edit_custom_box( $column_name, $post_type ) {
        if ( 'japanese_name' !== $column_name || 'wos_hero' !== $post_type ) {
            return;
        }
        wp_nonce_field( 'wos_save_hero_quick_edit', 'wos_hero_quick_edit_nonce' );
        ?>
        <fieldset class="inline-edit-col-left">
            <div class="inline-edit-col">
                <label>
                    <span class="title"><?php _e( 'Japanese Name', WOS_TEXT_DOMAIN ); ?></span>
                    <span class="input-text-wrap">
                        <input type="text" name="japanese_name" class="text" value="">
                    </span>
                </label>
            </div>
        </fieldset>
        <?php
    }

    /**
     * Quick Edit Javascript
     */
    public function quick_edit_javascript() {
        global $current_screen;
        if ( 'wos_hero' !== $current_screen->post_type ) {
            return;
        }
        ?>
        <script>
        jQuery(document).ready(function($){
            // Copy the instance of the edit function
            var $wp_inline_edit = inlineEditPost.edit;
            // Overwrite
            inlineEditPost.edit = function( id ) {
                // Run original
                $wp_inline_edit.apply( this, arguments );
                
                // Get Post ID
                var $post_id = 0;
                if ( typeof( id ) == 'object' ) {
                    $post_id = parseInt( this.getId( id ) );
                }

                if ( $post_id > 0 ) {
                    // Define rows
                    var $edit_row = $( '#edit-' + $post_id );
                    var $post_row = $( '#post-' + $post_id );
                    
                    // Get data
                    var $jp_name = $post_row.find( '.wos_hero_japanese_name_value' ).text();
                    
                    // Populate
                    $edit_row.find( 'input[name="japanese_name"]' ).val( $jp_name );
                }
            };
        });
        </script>
        <?php
    }

    /**
     * Save Meta Box Data (Handling both Edit Screen and Quick Edit)
     */
    public function save_meta_box_data( $post_id ) {
        // 1. Check if it's a Quick Edit Save
        $is_quick_edit = isset( $_POST['wos_hero_quick_edit_nonce'] ) && wp_verify_nonce( $_POST['wos_hero_quick_edit_nonce'], 'wos_save_hero_quick_edit' );
        
        // 2. Check if it's a Full Edit Save
        $is_full_edit = isset( $_POST['wos_hero_meta_box_nonce'] ) && wp_verify_nonce( $_POST['wos_hero_meta_box_nonce'], 'wos_save_hero_data' );

        // If neither, return
        if ( ! $is_quick_edit && ! $is_full_edit ) {
            return;
        }

        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
            return;
        }
        if ( ! current_user_can( 'edit_post', $post_id ) ) {
            return;
        }

        // --- Handle Image Sideload (Full Edit Only) ---
        if ( $is_full_edit && ! empty( $_POST['hero_sideload_image_url'] ) ) {
            $image_url = esc_url_raw( $_POST['hero_sideload_image_url'] );
            
            // Check if it's a valid URL
            if ( filter_var( $image_url, FILTER_VALIDATE_URL ) ) {
                require_once( ABSPATH . 'wp-admin/includes/media.php' );
                require_once( ABSPATH . 'wp-admin/includes/file.php' );
                require_once( ABSPATH . 'wp-admin/includes/image.php' );

                // Sideload image
                $desc = "Hero Image for Post $post_id";
                $img_id = media_sideload_image( $image_url, $post_id, $desc, 'id' );

                if ( ! is_wp_error( $img_id ) ) {
                    // Set as featured image
                    set_post_thumbnail( $post_id, $img_id );
                }
                // We do NOT save the URL to a meta field because we processed it.
                // The input field will be empty on reload.
            }
        }

        $fields = [
            'japanese_name',
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
                // japanese_name doesn't use underscore prefix
                $meta_key = ($field === 'japanese_name') ? $field : '_' . $field;

                // Use sanitize_textarea_field for skills as they might be longer
                if ( strpos($field, 'skill') !== false ) {
                    update_post_meta( $post_id, $meta_key, sanitize_textarea_field( $_POST[ $field ] ) );
                } else {
                    update_post_meta( $post_id, $meta_key, sanitize_text_field( $_POST[ $field ] ) );
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

/**
 * Add Japanese Name Column to Admin List
 * Priority 999 to override plugin settings
 */
add_filter( 'manage_wos_hero_posts_columns', function($columns) {
    // Append to end of columns
    $columns['japanese_name'] = __( 'Japanese Name', WOS_TEXT_DOMAIN );
    return $columns;
}, 999 );

add_action( 'manage_wos_hero_posts_custom_column', function($column, $post_id) {
    if ($column === 'japanese_name') {
        $jp_name = get_post_meta($post_id, 'japanese_name', true);
        echo esc_html($jp_name);
        // Hidden field for Quick Edit JS to grab
        echo '<span class="hidden wos_hero_japanese_name_value" style="display:none;">' . esc_attr($jp_name) . '</span>';
    }
}, 10, 2 );
