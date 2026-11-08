#!/usr/bin/env python3
"""Download only the listed hub files from user-v3-applied-picks.json (by basename filter)."""
from __future__ import annotations

import json
import os
import sys

HERE = os.path.dirname(os.path.abspath(__file__))
sys.path.insert(0, HERE)
from apply_user_v3_picks import download  # noqa: E402

MANIFEST = os.path.join(HERE, "user-v3-applied-picks.json")
IMG = os.path.join(HERE, "..", "wp-theme", "zhiraf-ufa", "assets", "images")

SUBSET = {
    "dest-turkey-new.jpg",
    "dest-uae-new.jpg",
    "dest-egypt-new.jpg",
    "dest-thailand-new.jpg",
    "dest-vietnam-new.jpg",
}


def main() -> None:
    picks = json.load(open(MANIFEST, encoding="utf-8"))
    for row in picks["files"]:
        if row["file"] not in SUBSET:
            continue
        dest = os.path.join(IMG, row["file"])
        download(int(row["pexels_id"]), dest)
        print(row["label"], "→", row["file"])


if __name__ == "__main__":
    main()
