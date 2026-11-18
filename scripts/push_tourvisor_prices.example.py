#!/usr/bin/env python3
"""Example push after parser wrote output/tourvisor-prices.json.

Copy to the parser repo or call from CI. Set env vars only — never commit secrets.

Required:
  ZHIRAF_PRICE_WEBHOOK_URL   e.g. https://b2b-tour.ru/wp-json/zhiraf/v1/update-prices
  ZHIRAF_TOURVISOR_WEBHOOK_TOKEN   same value as in WP → Настройки → Цены Tourvisor
Optional:
  TOURVISOR_PRICES_JSON      path to JSON (default: output/tourvisor-prices.json)
"""
from __future__ import annotations

import json
import os
import sys
from pathlib import Path

try:
    import requests
except ImportError:
    print("pip install requests", file=sys.stderr)
    raise SystemExit(2)


def main() -> int:
    url = os.environ.get("ZHIRAF_PRICE_WEBHOOK_URL", "").strip()
    token = os.environ.get("ZHIRAF_TOURVISOR_WEBHOOK_TOKEN", "").strip()
    json_path = Path(os.environ.get("TOURVISOR_PRICES_JSON", "output/tourvisor-prices.json"))

    if not url or not token:
        print("Set ZHIRAF_PRICE_WEBHOOK_URL and ZHIRAF_TOURVISOR_WEBHOOK_TOKEN", file=sys.stderr)
        return 1
    if not json_path.is_file():
        print(f"Missing file: {json_path}", file=sys.stderr)
        return 1

    payload = json.loads(json_path.read_text(encoding="utf-8"))
    resp = requests.post(
        url,
        json=payload,
        headers={
            "Content-Type": "application/json",
            "X-Zhiraf-Token": token,
        },
        timeout=60,
    )
    print(resp.status_code, resp.text[:500])
    return 0 if resp.ok else 1


if __name__ == "__main__":
    raise SystemExit(main())