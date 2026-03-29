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
/**
 * Seed All Heroes (Full Database)
 * Usage: /wp-admin/?seed_heroes=1
 */
function wos_seed_all_heroes() {
    if ( ! current_user_can('manage_options') || ! isset($_GET['seed_heroes']) ) {
        return;
    }

    $heroes_data = [
        // Gen 1
        'Molly'    => ['jp' => 'ジャスミン', 'gen' => 'Gen 1', 'type' => 'Lancer',   'rarity' => 'SSR', 'tier' => 'S',  'note' => 'ログイン報酬で入手可。序盤の主力。'],
        'Natalia'  => ['jp' => 'ナタリア',   'gen' => 'Gen 1', 'type' => 'Infantry', 'rarity' => 'SSR', 'tier' => 'A',  'note' => '課金英雄。初期の優秀なタンク。'],
        'Zinman'   => ['jp' => 'ジンマン',   'gen' => 'Gen 1', 'type' => 'Marksman', 'rarity' => 'SSR', 'tier' => 'B',  'note' => '建設バフ持ち。戦闘力は控えめ。'],
        'Jeronimo' => ['jp' => 'ジェロニモ', 'gen' => 'Gen 1', 'type' => 'Infantry', 'rarity' => 'SSR', 'tier' => 'S',  'note' => '最強の第1世代。長期間活躍可能。'],
        // Gen 2
        'Flint'    => ['jp' => 'フリント',   'gen' => 'Gen 2', 'type' => 'Infantry', 'rarity' => 'SSR', 'tier' => 'S',  'note' => 'ラキルレ英雄。F2Pの希望。'],
        'Alonso'   => ['jp' => 'アロンソ',   'gen' => 'Gen 2', 'type' => 'Marksman', 'rarity' => 'SSR', 'tier' => 'S',  'note' => '競技場で圧倒的な強さを誇る。'],
        'Philly'   => ['jp' => 'フレンダー', 'gen' => 'Gen 2', 'type' => 'Lancer',   'rarity' => 'SSR', 'tier' => 'A',  'note' => '回復スキル持ち。安定感がある。'],
        // Gen 3
        'Mia'      => ['jp' => 'ミア',       'gen' => 'Gen 3', 'type' => 'Lancer',   'rarity' => 'SSR', 'tier' => 'S+', 'note' => 'ラキルレ。デバフと火力が極めて優秀。'],
        'Logan'    => ['jp' => 'ローガン',   'gen' => 'Gen 3', 'type' => 'Infantry', 'rarity' => 'SSR', 'tier' => 'S',  'note' => '攻守のバランスが良いタンク。'],
        'Greg'     => ['jp' => 'グレッグ',   'gen' => 'Gen 3', 'type' => 'Marksman', 'rarity' => 'SSR', 'tier' => 'S',  'note' => '高火力の後衛アタッカー。'],
        // Gen 4
        'Ahmose'   => ['jp' => 'アクモス',   'gen' => 'Gen 4', 'type' => 'Infantry', 'rarity' => 'SSR', 'tier' => 'S+', 'note' => '回避と防御に特化。PvPで非常に強力。'],
        'Reina'    => ['jp' => 'レイナ',     'gen' => 'Gen 4', 'type' => 'Lancer',   'rarity' => 'SSR', 'tier' => 'S',  'note' => '槍兵の火力を底上げする。'],
        'Lynn'     => ['jp' => 'リオン',     'gen' => 'Gen 4', 'type' => 'Marksman', 'rarity' => 'SSR', 'tier' => 'S',  'note' => '汎用性の高いアタッカー。'],
        // Gen 5
        'Hector'   => ['jp' => 'ヘクトー',   'gen' => 'Gen 5', 'type' => 'Infantry', 'rarity' => 'SSR', 'tier' => 'S+', 'note' => '長期間メタに残る最強クラスの盾兵。'],
        'Norah'    => ['jp' => 'ノラ',       'gen' => 'Gen 5', 'type' => 'Lancer',   'rarity' => 'SSR', 'tier' => 'S',  'note' => '安定した火力供給が可能。'],
        'Gwen'     => ['jp' => 'グエン',     'gen' => 'Gen 5', 'type' => 'Marksman', 'rarity' => 'SSR', 'tier' => 'S',  'note' => '第5世代の主力弓兵。'],
        // Gen 6
        'Wu Ming'  => ['jp' => '無名',       'gen' => 'Gen 6', 'type' => 'Infantry', 'rarity' => 'SSR', 'tier' => 'S+', 'note' => 'スキルダメージ耐性が高く、防衛で無類。'],
        'Renee'    => ['jp' => 'レネ',       'gen' => 'Gen 6', 'type' => 'Lancer',   'rarity' => 'SSR', 'tier' => 'S',  'note' => '混乱デバフが強力なラキルレ英雄。'],
        'Wayne'    => ['jp' => 'ウェイン',   'gen' => 'Gen 6', 'type' => 'Marksman', 'rarity' => 'SSR', 'tier' => 'A',  'note' => '性能は良いが、代替が利きやすい。'],
        // Gen 7
        'Bradley'  => ['jp' => 'ブラッドリー', 'gen' => 'Gen 7', 'type' => 'Marksman', 'rarity' => 'SSR', 'tier' => 'S+', 'note' => '爆発的な火力を誇る。現環境の核。'],
        'Edith'    => ['jp' => 'エディス',   'gen' => 'Gen 7', 'type' => 'Infantry', 'rarity' => 'SSR', 'tier' => 'S',  'note' => '非常に高い耐久性能を持つ。'],
        'Gordon'   => ['jp' => 'ゴードン',   'gen' => 'Gen 7', 'type' => 'Lancer',   'rarity' => 'SSR', 'tier' => 'S',  'note' => '特定の編成で真価を発揮する。'],
        // Gen 8
        'Gatot'    => ['jp' => 'ガト',       'gen' => 'Gen 8', 'type' => 'Infantry', 'rarity' => 'SSR', 'tier' => 'S+', 'note' => 'シールドと反射が強力。'],
        'Sonya'    => ['jp' => 'ソニヤ',     'gen' => 'Gen 8', 'type' => 'Lancer',   'rarity' => 'SSR', 'tier' => 'S',  'note' => '高水準なバランス。'],
        // Gen 10
        'Freya'    => ['jp' => 'フレイヤ',   'gen' => 'Gen 10', 'type' => 'Lancer',  'rarity' => 'SSR', 'tier' => 'S+', 'note' => '第10世代のスター。広範囲攻撃が脅威。'],
        // Gen 11
        'Rufus'    => ['jp' => 'ルーファス', 'gen' => 'Gen 11', 'type' => 'Infantry', 'rarity' => 'SSR', 'tier' => 'S+', 'note' => '圧倒的なステータスを誇る最新世代。'],
        // Gen 13
        'Gisela'   => ['jp' => 'ギーゼラ',   'gen' => 'Gen 13', 'type' => 'Infantry', 'rarity' => 'SSR', 'tier' => 'S+', 'note' => 'サーバー経過950日付近で解放。'],
        'Flora'    => ['jp' => 'フローラ',   'gen' => 'Gen 13', 'type' => 'Lancer',   'rarity' => 'SSR', 'tier' => 'S+', 'note' => '妨害と火力を兼ね備える。'],
        'Vulcanus' => ['jp' => 'ウルカヌス', 'gen' => 'Gen 13', 'type' => 'Marksman', 'rarity' => 'SSR', 'tier' => 'S+', 'note' => '圧倒的な殲滅力。'],
    ];

    $count = 0;

    foreach ($heroes_data as $name => $data) {
        $existing = get_page_by_title($name, OBJECT, 'wos_hero');
        
        $post_data = array(
            'post_title'    => $name,
            'post_content'  => $data['note'],
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

            // Metadata
            update_post_meta($post_id, 'japanese_name', $data['jp']);
            update_post_meta($post_id, '_japanese_name', 'field_japanese_name');
            update_post_meta($post_id, 'overall_tier', $data['tier']);
            update_post_meta($post_id, '_overall_tier', 'field_overall_tier');
            
            // Link Generation and Troop Type for Tier List
            preg_match('/\d+/', $data['gen'], $matches);
            $gen_num = $matches[0] ?? 1;
            update_post_meta($post_id, 'generation', $gen_num);
            update_post_meta($post_id, 'troop_type', $data['type']);

            $count++;
        }
    }
    
    add_action('admin_notices', function() use ($count) {
        echo '<div class="notice notice-success"><p>Hero Database Updated Successfully! (' . $count . ' heroes processed)</p></div>';
    });
}
add_action('init', 'wos_seed_all_heroes');
