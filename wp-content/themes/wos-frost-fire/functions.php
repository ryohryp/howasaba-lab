<?php
/**
 * WoS Frost & Fire functions and definitions
 *
 * @package WoS_Frost_Fire
 */

if ( ! defined( 'WOS_THEME_VERSION' ) ) {
    // Replace the version number of the theme on each release.
    define( 'WOS_THEME_VERSION', '1.0.0' );
}

if ( ! defined( 'WOS_TEXT_DOMAIN' ) ) {
    define( 'WOS_TEXT_DOMAIN', 'wos-frost-fire' );
}

/**
 * Sets up theme defaults and registers support for various WordPress features.
 */
function wos_frost_fire_setup() {
    // Add default posts and comments RSS feed links to head.
    add_theme_support( 'automatic-feed-links' );

    // Let WordPress manage the document title.
    add_theme_support( 'title-tag' );

    // Enable support for Post Thumbnails on posts and pages.
    add_theme_support( 'post-thumbnails' );

    // This theme uses wp_nav_menu() in one location.
    register_nav_menus(
        [
            'menu-1' => esc_html__( 'Primary', WOS_TEXT_DOMAIN ),
        ]
    );

    /*
     * Switch default core markup for search form, comment form, and comments
     * to output valid HTML5.
     */
    add_theme_support(
        'html5',
        [
            'search-form',
            'comment-form',
            'comment-list',
            'gallery',
            'caption',
            'style',
            'script',
        ]
    );
}
add_action( 'after_setup_theme', 'wos_frost_fire_setup' );

/**
 * Enqueue scripts and styles.
 */
function wos_frost_fire_scripts() {
    // Enqueue compiled CSS (from Vite build)
    // Adjust path based on your Vite output structure
    wp_enqueue_style( 'wos-frost-fire-style', get_template_directory_uri() . '/assets/css/app.css', [], WOS_THEME_VERSION );

    // Enqueue Alpine.js (CDN for development, local for production recommended)
    wp_enqueue_script( 'alpine-js', 'https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js', [], '3.x.x', true );

    // Enqueue Theme JS
    wp_enqueue_script( 'wos-frost-fire-script', get_template_directory_uri() . '/assets/js/app.js', ['alpine-js'], WOS_THEME_VERSION, true );
}
add_action( 'wp_enqueue_scripts', 'wos_frost_fire_scripts' );

/**
 * Load Custom Post Types and Classes.
 */
require get_template_directory() . '/inc/cpt-heroes.php';
require get_template_directory() . '/inc/cpt-events.php';
require get_template_directory() . '/inc/class-wos-hero-query.php';

/**
 * Custom Template Tags for this theme.
 */
// require get_template_directory() . '/inc/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
// require get_template_directory() . '/inc/template-functions.php';
