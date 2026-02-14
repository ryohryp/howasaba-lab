import os
import requests
import json
import time

# Configuration
WP_API_URL = os.environ.get("WP_API_URL")
WP_USER = os.environ.get("WP_USER")
WP_APP_PASSWORD = os.environ.get("WP_APP_PASSWORD")

# Mock data for demonstration (Replace with actual scraping logic later)
# In production, this would fetch from Discord/Reddit and parse with LLM
MOCK_GIFT_CODES = [
    {
        "code": "WOS2024FEB",
        "rewards": "Gem x100, Speedup 1h x5",
        "expiry": "2026-02-28"
    },
    {
        "code": "SURVIVAL777",
        "rewards": "Gem x500",
        "expiry": "2026-03-15"
    }
]

def fetch_gift_codes_from_source():
    """
    Fetches gift codes from external sources.
    Currently returns mock data.
    """
    print("Fetching gift codes from sources...")
    # TODO: Implement scraping from Discord/Reddit
    # TODO: Implement LLM parsing
    return MOCK_GIFT_CODES

def post_gift_code_to_wp(gift_code):
    """
    Posts a single gift code to the WordPress REST API.
    """
    url = f"{WP_API_URL}/wos/v1/gift-code"
    auth = (WP_USER, WP_APP_PASSWORD)
    
    payload = {
        "code": gift_code["code"],
        "rewards": gift_code.get("rewards", ""),
        "expiry": gift_code.get("expiry", "")
    }

    try:
        response = requests.post(url, json=payload, auth=auth)
        response.raise_for_status()
        
        result = response.json()
        print(f"Result for {gift_code['code']}: {result.get('message', 'No message')}")
        
    except requests.exceptions.RequestException as e:
        print(f"Error posting {gift_code['code']}: {e}")
        if hasattr(e, 'response') and e.response is not None:
            print(f"Response content: {e.response.text}")

def main():
    if not WP_API_URL or not WP_USER or not WP_APP_PASSWORD:
        print("Error: Environment variables WP_API_URL, WP_USER, WP_APP_PASSWORD must be set.")
        return

    codes = fetch_gift_codes_from_source()
    
    for code_data in codes:
        print(f"Processing code: {code_data['code']}")
        post_gift_code_to_wp(code_data)

if __name__ == "__main__":
    main()
