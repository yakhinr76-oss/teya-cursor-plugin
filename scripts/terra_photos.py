#!/usr/bin/env python3
"""Parse Terra location/photos payloads for WordPress CDN embeds."""
from __future__ import annotations

from typing import Any


def tripadvisor_property_url(location_body: dict[str, Any]) -> str:
    urls = location_body.get("urls") or {}
    ta = urls.get("tripadvisor") or {}
    if isinstance(ta, dict):
        main = str(ta.get("main") or "").strip()
        if main:
            return main
    return ""


def location_display_name(location_body: dict[str, Any]) -> str:
    for item in location_body.get("names") or []:
        if isinstance(item, dict) and item.get("primary"):
            return str(item.get("value") or "").strip()
    for item in location_body.get("names") or []:
        if isinstance(item, dict) and item.get("value"):
            return str(item.get("value") or "").strip()
    return ""


def normalize_photo_entries(photos_body: dict[str, Any]) -> list[dict[str, Any]]:
    rows: list[dict[str, Any]] = []
    for item in photos_body.get("data") or []:
        if not isinstance(item, dict):
            continue
        photo = item.get("photo") or {}
        cdn = str(photo.get("original_size_url") or "").strip()
        if not cdn.startswith("https://"):
            continue
        source = item.get("source") or {}
        source_name = str(source.get("name") or "").strip() if isinstance(source, dict) else ""
        rows.append(
            {
                "photo_id": item.get("id"),
                "location_id": item.get("location_id"),
                "cdn_url": cdn,
                "source": source_name,
                "media_type": photo.get("media_type"),
                "width": photo.get("original_width"),
                "height": photo.get("original_height"),
            }
        )
    return rows


def pick_photos_for_display(
    entries: list[dict[str, Any]],
    *,
    limit: int = 3,
    management_first: bool = True,
) -> list[dict[str, Any]]:
    if limit <= 0:
        return []
    pool = list(entries)
    if management_first:
        mgmt = [p for p in pool if (p.get("source") or "").lower() == "management"]
        other = [p for p in pool if p not in mgmt]
        pool = mgmt + other
    return pool[:limit]
