<?php
/**
 * WoS Frost & Fire functions and definitions
 *
 * @package WoS_Frost_Fire
 */

if ( ! defined( 'WOS_THEME_VERSION' ) ) {
    // Replace the version number of the theme on each release.
    define( 'WOS_THEME_VERSION', '1.0.2' );
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

    // Load theme textdomain.
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

    // Enqueue Alpine.js (External) - REMOVED: Bundled in app.js
    // wp_enqueue_script( 'alpine-js', 'https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js', [], '3.x.x', true );

    // Enqueue App entry point (handled by manifest/vite)
    // Note: 'assets/js/app.js' imports the CSS in main app usually, 
    // but checks in loader will handle CSS extraction in production.
    $vite->enqueue( 'wos-frost-fire-app', 'assets/js/app.js', [] );
    
    // Explicitly enqueue CSS if it's a separate entry or solely for fallback
    // In standard Vite + plugin setup, JS entry imports CSS. 
    // Our manifest shows 'assets/js/app.js' has 'css' property, so enqueueing JS will handle CSS in production.
    // However, if we have a separate CSS entry in vite.config.js (we do: 'style'), we can check that.
    // 'assets/css/app.css' is defined in manifest.
    $vite->enqueue( 'wos-frost-fire-style', 'assets/css/app.css', [] );
    // Gift Code Radar Styles
    wp_enqueue_style( 'wos-survival-radar-style', get_template_directory_uri() . '/assets/css/gift-code-radar.css', array(), WOS_THEME_VERSION );

    // Tier List Styles
    wp_register_style( 'wos-tier-list-style', get_template_directory_uri() . '/assets/css/tier-list.css', array(), WOS_THEME_VERSION );
}
add_action( 'wp_enqueue_scripts', 'wos_frost_fire_scripts' );

/**
 * Load Custom Post Types and Classes.
 */
require get_template_directory() . '/inc/cpt-heroes.php';
require get_template_directory() . '/inc/cpt-events.php';
require get_template_directory() . '/inc/cpt-gift-codes.php'; // New Gift Code CPT
require get_template_directory() . '/inc/class-wos-hero-query.php';
require_once get_template_directory() . '/inc/api-endpoints.php'; // New API Endpoints
require get_template_directory() . '/inc/shortcode-gift-codes.php'; // New Shortcode
require get_template_directory() . '/inc/acf-tier-list.php'; // Tier List ACF Fields
require get_template_directory() . '/inc/shortcode-tier-list.php'; // Tier List Shortcode

/**
 * Custom Template Tags for this theme.
 */
// require get_template_directory() . '/inc/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
// require get_template_directory() . '/inc/template-functions.php';
/**
 * Seed Hero Data (Development Helper)
 */
function wos_seed_heroes() {
    // Run if user has capability and triggered via GET param
    if ( ! current_user_can('manage_options') || ! isset($_GET['seed_heroes']) ) {
        return;
    }

    $heroes_data = [
        // Gen 1
        'Jeronimo' => ['jp' => 'ジェロニモ', 'gen' => 'Gen 1', 'type' => 'Infantry', 'rarity' => 'SSR', 'stats' => [85, 90, 80], 'day' => 1],
        'Natalia'  => ['jp' => 'ナタリア',   'gen' => 'Gen 1', 'type' => 'Infantry', 'rarity' => 'SSR', 'stats' => [88, 85, 82], 'day' => 1],
        'Molly'    => ['jp' => 'モリー',     'gen' => 'Gen 1', 'type' => 'Lancer',   'rarity' => 'SSR', 'stats' => [92, 70, 75], 'day' => 1],
        'Zinman'   => ['jp' => 'ジンマン',   'gen' => 'Gen 1', 'type' => 'Marksman', 'rarity' => 'SSR', 'stats' => [80, 75, 78], 'day' => 1],
        // Gen 2
        'Flint'    => ['jp' => 'フリント',   'gen' => 'Gen 2', 'type' => 'Infantry', 'rarity' => 'SSR', 'stats' => [88, 95, 90], 'day' => 45],
        'Philly'   => ['jp' => 'フィリー',   'gen' => 'Gen 2', 'type' => 'Lancer',   'rarity' => 'SSR', 'stats' => [94, 72, 78], 'day' => 45],
        'Alonso'   => ['jp' => 'アロンソ',   'gen' => 'Gen 2', 'type' => 'Marksman', 'rarity' => 'SSR', 'stats' => [95, 65, 70], 'day' => 45],
        // Gen 11
        'Rufus'    => ['jp' => 'ルーファス', 'gen' => 'Gen 11', 'type' => 'Marksman', 'rarity' => 'SSR', 'stats' => [98, 70, 75], 'day' => 600],
        'Lloyd'    => ['jp' => 'ロイド',     'gen' => 'Gen 11', 'type' => 'Lancer',   'rarity' => 'SSR', 'stats' => [96, 75, 80], 'day' => 600],
        'Eleonora' => ['jp' => 'エレオノーラ', 'gen' => 'Gen 11', 'type' => 'Infantry', 'rarity' => 'SSR', 'stats' => [92, 95, 95], 'day' => 600],
    ];

    foreach ($heroes_data as $name => $data) {
        $existing = get_page_by_title($name, OBJECT, 'wos_hero');
        
        $post_data = array(
            'post_title'    => $name,
            'post_content'  => "Description for $name via seeder.",
            'post_status'   => 'publish',
            'post_type'     => 'wos_hero',
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
            wp_set_object_terms($post_id, strtolower($data['type']), 'hero_type');
            wp_set_object_terms($post_id, $data['rarity'], 'hero_rarity');

            // Set Meta
            update_post_meta($post_id, '_hero_unlock_day', $data['day']);
            update_post_meta($post_id, '_hero_stats_atk', $data['stats'][0]);
            update_post_meta($post_id, '_hero_stats_def', $data['stats'][1]);
            update_post_meta($post_id, '_hero_stats_hp', $data['stats'][2]);

            // Set Tier List Meta (ACF / Fallback)
            // Extract numeric gen
            preg_match('/\d+/', $data['gen'], $matches);
            $gen_num = $matches[0] ?? 1;
            
            update_post_meta($post_id, 'generation', $gen_num);
            update_post_meta($post_id, '_generation', 'field_generation'); // ACF key
            
            update_post_meta($post_id, 'troop_type', $data['type']);
            update_post_meta($post_id, '_troop_type', 'field_troop_type');

            // Random Tier for demo if not set? Or deterministically set for these seed heroes
            $tiers = ['S+', 'S', 'A', 'B', 'C'];
            $tier = $tiers[array_rand($tiers)];
            // Set specific tiers for specific heroes for better testing
            if ($name === 'Jeronimo') $tier = 'S+';
            if ($name === 'Natalia') $tier = 'S';
            if ($name === 'Molly') $tier = 'A';
            if ($name === 'Zinman') $tier = 'B';
            
            update_post_meta($post_id, 'overall_tier', $tier);
            update_post_meta($post_id, '_overall_tier', 'field_overall_tier');

            $roles = ['Rally', 'Defense', 'Arena'];
            $hero_roles = [$roles[array_rand($roles)]];
            update_post_meta($post_id, 'special_role', serialize($hero_roles)); // ACF stores array as serialized
            update_post_meta($post_id, '_special_role', 'field_special_role');

            // Japanese Name
            update_post_meta($post_id, 'japanese_name', $data['jp']);
            update_post_meta($post_id, '_japanese_name', 'field_japanese_name');
        }
    }
    
    // Add admin notice
    add_action('admin_notices', function() {
        echo '<div class="notice notice-success"><p>Heroes Seeded Successfully (Theme: WoS Frost & Fire)!</p></div>';
    });
}
add_action('init', 'wos_seed_heroes');

/**
 * Seed Event Data (Development Helper)
 */
function wos_seed_events() {
    // Run if user has capability and triggered via GET param
    if ( ! current_user_can('manage_options') || ! isset($_GET['seed_events']) ) {
        return;
    }

    $today = date('Y-m-d');
    $future = date('Y-m-d', strtotime('+30 days')); // Further out for upcoming
    // Make sure we have dates that differ
    $upcoming_date = date('Y-m-d', strtotime('+5 days'));
    $past_date = date('Y-m-d', strtotime('-10 days'));

    $events_data = [
        'Sunfire Castle Battle' => ['start' => $upcoming_date, 'duration' => '1 Day',  'server_age' => 90, 'desc' => 'Prepare for the ultimate battle for the Sunfire Castle!'], // Upcoming
        'Gina\'s Revenge'       => ['start' => $today,         'duration' => '3 Days', 'server_age' => 10, 'desc' => 'Hunt the beasts and earn exclusive rewards.'],       // Active
        'Bear Hunt'             => ['start' => $past_date,     'duration' => '2 Days', 'server_age' => 5,  'desc' => 'Join your alliance to take down the Polar Terror.'],   // Past
        'Crazy Joe'             => ['start' => $future,        'duration' => '1 Day',  'server_age' => 15, 'desc' => 'Defend your city against waves of bandits.'],          // Upcoming
        'Foundry Battle'        => ['start' => $today,         'duration' => '1 Day',  'server_age' => 30, 'desc' => 'Alliance vs Alliance battle.'],                        // Active
    ];

    foreach ($events_data as $name => $data) {
        $existing = get_page_by_title($name, OBJECT, 'wos_event');
        
        $post_data = array(
            'post_title'    => $name,
            'post_content'  => $data['desc'],
            'post_status'   => 'publish',
            'post_type'     => 'wos_event',
        );

        if ($existing) {
            $post_data['ID'] = $existing->ID;
            $post_id = wp_update_post($post_data);
        } else {
            $post_id = wp_insert_post($post_data);
        }

        if ( ! is_wp_error($post_id) ) {
            // Set Meta
            update_post_meta($post_id, '_event_start_date', $data['start']);
            update_post_meta($post_id, '_event_duration', $data['duration']);
            update_post_meta($post_id, '_server_age_requirement', $data['server_age']);
        }
    }
    
    // Add admin notice
    add_action('admin_notices', function() {
        echo '<div class="notice notice-success"><p>Events Seeded Successfully (Theme: WoS Frost & Fire)!</p></div>';
    });
}
add_action('init', 'wos_seed_events');

/**
 * Seed Pages (Development Helper)
 */
function wos_seed_pages() {
    // Run if user has capability and triggered via GET param
    if ( ! current_user_can('manage_options') || ! isset($_GET['seed_pages']) ) {
        return;
    }

    $pages_data = [
        '最強英雄Tierリスト' => [
            'content' => '<!-- wp:paragraph -->
<p>ホワイトアウト・サバイバルの全世代・英雄Tierリストです。最強の英雄を見つけて、戦略を有利に進めましょう。</p>
<!-- /wp:paragraph -->

<!-- wp:shortcode -->
[wos_tier_list]
<!-- /wp:shortcode -->',
            'slug'    => 'tier-list',
        ],
        'Strategy Guides' => [
            'content' => '',
            'slug'    => 'guide',
            'template'=> 'page-guide.php',
        ],
    ];

    foreach ($pages_data as $title => $data) {
        $existing = get_page_by_path($data['slug'], OBJECT, 'page');
        
        $post_data = array(
            'post_title'    => $title,
            'post_content'  => $data['content'],
            'post_status'   => 'publish',
            'post_type'     => 'page',
            'post_name'     => $data['slug'],
            'page_template' => $data['template'] ?? '',
        );

        if ($existing) {
            $post_data['ID'] = $existing->ID;
            wp_update_post($post_data);
        } else {
            wp_insert_post($post_data);
        }
    }
    
    // Add admin notice
    add_action('admin_notices', function() {
        echo '<div class="notice notice-success"><p>Pages Seeded Successfully (Theme: WoS Frost & Fire)!</p></div>';
    });
}
add_action('init', 'wos_seed_pages');

/**
 * Seed Gen 6 Heroes
 */
require get_template_directory() . '/inc/seed-gen6.php';

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
            $_COOKIE['wos_lang'] = $lang; // Update current request
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

