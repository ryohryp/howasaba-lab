<?php
/**
 * WoS Frost & Fire functions and definitions
 *
 * @package WoS_Frost_Fire
 */

if ( ! defined( 'WOS_THEME_VERSION' ) ) {
    define( 'WOS_THEME_VERSION', '1.0.2' );
}

if ( ! defined( 'WOS_TEXT_DOMAIN' ) ) {
    define( 'WOS_TEXT_DOMAIN', 'wos-frost-fire' );
}

/**
 * Sets up theme defaults and registers support for various WordPress features.
 */
function wos_frost_fire_setup() {
    add_theme_support( 'automatic-feed-links' );
    add_theme_support( 'title-tag' );
    add_theme_support( 'post-thumbnails' );

    register_nav_menus(
        [
            'menu-1' => esc_html__( 'Primary', WOS_TEXT_DOMAIN ),
        ]
    );

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

    load_theme_textdomain( WOS_TEXT_DOMAIN, get_template_directory() . '/languages' );
}
add_action( 'after_setup_theme', 'wos_frost_fire_setup' );

// Vite Asset Loader
require get_template_directory() . '/inc/class-vite-asset-loader.php';

/**
 * Enqueue scripts and styles.
 */
function wos_frost_fire_scripts() {
    $vite = new Vite_Asset_Loader();

    $vite->enqueue( 'wos-frost-fire-app', 'assets/js/app.js', [] );
    $vite->enqueue( 'wos-frost-fire-style', 'assets/css/app.css', [] );

    wp_enqueue_style( 'wos-survival-radar-style', get_template_directory_uri() . '/assets/css/gift-code-radar.css', array(), WOS_THEME_VERSION );
    wp_register_style( 'wos-tier-list-style', get_template_directory_uri() . '/assets/css/tier-list.css', array(), WOS_THEME_VERSION );
}
add_action( 'wp_enqueue_scripts', 'wos_frost_fire_scripts' );

/**
 * Load Custom Post Types and Classes.
 */
require get_template_directory() . '/inc/cpt-heroes.php';
require get_template_directory() . '/inc/cpt-events.php';
require get_template_directory() . '/inc/cpt-gift-codes.php';
require get_template_directory() . '/inc/class-wos-hero-query.php';
require_once get_template_directory() . '/inc/api-endpoints.php';
require get_template_directory() . '/inc/shortcode-gift-codes.php';
require get_template_directory() . '/inc/acf-tier-list.php';
require get_template_directory() . '/inc/shortcode-tier-list.php';

/**
 * Data Seeders (Development Helpers)
 */
require get_template_directory() . '/inc/seeders.php';

/**
 * Language Switcher Helper
 */
function wos_get_language_url( $lang ) {
    return add_query_arg( 'lang', $lang );
}

/**
 * Handle Language Setting
 */
function wos_handle_language_setting() {
    if ( isset( $_GET['lang'] ) ) {
        $lang = sanitize_text_field( $_GET['lang'] );
        if ( in_array( $lang, ['ja', 'en'] ) ) {
            setcookie( 'wos_lang', $lang, time() + 365 * 24 * 60 * 60, '/' );
            $_COOKIE['wos_lang'] = $lang;
        }
    }
}
add_action( 'init', 'wos_handle_language_setting' );

/**
 * Filter Locale based on Cookie/Param
 */
function wos_filter_locale( $locale ) {
    if ( isset( $_GET['lang'] ) ) {
        $lang = sanitize_text_field( $_GET['lang'] );
    } elseif ( isset( $_COOKIE['wos_lang'] ) ) {
        $lang = sanitize_text_field( $_COOKIE['wos_lang'] );
    } else {
        return $locale;
    }

    if ( $lang === 'ja' ) {
        return 'ja';
    } elseif ( $lang === 'en' ) {
        return 'en_US';
    }

    return $locale;
}
add_filter( 'locale', 'wos_filter_locale' );
