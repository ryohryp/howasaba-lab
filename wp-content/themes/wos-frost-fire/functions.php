<?php
/**
 * WOS Frost & Fire functions and definitions
 *
 * @package WOS_Frost_Fire
 */

if ( ! defined( 'WOS_VERSION' ) ) {
	// Version for cache busting
	define( 'WOS_VERSION', '1.0.0' );
}

/**
 * Enqueue scripts and styles.
 */
function wos_scripts() {
    // Vite integration
    $vite_env = 'production'; // Change to 'development' for local dev with HMR
    $dist_path = get_template_directory_uri() . '/dist';
    $dist_dir = get_template_directory() . '/dist';

    if ( defined( 'WP_ENVIRONMENT_TYPE' ) && 'local' === WP_ENVIRONMENT_TYPE ) {
        $vite_env = 'development';
    }

    if ( $vite_env === 'development' ) {
        // Vite Dev Server
        wp_enqueue_script( 'wos-vite-client', 'http://localhost:5173/@vite/client', array(), null, true );
        wp_enqueue_script( 'wos-app', 'http://localhost:5173/assets/js/app.js', array('wos-vite-client'), null, true );
        // CSS is injected by Vite in dev mode
    } else {
        // Production build
        $manifest_path = $dist_dir . '/.vite/manifest.json';
        if ( file_exists( $manifest_path ) ) {
            $manifest = json_decode( file_get_contents( $manifest_path ), true );
            
            // CSS
            if ( isset( $manifest['assets/js/app.js']['css'] ) ) {
                foreach ( $manifest['assets/js/app.js']['css'] as $css_file ) {
                    wp_enqueue_style( 'wos-app-style', $dist_path . '/' . $css_file, array(), WOS_VERSION );
                }
            }
            
            // JS
            if ( isset( $manifest['assets/js/app.js']['file'] ) ) {
                wp_enqueue_script( 'wos-app', $dist_path . '/' . $manifest['assets/js/app.js']['file'], array(), WOS_VERSION, true );
            }
        }
    }
}
add_action( 'wp_enqueue_scripts', 'wos_scripts' );

/**
 * Theme Support
 */
function wos_setup() {
    add_theme_support( 'title-tag' );
    add_theme_support( 'post-thumbnails' );
    add_theme_support( 'html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
        'style',
        'script',
    ) );
    
    register_nav_menus( array(
        'menu-1' => esc_html__( 'Primary', 'wos-frost-fire' ),
    ) );
}
add_action( 'after_setup_theme', 'wos_setup' );

/**
 * Load Custom Post Types & Taxonomies
 */
require get_template_directory() . '/inc/cpt.php';

