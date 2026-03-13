import os
import csv
import re

CSV_FILE = "英雄データベース：第1世代から第15世代まで - Table 1.csv"
SQL_FILE = "scripts/import_heroes.sql"

def extract_generation(gen_str):
    match = re.search(r'\d+', gen_str)
    return int(match.group()) if match else None

def map_rarity(rarity_str):
    rarity_str = rarity_str.upper()
    if "SSR" in rarity_str or "ミシック" in rarity_str or "レジェンド" in rarity_str:
        return "SSR"
    elif "SR" in rarity_str or "エピック" in rarity_str:
        return "SR"
    elif "R" in rarity_str or "レア" in rarity_str:
        return "R"
    return "SSR"  # Default

def map_troop_type(troop_str):
    if "歩" in troop_str or "盾" in troop_str:
        return "Infantry"
    elif "槍" in troop_str:
        return "Lancer"
    elif "弓" in troop_str:
        return "Marksman"
    return "Unknown"

def escape_sql(text):
    if not text:
        return "NULL"
    return "'" + text.replace("'", "''") + "'"

def generate_slug(name):
    return name.lower().replace(" ", "-").replace("'", "").replace(".", "")

def split_skills(skill_text):
    if not skill_text or skill_text == "情報なし":
        return "", "", ""
    text = re.sub(r'(\d\.)\s', r'|', skill_text)
    text = text.replace("・", "|")
    parts = [p.strip() for p in text.split("|") if p.strip()]
    skill1 = parts[0] if len(parts) > 0 else ""
    skill2 = parts[1] if len(parts) > 1 else ""
    skill3 = parts[2] if len(parts) > 2 else ""
    return skill1, skill2, skill3

def main():
    if not os.path.exists(CSV_FILE):
        print(f"Error: CSV file not found at {CSV_FILE}")
        return

    heroes_data = {}

    with open(CSV_FILE, mode="r", encoding="utf-8") as f:
        reader = csv.DictReader(f)
        for row in reader:
            gen = extract_generation(row.get("世代", ""))
            jp_name = row.get("英雄名 (日本語)", "").strip()
            en_name = row.get("Hero Name (English)", "").strip()
            
            if not en_name or "未詳細" in jp_name or "Gen 15 Hero" in en_name:
                continue
                
            rarity = map_rarity(row.get("レアリティ", ""))
            troop_type = map_troop_type(row.get("兵種", ""))
            
            exp_skills_text = row.get("探検スキルと効果", "")
            expe_skills_text = row.get("遠征スキルと効果", "")
            
            exp_1, exp_2, exp_3 = split_skills(exp_skills_text)
            expe_1, expe_2, expe_3 = split_skills(expe_skills_text)

            slug = generate_slug(en_name)
            
            if slug not in heroes_data:
                heroes_data[slug] = {
                    "name": en_name,
                    "japanese_name": jp_name,
                    "slug": slug,
                    "generation": gen,
                    "troop_type": troop_type,
                    "rarity": rarity,
                    "skill_exploration_active": exp_1,
                    "skill_exploration_passive_1": exp_2,
                    "skill_exploration_passive_2": exp_3,
                    "skill_expedition_1": expe_1,
                    "skill_expedition_2": expe_2,
                    "skill_expedition_3": expe_3,
                }
            else:
                if len(exp_skills_text) > len(heroes_data[slug]["skill_exploration_active"]):
                    heroes_data[slug].update({
                        "skill_exploration_active": exp_1,
                        "skill_exploration_passive_1": exp_2,
                        "skill_exploration_passive_2": exp_3,
                        "skill_expedition_1": expe_1,
                        "skill_expedition_2": expe_2,
                        "skill_expedition_3": expe_3,
                    })

    with open(SQL_FILE, mode="w", encoding="utf-8") as out:
        out.write("BEGIN;\n\n")
        
        for slug, data in heroes_data.items():
            name = escape_sql(data['name'])
            jp_name = escape_sql(data['japanese_name'])
            slug_sql = escape_sql(data['slug'])
            gen = data['generation'] if data['generation'] else "NULL"
            troop = escape_sql(data['troop_type'])
            rarity = escape_sql(data['rarity'])
            exp_1 = escape_sql(data['skill_exploration_active'])
            exp_2 = escape_sql(data['skill_exploration_passive_1'])
            exp_3 = escape_sql(data['skill_exploration_passive_2'])
            expe_1 = escape_sql(data['skill_expedition_1'])
            expe_2 = escape_sql(data['skill_expedition_2'])
            expe_3 = escape_sql(data['skill_expedition_3'])
            
            # Use UPSERT (INSERT ... ON CONFLICT (name) DO UPDATE ...)
            # Wait, does the 'name' column have a UNIQUE constraint?
            # Let's just do an UPDATE based on name, and if it doesn't exist, we could INSERT.
            # But the simplest is to just INSERT and on conflict do update. Or delete and insert.
            # Since uuid is the primary key and we don't know the unique constraint,
            # Let's write an UPDATE statement, then an INSERT statement where name doesn't exist.
            
            out.write(f"-- Hero: {data['name']}\n")
            out.write(f"UPDATE heroes SET japanese_name = {jp_name}, slug = {slug_sql}, generation = {gen}, troop_type = {troop}, rarity = {rarity}, skill_exploration_active = {exp_1}, skill_exploration_passive_1 = {exp_2}, skill_exploration_passive_2 = {exp_3}, skill_expedition_1 = {expe_1}, skill_expedition_2 = {expe_2}, skill_expedition_3 = {expe_3} WHERE name = {name};\n")
            
            out.write(f"INSERT INTO heroes (name, japanese_name, slug, generation, troop_type, rarity, skill_exploration_active, skill_exploration_passive_1, skill_exploration_passive_2, skill_expedition_1, skill_expedition_2, skill_expedition_3) SELECT {name}, {jp_name}, {slug_sql}, {gen}, {troop}, {rarity}, {exp_1}, {exp_2}, {exp_3}, {expe_1}, {expe_2}, {expe_3} WHERE NOT EXISTS (SELECT 1 FROM heroes WHERE name = {name});\n\n")

        out.write("COMMIT;\n")

    print(f"SQL file generated at {SQL_FILE}")

if __name__ == "__main__":
    main()
