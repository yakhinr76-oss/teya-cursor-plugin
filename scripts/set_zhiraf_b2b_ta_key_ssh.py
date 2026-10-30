#!/usr/bin/env python3
"""Store ZHIRAF_TA_API_KEY in WP options on b2b-tour.ru (server-side import only)."""
from __future__ import annotations

import os
import sys

try:
    import paramiko
except ImportError:
    print("pip install paramiko", file=sys.stderr)
    sys.exit(2)

from run_zhiraf_b2b_hotel_imports_ssh import env_any, run, ssh_connect


def main() -> int:
    key = os.environ.get("ZHIRAF_TA_API_KEY", "").strip()
    if not key:
        print("BLOCKER: ZHIRAF_TA_API_KEY not set in environment", file=sys.stderr)
        return 1

    theme_path = env_any(("B2B_TOUR_SSH_THEME_PATH", "SSH_THEME_PATH")).rstrip("/")
    php_bin = env_any(("B2B_TOUR_SSH_PHP_BIN", "SSH_PHP_BIN")) or "php"

    client = ssh_connect()
    try:
        if not theme_path:
            find_cmd = (
                "find $HOME /var/www -path '*/b2b-tour.ru/wp-content/themes/zhiraf-ufa/style.css' "
                "2>/dev/null | head -1 | sed 's|/style.css||'"
            )
            _, out, _ = run(client, find_cmd)
            theme_path = out.strip().split("\n")[0] if out.strip() else ""
        if not theme_path:
            print("BLOCKER: B2B_TOUR_SSH_THEME_PATH", file=sys.stderr)
            return 1

        wp_load = f"{theme_path}/../../../wp-load.php"
        import base64

        b64 = base64.b64encode(key.encode()).decode()
        php = (
            f"{php_bin} -r "
            f"'require \"{wp_load}\"; "
            f"$k=base64_decode(\"{b64}\"); "
            f"update_option(\"zhiraf_ta_api_key\", $k); "
            f"echo strlen((string)get_option(\"zhiraf_ta_api_key\",\"\"))>10?\"ta_key_ok\":\"ta_key_fail\";'"
        )
        code, out, err = run(client, php)
        if err:
            print(err.rstrip(), file=sys.stderr)
        if code != 0 or "ta_key_ok" not in out:
            print(f"FAIL set key code={code} out={out.strip()}", file=sys.stderr)
            return 1
        print("OK: ZHIRAF_TA_API_KEY stored in wp option (not logged)")
        return 0
    finally:
        client.close()


if __name__ == "__main__":
    raise SystemExit(main())
