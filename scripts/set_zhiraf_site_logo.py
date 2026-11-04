#!/usr/bin/env python3
"""Set site_logo (media ID 2671) on b2b-tour.ru via WordPress REST API."""
from __future__ import annotations

import base64
import json
import os
import sys
import urllib.request

SITE = "https://b2b-tour.ru"
USER = "site-admin"
LOGO_ID = 2671


def main() -> int:
    password = os.environ.get("B2B_TOUR_WP_APP_PASSWORD", "").strip()
    if not password:
        print("BLOCKER: B2B_TOUR_WP_APP_PASSWORD not set", file=sys.stderr)
        return 2
    token = base64.b64encode(f"{USER}:{password}".encode()).decode()
    headers = {
        "Authorization": f"Basic {token}",
        "Content-Type": "application/json",
        "Accept": "application/json",
    }
    body = json.dumps({"site_logo": LOGO_ID}).encode("utf-8")
    req = urllib.request.Request(
        SITE + "/wp-json/wp/v2/settings",
        data=body,
        headers=headers,
        method="POST",
    )
    with urllib.request.urlopen(req, timeout=60) as resp:
        data = json.loads(resp.read().decode("utf-8"))
    print(f"site_logo set to {data.get('site_logo', LOGO_ID)}")
    return 0


if __name__ == "__main__":
    raise SystemExit(main())
