#!/usr/bin/env python3
"""Media sync for hero fullbleed deploy — import/replace attachment 145."""
from __future__ import annotations

import base64
import ftplib
import io
import json
import sys
import urllib.request
from pathlib import Path

ROOT = Path("C:/Users/admin/proekt/zhiraf-b2b").resolve()
sys.path.insert(0, str(ROOT / "teya" / "scripts"))
from teya_wp_media_import import manifest_to_b64, parse_media_import_output, write_wp_media_artifacts

THEME_SLUG = "ecotravelsystem"
THEME_DIR = ROOT / "teya-memory/wp/theme" / THEME_SLUG
WP_DIR = ROOT / "teya-memory/wp"
PUBLIC_URL = "https://zhiraf-b2b.ru/"


def load_env() -> dict[str, str]:
    env: dict[str, str] = {}
    for line in (ROOT / "teya-memory/teya.env.local").read_text(encoding="utf-8").splitlines():
        line = line.strip()
        if "=" in line and not line.startswith("#"):
            k, v = line.split("=", 1)
            env[k.strip()] = v.strip()
    return env


def build_manifest() -> dict:
    media_map = json.loads((THEME_DIR / "media-map.json").read_text(encoding="utf-8"))
    assets = []
    skip = {"blog-cover-style-anchor", "favicon-brand-mark"}
    for item in media_map.get("assets", []):
        registry_id = item.get("registry_id") or item.get("id")
        if registry_id in skip:
            continue
        file_name = str(item.get("file") or item.get("local_path") or item.get("path") or "").replace("\\", "/")
        if file_name.startswith("assets/images/"):
            file_name = file_name[len("assets/images/") :]
        elif file_name.startswith("assets/"):
            file_name = file_name[len("assets/") :]
            if file_name.startswith("images/"):
                file_name = file_name[len("images/") :]
        alt = (item.get("alt_text") or "").strip()
        if not file_name or not alt:
            continue
        assets.append(
            {
                "id": registry_id,
                "registry_id": registry_id,
                "file": file_name,
                "alt_text": alt,
                "used_in": item.get("used_in", []),
            }
        )
    return {"theme_slug": THEME_SLUG, "assets": assets}


MEDIA_PHP = r"""<?php
require __DIR__ . '/wp-load.php';
require_once ABSPATH . 'wp-admin/includes/file.php';
require_once ABSPATH . 'wp-admin/includes/media.php';
require_once ABSPATH . 'wp-admin/includes/image.php';

function teya_force_replace_theme_image( $abs_path, $registry_id, $alt_text, $force_replace_id = 0 ) {
	if ( ! file_exists( $abs_path ) ) {
		return new WP_Error( 'missing_file', 'File not found: ' . $abs_path );
	}
	$existing = get_posts(
		array(
			'post_type'      => 'attachment',
			'posts_per_page' => 1,
			'fields'         => 'ids',
			'meta_query'     => array(
				array(
					'key'   => 'teya_registry_id',
					'value' => $registry_id,
				),
			),
		)
	);
	if ( ! empty( $existing[0] ) && ! $force_replace_id ) {
		$aid = (int) $existing[0];
		update_post_meta( $aid, '_wp_attachment_image_alt', $alt_text );
		return $aid;
	}
	if ( $force_replace_id ) {
		$existing = array( (int) $force_replace_id );
	}
	$filename = basename( $abs_path );
	$contents = file_get_contents( $abs_path );
	if ( false === $contents ) {
		return new WP_Error( 'read_fail', 'Cannot read: ' . $abs_path );
	}
	$upload = wp_upload_bits( $filename, null, $contents );
	if ( ! empty( $upload['error'] ) ) {
		return new WP_Error( 'upload_fail', $upload['error'] );
	}
	if ( ! empty( $existing[0] ) ) {
		$aid = (int) $existing[0];
		$old_file = get_attached_file( $aid );
		update_attached_file( $aid, $upload['file'] );
		$attach_data = wp_generate_attachment_metadata( $aid, $upload['file'] );
		wp_update_attachment_metadata( $aid, $attach_data );
		update_post_meta( $aid, '_wp_attachment_image_alt', $alt_text );
		update_post_meta( $aid, 'teya_registry_id', $registry_id );
		if ( $old_file && $old_file !== $upload['file'] && file_exists( $old_file ) ) {
			@unlink( $old_file );
		}
		return $aid;
	}
	$filetype   = wp_check_filetype( $filename, null );
	$attachment = array(
		'post_mime_type' => $filetype['type'],
		'post_title'     => sanitize_file_name( pathinfo( $filename, PATHINFO_FILENAME ) ),
		'post_content'   => '',
		'post_status'    => 'inherit',
	);
	$aid = wp_insert_attachment( $attachment, $upload['file'] );
	if ( is_wp_error( $aid ) ) {
		return $aid;
	}
	$attach_data = wp_generate_attachment_metadata( $aid, $upload['file'] );
	wp_update_attachment_metadata( $aid, $attach_data );
	update_post_meta( $aid, '_wp_attachment_image_alt', $alt_text );
	update_post_meta( $aid, 'teya_registry_id', $registry_id );
	return (int) $aid;
}

$teya_manifest = json_decode( base64_decode( '__MEDIA_MANIFEST_B64__' ), true );
$teya_theme_dir = get_template_directory();
$teya_map = array(
	'theme_slug'       => $teya_manifest['theme_slug'] ?? wp_get_theme()->get_stylesheet(),
	'public_site_url'  => home_url( '/' ),
	'imported_at'      => gmdate( 'c' ),
	'assets'           => array(),
	'verdict'          => 'pass',
	'transport_status' => 'pass',
);
foreach ( $teya_manifest['assets'] as $teya_asset ) {
	$file = $teya_asset['file'] ?? '';
	$registry_id = $teya_asset['registry_id'] ?? ( $teya_asset['id'] ?? $file );
	$alt = $teya_asset['alt_text'] ?? '';
	$path = $teya_theme_dir . '/assets/images/' . ltrim( $file, '/' );
	$force_id = ( 'hero-ecotravelsystem-fullbleed' === $registry_id ) ? 145 : 0;
	$aid = teya_force_replace_theme_image( $path, $registry_id, $alt, $force_id );
	if ( is_wp_error( $aid ) ) {
		echo 'MEDIA_ERR|' . $registry_id . '|' . $aid->get_error_message() . "\n";
		$teya_map['verdict'] = 'fail';
		continue;
	}
	$url = wp_get_attachment_url( $aid );
	$teya_map['assets'][] = array(
		'id'                => $registry_id,
		'registry_id'       => $registry_id,
		'file'              => $file,
		'local_source_path' => 'teya-memory/wp/theme/' . $teya_map['theme_slug'] . '/assets/images/' . $file,
		'attachment_id'     => $aid,
		'attachment_url'    => $url,
		'alt_text'          => $alt,
		'used_in'           => $teya_asset['used_in'] ?? array(),
	);
	echo 'MEDIA_OK|' . $registry_id . '|' . $aid . '|' . $url . "\n";
}
file_put_contents(
	$teya_theme_dir . '/media-map.json',
	wp_json_encode( $teya_map, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT )
);
echo 'MEDIA_MAP_JSON=' . base64_encode( wp_json_encode( $teya_map, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES ) ) . "\n";
echo "MEDIA_IMPORT_DONE\n";
"""


def upload_and_run(env: dict[str, str], php: str, remote_name: str) -> str:
    ftp = ftplib.FTP()
    ftp.connect(env["FTP_HOST"], int(env.get("FTP_PORT", "21")), timeout=120)
    ftp.login(env["FTP_USER"], env["FTP_PASS"])
    ftp.set_pasv(True)
    ftp.cwd("/")
    entries = {Path(e).name for e in ftp.nlst()}
    if "public_html" in entries:
        ftp.cwd("public_html")
    ftp.storbinary(f"STOR {remote_name}", io.BytesIO(php.encode("utf-8")))
    ftp.quit()

    url = f"{PUBLIC_URL}{remote_name}"
    req = urllib.request.Request(url, headers={"User-Agent": "TeyaAuroraDeploy/1.0"})
    with urllib.request.urlopen(req, timeout=300) as resp:
        out = resp.read().decode("utf-8", errors="replace")

    ftp = ftplib.FTP()
    ftp.connect(env["FTP_HOST"], int(env.get("FTP_PORT", "21")), timeout=60)
    ftp.login(env["FTP_USER"], env["FTP_PASS"])
    ftp.set_pasv(True)
    ftp.cwd("/")
    if "public_html" in entries:
        ftp.cwd("public_html")
    try:
        ftp.delete(remote_name)
    except ftplib.error_perm:
        pass
    ftp.quit()
    return out


def main() -> int:
    env = load_env()
    manifest = build_manifest()
    php = MEDIA_PHP.replace("__MEDIA_MANIFEST_B64__", manifest_to_b64(manifest))
    print(f"Manifest assets: {len(manifest['assets'])}")
    out = upload_and_run(env, php, "ets-media-fullbleed-once.php")
    print(out)
    media_map = parse_media_import_output(out)
    if media_map:
        write_wp_media_artifacts(WP_DIR, media_map, theme_dir=THEME_DIR)
        print(f"Saved wp-media-map.json with {len(media_map.get('assets', []))} assets")
    return 0 if "MEDIA_IMPORT_DONE" in out else 1


if __name__ == "__main__":
    raise SystemExit(main())
