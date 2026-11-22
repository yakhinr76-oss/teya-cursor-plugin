"""Shared Terra quota helpers for hotel JSON enrichment (Mode A: tacdn CDN embed)."""
from __future__ import annotations

from typing import Any

TACDN_MARKER = "dynamic-media.tacdn.com"

DOMESTIC_COUNTRIES = frozenset({"russia", "abkhazia"})


def photo_is_tacdn(value: Any) -> bool:
    return isinstance(value, str) and TACDN_MARKER in value.lower()


def hotel_has_mode_a(hotel: dict[str, Any]) -> bool:
    """ta_location_id + at least one tacdn URL in photos[] — no API needed."""
    if not hotel.get("ta_location_id"):
        return False
    photos = hotel.get("photos") or []
    if not isinstance(photos, list):
        return False
    return any(photo_is_tacdn(p) for p in photos)


def hotel_needs_api(hotel: dict[str, Any], *, force: bool = False) -> bool:
    if force:
        return True
    return not hotel_has_mode_a(hotel)


def audit_hotel_files(paths: list, *, domestic: frozenset[str] | None = None) -> dict[str, Any]:
    """Offline stats per data/*_hotels_*.json (no HTTP)."""
    domestic = domestic or DOMESTIC_COUNTRIES
    rows: list[dict[str, Any]] = []
    abroad_total = abroad_missing = 0
    domestic_total = domestic_missing = 0

    for path in paths:
        import json
        from pathlib import Path

        p = Path(path)
        if not p.is_file():
            continue
        hotels = json.loads(p.read_text(encoding="utf-8"))
        if not isinstance(hotels, list):
            continue
        total = len(hotels)
        missing = sum(1 for h in hotels if isinstance(h, dict) and not hotel_has_mode_a(h))
        country = ""
        if hotels and isinstance(hotels[0], dict):
            country = str(hotels[0].get("country") or "").strip().lower()
        is_domestic = country in domestic
        if is_domestic:
            domestic_total += total
            domestic_missing += missing
        else:
            abroad_total += total
            abroad_missing += missing
        rows.append(
            {
                "file": p.name,
                "country": country or "?",
                "bucket": "russia/abkhazia" if is_domestic else "abroad",
                "total": total,
                "mode_a": total - missing,
                "needs_api": missing,
            }
        )

    return {
        "files": rows,
        "abroad": {"total": abroad_total, "needs_api": abroad_missing, "mode_a": abroad_total - abroad_missing},
        "domestic": {"total": domestic_total, "needs_api": domestic_missing, "mode_a": domestic_total - domestic_missing},
        "grand_total": abroad_total + domestic_total,
        "grand_needs_api": abroad_missing + domestic_missing,
    }


def print_audit_report(report: dict[str, Any]) -> None:
    print("\n=== Hotel JSON audit (Mode A: ta_location_id + tacdn) ===\n")
    print(f"{'file':<32} {'bucket':<16} {'total':>5} {'ok':>5} {'need API':>8}")
    print("-" * 72)
    for row in sorted(report["files"], key=lambda r: r["file"]):
        print(
            f"{row['file']:<32} {row['bucket']:<16} {row['total']:>5} "
            f"{row['mode_a']:>5} {row['needs_api']:>8}"
        )
    print("-" * 72)
    ab = report["abroad"]
    dom = report["domestic"]
    print(f"Зарубежные:     всего {ab['total']:>4}  |  Mode A: {ab['mode_a']:>4}  |  нужен API: {ab['needs_api']:>4}")
    print(f"РФ / Абхазия:   всего {dom['total']:>4}  |  Mode A: {dom['mode_a']:>4}  |  нужен API: {dom['needs_api']:>4}")
    print(f"ИТОГО:          всего {report['grand_total']:>4}  |  нужен API: {report['grand_needs_api']:>4}")
    est = report["grand_needs_api"] * 2
    print(f"\nОценка квоты при 2 запроса/отель (только need API): ~{est} HTTP-вызовов")