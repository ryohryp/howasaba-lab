import os
import requests
import feedparser
import re
import time

# Configuration
# Reddit RSS Feed (New posts)
RSS_URL = "https://www.reddit.com/r/whiteoutsurvival/new/.rss"

# WordPress API
WP_API_URL = os.environ.get("WP_API_URL")
WP_USER = os.environ.get("WP_USER")
WP_APP_PASSWORD = os.environ.get("WP_APP_PASSWORD")

# Gift Code Pattern (Simple Regex)
# Captures 4-12 alphanumeric characters, uppercase
CODE_PATTERN = r'\b([A-Z0-9]{4,12})\b'

# Filter out common false positives
IGNORE_LIST = {
    "REDDIT", "POST", "GAME", "STATE", "SVS", "GEN", "FC", 
    "S1", "S2", "S3", "S4", "S5", "S6", "UTC", "PST", "EST", "KEY", "NEW"
}

def fetch_reddit_rss():
    """
    Fetches the Reddit RSS feed using a custom User-Agent via requests,
    then parses it with feedparser.
    """
    headers = {
        'User-Agent': 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36 WosRadar/1.0'
    }
    
    try:
        print(f"Fetching RSS feed from: {RSS_URL}")
        response = requests.get(RSS_URL, headers=headers, timeout=10)
        
        if response.status_code != 200:
            print(f"Error fetching RSS: Status {response.status_code}")
            return []
            
        # Parse the XML content
        feed = feedparser.parse(response.content)
        
        if feed.bozo:
             print(f"Warning: Malformed XML received. Error: {feed.bozo_exception}")

        print(f"Fetched {len(feed.entries)} entries from RSS.")
        return feed.entries
        
    except Exception as e:
        print(f"Error fetching Reddit RSS: {e}")
        return []

def extract_potential_codes(text):
    """
    Extracts potential gift codes from text using regex and an ignore list.
    """
    matches = re.findall(CODE_PATTERN, text)
    valid_codes = []
    for code in matches:
        upper_code = code.upper()
        # Basic validation:
        # 1. Not in IGNORE_LIST
        # 2. Not purely digits (usually post IDs or counts)
        # 3. Must contain at least one digit (most WOS codes have numbers, e.g. WOS2024) - OPTIONAL heuristic
        if upper_code not in IGNORE_LIST and not upper_code.isdigit():
             valid_codes.append(upper_code)
    return valid_codes

def submit_code_to_api(code, source_title, source_link):
    """
    Submits the extracted code to the WordPress REST API.
    """
    if not WP_API_URL:
        print("WP_API_URL is not set. Skipping API submission.")
        return

    payload = {
        "code_string": code,
        "rewards": f"Found via Reddit RSS: {source_title[:50]}...",
        "source_link": source_link, # If your API supports saving the source URL
        "status": "publish" 
    }
    
    auth = (WP_USER, WP_APP_PASSWORD)
    
    try:
        print(f"Submitting code: {code}...")
        response = requests.post(WP_API_URL, json=payload, auth=auth)
        
        if response.status_code == 201:
            print(f"SUCCESS: Code '{code}' registered.")
        elif response.status_code == 409: # Conflict often means duplicate
            print(f"SKIPPED: Code '{code}' already exists (409).")
        else:
            print(f"FAILED: Code '{code}' - Status: {response.status_code}, Response: {response.text}")
            
    except Exception as e:
        print(f"Network Error submitting '{code}': {e}")

def main():
    print("--- WOS Gift Code Radar (RSS Mode) Started ---")
    
    if not WP_API_URL:
        print("WARNING: WP_API_URL environment variable is missing.")
    
    entries = fetch_reddit_rss()
    
    processed_codes = set()
    
    for entry in entries:
        # Combine title and summary/content for search
        # content is essentially 'summary' in RSS 2.0 or 'content' in Atom
        content = ""
        if hasattr(entry, 'summary'):
            content = entry.summary
        elif hasattr(entry, 'description'):
            content = entry.description
            
        full_text = f"{entry.title} {content}"
        
        # Remove HTML tags from content for cleaner regex matching (rudimentary)
        clean_text = re.sub('<[^<]+?>', ' ', full_text)
        
        codes = extract_potential_codes(clean_text)
        
        for code in codes:
            if code in processed_codes:
                continue
            
            # Additional heuristic to reduce noise
            if len(code) < 4: 
                continue

            submit_code_to_api(code, entry.title, entry.link)
            processed_codes.add(code)
            time.sleep(1) # Be gentle to the API

    print("--- Radar Scan Completed ---")

if __name__ == "__main__":
    main()
