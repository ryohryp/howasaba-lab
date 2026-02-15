<?php
/**
 * Seed Gen 6 Heroes
 */
function wos_seed_gen6_heroes() {
    // Run if user has capability and triggered via GET param
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
            'tier_f2p'      => 'S', // 推定
            'overall_tier'  => 'S+', // Backward compatibility
            'skill_active'  => '「広域展開」- 2秒間の無敵効果（Invincibility）付与。',
            'desc'          => "入手方法: 最強王国 (SvS) / 英雄の殿堂\n特徴: 2秒間の無敵スキルが強力。",
        ],
        'Renee' => [
            'japanese_name' => 'レネ',
            'type'          => 'Lancer',
            'generation'    => 'Gen 6',
            'rarity'        => 'SSR',
            'tier_whale'    => 'A',
            'tier_f2p'      => 'A', // 推定
            'overall_tier'  => 'A', // Backward compatibility
            'skill_active'  => '「フレイムボレー」- 扇形範囲の敵にダメージを与え、燃焼効果を付与。',
            'desc'          => "入手方法: 幸運のルーレット\n特徴: 燃焼ダメージとデバフが強力。",
        ],
        'Wayne' => [
            'japanese_name' => 'ウェイン',
            'type'          => 'Marksman',
            'generation'    => 'Gen 6',
            'rarity'        => 'SSR',
            'tier_whale'    => 'S',
            'tier_f2p'      => 'A', // 推定
            'overall_tier'  => 'S', // Backward compatibility
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
            // Taxonomies
            wp_set_object_terms($post_id, $data['generation'], 'hero_generation');
            wp_set_object_terms($post_id, strtolower($data['type']), 'hero_type');
            wp_set_object_terms($post_id, $data['rarity'], 'hero_rarity');

            // Custom Fields
            update_post_meta($post_id, 'japanese_name', $data['japanese_name']);
            update_post_meta($post_id, '_japanese_name', 'field_japanese_name'); // For ACF compatibility if needed

            // Tiers
            update_post_meta($post_id, 'tier_whale', $data['tier_whale']);
            update_post_meta($post_id, 'tier_f2p', $data['tier_f2p']);
            update_post_meta($post_id, 'overall_tier', $data['overall_tier']); // Legacy / Compatibility

            // Skills (Sanitized with HTML allowed)
            // Using wp_kses in save handler, here we trust input as it is hardcoded/admin only
            // But we should use the same allowed tags structure if we were processing input.
            // Since this is direct DB update via PHP, we can just save it.
            // We use the 'skill_exploration_active' key as per previous file schema
            // Adding a wrapper span for numbers if needed, or just plain text as requested.
            // Request said: Skill: "2秒間の無敵効果（Invincibility）"
            
            // Let's format it nicely with HTML if appropriate
            $skill_html = $data['skill_active'];
            // Simple highlight for numerical values or specific keywords if we want, 
            // but for now keeping it simple as requested logic.
            
            update_post_meta($post_id, 'skill_exploration_active', $skill_html);
            
            // Assuming other stats are generic for now or can be added if specs provided.
            
            $count++;
        }
    }
    
    add_action('admin_notices', function() use ($count) {
        echo '<div class="notice notice-success"><p>Gen 6 Heroes Seeded Successfully via seed-gen6.php (' . $count . ' heroes updated)!</p></div>';
    });
}
add_action('init', 'wos_seed_gen6_heroes');
