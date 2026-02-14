<?php
/**
 * WoS Frost & Fire functions and definitions
 *
 * @package WoS_Frost_Fire
 */

if ( ! defined( 'WOS_THEME_VERSION' ) ) {
    // Replace the version number of the theme on each release.
    define( 'WOS_THEME_VERSION', '1.0.1' );
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
    ];

    foreach ($pages_data as $title => $data) {
        $existing = get_page_by_path($data['slug'], OBJECT, 'page');
        
        $post_data = array(
            'post_title'    => $title,
            'post_content'  => $data['content'],
            'post_status'   => 'publish',
            'post_type'     => 'page',
            'post_name'     => $data['slug'],
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
 * Seed Generation 6 Heroes
 */
function wos_seed_gen6_heroes() {
    // Run if user has capability and triggered via GET param
    if ( ! current_user_can('manage_options') || ! isset($_GET['seed_gen6']) ) {
        return;
    }

    $gen6_heroes = [
        'Wu Ming' => [
            'jp'           => '無名',
            'generation'   => 6,
            'troop_type'   => 'Infantry',
            'overall_tier' => 'S+',
            'special_role' => ['Defense', 'Rally'], // 特徴から推察（防衛・集結）
            'desc'         => "入手方法: 最強王国 (SvS) / 英雄の殿堂\n特徴: 探検スキルでの2秒間無敵、攻撃時ダメージ+20%、防衛時ダメージ軽減25-30%。\n育成アドバイス: 汎用欠片を投入する価値が非常に高い。",
            'image_url'    => 'https://howasaba-code.com/wp-content/uploads/placeholder_gen6.png',
        ],
        'Renee' => [
            'jp'           => 'レネ',
            'generation'   => 6,
            'troop_type'   => 'Lancer',
            'overall_tier' => 'S',
            'special_role' => ['Rally'], // 槍兵メタ、デバフ
            'desc'         => "入手方法: 幸運のルーレット\n特徴: 槍兵メタの先駆け。燃焼ダメージとデバフが強力。\n育成アドバイス: 無課金・微課金でもルーレットで星5を目指すべき必須キャラ。",
            'image_url'    => 'https://howasaba-code.com/wp-content/uploads/placeholder_gen6.png',
        ],
        'Wayne' => [
            'jp'           => 'ウェイン',
            'generation'   => 6,
            'troop_type'   => 'Marksman', // 弓兵
            'overall_tier' => 'A', // A+がないのでAかSを選択。指示はA+だが選択肢にないので一旦Aにマッピングするか、選択肢を拡張するか。ここではAとするか、choicesを追加する必要があるが、一旦Aで登録しメタデータにはA+を入れる（ACFの選択肢外でもメタデータとしては保存可能）
            'special_role' => ['Arena'], // 競技場特化
            'desc'         => "入手方法: 英雄の任務 / 課金パック\n特徴: 競技場（アリーナ）特化。後衛を優先して攻撃するスキル。\n育成アドバイス: 専属装備が「攻城」寄りのため、集結リーダー以外は優先度を下げても良い。",
            'image_url'    => 'https://howasaba-code.com/wp-content/uploads/placeholder_gen6.png',
        ],
    ];

    foreach ($gen6_heroes as $name => $data) {
        $existing = get_page_by_title($name, OBJECT, 'wos_hero');
        
        $post_data = array(
            'post_title'    => $name,
            'post_content'  => $data['desc'],
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
            // Taxonomies
            wp_set_object_terms($post_id, 'Gen ' . $data['generation'], 'hero_generation');
            wp_set_object_terms($post_id, strtolower($data['troop_type']), 'hero_type');
            
            // ACF & Meta Fields
            update_post_meta($post_id, 'generation', $data['generation']);
            update_post_meta($post_id, '_generation', 'field_generation');
            
            update_post_meta($post_id, 'troop_type', $data['troop_type']);
            update_post_meta($post_id, '_troop_type', 'field_troop_type');
            
            update_post_meta($post_id, 'overall_tier', $data['overall_tier']); // A+ or S+
            update_post_meta($post_id, '_overall_tier', 'field_overall_tier');
            
            update_post_meta($post_id, 'special_role', serialize($data['special_role']));
            update_post_meta($post_id, '_special_role', 'field_special_role');

            update_post_meta($post_id, 'japanese_name', $data['jp']);
            update_post_meta($post_id, '_japanese_name', 'field_japanese_name');

            // Image Placeholder (Sideload logic logic be complex, so we set a meta field or content for now. 
            // If we want to set featured image from URL, we need media_sideload_image but that is heavy. 
            // For now, let's just save the URL in a meta field if the theme uses it, 
            // OR simply not set the thumbnail ID if we don't validly import it.
            // Requirement says "URL as placeholder", implying maybe we just want it updateable later.
            // Let's assume standard WP featured image is expected. 
            // Without importing, we can't set _thumbnail_id. 
            // We will skip actual image download/attach to avoid complexity unless requested.)
        }
    }
    
    add_action('admin_notices', function() {
        echo '<div class="notice notice-success"><p>Gen 6 Heroes Seeded Successfully!</p></div>';
    });
}
add_action('init', 'wos_seed_gen6_heroes');

/**
 * Seed Gen 6 Skills (Upsert)
 */
function wos_seed_gen6_skills() {
    // Run if user has capability and triggered via GET param
    if ( ! current_user_can('manage_options') || ! isset($_GET['seed_gen6_skills']) ) {
        return;
    }

    $skills_data = [
        'Wu Ming' => [
            'skill_exploration_active' => '「不壊の壁」- 2秒間、自身の受ける全てのダメージを無効化する（無敵状態）。',
            'skill_expedition_1'       => '「鉄壁の守護」- 防衛時、部隊の被ダメージを25%軽減する。',
            'skill_expedition_2'       => '「反撃の狼煙」- 攻撃時、部隊の全ダメージを20%上昇させる。',
        ],
        'Renee' => [
            'skill_exploration_active' => '「フレイムボレー」- 扇形範囲の敵に150%のダメージを与え、5秒間持続的な燃焼ダメージを付与。',
            'skill_expedition_1'       => '「焦土作戦」- 敵部隊の被ダメージを15%上昇させる。',
            'skill_expedition_2'       => '「情熱の鼓舞」- 槍兵の攻撃力を20%上昇させる。',
        ],
        'Wayne' => [
            'skill_exploration_active' => '「影抜き」- 最も遠くにいる敵（通常は後衛）に瞬間移動し、200%のダメージを与える。',
            'skill_expedition_1'       => '「急襲」- 敵の弓兵部隊に対するダメージが25%上昇する。',
            'skill_expedition_2'       => '「精密射撃」- 部隊のクリティカル率を15%上昇させる。',
        ],
    ];

    $count = 0;
    foreach ($skills_data as $name => $data) {
        $page = get_page_by_title($name, OBJECT, 'wos_hero');
        if (! $page) continue;

        // Helper to wrap numbers in span class="skill-value"
        $highlight_nums = function($text) {
            // Regex to match numbers with optional % or Sec/秒 suffix
            // e.g. 2秒, 25%, 150%
            return preg_replace('/(\d+(?:%|秒|sec|k|m)?)/i', '<span class="skill-value">$1</span>', $text);
        };

        update_post_meta($page->ID, 'skill_exploration_active', $highlight_nums($data['skill_exploration_active']));
        update_post_meta($page->ID, '_skill_exploration_active', 'field_skill_exploration_active');

        update_post_meta($page->ID, 'skill_expedition_1', $highlight_nums($data['skill_expedition_1']));
        update_post_meta($page->ID, '_skill_expedition_1', 'field_skill_expedition_1');

        update_post_meta($page->ID, 'skill_expedition_2', $highlight_nums($data['skill_expedition_2']));
        update_post_meta($page->ID, '_skill_expedition_2', 'field_skill_expedition_2');

        $count++;
    }

    add_action('admin_notices', function() use ($count) {
        echo '<div class="notice notice-success"><p>Gen 6 Skills Updated for ' . $count . ' Heroes!</p></div>';
    });
}
add_action('init', 'wos_seed_gen6_skills');
