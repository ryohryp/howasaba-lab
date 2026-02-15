import requests
import os
import json

# Configuration
WP_API_URL = os.environ.get("WP_API_URL", "http://localhost:10008/wp-json") 
TOKEN = os.environ.get("X_RADAR_TOKEN", "WosRadarSecret2026_Operation!")

HEADERS = {
    "X-Radar-Token": TOKEN,
    "Content-Type": "application/json"
}

# Hero Data Definition
HEROES = [
    # --- SSR (Mythic) Heroes ---
    {
        "name": "Jeronimo",
        "japanese_name": "ジェロニモ",
        "slug": "jeronimo",
        "generation": "1",
        "type": "Infantry",
        "rarity": "SSR",
        "overall_tier": "S+",
        "skill_expedition_1": "全部隊の殺傷力・HP上昇",
        "note": "重課金向け、VIP7/最強領主報酬。"
    },
    {
        "name": "Natalia",
        "japanese_name": "ナタリア",
        "slug": "natalia",
        "generation": "1",
        "type": "Infantry",
        "rarity": "SSR",
        "overall_tier": "S",
        "skill_expedition_1": "全部隊の攻撃・防御力上昇",
        "note": "課金者向け、初回チャージ/VIP報酬。"
    },
    {
        "name": "Zinman",
        "japanese_name": "ジンマン",
        "slug": "zinman",
        "generation": "1",
        "type": "Marksman",
        "rarity": "SSR",
        "overall_tier": "A",
        "note": "デイリー割引。建設バフ、中盤以降は過小不足で価値低下。"
    },
    {
        "name": "Jasmine",
        "japanese_name": "ジャスミン",
        "slug": "jasmine",
        "generation": "1",
        "type": "Lancer",
        "rarity": "SSR",
        "overall_tier": "A",
        "note": "無課金最優先。7日間ログイン報酬。"
    },

    # --- SR (Epic) Heroes ---
    {
        "name": "Sergey",
        "japanese_name": "セルゲイ",
        "slug": "sergey",
        "generation": "1",
        "type": "Infantry",
        "rarity": "SR",
        "overall_tier": "B",
        "note": "序盤の最強タンク。PvPやクレイジージョイなど防衛で長期間活躍。"
    },
    {
        "name": "Jessie",
        "japanese_name": "ジェシー",
        "slug": "jessie",
        "generation": "1",
        "type": "Lancer",
        "rarity": "SR",
        "overall_tier": "B",
        "note": "遠征スキルで集結部隊のダメージUP。熊狩りや集結攻撃の必須枠。"
    },
    {
        "name": "Patrick",
        "japanese_name": "パトリック",
        "slug": "patrick",
        "generation": "1",
        "type": "Infantry",
        "rarity": "SR",
        "overall_tier": "B",
        "note": "HP回復と防衛時のHPバフ持ち。防衛・駐屯において非常に優秀。"
    },
    {
        "name": "Jasser",
        "japanese_name": "ジャセル",
        "slug": "jasser",
        "generation": "1",
        "type": "Marksman",
        "rarity": "SR",
        "overall_tier": "C",
        "note": "研究速度UPの内政バフ持ち。遠征スキルで集結火力も上げられる。"
    },
    {
        "name": "Seo-yoon",
        "japanese_name": "ソユン",
        "slug": "seo-yoon",
        "generation": "1",
        "type": "Lancer",
        "rarity": "SR",
        "overall_tier": "C",
        "note": "遠征スキルで集結火力を上昇。熊狩り等でのバッファーとして活躍。"
    },
    {
        "name": "Gina",
        "japanese_name": "ジーナ",
        "slug": "gina",
        "generation": "1",
        "type": "Marksman",
        "rarity": "SR",
        "overall_tier": "C",
        "note": "「ジーナの復讐」イベントで育成容易。野獣狩りのスタミナ軽減・行軍速度UPで必須。"
    },
    {
        "name": "Bahiti",
        "japanese_name": "バシティ",
        "slug": "bahiti",
        "generation": "1",
        "type": "Marksman",
        "rarity": "SR",
        "overall_tier": "B",
        "note": "序盤の貴重な弓火力枠。星上げが容易で長く無課金の主力となる。"
    },
    {
        "name": "Walis Bokan",
        "japanese_name": "ヴァリス・ボーガン",
        "slug": "walis-bokan",
        "generation": "1",
        "type": "Lancer",
        "rarity": "SR",
        "overall_tier": "C",
        "note": "兵士の訓練速度UPバフを持つ内政特化英雄。"
    },
    {
        "name": "Ling Shuang",
        "japanese_name": "リンソウ",
        "slug": "ling-shuang",
        "generation": "1",
        "type": "Infantry",
        "rarity": "SR",
        "overall_tier": "C",
        "note": "治療速度UPと鉄鉱所出力UPを持つ内政・サポート枠。"
    },

    # --- R (Rare) Heroes ---
    {
        "name": "Smith",
        "japanese_name": "スミス",
        "slug": "smith",
        "generation": "1",
        "type": "Infantry",
        "rarity": "R",
        "overall_tier": "D",
        "note": "鉄鉱工場の出力UP。"
    },
    {
        "name": "Eugene",
        "japanese_name": "ユージーン",
        "slug": "eugene",
        "generation": "1",
        "type": "Lancer",
        "rarity": "R",
        "overall_tier": "D",
        "note": "ハンターの家の出力（生肉）UP。"
    },
    {
        "name": "Charlie",
        "japanese_name": "チャーリー",
        "slug": "charlie",
        "generation": "1",
        "type": "Infantry",
        "rarity": "R",
        "overall_tier": "D",
        "note": "炭鉱工場の出力UP。"
    },
    {
        "name": "Cloris",
        "japanese_name": "クラリス",
        "slug": "cloris",
        "generation": "1",
        "type": "Marksman",
        "rarity": "R",
        "overall_tier": "D",
        "note": "伐採場の出力UP。"
    }
]

def get_term_id(taxonomy, term_name):
    """Get term ID by name, creating it if it doesn't exist."""
    print(f"Fetching term '{term_name}' in taxonomy '{taxonomy}'...")
    url = f"{WP_API_URL}/wp/v2/{taxonomy}"
    params = {"search": term_name}
    
    try:
        response = requests.get(url, params=params, headers=HEADERS)
        response.raise_for_status()
        terms = response.json()
        
        for term in terms:
            if term['name'].lower() == term_name.lower():
                print(f"  Found term ID: {term['id']}")
                return term['id']
                
        # If not found, create it
        print(f"  Term '{term_name}' not found. Creating...")
        create_url = f"{WP_API_URL}/wp/v2/{taxonomy}"
        data = {"name": term_name}
        create_response = requests.post(create_url, json=data, headers=HEADERS)
        create_response.raise_for_status()
        new_term = create_response.json()
        print(f"  Created term ID: {new_term['id']}")
        return new_term['id']

    except requests.exceptions.HTTPError as e:
        print(f"Error getting/creating term: {e}")
        if e.response:
             print(f"Response: {e.response.text}")
        return None

def seed_heroes():
    print("Starting hero seeding...")
    
    # 1. Resolve Taxonomy Terms
    # We cache term IDs to avoid repeated calls
    term_cache = {
        "generation": {},
        "type": {},
        "rarity": {}
    }

    for hero in HEROES:
        print(f"\nProcessing hero: {hero['name']} ({hero['slug']})")
        
        # Resolve Generation
        gen_name = hero['generation']
        if gen_name not in term_cache["generation"]:
            term_id = get_term_id("hero_generation", gen_name)
            if term_id:
                term_cache["generation"][gen_name] = term_id
        gen_id = term_cache["generation"].get(gen_name)

        # Resolve Type
        type_name = hero['type']
        if type_name not in term_cache["type"]:
             term_id = get_term_id("hero_type", type_name)
             if term_id:
                 term_cache["type"][type_name] = term_id
        type_id = term_cache["type"].get(type_name)

        # Resolve Rarity
        rarity_name = hero.get('rarity', 'SSR') # Default to SSR if not specified
        if rarity_name not in term_cache["rarity"]:
             term_id = get_term_id("hero_rarity", rarity_name)
             if term_id:
                 term_cache["rarity"][rarity_name] = term_id
        rarity_id = term_cache["rarity"].get(rarity_name)

        if not gen_id or not type_id:
            print(f"Skipping {hero['name']}: Could not resolve basic taxonomy terms.")
            continue

        # 2. Check if hero exists
        check_url = f"{WP_API_URL}/wp/v2/hero"
        check_params = {"slug": hero['slug'], "status": "any"}
        response = requests.get(check_url, params=check_params, headers=HEADERS)
        
        if response.status_code != 200:
             print(f"Error checking hero existence: {response.text}")
             continue
             
        existing_heroes = response.json()
        
        # 3. Prepare Data
        post_data = {
            "title": hero['name'],
            "status": "publish",
            "slug": hero['slug'],
            "content": hero['note'], # Putting note in content
            "hero_generation": [gen_id],
            "hero_type": [type_id],
            "meta": {
                "overall_tier": hero['overall_tier'],
                "skill_expedition_1": hero.get('skill_expedition_1', ''),
                "japanese_name": hero['japanese_name'],
            }
        }
        
        if rarity_id:
            post_data['hero_rarity'] = [rarity_id]

        # 4. Create or Update
        if existing_heroes:
            hero_id = existing_heroes[0]['id']
            print(f"  Hero exists (ID: {hero_id}). Updating...")
            update_url = f"{WP_API_URL}/wp/v2/hero/{hero_id}"
            update_response = requests.post(update_url, json=post_data, headers=HEADERS)
            
            if update_response.status_code == 200:
                print("  Update successful.")
            else:
                print(f"  Update failed: {update_response.text}")
        else:
            print("  Hero does not exist. Creating...")
            create_url = f"{WP_API_URL}/wp/v2/hero"
            create_response = requests.post(create_url, json=post_data, headers=HEADERS)
            
            if create_response.status_code == 201:
                print(f"  Creation successful (ID: {create_response.json()['id']}).")
            else:
                print(f"  Creation failed: {create_response.text}")

if __name__ == "__main__":
    seed_heroes()
