#!/usr/bin/env python3
"""Create or update /o-nas/ WordPress page with page-o-nas.php template."""
from __future__ import annotations

import ftplib
import io
import sys
import urllib.request
from pathlib import Path

ROOT = Path(__file__).resolve().parents[2]
if not (ROOT / "teya-memory").is_dir():
    cwd = Path.cwd()
    if (cwd / "teya-memory").is_dir():
        ROOT = cwd


def project_root() -> Path:
    return ROOT

PUBLIC_URL = "https://zhiraf-b2b.ru/"

CREATE_PAGE_PHP = r"""<?php
require __DIR__ . '/wp-load.php';
require_once ABSPATH . 'wp-admin/includes/post.php';

$slug = 'o-nas';
$title = 'О нас — EcoTravelSystem';
$excerpt = 'TMC из Уфы: история с 2013 года, команда, реквизиты ООО «Жираф».';
$template = 'page-o-nas.php';

$existing = get_page_by_path($slug, OBJECT, 'page');
if ($existing instanceof WP_Post) {
    $id = (int) $existing->ID;
    wp_update_post([
        'ID' => $id,
        'post_title' => $title,
        'post_excerpt' => $excerpt,
        'post_status' => 'publish',
    ]);
} else {
    $id = (int) wp_insert_post([
        'post_title' => $title,
        'post_name' => $slug,
        'post_excerpt' => $excerpt,
        'post_status' => 'publish',
        'post_type' => 'page',
        'post_content' => '',
    ], true);
}
if (is_wp_error($id) || !$id) {
    echo "ERR page create\n";
    exit(1);
}
update_post_meta($id, '_wp_page_template', $template);

// Footer company menu — ensure О нас item exists
$menu_name = 'Footer Company';
$menu = wp_get_nav_menu_object($menu_name);
if ($menu) {
    $menu_id = (int) $menu->term_id;
    $items = wp_get_nav_menu_items($menu_id);
    $has = false;
    if (is_array($items)) {
        foreach ($items as $item) {
            if ((int) $item->object_id === $id) {
                $has = true;
                break;
            }
        }
    }
    if (!$has) {
        wp_update_nav_menu_item($menu_id, 0, [
            'menu-item-title' => 'О нас',
            'menu-item-object' => 'page',
            'menu-item-object-id' => $id,
            'menu-item-type' => 'post_type',
            'menu-item-status' => 'publish',
        ]);
    }
}

flush_rewrite_rules(false);
echo "OK page o-nas=$id template=$template\n";
echo 'url=' . get_permalink($id) . "\n";
"""


def load_env() -> dict[str, str]:
    env: dict[str, str] = {}
    env_path = project_root() / "teya-memory" / "teya.env.local"
    for line in env_path.read_text(encoding="utf-8").splitlines():
        line = line.strip()
        if "=" in line and not line.startswith("#"):
            k, v = line.split("=", 1)
            env[k.strip()] = v.strip()
    return env


def upload_and_run_php(env: dict, php: str, remote_name: str) -> str:
    ftp = ftplib.FTP()
    ftp.connect(env["FTP_HOST"], int(env.get("FTP_PORT", "21")), timeout=120)
    ftp.login(env["FTP_USER"], env["FTP_PASS"])
    ftp.set_pasv(True)
    ftp.cwd("/")
    ftp.storbinary(f"STOR {remote_name}", io.BytesIO(php.encode("utf-8")))
    ftp.quit()

    url = f"{PUBLIC_URL}{remote_name}"
    req = urllib.request.Request(url, headers={"User-Agent": "TeyaCreateONasPage/1.0"})
    with urllib.request.urlopen(req, timeout=120) as resp:
        out = resp.read().decode("utf-8", errors="replace")

    ftp = ftplib.FTP()
    ftp.connect(env["FTP_HOST"], int(env.get("FTP_PORT", "21")), timeout=60)
    ftp.login(env["FTP_USER"], env["FTP_PASS"])
    ftp.set_pasv(True)
    ftp.cwd("/")
    try:
        ftp.delete(remote_name)
    except ftplib.error_perm:
        pass
    ftp.quit()
    return out


def main() -> int:
    env = load_env()
    if env.get("TEYA_ALLOW_PUBLISH", "").lower() != "yes":
        print("BLOCKER: TEYA_ALLOW_PUBLISH != yes")
        return 1

    print("=== Create /o-nas/ page ===")
    out = upload_and_run_php(env, CREATE_PAGE_PHP, "ets-create-o-nas-once.php")
    print(out)
    return 0 if "OK page o-nas=" in out else 1


if __name__ == "__main__":
    raise SystemExit(main())
