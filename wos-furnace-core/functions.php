<?php
/**
 * wos-furnace-core functions and definitions
 *
 * @package wos-furnace-core
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Enqueue scripts and styles.
 */
function wos_furnace_core_scripts() {
	// Enqueue the main stylesheet.
	wp_enqueue_style( 'wos-furnace-core-style', get_stylesheet_uri(), array(), '1.0.0' );

    // Tailwind CSS via CDN is loaded in header.php for earlier execution in <head> 
    // to avoid FOUC as much as possible with CDN, though normally enqueue is preferred.
    // For strict WordPress standards we can enqueue it here, but putting the config script 
    // right after is easier in the header template for this specific "no-build" setup.
}
add_action( 'wp_enqueue_scripts', 'wos_furnace_core_scripts' );

/**
 * Theme Support
 */
function wos_furnace_core_setup() {
	/*
	 * Let WordPress manage the document title.
	 * By adding theme support, we declare that this theme does not use a
	 * hard-coded <title> tag in the document head, and expects WordPress to
	 * provide it for us.
	 */
	add_theme_support( 'title-tag' );

	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 */
	add_theme_support( 'post-thumbnails' );

	// Register Navigation Menus
	register_nav_menus(
		array(
			'global-nav' => esc_html__( 'Global Navigation', 'wos-furnace-core' ),
		)
	);
}
add_action( 'after_setup_theme', 'wos_furnace_core_setup' );
