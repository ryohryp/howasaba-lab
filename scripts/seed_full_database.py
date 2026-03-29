import requests
import os
import json
import re

# Configuration
WP_API_URL = os.environ.get("WP_API_URL", "http://localhost:10008/wp-json") 
TOKEN = os.environ.get("X_RADAR_TOKEN", "WosRadarSecret2026_Operation!")

HEADERS = {
    "X-Radar-Token": TOKEN,
    "Content-Type": "application/json"
}

# Hero Data Definition from User Request
HEROES = [
    # Gen 1
    {"name": "Molly", "jp_name": "ジャスミン", "gen": "1", "type": "Lancer", "tier": "S", "note": "ログイン報酬で入手可。序盤の主力。"},
    {"name": "Natalia", "jp_name": "ナタリア", "gen": "1", "type": "Infantry", "tier": "A", "note": "課金英雄。初期の優秀なタンク。"},
    {"name": "Zinman", "jp_name": "ジンマン", "gen": "1", "type": "Marksman", "tier": "B", "note": "建設バフ持ち。戦闘力は控えめ。"},
    {"name": "Jeronimo", "jp_name": "ジェロニモ", "gen": "1", "type": "Infantry", "tier": "S", "note": "最強の第1世代。長期間活躍可能。"},
    # Gen 2
    {"name": "Flint", "jp_name": "フリント", "gen": "2", "type": "Infantry", "tier": "S", "note": "ラキルレ英雄。F2Pの希望。"},
    {"name": "Alonso", "jp_name": "アロンソ", "gen": "2", "type": "Marksman", "tier": "S", "note": "競技場で圧倒的な強さを誇る。"},
    {"name": "Philly", "jp_name": "フレンダー", "gen": "2", "type": "Lancer", "tier": "A", "note": "回復スキル持ち。安定感がある。"},
    # Gen 3
    {"name": "Mia", "jp_name": "ミア", "gen": "3", "type": "Lancer", "tier": "S+", "note": "ラキルレ。デバフと火力が極めて優秀。"},
    {"name": "Logan", "jp_name": "ローガン", "gen": "3", "type": "Infantry", "tier": "S", "note": "攻守のバランスが良いタンク。"},
    {"name": "Greg", "jp_name": "グレッグ", "gen": "3", "type": "Marksman", "tier": "S", "note": "高火力の後衛アタッカー。"},
    # Gen 4
    {"name": "Ahmose", "jp_name": "アクモス", "gen": "4", "type": "Infantry", "tier": "S+", "note": "回避と防御に特化。PvPで非常に強力。"},
    {"name": "Reina", "jp_name": "レイナ", "gen": "4", "type": "Lancer", "tier": "S", "note": "槍兵の火力を底上げする。"},
    {"name": "Lynn", "jp_name": "リオン", "gen": "4", "type": "Marksman", "tier": "S", "note": "汎用性の高いアタッカー。"},
    # Gen 5
    {"name": "Hector", "jp_name": "ヘクトー", "gen": "5", "type": "Infantry", "tier": "S+", "note": "長期間メタに残る最強クラスの盾兵。"},
    {"name": "Norah", "jp_name": "ノラ", "gen": "5", "type": "Lancer", "tier": "S", "note": "安定した火力供給が可能。"},
    {"name": "Gwen", "jp_name": "グエン", "gen": "5", "type": "Marksman", "tier": "S", "note": "第5世代の主力弓兵。"},
    # Gen 6
    {"name": "Wu Ming", "jp_name": "無名", "gen": "6", "type": "Infantry", "tier": "S+", "note": "スキルダメージ耐性が高く、防衛で無類。"},
    {"name": "Renee", "jp_name": "レネ", "gen": "6", "type": "Lancer", "tier": "S", "note": "混乱デバフが強力なラキルレ英雄。"},
    {"name": "Wayne", "jp_name": "ウェイン", "gen": "6", "type": "Marksman", "tier": "A", "note": "性能は良いが、代替が利きやすい。"},
    # Gen 7
    {"name": "Bradley", "jp_name": "ブラッドリー", "gen": "7", "type": "Marksman", "tier": "S+", "note": "爆発的な火力を誇る。現環境の核。"},
    {"name": "Edith", "jp_name": "エディス", "gen": "7", "type": "Infantry", "tier": "S", "note": "非常に高い耐久性能を持つ。"},
    {"name": "Gordon", "jp_name": "ゴードン", "gen": "7", "type": "Lancer", "tier": "S", "note": "特定の編成で真価を発揮する。"},
    # Gen 8
    {"name": "Gatot", "jp_name": "ガト", "gen": "8", "type": "Infantry", "tier": "S+", "note": "シールドと反射が強力。"},
    {"name": "Sonya", "jp_name": "ソニヤ", "gen": "8", "type": "Lancer", "tier": "S", "note": "高水準なバランス。"},
    # Gen 10
    {"name": "Freya", "jp_name": "フレイヤ", "gen": "10", "type": "Lancer", "tier": "S+", "note": "第10世代のスター。広範囲攻撃が脅威。"},
    # Gen 11
    {"name": "Rufus", "jp_name": "ルーファス", "gen": "11", "type": "Infantry", "tier": "S+", "note": "圧倒的なステータスを誇る最新世代。"},
    # Gen 13
    {"name": "Gisela", "jp_name": "ギーゼラ", "gen": "13", "type": "Infantry", "tier": "S+", "note": "サーバー経過950日付近で解放。"},
    {"name": "Flora", "jp_name": "フローラ", "gen": "13", "type": "Lancer", "tier": "S+", "note": "妨害と火力を兼ね備える。"},
    {"name": "Vulcanus", "jp_name": "ウルカヌス", "gen": "13", "type": "Marksman", "tier": "S+", "note": "圧倒的な殲滅力。"},
]

def get_term_id(taxonomy, term_name):
    """Get term ID by name, creating it if it doesn't exist."""
    url = f"{WP_API_URL}/wp/v2/{taxonomy}"
    params = {"search": term_name}
    
    try:
        response = requests.get(url, params=params, headers=HEADERS)
        response.raise_for_status()
        terms = response.json()
        
        for term in terms:
            if term['name'].lower() == term_name.lower():
                return term['id']
                
        # Create it
        create_response = requests.post(url, json={"name": term_name}, headers=HEADERS)
        create_response.raise_for_status()
        return create_response.json()['id']
    except Exception as e:
        print(f"Error resolving term '{term_name}': {e}")
        return None

def main():
    print("Starting Hero Database Sync...")
    
    term_cache = {"gen": {}, "type": {}}
    supabase_sql = ["BEGIN;", ""]

    for hero in HEROES:
        print(f"\nProcessing: {hero['name']} ({hero['jp_name']})")
        
        # 1. Resolve Terms
        gen_str = f"Gen {hero['gen']}"
        if gen_str not in term_cache["gen"]:
            term_cache["gen"][gen_str] = get_term_id("hero_generation", gen_str)
        
        type_str = hero['type']
        if type_str not in term_cache["type"]:
            term_cache["type"][type_str] = get_term_id("hero_type", type_str)
            
        gen_id = term_cache["gen"].get(gen_str)
        type_id = term_cache["type"].get(type_str)

        # 2. Update WordPress
        slug = hero['name'].lower().replace(" ", "-")
        check_url = f"{WP_API_URL}/wp/v2/hero"
        check_params = {"slug": slug, "status": "any"}
        
        try:
            res = requests.get(check_url, params=check_params, headers=HEADERS)
            existing = res.json()
            
            payload = {
                "title": hero['name'],
                "content": hero['note'],
                "status": "publish",
                "slug": slug,
                "hero_generation": [gen_id] if gen_id else [],
                "hero_type": [type_id] if type_id else [],
                "meta": {
                    "japanese_name": hero['jp_name'],
                    "overall_tier": hero['tier'],
                    "generation": int(hero['gen']),
                    "troop_type": hero['type']
                }
            }
            
            if existing:
                hero_id = existing[0]['id']
                update_url = f"{WP_API_URL}/wp/v2/hero/{hero_id}"
                requests.post(update_url, json=payload, headers=HEADERS)
                print(f"  WordPress: Updated (ID: {hero_id})")
            else:
                create_url = f"{WP_API_URL}/wp/v2/hero"
                requests.post(create_url, json=payload, headers=HEADERS)
                print(f"  WordPress: Created")
        except Exception as e:
            print(f"  WordPress Error: {e}")

        # 3. Generate Supabase SQL
        n = hero['name'].replace("'", "''")
        jp = hero['jp_name'].replace("'", "''")
        nt = hero['note'].replace("'", "''")
        t = hero['type']
        tr = hero['tier']
        g = hero['gen']
        
        sql = f"INSERT INTO heroes (name, japanese_name, slug, generation, troop_type, overall_tier, note) " \
              f"VALUES ('{n}', '{jp}', '{slug}', {g}, '{t}', '{tr}', '{nt}') " \
              f"ON CONFLICT (name) DO UPDATE SET " \
              f"japanese_name = EXCLUDED.japanese_name, generation = EXCLUDED.generation, " \
              f"troop_type = EXCLUDED.troop_type, overall_tier = EXCLUDED.overall_tier, note = EXCLUDED.note;"
        supabase_sql.append(sql)

    supabase_sql.append("\nCOMMIT;")
    
    with open("scripts/update_heroes.sql", "w", encoding="utf-8") as f:
        f.write("\n".join(supabase_sql))
    print(f"\nSupabase SQL generated at scripts/update_heroes.sql")

if __name__ == "__main__":
    main()
