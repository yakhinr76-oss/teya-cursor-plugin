#!/usr/bin/env python3
"""Run hotel JSON → CPT imports on b2b-tour.ru via SSH (after theme deploy)."""
from __future__ import annotations

import argparse
import os
import sys

try:
    import paramiko
except ImportError:
    print("pip install paramiko", file=sys.stderr)
    sys.exit(2)

COUNTRIES = (
    "turkey",
    "egypt",
    "uae",
    "thailand",
    "vietnam",
    "china",
    "india",
    "maldives",
    "seychelles",
    "sri_lanka",
    "indonesia",
    "uzbekistan",
    "armenia",
    "russia",
    "abkhazia",
)


def env_any(names: tuple[str, ...]) -> str:
    for name in names:
        value = os.environ.get(name, "").strip()
        if value:
            return value
    return ""


def ssh_connect() -> paramiko.SSHClient:
    host = env_any(("B2B_TOUR_SSH_HOST", "SSH_HOST"))
    user = env_any(("B2B_TOUR_SSH_USER", "SSH_USER"))
    password = env_any(("B2B_TOUR_SSH_PASS", "SSH_PASS"))
    key_path = env_any(("B2B_TOUR_SSH_KEY_PATH", "SSH_KEY_PATH"))
    port = int(env_any(("B2B_TOUR_SSH_PORT", "SSH_PORT")) or "22")
    if "://" in host:
        from urllib.parse import urlparse

        parsed = urlparse(host if "://" in host else f"ssh://{host}")
        host = parsed.hostname or host
    if not host or not user:
        print("BLOCKER: B2B_TOUR_SSH_HOST / B2B_TOUR_SSH_USER", file=sys.stderr)
        sys.exit(1)
    if not password and not key_path:
        print("BLOCKER: B2B_TOUR_SSH_PASS or key", file=sys.stderr)
        sys.exit(1)
    client = paramiko.SSHClient()
    client.set_missing_host_key_policy(paramiko.AutoAddPolicy())
    if key_path:
        pkey = paramiko.RSAKey.from_private_key_file(key_path)
        client.connect(host, port=port, username=user, pkey=pkey, timeout=60)
    else:
        client.connect(host, port=port, username=user, password=password, timeout=60)
    return client


def run(client: paramiko.SSHClient, cmd: str) -> tuple[int, str, str]:
    stdin, stdout, stderr = client.exec_command(cmd, timeout=3600)
    del stdin
    out = stdout.read().decode("utf-8", errors="replace")
    err = stderr.read().decode("utf-8", errors="replace")
    code = stdout.channel.recv_exit_status()
    return code, out, err


def main() -> int:
    parser = argparse.ArgumentParser()
    parser.add_argument(
        "--skip-photos",
        action="store_true",
        help="Import meta/CPT only (no Terra photo download)",
    )
    parser.add_argument(
        "--countries",
        nargs="*",
        default=list(COUNTRIES),
        help="Subset of countries (default: all)",
    )
    parser.add_argument("--dry-run", action="store_true")
    args = parser.parse_args()

    theme_path = env_any(("B2B_TOUR_SSH_THEME_PATH", "SSH_THEME_PATH")).rstrip("/")
    php_bin = env_any(("B2B_TOUR_SSH_PHP_BIN", "SSH_PHP_BIN")) or "php"

    client = ssh_connect()
    try:
        if not theme_path:
            code, out, err = run(
                client,
                f"{php_bin} -r 'echo realpath(getcwd());' 2>/dev/null || pwd",
            )
            _ = (code, out, err)
            find_cmd = (
                "find $HOME -path '*/b2b-tour.ru/wp-content/themes/zhiraf-ufa/style.css' "
                "2>/dev/null | head -1 | sed 's|/style.css||'"
            )
            code, out, err = run(client, find_cmd)
            theme_path = out.strip().split("\n")[0] if out.strip() else ""
        if not theme_path:
            print("BLOCKER: set B2B_TOUR_SSH_THEME_PATH to theme directory", file=sys.stderr)
            return 1

        wp_load = f"{theme_path}/../../../wp-load.php"
        skip = " --skip-photos" if args.skip_photos else ""
        dry = " --dry-run" if args.dry_run else ""

        for country in args.countries:
            cmd = (
                f"cd {theme_path} && "
                f"WP_LOAD_PATH={wp_load} {php_bin} bin/import-hotels.php "
                f"--country={country}{skip}{dry}"
            )
            print(f"\n=== {country} ===")
            code, out, err = run(client, cmd)
            if out:
                print(out.rstrip())
            if err:
                print(err.rstrip(), file=sys.stderr)
            if code != 0:
                print(f"FAIL {country} exit={code}", file=sys.stderr)
                return code
            print(f"OK {country}")
    finally:
        client.close()

    print("\nAll imports finished.")
    return 0


if __name__ == "__main__":
    sys.exit(main())
