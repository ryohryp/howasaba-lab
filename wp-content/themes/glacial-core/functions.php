<?php
/**
 * GlacialCore Theme Functions
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

// Define Constants
define( 'GLACIAL_CORE_VERSION', '1.0.0' );
define( 'GLACIAL_CORE_DIR', get_template_directory() );
define( 'GLACIAL_CORE_URI', get_template_directory_uri() );

/**
 * Enqueue Scripts and Styles
 */
function glacial_core_scripts() {
    // Tailwind CSS (CDN for development/mockup as requested)
    wp_enqueue_script( 'tailwindcss', 'https://cdn.tailwindcss.com', array(), '3.3.0', false );

    // Alpine.js
    wp_enqueue_script( 'alpinejs', 'https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js', array(), '3.13.0', true );

    // Theme Styles
    wp_enqueue_style( 'glacial-core-style', get_stylesheet_uri(), array(), GLACIAL_CORE_VERSION );

    // Custom Scripts (Snow effect, interactions)
    wp_enqueue_script( 'glacial-core-main', GLACIAL_CORE_URI . '/assets/js/main.js', array('alpinejs'), GLACIAL_CORE_VERSION, true );
}
add_action( 'wp_enqueue_scripts', 'glacial_core_scripts' );

/**
 * Theme Setup
 */
function glacial_core_setup() {
    add_theme_support( 'title-tag' );
    add_theme_support( 'post-thumbnails' );
    add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption' ) );
}
add_action( 'after_setup_theme', 'glacial_core_setup' );

/**
 * Seed Hero Data (Development Helper)
 */
function glacial_seed_heroes() {
    // Only run if admin and triggered via specific GET param (e.g. ?seed_heroes=1)
    if ( ! is_admin() || ! isset($_GET['seed_heroes']) ) {
        return;
    }

    $heroes_data = [
        // Gen 1
        'Jeronimo' => ['gen' => 'Gen 1', 'class' => 'Infantry', 'rarity' => 'Mythic', 'stats' => [85, 90, 80], 'day' => 1],
        'Natalia'  => ['gen' => 'Gen 1', 'class' => 'Infantry', 'rarity' => 'Mythic', 'stats' => [88, 85, 82], 'day' => 1],
        'Molly'    => ['gen' => 'Gen 1', 'class' => 'Lancer',   'rarity' => 'Mythic', 'stats' => [92, 70, 75], 'day' => 1],
        'Zinman'   => ['gen' => 'Gen 1', 'class' => 'Marksman', 'rarity' => 'Mythic', 'stats' => [80, 75, 78], 'day' => 1],
        // Gen 2
        'Flint'    => ['gen' => 'Gen 2', 'class' => 'Infantry', 'rarity' => 'Mythic', 'stats' => [88, 95, 90], 'day' => 45],
        'Philly'   => ['gen' => 'Gen 2', 'class' => 'Lancer',   'rarity' => 'Mythic', 'stats' => [94, 72, 78], 'day' => 45],
        'Alonso'   => ['gen' => 'Gen 2', 'class' => 'Marksman', 'rarity' => 'Mythic', 'stats' => [95, 65, 70], 'day' => 45],
        // Gen 11
        'Rufus'    => ['gen' => 'Gen 11', 'class' => 'Marksman', 'rarity' => 'Mythic', 'stats' => [98, 70, 75], 'day' => 600],
        'Lloyd'    => ['gen' => 'Gen 11', 'class' => 'Lancer',   'rarity' => 'Mythic', 'stats' => [96, 75, 80], 'day' => 600],
        'Eleonora' => ['gen' => 'Gen 11', 'class' => 'Infantry', 'rarity' => 'Mythic', 'stats' => [92, 95, 95], 'day' => 600],
    ];

    foreach ($heroes_data as $name => $data) {
        $existing = get_page_by_title($name, OBJECT, 'hero');
        
        $post_data = array(
            'post_title'    => $name,
            'post_content'  => "Description for $name via seeder.",
            'post_status'   => 'publish',
            'post_type'     => 'hero',
        );

        if ($existing) {
            $post_data['ID'] = $existing->ID;
            $post_id = wp_update_post($post_data);
        } else {
            $post_id = wp_insert_post($post_data);
        }

        if ( ! is_wp_error($post_id) ) {
            // Set Taxonomies
            wp_set_object_terms($post_id, $data['gen'], 'hero_generation');
            wp_set_object_terms($post_id, strtolower($data['class']), 'hero_class');
            wp_set_object_terms($post_id, strtolower($data['rarity']), 'hero_rarity');

            // Set Meta
            update_post_meta($post_id, '_hero_unlock_day', $data['day']);
            update_post_meta($post_id, '_hero_stats_atk', $data['stats'][0]);
            update_post_meta($post_id, '_hero_stats_def', $data['stats'][1]);
            update_post_meta($post_id, '_hero_stats_hp', $data['stats'][2]);
        }
    }
    
    // Add admin notice
    add_action('admin_notices', function() {
        echo '<div class="notice notice-success"><p>Heroes Seeded Successfully!</p></div>';
    });
}
add_action('init', 'glacial_seed_heroes');
