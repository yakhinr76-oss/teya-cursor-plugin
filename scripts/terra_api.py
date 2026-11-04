#!/usr/bin/env python3
"""Tripadvisor Terra API helpers (Discover / Content API successor)."""
from __future__ import annotations

import json
import os
import urllib.error
import urllib.parse
import urllib.request
from pathlib import Path
from typing import Any

TERRA_API_BASE = "https://terra.tripadvisor.com/api"
ENV_KEY_NAMES = (
    "ZHIRAF_TA_API_KEY",
    "TERRA_API_KEY",
    "TRIPADVISOR_API_KEY",
)


def project_root(explicit: Path | None = None) -> Path:
    if explicit is not None:
        return explicit.resolve()
    candidate = Path(__file__).resolve().parents[2]
    if (candidate / "teya-memory").is_dir():
        return candidate
    cwd = Path.cwd().resolve()
    if (cwd / "teya-memory").is_dir():
        return cwd
    for parent in cwd.parents:
        if (parent / "teya-memory").is_dir():
            return parent
    return candidate


def load_env_file(path: Path) -> dict[str, str]:
    if not path.is_file():
        return {}
    values: dict[str, str] = {}
    for raw_line in path.read_text(encoding="utf-8").splitlines():
        line = raw_line.strip()
        if not line or line.startswith("#") or "=" not in line:
            continue
        key, value = line.split("=", 1)
        values[key.strip()] = value.strip().strip('"').strip("'")
    return values


def resolve_api_key(*, root: Path | None = None) -> tuple[str, str]:
    """Return (api_key, source_label). Never log the key itself."""
    for name in ENV_KEY_NAMES:
        value = os.environ.get(name, "").strip()
        if value:
            return value, f"env:{name}"

    base = project_root(root)
    for rel in ("teya-memory/teya.env.local", "teya/shared/teya.env.local"):
        env = load_env_file(base / rel)
        for name in ENV_KEY_NAMES:
            value = env.get(name, "").strip()
            if value:
                return value, f"{rel}:{name}"

    raise RuntimeError(
        "Terra API key not found. Set ZHIRAF_TA_API_KEY (Cloud Agent secret) or "
        "TERRA_API_KEY in teya-memory/teya.env.local"
    )


def terra_get(
    path: str,
    *,
    api_key: str,
    params: dict[str, str | int | list[str]] | None = None,
    timeout: float = 30.0,
) -> tuple[int, dict[str, Any] | list[Any] | str]:
    """GET Terra JSON endpoint. path is e.g. /locations/search (no host)."""
    path = path if path.startswith("/") else f"/{path}"
    query: dict[str, str] = {"version": "1"}
    if params:
        for key, value in params.items():
            if isinstance(value, list):
                for item in value:
                    query.setdefault(key, "")
                    if query[key]:
                        query[key] += f",{item}"
                    else:
                        query[key] = str(item)
            else:
                query[key] = str(value)

    url = TERRA_API_BASE + path
    if query:
        url += "?" + urllib.parse.urlencode(query, doseq=True)

    req = urllib.request.Request(
        url,
        headers={
            "X-API-Key": api_key,
            "Accept": "application/json",
            "User-Agent": "TeyaTerraClient/1.0",
        },
    )
    try:
        with urllib.request.urlopen(req, timeout=timeout) as resp:
            raw = resp.read().decode("utf-8", errors="replace")
            status = resp.status
    except urllib.error.HTTPError as exc:
        raw = exc.read().decode("utf-8", errors="replace")
        status = exc.code
        try:
            body: Any = json.loads(raw) if raw else {"detail": exc.reason}
        except json.JSONDecodeError:
            body = raw
        return status, body

    if not raw:
        return status, {}
    try:
        return status, json.loads(raw)
    except json.JSONDecodeError:
        return status, raw
