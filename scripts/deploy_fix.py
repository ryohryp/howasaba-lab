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

def flush_rules():
    print("Requesting Rewrite Rules Flush via API...")
    url = f"{WP_API_URL}/wos-radar/v1/debug/flush"
    
    try:
        response = requests.post(url, headers=HEADERS)
        
        if response.status_code == 200:
            print("Flush successful.")
            data = response.json()
            print("Registered Hero Routes:")
            for route in data.get('routes', []):
                print(f" - {route}")
        else:
            print(f"Flush failed: {response.status_code}")
            print(response.text)
            
    except Exception as e:
        print(f"Error: {e}")

if __name__ == "__main__":
    flush_rules()
