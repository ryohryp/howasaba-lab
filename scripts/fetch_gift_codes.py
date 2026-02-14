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

# Expanded Ignore List (Common French/English words that look like codes)
IGNORE_LIST = {
    "REDDIT", "POST", "GAME", "STATE", "SVS", "GEN", "FC", 
    "S1", "S2", "S3", "S4", "S5", "S6", "UTC", "PST", "EST", "KEY", "NEW", 
    "CODE", "GIFT", "RETOUR", "MOMENT", "COPIER", "MERCI", "SALUT", "BONJOUR", 
    "HELLO", "THANKS", "PLEASE", "SHARE", "FOUND", "TODAY", "DAILY", "WEEKLY",
    "SERVER", "REGION", "UPDATE", "PATCH", "NOTE", "LINK", "HTTP", "HTTPS",
    "JOIN", "ALLY", "ALLIANCE", "RECRUIT", "GROUP", "CHAT", "DISCORD", "VOTE",
    "POLL", "EVENT", "BATTLE", "FIGHT", "WAR", "KILL", "SCORE", "RANK", "BEST",
    "GOOD", "LUCK", "HELP", "NEED", "WANT", "LOOK", "FIND", "OPEN", "CLOSE"
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
    Enhanced extraction logic:
    1. Only process text containing 'code' or 'gift'.
    2. Extract 5-15 char alphanumeric strings (Upper + Lower).
    3. Filter out IGNORE_LIST.
    4. Validate:
       - If contains digit: High Confidence (e.g. WOS2024, Vday2026).
       - If ALL UPPER without digit: Needs context (e.g. HAPPYWEEKEND).
       - If Mixed Case without digit: Skip (e.g. Hello, Code).
    """
    # 1. Broad filter: skip if no keywords found
    text_lower = text.lower()
    if "code" not in text_lower and "gift" not in text_lower:
        return []

    # Regex for potential candidates: 5-15 alphanumeric (broad match)
    candidates = re.findall(r'\b[A-Za-z0-9]{5,15}\b', text)
    valid_codes = set()
    
    for cand in candidates:
        if cand.upper() in IGNORE_LIST: 
            continue
            
        has_digit = any(c.isdigit() for c in cand)
        is_all_upper = cand.isupper()
        
        # Rule A: Contains digit -> Validate as code (e.g. Vday2026, wos2024)
        if has_digit:
            valid_codes.add(cand.upper()) # Normalize to uppercase
            continue
            
        # Rule B: Digit-less -> Must be ALL UPPER + Context Check (e.g. HAPPYWEEKEND)
        # Note: This automatically skips "Hello" (Mixed case, no digit)
        if is_all_upper:
            # Check context: looking for indicator keywords nearby
            try:
                idx = text.find(cand) 
                if idx > 0:
                    start_context = max(0, idx - 40)
                    context_snippet = text[start_context:idx].lower()
                    
                    # Check for strong indicator keywords
                    indicators = ["code", "gift", "cdk", "key", "redeem"]
                    if any(ind in context_snippet for ind in indicators):
                        valid_codes.add(cand.upper())
            except Exception:
                pass

    return list(valid_codes)

def submit_code_to_api(code, source_title, source_link):
    """
    Submits the extracted code to the WordPress REST API.
    """
    if not WP_API_URL:
        # For local testing, just print
        # print(f"[DRY RUN] Would submit: {code}")
        return

    payload = {
        "code_string": code,
        "rewards": f"Found via Reddit RSS: {source_title[:50]}...",
        "source_link": source_link,
        "status": "publish" 
    }
    
    auth = (WP_USER, WP_APP_PASSWORD)
    
    try:
        print(f"Submitting code: {code}...")
        response = requests.post(WP_API_URL, json=payload, auth=auth)
        
        if response.status_code == 201:
            print(f"SUCCESS: Code '{code}' registered.")
        elif response.status_code == 409:
            print(f"SKIPPED: Code '{code}' already exists (409).")
        elif response.status_code == 401:
            print(f"AUTH ERROR (401): Check WP_USER/WP_APP_PASSWORD or .htaccess.")
            print(f"Response: {response.text}")
        else:
            print(f"FAILED: Code '{code}' - Status: {response.status_code}")
            print(f"Response: {response.text[:200]}") # Truncate long error HTML
            
    except Exception as e:
        print(f"Network Error submitting '{code}': {e}")

def main():
    print("--- WOS Gift Code Radar (Production) Started ---")
    
    if not WP_API_URL:
        print("WARNING: WP_API_URL environment variable is missing.")
    
    entries = fetch_reddit_rss()
    
    processed_codes = set()
    
    for entry in entries:
        title = entry.title
        # Handle summary/content safely
        content = ""
        if hasattr(entry, 'summary'):
            content = entry.summary
        elif hasattr(entry, 'description'):
            content = entry.description
            
        # Combine text for search
        full_text = f"{title} {content}"
        
        # Clean HTML tags very roughly
        clean_text = re.sub('<[^<]+?>', ' ', full_text)
        
        # Extract codes with new logic
        codes = extract_potential_codes(clean_text)
        
        for code in codes:
            if code in processed_codes:
                continue

            submit_code_to_api(code, title, entry.link)
            processed_codes.add(code)
            
            # Rate limit slightly
            if WP_API_URL:
                time.sleep(1)

    print("--- Radar Scan Completed ---")

if __name__ == "__main__":
    main()
