<?php
/**
 * Register Hero Custom Post Type
 */
function glacial_register_hero_cpt() {
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
        'supports'            => array( 'title', 'editor', 'thumbnail' ),
        'rewrite'             => array( 'slug' => 'hero' ),
        'show_in_rest'        => true,
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

    $hero_gen = get_post_meta( $post->ID, '_hero_gen', true );
    $hero_type = get_post_meta( $post->ID, '_hero_type', true );
    $max_power = get_post_meta( $post->ID, '_max_power', true );
    $exploration_skill = get_post_meta( $post->ID, '_exploration_skill', true );
    $expedition_skill = get_post_meta( $post->ID, '_expedition_skill', true );
    $recommended_widgets = get_post_meta( $post->ID, '_recommended_widgets', true );

    ?>
    <style>
        .glacial-meta-row { margin-bottom: 10px; }
        .glacial-meta-row label { display: block; font-weight: bold; margin-bottom: 5px; }
        .glacial-meta-row input[type="text"], .glacial-meta-row textarea, .glacial-meta-row select { width: 100%; }
    </style>
    <div class="glacial-meta-row">
        <label for="hero_gen">Generation (e.g., "Gen 1")</label>
        <input type="text" id="hero_gen" name="hero_gen" value="<?php echo esc_attr( $hero_gen ); ?>" />
    </div>
    <div class="glacial-meta-row">
        <label for="hero_type">Hero Type</label>
        <select id="hero_type" name="hero_type">
            <option value="Infantry" <?php selected( $hero_type, 'Infantry' ); ?>>Infantry</option>
            <option value="Lancer" <?php selected( $hero_type, 'Lancer' ); ?>>Lancer</option>
            <option value="Marksman" <?php selected( $hero_type, 'Marksman' ); ?>>Marksman</option>
        </select>
    </div>
    <div class="glacial-meta-row">
        <label for="max_power">Max Power</label>
        <input type="text" id="max_power" name="max_power" value="<?php echo esc_attr( $max_power ); ?>" />
    </div>
    <div class="glacial-meta-row">
        <label for="exploration_skill">Exploration Skill</label>
        <textarea id="exploration_skill" name="exploration_skill" rows="3"><?php echo esc_textarea( $exploration_skill ); ?></textarea>
    </div>
    <div class="glacial-meta-row">
        <label for="expedition_skill">Expedition Skill</label>
        <textarea id="expedition_skill" name="expedition_skill" rows="3"><?php echo esc_textarea( $expedition_skill ); ?></textarea>
    </div>
    <div class="glacial-meta-row">
        <label for="recommended_widgets">Recommended Widgets (Level)</label>
        <input type="text" id="recommended_widgets" name="recommended_widgets" value="<?php echo esc_attr( $recommended_widgets ); ?>" />
    </div>
    <?php
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

    $fields = array( 'hero_gen', 'hero_type', 'max_power', 'exploration_skill', 'expedition_skill', 'recommended_widgets' );

    foreach ( $fields as $field ) {
        if ( isset( $_POST[ $field ] ) ) {
            update_post_meta( $post_id, '_' . $field, sanitize_text_field( $_POST[ $field ] ) );
        }
    }
}
