import requests
import os
import json

# Configuration
WP_API_URL = os.environ.get("WP_API_URL", "http://localhost/wp-json") 
TOKEN = os.environ.get("X_RADAR_TOKEN", "WosRadarSecret2026_Operation!")

HEADERS = {
    "X-Radar-Token": TOKEN,
    "Content-Type": "application/json"
}

# Hero Data Definition
HEROES = [
    {
        "name": "Jeronimo",
        "japanese_name": "ジェロニモ",
        "slug": "jeronimo",
        "generation": "1",
        "type": "Infantry",
        "troop_type_jp": "盾",
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
        "troop_type_jp": "盾",
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
        "troop_type_jp": "弓",
        "overall_tier": "A",
        "note": "デイリー割引。建設バフ、中盤以降は過小不足で価値低下。"
    },
    {
        "name": "Jasmine",
        "japanese_name": "ジャスミン",
        "slug": "jasmine",
        "generation": "1",
        "type": "Lancer",
        "troop_type_jp": "槍",
        "overall_tier": "A",
        "note": "無課金最優先。7日間ログイン報酬。"
    },
    {
        "name": "Sergey",
        "japanese_name": "セルゲイ",
        "slug": "sergey",
        "generation": "1",
        "type": "Infantry",
        "troop_type_jp": "盾",
        "overall_tier": "B",
        "note": "初期タンク。PvP/防衛/ジョイで優秀。"
    },
    {
        "name": "Bahiti",
        "japanese_name": "バシティ",
        "slug": "bahiti",
        "generation": "1",
        "type": "Marksman",
        "troop_type_jp": "弓",
        "overall_tier": "B",
        "note": "初期弓火力。星上げが容易。"
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
        "type": {}
    }

    for hero in HEROES:
        print(f"\nProcessing hero: {hero['name']} ({hero['slug']})")
        
        # Resolve Generation
        gen_name = hero['generation']
        # Try to match the existing naming convention if possible, or just use "1" as requested.
        # User requested "1" for taxonomy `generation`.
        # Existing site might use "Gen 1", but let's stick to requirement "1" or try to find "Gen 1" if "1" fails?
        # Let's use "Gen 1" as the name but maybe slug "gen-1" is better?
        # The prompt says: "タクソノミー `generation` に "1" ... を設定すること"
        # I will use name "1" for now as requested.
        
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

        if not gen_id or not type_id:
            print(f"Skipping {hero['name']}: Could not resolve taxonomy terms.")
            continue

        # 2. Check if hero exists
        # Use slug
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
                # Add troop_type_jp if we want to store it, but type taxonomy should handle it generally.
            }
        }

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
