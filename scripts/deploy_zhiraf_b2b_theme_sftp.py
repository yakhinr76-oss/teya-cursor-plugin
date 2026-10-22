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


def env(name: str, default: str = "") -> str:
    return os.environ.get(name, default).strip()


def should_skip(rel: str) -> bool:
    parts = Path(rel).parts
    return any(p in IGNORE for p in parts)


def main() -> int:
    parser = argparse.ArgumentParser()
    parser.add_argument("--project-root", default="/workspace")
    parser.add_argument("--dry-run", action="store_true")
    args = parser.parse_args()

    host = env("B2B_TOUR_SSH_HOST")
    user = env("B2B_TOUR_SSH_USER")
    password = env("B2B_TOUR_SSH_PASS")
    key_path = env("B2B_TOUR_SSH_KEY_PATH")
    port = int(env("B2B_TOUR_SSH_PORT", "22") or "22")
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
