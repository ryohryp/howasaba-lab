import os
import requests
import markdown
import sys

# Configuration
# Default to production URL if not set
WP_BASE_URL = os.environ.get("WP_BASE_URL", "https://howasaba-code.com")
WP_API_ENDPOINT = f"{WP_BASE_URL}/wp-json/wos-radar/v1/update-post"
X_RADAR_TOKEN = os.environ.get("X_RADAR_TOKEN")

# Target Post Slug
# Allow overriding via environment variable (e.g. from GitHub Actions input)
TARGET_SLUG = os.environ.get("TARGET_SLUG", "hero-leveling-myth-5")
# Backup slug if the original is not found (and we might want to create likely)
# But API only updates.

CONTENT_FILE = os.path.join(os.path.dirname(__file__), "content", "hero-leveling-myth-5.md")

def load_content():
    if not os.path.exists(CONTENT_FILE):
        print(f"Error: Content file not found at {CONTENT_FILE}")
        sys.exit(1)
    
    with open(CONTENT_FILE, 'r', encoding='utf-8') as f:
        md_content = f.read()
    
    # Convert Markdown to HTML
    # We use 'extra' extension to support markdown features like tables, etc.
    html_content = markdown.markdown(md_content, extensions=['extra'])
    return html_content

def update_post(slug, content):
    if not X_RADAR_TOKEN:
        print("Error: X_RADAR_TOKEN environment variable is not set.")
        sys.exit(1)

    headers = {
        'X-Radar-Token': X_RADAR_TOKEN,
        'Content-Type': 'application/json'
    }

    payload = {
        'slug': slug,
        'content': content,
        'title': '【重要】英雄育成の罠：第11世代メタ対応リファイン版', # Optional: Update title ensuring it matches
        'meta': {
            '_wos_updated_by': 'radar_script_v1'
        }
    }

    print(f"Sending update request to {WP_API_ENDPOINT} for slug: {slug}...")
    
    try:
        response = requests.post(WP_API_ENDPOINT, json=payload, headers=headers, timeout=30)
        
        if response.status_code == 200:
            print("SUCCESS: Post updated successfully.")
            print(response.json())
        else:
            print(f"FAILED: Status {response.status_code}")
            print(f"Response: {response.text}")
            sys.exit(1)

    except Exception as e:
        print(f"Error sending request: {e}")
        sys.exit(1)

if __name__ == "__main__":
    print("--- WOS Post Updater Started ---")
    html_content = load_content()
    update_post(TARGET_SLUG, html_content)
    print("--- Completed ---")
