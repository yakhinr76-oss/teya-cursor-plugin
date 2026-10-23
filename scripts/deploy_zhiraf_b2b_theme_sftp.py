#!/usr/bin/env python3
"""Upload zhiraf-ufa theme to b2b-tour.ru staging via SFTP.

Environment (Cloud Agent secrets):
  B2B_TOUR_SSH_HOST          — hostname
  B2B_TOUR_SSH_PORT          — optional, default 22
  B2B_TOUR_SSH_USER          — username
  B2B_TOUR_SSH_PASS          — password (or B2B_TOUR_SSH_KEY_PATH for key)
  B2B_TOUR_SSH_THEME_PATH    — remote dir, e.g. .../wp-content/themes/zhiraf-ufa

Local theme: deliverables/zhiraf-ufa/wp-theme/zhiraf-ufa/
"""
from __future__ import annotations

import argparse
import os
import sys
from pathlib import Path

try:
    import paramiko
except ImportError:
    print("pip install paramiko", file=sys.stderr)
    sys.exit(2)

IGNORE = {".git", "node_modules", ".DS_Store", "assets/src"}

THEME_SLUG = "zhiraf-ufa"
REMOTE_CANDIDATES = [
    f"wp-content/themes/{THEME_SLUG}",
    f"public_html/wp-content/themes/{THEME_SLUG}",
    f"www/b2b-tour.ru/wp-content/themes/{THEME_SLUG}",
    f"data/www/b2b-tour.ru/wp-content/themes/{THEME_SLUG}",
    f"domains/b2b-tour.ru/public_html/wp-content/themes/{THEME_SLUG}",
    f"home/b2b-tour.ru/public_html/wp-content/themes/{THEME_SLUG}",
]


def env(name: str, default: str = "") -> str:
    return os.environ.get(name, default).strip()


def env_any(names: tuple[str, ...]) -> str:
    for name in names:
        value = env(name)
        if value:
            return value
    return ""


def resolve_remote_theme_path(sftp: paramiko.SFTPClient, configured: str) -> str:
    if configured:
        try:
            sftp.stat(f"{configured.rstrip('/')}/style.css")
            return configured.rstrip("/")
        except OSError:
            print(f"WARN: configured path missing style.css: {configured}", file=sys.stderr)

    home = sftp.normalize(".")
    roots = [home, "/"]
    seen: set[str] = set()
    for root in roots:
        for rel in REMOTE_CANDIDATES:
            remote = f"{root.rstrip('/')}/{rel}".replace("//", "/")
            if remote in seen:
                continue
            seen.add(remote)
            try:
                sftp.stat(f"{remote}/style.css")
                print(f"Resolved theme path: {remote}")
                return remote
            except OSError:
                continue

    print(
        "BLOCKER: could not find wp-content/themes/zhiraf-ufa — set B2B_TOUR_SSH_THEME_PATH",
        file=sys.stderr,
    )
    sys.exit(1)


def should_skip(rel: str) -> bool:
    parts = Path(rel).parts
    return any(p in IGNORE for p in parts)


def main() -> int:
    parser = argparse.ArgumentParser()
    parser.add_argument("--project-root", default="/workspace")
    parser.add_argument("--dry-run", action="store_true")
    args = parser.parse_args()

    host = env_any(("B2B_TOUR_SSH_HOST", "SSH_HOST", "SFTP_HOST", "FTP_HOST"))
    user = env_any(("B2B_TOUR_SSH_USER", "SSH_USER", "SFTP_USER", "FTP_USER"))
    password = env_any(("B2B_TOUR_SSH_PASS", "SSH_PASS", "SFTP_PASS", "FTP_PASS"))
    key_path = env_any(("B2B_TOUR_SSH_KEY_PATH", "SSH_KEY_PATH"))
    port = int(
        env_any(("B2B_TOUR_SSH_PORT", "SSH_PORT", "SFTP_PORT", "FTP_PORT")) or "22"
    )
    remote_base = env("B2B_TOUR_SSH_THEME_PATH").rstrip("/")

    if not all([host, user, remote_base]):
        print(
            "BLOCKER: set B2B_TOUR_SSH_HOST, B2B_TOUR_SSH_USER, B2B_TOUR_SSH_THEME_PATH",
            file=sys.stderr,
        )
        return 1
    if not password and not key_path:
        print("BLOCKER: set B2B_TOUR_SSH_PASS or B2B_TOUR_SSH_KEY_PATH", file=sys.stderr)
        return 1

    theme_local = Path(args.project_root).resolve() / "deliverables/zhiraf-ufa/wp-theme/zhiraf-ufa"
    if not theme_local.is_dir():
        print(f"BLOCKER: theme missing: {theme_local}", file=sys.stderr)
        return 1

    files = []
    for path in theme_local.rglob("*"):
        if path.is_file():
            rel = path.relative_to(theme_local).as_posix()
            if should_skip(rel):
                continue
            files.append((path, rel))

    print(f"Upload {len(files)} files → {host}:{remote_base}")
    if args.dry_run:
        for _, rel in files[:20]:
            print(" ", rel)
        if len(files) > 20:
            print(f"  ... and {len(files) - 20} more")
        return 0

    transport = paramiko.Transport((host, port))
    try:
        if key_path:
            pkey = paramiko.RSAKey.from_private_key_file(key_path)
            transport.connect(username=user, pkey=pkey)
        else:
            transport.connect(username=user, password=password)
        sftp = paramiko.SFTPClient.from_transport(transport)
        remote_base = resolve_remote_theme_path(sftp, remote_base)

        def ensure_dir(remote_dir: str) -> None:
            parts = remote_dir.strip("/").split("/")
            cur = ""
            for part in parts:
                cur += "/" + part
                try:
                    sftp.stat(cur)
                except OSError:
                    sftp.mkdir(cur)

        for local, rel in files:
            remote = f"{remote_base}/{rel}"
            ensure_dir(str(Path(remote).parent).replace("\\", "/"))
            sftp.put(str(local), remote)
        sftp.close()
    finally:
        transport.close()

    print("OK: theme uploaded. Purge host cache if used, then hard-refresh browser.")
    return 0


if __name__ == "__main__":
    sys.exit(main())
