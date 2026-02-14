import os
import requests
import re
import time

# Configuration
# Reddit API (Public JSON endpoint)
SOURCE_URL = "https://www.reddit.com/r/whiteoutsurvival/hot.json?limit=10"
# WordPress API
WP_API_URL = os.environ.get("WP_API_URL")
WP_USER = os.environ.get("WP_USER")
WP_APP_PASSWORD = os.environ.get("WP_APP_PASSWORD")

# Gift Code Pattern (Simple Regex for Demo)
# Adjust pattern as needed. e.g. WOS followed by digits, or general uppercase alphanumeric
CODE_PATTERN = r'\b([A-Z0-9]{4,12})\b' # Example: WOS2024, GIFT123
# Filter out common words that match the pattern (False Positives)
IGNORE_LIST = {"REDDIT", "POST", "GAME", "STATE", "SVS", "GEN", "FC", "S1", "S2", "S3", "S4", "S5", "S6"}

def fetch_reddit_posts():
    headers = {'User-Agent': 'WOS-Gift-Radar/1.0'}
    try:
        response = requests.get(SOURCE_URL, headers=headers)
        response.raise_for_status()
        data = response.json()
        posts = []
        for child in data['data']['children']:
            post = child['data']
            posts.append({
                'title': post['title'],
                'selftext': post.get('selftext', '')
            })
        return posts
    except Exception as e:
        print(f"Error fetching Reddit posts: {e}")
        return []

def extract_potential_codes(text):
    matches = re.findall(CODE_PATTERN, text)
    valid_codes = []
    for code in matches:
        if code.upper() not in IGNORE_LIST and not code.isdigit(): # Ignore pure numbers
             valid_codes.append(code.upper())
    return valid_codes

def submit_code_to_api(code, source_context):
    if not WP_API_URL:
        print("WP_API_URL is not set. Skipping API submission.")
        return

    payload = {
        "code_string": code,
        "rewards": f"Found in: {source_context[:30]}...", # Brief context
        "status": "publish" # or 'pending' if you want manual review
    }
    
    auth = (WP_USER, WP_APP_PASSWORD)
    
    try:
        print(f"Submitting code: {code}...")
        response = requests.post(WP_API_URL, json=payload, auth=auth)
        
        if response.status_code == 201:
            print(f"SUCCESS: Code '{code}' registered.")
        elif response.status_code == 409:
            print(f"SKIPPED: Code '{code}' already exists.")
        else:
            print(f"FAILED: Code '{code}' - Status: {response.status_code}, Response: {response.text}")
            
    except Exception as e:
        print(f"Network Error submitting '{code}': {e}")

def main():
    print("--- WOS Gift Code Radar Started ---")
    
    if not WP_API_URL:
        print("WARNING: WP_API_URL environment variable is missing.")
    
    posts = fetch_reddit_posts()
    print(f"Fetched {len(posts)} posts from Reddit.")
    
    processed_codes = set()
    
    for post in posts:
        text = post['title'] + " " + post['selftext']
        codes = extract_potential_codes(text)
        
        for code in codes:
            if code in processed_codes:
                continue
            
            # Additional heuristic: length check or prefix check if known
            if len(code) < 5: 
                continue

            submit_code_to_api(code, post['title'])
            processed_codes.add(code)
            time.sleep(1) # Be gentle to the API

    print("--- Radar Scan Completed ---")

if __name__ == "__main__":
    main()
