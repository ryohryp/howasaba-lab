import os
import requests
import markdown
import sys

# Configuration
# Users should set these environment variables
WP_API_URL = os.environ.get("WP_API_URL")
RADAR_SECRET_TOKEN = os.environ.get("WOS_RADAR_TOKEN")
DRAFT_FILE_PATH = os.path.join(os.path.dirname(__file__), '../drafts/gen6_review.md')

def post_article():
    """
    Reads the markdown draft, converts to HTML, and posts to WordPress.
    """
    print("--- Gen 6 Article Poster Started ---")

    # 1. Validation
    missing_vars = []
    if not WP_API_URL:
        missing_vars.append("WP_API_URL")
    if not RADAR_SECRET_TOKEN:
        missing_vars.append("WOS_RADAR_TOKEN")
        
    if missing_vars:
        print("Error: Missing environment variables:")
        for var in missing_vars:
            print(f"  - {var}")
        print("\nPlease set them before running. Example:")
        print("  $env:WP_API_URL='https://howasaba-code.com/wp-json/wos-radar/v1/create-post'")
        print("  $env:WOS_RADAR_TOKEN='WosRadarSecret2026_Operation!'")
        return

    post_url = WP_API_URL
    # Logic to ensure we are hitting the create-post endpoint if a generic base URL is provided
    # If the user provided the 'add-code' endpoint by mistake (copy-paste from other script), fix it.
    if '/add-code' in post_url:
        print(f"Warning: WP_API_URL points to 'add-code'. Switching to 'create-post'.")
        post_url = post_url.replace('/add-code', '/create-post')
    elif not post_url.endswith('/create-post'):
        # If it doesn't end in create-post, warn but proceed (might be a custom alias)
        # OR attempt to append if it looks like a base root.
        if post_url.endswith('/v1') or post_url.endswith('/v1/'):
             post_url = post_url.rstrip('/') + '/create-post'
             print(f"Auto-appended endpoint: {post_url}")

    if not os.path.exists(DRAFT_FILE_PATH):
        print(f"Error: Draft file not found at {DRAFT_FILE_PATH}")
        return

    # 2. Processing
    print(f"Reading draft from: {DRAFT_FILE_PATH}")
    try:
        with open(DRAFT_FILE_PATH, 'r', encoding='utf-8') as f:
            md_content = f.read()
    except Exception as e:
        print(f"Error reading file: {e}")
        return

    html_content = markdown.markdown(md_content)
    
    payload = {
        "title": "第6世代（Gen 6）英雄徹底評価：無名・レネ・ウェインは引くべきか？",
        "content": html_content,
        "status": "draft",
        "slug": "gen6-hero-review" 
    }
    
    headers = {
        'X-Radar-Token': RADAR_SECRET_TOKEN
    }

    # 3. Submission
    print(f"Submitting article to: {post_url}")
    try:
        response = requests.post(post_url, json=payload, headers=headers)
        
        if response.status_code == 201:
            data = response.json()
            print(f"SUCCESS: Article created successfully.")
            print(f"  ID: {data.get('post_id')}")
            print(f"  Title: {data.get('title')}")
            print("  Status: Draft (Check WP Admin)")
        else:
            print(f"FAILED: Status {response.status_code}")
            print(f"Response: {response.text}")
            
    except Exception as e:
        print(f"Network Error: {e}")

if __name__ == "__main__":
    post_article()

