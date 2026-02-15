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

def debug_rest():
    print(f"Checking REST API at {WP_API_URL}...")
    
    # 1. Check Taxonomies
    print("\n--- Checking Taxonomies (/wp/v2/taxonomies) ---")
    try:
        response = requests.get(f"{WP_API_URL}/wp/v2/taxonomies", headers=HEADERS)
        if response.status_code == 200:
            taxonomies = response.json()
            found_gen = False
            for tax_slug, tax_data in taxonomies.items():
                if 'hero' in tax_slug:
                    print(f"Found Taxonomy: {tax_slug}")
                    print(f"  - REST Base: {tax_data.get('rest_base')}")
                    print(f"  - REST Namespace: {tax_data.get('rest_namespace')}")
                    found_gen = True
            if not found_gen:
                print("WARNING: No taxonomies with 'hero' in slug found.")
                print("Available taxonomies:", list(taxonomies.keys()))
        else:
            print(f"Failed to fetch taxonomies: {response.status_code}")
            print(response.text)
    except Exception as e:
        print(f"Error fetching taxonomies: {e}")

    # 2. Check Post Types
    print("\n--- Checking Post Types (/wp/v2/types) ---")
    try:
        response = requests.get(f"{WP_API_URL}/wp/v2/types", headers=HEADERS)
        if response.status_code == 200:
            types = response.json()
            found_hero = False
            for type_slug, type_data in types.items():
                if 'hero' in type_slug:
                    print(f"Found Post Type: {type_slug}")
                    print(f"  - REST Base: {type_data.get('rest_base')}")
                    found_hero = True
            if not found_hero:
                print("WARNING: No post types with 'hero' in slug found.")
                print("Available types:", list(types.keys()))
        else:
            print(f"Failed to fetch types: {response.status_code}")
    except Exception as e:
        print(f"Error fetching types: {e}")

if __name__ == "__main__":
    debug_rest()
