#!/usr/bin/env python3
"""Smoke-test Tripadvisor Terra API key (no secret output)."""
from __future__ import annotations

import argparse
import json
import sys
from pathlib import Path

from terra_api import resolve_api_key, terra_get


def main() -> int:
    parser = argparse.ArgumentParser(description="Probe Terra API using Cloud/env/local key.")
    parser.add_argument("--project-root", type=Path, default=None)
    sub = parser.add_subparsers(dest="cmd", required=True)

    search = sub.add_parser("search", help="GET /locations/search")
    search.add_argument("query", help="Free-text query")
    search.add_argument("--size", type=int, default=3)
    search.add_argument("--category", choices=("HOTEL", "RESTAURANT", "ATTRACTION"))

    loc = sub.add_parser("location", help="GET /locations/{id}")
    loc.add_argument("location_id", type=int)

    args = parser.parse_args()
    try:
        api_key, source = resolve_api_key(root=args.project_root)
    except RuntimeError as exc:
        print(str(exc), file=sys.stderr)
        return 2

    print(f"key_source: {source}")

    if args.cmd == "search":
        params: dict[str, str | int] = {"query": args.query, "size": min(args.size, 20)}
        if args.category:
            params["category"] = args.category
        status, body = terra_get("/locations/search", api_key=api_key, params=params)
    else:
        status, body = terra_get(
            f"/locations/{args.location_id}",
            api_key=api_key,
            params={},
        )

    print(f"http_status: {status}")
    print(json.dumps(body, ensure_ascii=False, indent=2)[:8000])
    if status == 401:
        print("hint: invalid or disabled X-API-Key", file=sys.stderr)
        return 1
    if status == 403:
        print("hint: endpoint not in your Terra subscription", file=sys.stderr)
        return 1
    if status == 404 and args.cmd == "location":
        print(
            "hint: unknown id or location not on your Terra allowlist (both return 404)",
            file=sys.stderr,
        )
    return 0 if status < 400 else 1


if __name__ == "__main__":
    raise SystemExit(main())
