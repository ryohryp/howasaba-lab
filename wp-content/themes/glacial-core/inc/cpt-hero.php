<?php
/**
 * Register Hero Custom Post Type and Taxonomies
 */
function glacial_register_hero_cpt() {
    // Register Taxonomies first
    
    // Generation (e.g., Gen 1, Gen 2)
    register_taxonomy('hero_generation', 'hero', array(
        'labels' => array('name' => 'Generations', 'singular_name' => 'Generation'),
        'hierarchical' => true,
        'show_ui' => true,
        'show_admin_column' => true,
        'query_var' => true,
        'rewrite' => array('slug' => 'generation'),
        'show_in_rest' => true,
    ));

    // Class (Infantry, Lancer, Marksman)
    register_taxonomy('hero_class', 'hero', array(
        'labels' => array('name' => 'Classes', 'singular_name' => 'Class'),
        'hierarchical' => true,
        'show_ui' => true,
        'show_admin_column' => true,
        'query_var' => true,
        'rewrite' => array('slug' => 'class'),
        'show_in_rest' => true,
    ));

    // Rarity (Mythic, Epic, Rare)
    register_taxonomy('hero_rarity', 'hero', array(
        'labels' => array('name' => 'Rarities', 'singular_name' => 'Rarity'),
        'hierarchical' => true,
        'show_ui' => true,
        'show_admin_column' => true,
        'query_var' => true,
        'rewrite' => array('slug' => 'rarity'),
        'show_in_rest' => true,
    ));

    // Register Post Type
    $labels = array(
        'name'               => 'Heroes',
        'singular_name'      => 'Hero',
        'menu_name'          => 'Heroes',
        'add_new'            => 'Add New',
        'add_new_item'       => 'Add New Hero',
        'edit_item'          => 'Edit Hero',
        'new_item'           => 'New Hero',
        'view_item'          => 'View Hero',
        'search_items'       => 'Search Heroes',
        'not_found'          => 'No heroes found',
        'not_found_in_trash' => 'No heroes found in Trash',
    );

    $args = array(
        'labels'              => $labels,
        'public'              => true,
        'has_archive'         => true,
        'menu_icon'           => 'dashicons-shield',
        'supports'            => array( 'title', 'editor', 'thumbnail', 'excerpt' ),
        'rewrite'             => array( 'slug' => 'hero' ),
        'show_in_rest'        => true,
        'taxonomies'          => array('hero_generation', 'hero_class', 'hero_rarity'),
    );

    register_post_type( 'hero', $args );
}
add_action( 'init', 'glacial_register_hero_cpt' );

/**
 * Register Meta Boxes for Hero
 */
function glacial_hero_add_meta_boxes() {
    add_meta_box(
        'hero_details',
        'Hero Details',
        'glacial_hero_meta_box_callback',
        'hero',
        'normal',
        'high'
    );
}
add_action( 'add_meta_boxes', 'glacial_hero_add_meta_boxes' );

/**
 * Meta Box Callback
 */
function glacial_hero_meta_box_callback( $post ) {
    wp_nonce_field( 'glacial_save_hero_data', 'glacial_hero_meta_box_nonce' );

    $fields = array(
        'hero_unlock_day' => 'Unlock Day (from server start)',
        'hero_source' => 'Source (e.g. Lucky Wheel)',
        'hero_widget_name' => 'Exclusive Widget Name',
        'hero_stats_atk' => 'ATK (0-100)',
        'hero_stats_def' => 'DEF (0-100)',
        'hero_stats_hp' => 'HP (0-100)',
    );

    $values = array();
    foreach($fields as $key => $label) {
        $values[$key] = get_post_meta( $post->ID, '_' . $key, true );
    }

    echo '<style>
        .glacial-meta-row { margin-bottom: 15px; border-bottom: 1px solid #eee; padding-bottom: 10px; }
        .glacial-meta-row:last-child { border-bottom: none; }
        .glacial-meta-row label { display: block; font-weight: bold; margin-bottom: 5px; }
        .glacial-meta-row input[type="text"], .glacial-meta-row input[type="number"] { width: 100%; max-width: 400px; }
    </style>';

    foreach($fields as $key => $label) {
        $type = strpos($key, 'stats') !== false || strpos($key, 'day') !== false ? 'number' : 'text';
        echo '<div class="glacial-meta-row">';
        echo '<label for="' . esc_attr($key) . '">' . esc_html($label) . '</label>';
        echo '<input type="' . $type . '" id="' . esc_attr($key) . '" name="' . esc_attr($key) . '" value="' . esc_attr($values[$key]) . '" />';
        echo '</div>';
    }
}

/**
 * Save Meta Box Data
 */
function glacial_save_hero_data( $post_id ) {
    if ( ! isset( $_POST['glacial_hero_meta_box_nonce'] ) ) {
        return;
    }
    if ( ! wp_verify_nonce( $_POST['glacial_hero_meta_box_nonce'], 'glacial_save_hero_data' ) ) {
        return;
    }
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }
    if ( ! current_user_can( 'edit_post', $post_id ) ) {
        return;
    }

    $fields = array(
        'hero_unlock_day',
        'hero_source',
        'hero_widget_name',
        'hero_stats_atk',
        'hero_stats_def',
        'hero_stats_hp'
    );

    foreach ( $fields as $field ) {
        if ( isset( $_POST[ $field ] ) ) {
            update_post_meta( $post_id, '_' . $field, sanitize_text_field( $_POST[ $field ] ) );
        }
    }
}

/**
 * Helper function to get Hero Stats
 */
function glacial_get_hero_stats($post_id) {
    return array(
        'atk' => (int) get_post_meta($post_id, '_hero_stats_atk', true),
        'def' => (int) get_post_meta($post_id, '_hero_stats_def', true),
        'hp'  => (int) get_post_meta($post_id, '_hero_stats_hp', true),
    );
}
