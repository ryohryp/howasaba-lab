import os
import requests
import feedparser
import re
import time
from bs4 import BeautifulSoup

from supabase import create_client, Client

# Sources Configuration
# Supported types: 'reddit_rss', 'html_wiki', 'sns_stub'
SOURCES = [
    {"type": "reddit_rss", "url": "https://www.reddit.com/r/whiteoutsurvival/new/.rss", "name": "Reddit"},
    {"type": "html_wiki", "url": "https://www.whiteoutsurvival.wiki/giftcodes/", "name": "WOS Wiki"},
    {"type": "sns_stub", "url": "", "name": "Official SNS"}
]

# Keyword Detection (Case-insensitive)
SPECIAL_KEYWORDS = {
    "jpholiday": 2.0, # High priority / specific
    "wos": 1.5,
    "whiteout": 1.2,
    "gift": 1.0,
    "code": 1.0
}

# Expanded Ignore List
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

# Supabase Configuration
SUPABASE_URL = os.environ.get("SUPABASE_URL")
SUPABASE_KEY = os.environ.get("SUPABASE_SERVICE_ROLE_KEY")

supabase: Client | None = None
if SUPABASE_URL and SUPABASE_KEY:
    supabase = create_client(SUPABASE_URL, SUPABASE_KEY)

# WordPress Configuration
WP_API_URL = os.environ.get("WP_API_URL") # Example: https://howasaba-code.com/wp-json/wp/v2/gift_code
WP_RADAR_TOKEN = os.environ.get("WP_RADAR_TOKEN")

def fetch_reddit_rss(url):
    """
    Fetches the Reddit RSS feed using a custom User-Agent via requests,
    then parses it with feedparser.
    """
    headers = {
        'User-Agent': 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36 WosRadar/1.0'
    }
    
    try:
        print(f"Fetching RSS feed from: {url}")
        response = requests.get(url, headers=headers, timeout=10)
        
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

def fetch_html_source(url, name="External"):
    """
    Fetches raw HTML from a URL and extracts potential codes via BeautifulSoup.
    """
    headers = {
        'User-Agent': 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36 WosRadar/1.0'
    }
    try:
        print(f"Fetching HTML source from {name}: {url}")
        response = requests.get(url, headers=headers, timeout=15)
        if response.status_code == 200:
            soup = BeautifulSoup(response.text, 'html.parser')
            
            # Target specific container for whiteoutsurvival.wiki
            # If others are added, we might need a per-URL selector map
            found_codes = []
            if "whiteoutsurvival.wiki" in url:
                # Based on subagent investigation: div.my-post-content span.code
                selectors = ['.my-post-content span.code', 'span.code']
                for selector in selectors:
                    elements = soup.select(selector)
                    for el in elements:
                        txt = el.get_text(strip=True)
                        if txt:
                            # Still use extract logic for normalization and filtering
                            found_codes.extend(extract_potential_codes(txt))
            else:
                # Fallback to general text scraping
                text = soup.get_text(separator=' ')
                found_codes = extract_potential_codes(text)
                
            return list(set(found_codes))
        else:
            print(f"Error fetching HTML from {name}: Status {response.status_code}")
            return []
    except Exception as e:
        print(f"Error fetching HTML from {name}: {e}")
        return []

def fetch_sns_codes_stub():
    """
    STUB: Placeholder for fetching gift codes from official SNS (X/Facebook).
    Future implementation may use scraping or official APIs.
    """
    # Example logic:
    # 1. Access X.com/Facebook official page
    # 2. Extract latest posts
    # 3. Use extract_potential_codes(post_text)
    print("Fetching codes from SNS (Stub)...")
    return [] # Currently returns empty list until implemented

def extract_potential_codes(text):
    """
    Enhanced extraction logic:
    1. Only process text containing 'code' or 'gift'.
    2. Extract 5-15 char alphanumeric strings (Upper + Lower).
    3. Filter out IGNORE_LIST.
    4. Validate:
       - If contains digit OR matches keywords: High Confidence.
       - If ALL UPPER without digit: Needs context.
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
        
        # Keyword Boost Logic
        cand_lower = cand.lower()
        keyword_match = any(kw in cand_lower for kw in SPECIAL_KEYWORDS)
        
        # Rule A: Contains digit OR starts with special keyword -> Validate as code
        if has_digit or (keyword_match and len(cand) >= 5):
            valid_codes.add(cand.upper()) 
            continue
            
        # Rule B: Digit-less -> Must be ALL UPPER + Context Check
        if is_all_upper:
            idx = text.find(cand) 
            if idx > 0:
                start_context = max(0, idx - 50)
                context_snippet = text[start_context:idx].lower()
                indicators = ["code", "gift", "cdk", "key", "redeem", "coupon"]
                if any(ind in context_snippet for ind in indicators):
                    valid_codes.add(cand.upper())

    return list(valid_codes)

def get_existing_codes_from_wp():
    """
    Fetches the existing codes list from WordPress in one go.
    Returns a set of normalized (uppercase) codes.
    """
    if not WP_API_URL or not WP_RADAR_TOKEN:
        return set()

    headers = {'X-Radar-Token': WP_RADAR_TOKEN}
    # Latest 100 codes should be enough for deduplication
    url = WP_API_URL 
    params = {
        "per_page": 100,
        "status": "publish,draft"
    }

    try:
        print("Fetching existing codes from WordPress for deduplication...")
        response = requests.get(url, headers=headers, params=params, timeout=20)
        if response.status_code == 200:
            posts = response.json()
            existing = set()
            for post in posts:
                # Title typically holds the code or "ギフトコード: CODE"
                title = post.get('title', {}).get('rendered', '')
                title_match = re.search(r'ギフトコード: ([A-Za-z0-9]+)', title)
                if title_match:
                    existing.add(title_match.group(1).upper())
                else:
                    existing.add(title.upper()) 
                
                # Also check ACF
                acf_code = post.get('acf', {}).get('code_string')
                if acf_code:
                    existing.add(acf_code.upper())
            print(f"Found {len(existing)} existing codes in WordPress.")
            return existing
        else:
            print(f"Warning: Failed to fetch existing codes from WP (Status {response.status_code})")
            return set()
    except Exception as e:
        print(f"Warning: Error fetching WP codes: {e}")
        return set()

def submit_code_to_supabase(code, source_title, source_link):
    """
    Submits the extracted code to Supabase gift_codes table.
    """
    if not supabase:
        print(f"[DRY RUN] Would submit to Supabase: {code}")
        return

    data = {
        "code": code,
        "rewards": f"Found via {source_title[:50]}...\nSource: {source_link}",
        "source": "radar-script",
        "is_active": True
    }
    
    try:
        print(f"Submitting code to Supabase: {code}...")
        response = supabase.table("gift_codes").insert(data).execute()
        print(f"SUCCESS: Code '{code}' registered in Supabase.")
    except Exception as e:
        error_str = str(e).lower()
        if "duplicate key value violates unique constraint" in error_str or "23505" in error_str:
            print(f"SKIPPED: Code '{code}' already exists in Supabase.")
        else:
            print(f"FAILED: Code '{code}' in Supabase - Error: {e}")

def submit_code_to_wordpress(code, rewards, existing_codes, expiration_date=None):
    """
    Submits the gift code to WordPress using the custom 'wos-radar/v1/add-code' endpoint.
    This custom endpoint handles deduplication and ACF mapping correctly.
    """
    if not WP_API_URL or not WP_RADAR_TOKEN:
        print(f"[DRY RUN] Would submit to WordPress: {code}")
        return

    # Check for duplicates using the pre-fetched list (Client-side check)
    if code.upper() in existing_codes:
        print(f"SKIPPED: Code '{code}' already exists in WordPress (Client-side).")
        return

    # Derive the custom endpoint URL from the base WP_API_URL
    # Standard: .../wp-json/wp/v2/gift_code -> Custom: .../wp-json/wos-radar/v1/add-code
    base_url = WP_API_URL.split('/wp/v2/')[0] if '/wp/v2/' in WP_API_URL else WP_API_URL.split('/wp-json/')[0] + '/wp-json' if '/wp-json/' in WP_API_URL else WP_API_URL
    custom_endpoint = f"{base_url.rstrip('/')}/wos-radar/v1/add-code"

    headers = {
        'X-Radar-Token': WP_RADAR_TOKEN,
        'Content-Type': 'application/json'
    }

    # Payload for the custom 'wos-radar/v1/add-code' endpoint
    payload = {
        "code_string": code,
        "rewards": rewards,
        "expiration_date": expiration_date if expiration_date else "",
        "status": "publish"
    }

    try:
        print(f"Submitting code to WordPress (Custom API): {code}...")
        response = requests.post(custom_endpoint, json=payload, headers=headers, timeout=20)
        
        if response.status_code == 201:
            print(f"SUCCESS: Code '{code}' created in WordPress.")
        elif response.status_code == 200:
            # The custom endpoint might return 200 if it was skipped internally
            res_data = response.json()
            if res_data.get('code') == 'gift_code_exists':
                print(f"SKIPPED: Code '{code}' already exists (Server-side check).")
            else:
                print(f"SUCCESS: WordPress response: {res_data.get('message')}")
        else:
            print(f"FAILED: WordPress API Status {response.status_code}")
            print(f"Response: {response.text}")
    except Exception as e:
        print(f"FAILED: WordPress Submission Error: {e}")

def main():
    print("--- WOS Gift Code Radar (Standard REST API) Started ---")
    
    # Pre-fetch existing codes for efficiency
    existing_codes = get_existing_codes_from_wp()
    processed_in_session = set()
    
    for src in SOURCES:
        src_type = src.get("type")
        src_url = src.get("url")
        src_name = src.get("name")
        
        codes_found = []
        
        if src_type == "reddit_rss":
            entries = fetch_reddit_rss(src_url)
            for entry in entries:
                content = getattr(entry, 'summary', getattr(entry, 'description', ''))
                clean_text = re.sub('<[^<]+?>', ' ', f"{entry.title} {content}")
                found = extract_potential_codes(clean_text)
                for c in found:
                    codes_found.append({"code": c, "title": entry.title, "link": entry.link})
                    
        elif src_type == "html_wiki":
            found = fetch_html_source(src_url, src_name)
            for c in found:
                codes_found.append({"code": c, "title": src_name, "link": src_url})
                
        elif src_type == "sns_stub":
            found = fetch_sns_codes_stub()
            for c in found:
                codes_found.append({"code": c, "title": src_name, "link": src_url})

        # Process found codes
        for item in codes_found:
            code = item["code"]
            if code in processed_in_session:
                continue
            
            # Submit to WordPress
            submit_code_to_wordpress(code, f"Found via {item['title']}\nSource: {item['link']}", existing_codes)
            
            # Submit to Supabase (Legacy)
            submit_code_to_supabase(code, item["title"], item["link"])
            
            processed_in_session.add(code)
            time.sleep(1)

    print("--- Radar Scan Completed ---")

if __name__ == "__main__":
    main()
