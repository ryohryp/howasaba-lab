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

// Vite Asset Loader
require get_template_directory() . '/inc/class-vite-asset-loader.php';

/**
 * Enqueue scripts and styles.
 */
function wos_frost_fire_scripts() {
    $vite = new Vite_Asset_Loader();

    // Enqueue Alpine.js (External)
    wp_enqueue_script( 'alpine-js', 'https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js', [], '3.x.x', true );

    // Enqueue App entry point (handled by manifest/vite)
    // Note: 'assets/js/app.js' imports the CSS in main app usually, 
    // but checks in loader will handle CSS extraction in production.
    $vite->enqueue( 'wos-frost-fire-app', 'assets/js/app.js', ['alpine-js'] );
    
    // Explicitly enqueue CSS if it's a separate entry or solely for fallback
    // In standard Vite + plugin setup, JS entry imports CSS. 
    // Our manifest shows 'assets/js/app.js' has 'css' property, so enqueueing JS will handle CSS in production.
    // However, if we have a separate CSS entry in vite.config.js (we do: 'style'), we can check that.
    // 'assets/css/app.css' is defined in manifest.
    $vite->enqueue( 'wos-frost-fire-style', 'assets/css/app.css', [] );
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
