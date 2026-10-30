#!/usr/bin/env python3
"""Upload zhiraf-ufa theme to b2b-tour.ru staging via SFTP.

Environment (Cloud Agent secrets):
  B2B_TOUR_SSH_HOST          — hostname or ISPmanager URL (https://host:1500/)
  B2B_TOUR_SSH_PORT          — optional, default 22
  B2B_TOUR_SSH_USER          — username
  B2B_TOUR_SSH_PASS          — password (or B2B_TOUR_SSH_KEY_PATH for key)
  B2B_TOUR_SSH_THEME_PATH    — remote dir, e.g. .../wp-content/themes/zhiraf-ufa

Local theme: deliverables/zhiraf-ufa/wp-theme/zhiraf-ufa/
"""
from __future__ import annotations

import argparse
import io
import os
import shlex
import sys
import tarfile
from pathlib import Path
from urllib.parse import urlparse

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


def normalize_ssh_endpoint(raw_host: str, configured_port: int) -> tuple[str, int]:
    """Accept hostname or panel URL (https://host:1500/) from Cloud Agent secrets."""
    raw = raw_host.strip()
    if not raw:
        return "", configured_port
    if "://" not in raw and not raw.startswith("//"):
        if "/" in raw and ":" in raw.split("/")[0]:
            raw = f"ssh://{raw}"
        elif "/" in raw:
            raw = f"ssh://{raw.lstrip('/')}"
    if "://" in raw or raw.startswith("//"):
        parsed = urlparse(raw if "://" in raw else f"ssh:{raw}")
        host = (parsed.hostname or "").strip()
        if parsed.scheme in ("http", "https"):
            return host, configured_port
        port = parsed.port or configured_port
        return host, port
    return raw.split("/")[0].strip(), configured_port


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


def collect_files(theme_local: Path) -> list[tuple[Path, str]]:
    files: list[tuple[Path, str]] = []
    for path in theme_local.rglob("*"):
        if path.is_file():
            rel = path.relative_to(theme_local).as_posix()
            if should_skip(rel):
                continue
            files.append((path, rel))
    return files


def connect_transport(
    host: str, port: int, user: str, password: str, key_path: str
) -> paramiko.Transport:
    transport = paramiko.Transport((host, port))
    transport.banner_timeout = 30
    transport.set_keepalive(15)
    if key_path:
        pkey = paramiko.RSAKey.from_private_key_file(key_path)
        transport.connect(username=user, pkey=pkey)
    else:
        transport.connect(username=user, password=password)
    return transport


def upload_tar_stream(
    host: str,
    port: int,
    user: str,
    password: str,
    key_path: str,
    remote_base: str,
    files: list[tuple[Path, str]],
) -> None:
    buf = io.BytesIO()
    with tarfile.open(fileobj=buf, mode="w|") as tar:
        for local, rel in files:
            tar.add(local, arcname=rel)
    payload = buf.getvalue()
    print(f"  tar payload: {len(payload) / 1024 / 1024:.1f} MiB", flush=True)

    client = paramiko.SSHClient()
    client.set_missing_host_key_policy(paramiko.AutoAddPolicy())
    connect_kwargs: dict = {
        "hostname": host,
        "port": port,
        "username": user,
        "banner_timeout": 30,
        "allow_agent": False,
        "look_for_keys": False,
    }
    if key_path:
        connect_kwargs["pkey"] = paramiko.RSAKey.from_private_key_file(key_path)
    else:
        connect_kwargs["password"] = password
    client.connect(**connect_kwargs)
    cmd = f"mkdir -p {shlex.quote(remote_base)} && tar xf - -C {shlex.quote(remote_base)}"
    stdin, stdout, stderr = client.exec_command(cmd, timeout=600)
    stdin.channel.sendall(payload)
    stdin.channel.shutdown_write()
    err = stderr.read().decode("utf-8", errors="replace").strip()
    code = stdout.channel.recv_exit_status()
    client.close()
    if code != 0:
        raise RuntimeError(f"remote tar failed ({code}): {err or 'unknown error'}")


def upload_sftp_files(
    sftp: paramiko.SFTPClient,
    remote_base: str,
    files: list[tuple[Path, str]],
) -> None:
    def ensure_dir(remote_dir: str) -> None:
        parts = remote_dir.strip("/").split("/")
        cur = ""
        for part in parts:
            cur += "/" + part
            try:
                sftp.stat(cur)
            except OSError:
                sftp.mkdir(cur)

    total = len(files)
    for i, (local, rel) in enumerate(files, start=1):
        remote = f"{remote_base}/{rel}"
        ensure_dir(str(Path(remote).parent).replace("\\", "/"))
        sftp.put(str(local), remote)
        if i == 1 or i == total or i % 25 == 0:
            print(f"  {i}/{total} {rel}", flush=True)


def should_skip(rel: str) -> bool:
    parts = Path(rel).parts
    return any(p in IGNORE for p in parts)


def main() -> int:
    parser = argparse.ArgumentParser()
    parser.add_argument("--project-root", default="/workspace")
    parser.add_argument("--dry-run", action="store_true")
    parser.add_argument(
        "--method",
        choices=("tar", "sftp"),
        default="tar",
        help="tar: single stream (default); sftp: file-by-file",
    )
    args = parser.parse_args()

    raw_host = env_any(("B2B_TOUR_SSH_HOST", "SSH_HOST", "SFTP_HOST", "FTP_HOST"))
    user = env_any(("B2B_TOUR_SSH_USER", "SSH_USER", "SFTP_USER", "FTP_USER"))
    password = env_any(("B2B_TOUR_SSH_PASS", "SSH_PASS", "SFTP_PASS", "FTP_PASS"))
    key_path = env_any(("B2B_TOUR_SSH_KEY_PATH", "SSH_KEY_PATH"))
    port = int(
        env_any(("B2B_TOUR_SSH_PORT", "SSH_PORT", "SFTP_PORT", "FTP_PORT")) or "22"
    )
    host, port = normalize_ssh_endpoint(raw_host, port)
    remote_base = env("B2B_TOUR_SSH_THEME_PATH").rstrip("/")

    if not host or not user:
        print(
            "BLOCKER: set B2B_TOUR_SSH_HOST and B2B_TOUR_SSH_USER",
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

    files = collect_files(theme_local)
    dest = remote_base or "(auto-detect)"
    print(f"Upload {len(files)} files via {args.method} → {host}:{dest}")
    if args.dry_run:
        for _, rel in files[:20]:
            print(" ", rel)
        if len(files) > 20:
            print(f"  ... and {len(files) - 20} more")
        return 0

    transport = connect_transport(host, port, user, password, key_path)
    try:
        sftp = paramiko.SFTPClient.from_transport(transport)
        remote_base = resolve_remote_theme_path(sftp, remote_base)
        sftp.close()

        if args.method == "tar":
            print("Streaming tar to remote…", flush=True)
            upload_tar_stream(host, port, user, password, key_path, remote_base, files)
        else:
            sftp = paramiko.SFTPClient.from_transport(transport)
            upload_sftp_files(sftp, remote_base, files)
            sftp.close()
    finally:
        transport.close()

    print("OK: theme uploaded. Purge host cache if used, then hard-refresh browser.")
    return 0


if __name__ == "__main__":
    sys.exit(main())
