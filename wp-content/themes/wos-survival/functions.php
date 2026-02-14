<?php
/**
 * WOS Survival Theme functions and definitions
 *
 * @package WOS_Survival
 */

if ( ! defined( 'WOS_SURVIVAL_VERSION' ) ) {
	define( 'WOS_SURVIVAL_VERSION', '1.0.0' );
}

/**
 * Sets up theme defaults and registers support for various WordPress features.
 */
function wos_survival_setup() {
	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	// Let WordPress manage the document title.
	add_theme_support( 'title-tag' );

	// Enable support for Post Thumbnails on posts and pages.
	add_theme_support( 'post-thumbnails' );

	// Switch default core markup for search form, comment form, and comments to output valid HTML5.
	add_theme_support(
		'html5',
		array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
			'style',
			'script',
		)
	);
}
add_action( 'after_setup_theme', 'wos_survival_setup' );

/**
 * Enqueue scripts and styles.
 */
function wos_survival_scripts() {
	wp_enqueue_style( 'wos-survival-style', get_stylesheet_uri(), array(), WOS_SURVIVAL_VERSION );
	
    // Custom styles for Frost & Fire aesthetics
    wp_enqueue_style( 'wos-survival-custom-style', get_template_directory_uri() . '/assets/css/style.css', array(), WOS_SURVIVAL_VERSION );

    // Snowstorm animation
    wp_enqueue_script( 'wos-survival-snowstorm', get_template_directory_uri() . '/assets/js/snowstorm.js', array(), WOS_SURVIVAL_VERSION, true );

    // Gift Code Radar Styles
    wp_enqueue_style( 'wos-survival-radar-style', get_template_directory_uri() . '/assets/css/gift-code-radar.css', array(), WOS_SURVIVAL_VERSION );

}
add_action( 'wp_enqueue_scripts', 'wos_survival_scripts' );

/**
 * Load Custom Post Types and Queries.
 */
require get_template_directory() . '/inc/custom-post-types.php';
require get_template_directory() . '/inc/custom-queries.php';

