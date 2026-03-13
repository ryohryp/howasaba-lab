<?php
/**
 * Data Seeders (Development Helpers)
 *
 * 管理画面で ?seed_xxx=1 のパラメータを付けてアクセスすると実行される。
 * 本番環境では manage_options 権限 + GET パラメータが必要なため影響なし。
 *
 * @package WoS_Frost_Fire
 */

/**
 * Seed Hero Data
 * Usage: /wp-admin/?seed_heroes=1
 */
function wos_seed_heroes() {
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
            // Taxonomies
            wp_set_object_terms($post_id, $data['gen'], 'hero_generation');
            wp_set_object_terms($post_id, strtolower($data['type']), 'hero_type');
            wp_set_object_terms($post_id, $data['rarity'], 'hero_rarity');

            // Stats
            update_post_meta($post_id, '_hero_unlock_day', $data['day']);
            update_post_meta($post_id, '_hero_stats_atk', $data['stats'][0]);
            update_post_meta($post_id, '_hero_stats_def', $data['stats'][1]);
            update_post_meta($post_id, '_hero_stats_hp', $data['stats'][2]);

            // Tier List Meta (ACF)
            preg_match('/\d+/', $data['gen'], $matches);
            $gen_num = $matches[0] ?? 1;
            
            update_post_meta($post_id, 'generation', $gen_num);
            update_post_meta($post_id, '_generation', 'field_generation');
            update_post_meta($post_id, 'troop_type', $data['type']);
            update_post_meta($post_id, '_troop_type', 'field_troop_type');

            // Tier
            $tiers = ['S+', 'S', 'A', 'B', 'C'];
            $tier = $tiers[array_rand($tiers)];
            if ($name === 'Jeronimo') $tier = 'S+';
            if ($name === 'Natalia') $tier = 'S';
            if ($name === 'Molly') $tier = 'A';
            if ($name === 'Zinman') $tier = 'B';
            
            update_post_meta($post_id, 'overall_tier', $tier);
            update_post_meta($post_id, '_overall_tier', 'field_overall_tier');

            $roles = ['Rally', 'Defense', 'Arena'];
            $hero_roles = [$roles[array_rand($roles)]];
            update_post_meta($post_id, 'special_role', serialize($hero_roles));
            update_post_meta($post_id, '_special_role', 'field_special_role');

            // Japanese Name
            update_post_meta($post_id, 'japanese_name', $data['jp']);
            update_post_meta($post_id, '_japanese_name', 'field_japanese_name');
        }
    }
    
    add_action('admin_notices', function() {
        echo '<div class="notice notice-success"><p>Heroes Seeded Successfully!</p></div>';
    });
}
add_action('init', 'wos_seed_heroes');

/**
 * Seed Event Data
 * Usage: /wp-admin/?seed_events=1 or /wp-admin/?seed_year_beast=1
 */
function wos_seed_events() {
    if ( ! current_user_can('manage_options') ) {
        return;
    }

    if ( isset($_GET['seed_events']) ) {
        $today = date('Y-m-d');
        $future = date('Y-m-d', strtotime('+30 days'));
        $upcoming_date = date('Y-m-d', strtotime('+5 days'));
        $past_date = date('Y-m-d', strtotime('-10 days'));

        $events_data = [
            'Sunfire Castle Battle' => ['start' => $upcoming_date, 'duration' => '1 Day',  'server_age' => 90, 'desc' => 'Prepare for the ultimate battle for the Sunfire Castle!'],
            'Gina\'s Revenge'       => ['start' => $today,         'duration' => '3 Days', 'server_age' => 10, 'desc' => 'Hunt the beasts and earn exclusive rewards.'],
            'Bear Hunt'             => ['start' => $past_date,     'duration' => '2 Days', 'server_age' => 5,  'desc' => 'Join your alliance to take down the Polar Terror.'],
            'Crazy Joe'             => ['start' => $future,        'duration' => '1 Day',  'server_age' => 15, 'desc' => 'Defend your city against waves of bandits.'],
            'Foundry Battle'        => ['start' => $today,         'duration' => '1 Day',  'server_age' => 30, 'desc' => 'Alliance vs Alliance battle.'],
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
                update_post_meta($post_id, '_event_start_date', $data['start']);
                update_post_meta($post_id, '_event_duration', $data['duration']);
                update_post_meta($post_id, '_server_age_requirement', $data['server_age']);
            }
        }
        
        add_action('admin_notices', function() {
            echo '<div class="notice notice-success"><p>Events Seeded Successfully!</p></div>';
        });
    }

    // Year Beast Event Seeder
    if ( isset($_GET['seed_year_beast']) ) {
        $event_data = [
            'title'     => 'イヤービースト大襲撃 (Year Beast Big Raid)',
            'slug'      => 'year-beast-2026',
            'content'   => '爆竹の歓声が雪原に響き渡り、伝説のイヤービーストが生存者たちを襲い始めました。無名と共に撃退し、祝祭を迎えましょう！',
            'start'     => '2026-02-15',
            'duration'  => '7 Days',
            'server_age'=> 1,
            'currency'  => '寒玉コイン',
            'shop_close'=> '2026-02-22'
        ];

        $existing = get_page_by_path($event_data['slug'], OBJECT, 'wos_event');
        
        $post_data = array(
            'post_title'    => $event_data['title'],
            'post_content'  => $event_data['content'],
            'post_status'   => 'publish',
            'post_type'     => 'wos_event',
            'post_name'     => $event_data['slug'],
        );

        if ($existing) {
            $post_data['ID'] = $existing->ID;
            $post_id = wp_update_post($post_data);
        } else {
            $post_id = wp_insert_post($post_data);
        }

        if ( ! is_wp_error($post_id) ) {
            update_post_meta($post_id, '_event_start_date', $event_data['start']);
            update_post_meta($post_id, '_event_duration', $event_data['duration']);
            update_post_meta($post_id, '_server_age_requirement', $event_data['server_age']);
            update_post_meta($post_id, '_event_currency_name', $event_data['currency']);
            update_post_meta($post_id, '_event_shop_closing_date', $event_data['shop_close']);
        }

        add_action('admin_notices', function() {
            echo '<div class="notice notice-success"><p>Year Beast Event Seeded Successfully</p></div>';
        });
    }
}
add_action('init', 'wos_seed_events');

/**
 * Seed Pages
 * Usage: /wp-admin/?seed_pages=1
 */
function wos_seed_pages() {
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
    
    add_action('admin_notices', function() {
        echo '<div class="notice notice-success"><p>Pages Seeded Successfully!</p></div>';
    });
}
add_action('init', 'wos_seed_pages');

/**
 * Seed Gen 6 Heroes
 * Usage: /wp-admin/?seed_gen6=1
 */
function wos_seed_gen6_heroes() {
    if ( ! current_user_can('manage_options') || ! isset($_GET['seed_gen6']) ) {
        return;
    }

    $heroes_data = [
        'Wu Ming' => [
            'japanese_name' => '無名',
            'type'          => 'Infantry',
            'generation'    => 'Gen 6',
            'rarity'        => 'SSR',
            'tier_whale'    => 'S+',
            'tier_f2p'      => 'S',
            'overall_tier'  => 'S+',
            'skill_active'  => '「広域展開」- 2秒間の無敵効果（Invincibility）付与。',
            'desc'          => "入手方法: 最強王国 (SvS) / 英雄の殿堂\n特徴: 2秒間の無敵スキルが強力。",
        ],
        'Renee' => [
            'japanese_name' => 'レネ',
            'type'          => 'Lancer',
            'generation'    => 'Gen 6',
            'rarity'        => 'SSR',
            'tier_whale'    => 'A',
            'tier_f2p'      => 'A',
            'overall_tier'  => 'A',
            'skill_active'  => '「フレイムボレー」- 扇形範囲の敵にダメージを与え、燃焼効果を付与。',
            'desc'          => "入手方法: 幸運のルーレット\n特徴: 燃焼ダメージとデバフが強力。",
        ],
        'Wayne' => [
            'japanese_name' => 'ウェイン',
            'type'          => 'Marksman',
            'generation'    => 'Gen 6',
            'rarity'        => 'SSR',
            'tier_whale'    => 'S',
            'tier_f2p'      => 'A',
            'overall_tier'  => 'S',
            'skill_active'  => '「影抜き」- 後衛を優先攻撃し、大ダメージを与える。',
            'desc'          => "入手方法: 英雄の任務 / 課金パック\n特徴: 競技場特化。",
        ],
    ];

    $count = 0;

    foreach ($heroes_data as $name => $data) {
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
            wp_set_object_terms($post_id, $data['generation'], 'hero_generation');
            wp_set_object_terms($post_id, strtolower($data['type']), 'hero_type');
            wp_set_object_terms($post_id, $data['rarity'], 'hero_rarity');

            update_post_meta($post_id, 'japanese_name', $data['japanese_name']);
            update_post_meta($post_id, '_japanese_name', 'field_japanese_name');
            update_post_meta($post_id, 'tier_whale', $data['tier_whale']);
            update_post_meta($post_id, 'tier_f2p', $data['tier_f2p']);
            update_post_meta($post_id, 'overall_tier', $data['overall_tier']);
            update_post_meta($post_id, 'skill_exploration_active', $data['skill_active']);
            
            $count++;
        }
    }
    
    add_action('admin_notices', function() use ($count) {
        echo '<div class="notice notice-success"><p>Gen 6 Heroes Seeded Successfully (' . $count . ' heroes updated)!</p></div>';
    });
}
add_action('init', 'wos_seed_gen6_heroes');
