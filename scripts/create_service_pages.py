#!/usr/bin/env python3
"""Create child service pages under /uslugi/ for EcoTravelSystem theme."""
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

PUBLIC_URL = "https://zhiraf-b2b.ru/"

CREATE_SERVICE_PAGES_PHP = r"""<?php
require __DIR__ . '/wp-load.php';
require_once ABSPATH . 'wp-admin/includes/post.php';

$parent = get_page_by_path('uslugi', OBJECT, 'page');
if (!$parent instanceof WP_Post) {
    echo "ERR parent uslugi missing\n";
    exit(1);
}
$parent_id = (int) $parent->ID;

$pages = [
    [
        'title' => 'Корпоративный тревел-менеджмент',
        'slug' => 'trevel-menedzhment',
        'excerpt' => 'Авиа, ж/д, отели и сложные маршруты для корпоративных клиентов.',
        'template' => 'page-trevel-menedzhment.php',
    ],
    [
        'title' => 'Визовая поддержка',
        'slug' => 'vizovaya-podderzhka',
        'excerpt' => 'Документы и сопровождение визовых кейсов для деловых поездок.',
        'template' => 'page-vizovaya-podderzhka.php',
    ],
    [
        'title' => 'MICE и группы',
        'slug' => 'mice-i-gruppy',
        'excerpt' => 'Конференции, делегации и групповые корпоративные поездки.',
        'template' => 'page-mice-i-gruppy.php',
    ],
    [
        'title' => 'Сложные маршруты',
        'slug' => 'slozhnye-marshruty',
        'excerpt' => 'Стыковки, нестандартные направления и поддержка при форс-мажоре 24/7.',
        'template' => 'page-slozhnye-marshruty.php',
    ],
];

foreach ($pages as $page) {
    $path = 'uslugi/' . $page['slug'];
    $existing = get_page_by_path($path, OBJECT, 'page');
    if ($existing instanceof WP_Post) {
        $id = (int) $existing->ID;
        wp_update_post([
            'ID' => $id,
            'post_title' => $page['title'],
            'post_excerpt' => $page['excerpt'],
            'post_status' => 'publish',
            'post_parent' => $parent_id,
        ]);
    } else {
        $id = (int) wp_insert_post([
            'post_title' => $page['title'],
            'post_name' => $page['slug'],
            'post_excerpt' => $page['excerpt'],
            'post_status' => 'publish',
            'post_type' => 'page',
            'post_parent' => $parent_id,
            'post_content' => '',
        ], true);
    }
    if (is_wp_error($id) || !$id) {
        echo "ERR page {$page['slug']}\n";
        continue;
    }
    update_post_meta($id, '_wp_page_template', $page['template']);
    echo "OK page {$page['slug']}=$id url=" . get_permalink($id) . "\n";
}

// Footer services menu — point to child landings
$menu_name = 'Footer Services';
$menu = wp_get_nav_menu_object($menu_name);
if ($menu) {
    $menu_id = (int) $menu->term_id;
    $items = wp_get_nav_menu_items($menu_id);
    $targets = [
        'Тревел-менеджмент' => home_url('/uslugi/trevel-menedzhment/'),
        'Визовая поддержка' => home_url('/uslugi/vizovaya-podderzhka/'),
        'MICE и группы' => home_url('/uslugi/mice-i-gruppy/'),
        'Сложные маршруты' => home_url('/uslugi/slozhnye-marshruty/'),
    ];
    $by_title = [];
    if (is_array($items)) {
        foreach ($items as $item) {
            $by_title[$item->title] = $item;
        }
    }
    foreach ($targets as $label => $url) {
        if (isset($by_title[$label])) {
            wp_update_nav_menu_item($menu_id, (int) $by_title[$label]->ID, [
                'menu-item-title' => $label,
                'menu-item-url' => $url,
                'menu-item-type' => 'custom',
                'menu-item-status' => 'publish',
            ]);
        } else {
            wp_update_nav_menu_item($menu_id, 0, [
                'menu-item-title' => $label,
                'menu-item-url' => $url,
                'menu-item-type' => 'custom',
                'menu-item-status' => 'publish',
            ]);
        }
    }
}

flush_rewrite_rules(false);
echo "DONE service pages\n";
"""


def load_env() -> dict[str, str]:
    env: dict[str, str] = {}
    env_path = ROOT / "teya-memory" / "teya.env.local"
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
    req = urllib.request.Request(url, headers={"User-Agent": "TeyaCreateServicePages/1.0"})
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

    print("=== Create service child pages ===")
    out = upload_and_run_php(env, CREATE_SERVICE_PAGES_PHP, "ets-create-service-pages-once.php")
    print(out)
    ok = all(
        token in out
        for token in (
            "OK page trevel-menedzhment=",
            "OK page vizovaya-podderzhka=",
            "OK page mice-i-gruppy=",
            "OK page slozhnye-marshruty=",
        )
    )
    return 0 if ok else 1


if __name__ == "__main__":
    raise SystemExit(main())
