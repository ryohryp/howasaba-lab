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
 * Register Custom Post Types and Fields
 */
require_once GLACIAL_CORE_DIR . '/inc/cpt-hero.php';
require_once GLACIAL_CORE_DIR . '/inc/cpt-event.php';
