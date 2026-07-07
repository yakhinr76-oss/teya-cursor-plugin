#!/usr/bin/env python3
"""Run Impeccable detect on Aurora WP theme and write AURA_IMPECCABLE_REPORT.json."""
from __future__ import annotations

import argparse
import json
import shutil
import subprocess
import sys
from datetime import datetime, timezone
from pathlib import Path
from typing import Any


def resolve_project_root(explicit: Path | None) -> Path:
    if explicit is not None:
        return explicit.resolve()
    return Path(__file__).resolve().parents[2]


def resolve_theme_dir(root: Path, theme_slug: str) -> Path:
    return root / "teya-memory" / "wp" / "theme" / theme_slug


def run_impeccable_detect(target: Path) -> tuple[int, Any, str]:
    npx = shutil.which("npx")
    if not npx:
        return -1, None, "npx not found (install Node.js 24+)"

    cmd = [npx, "--yes", "impeccable", "detect", "--json", str(target)]
    try:
        proc = subprocess.run(
            cmd,
            capture_output=True,
            text=True,
            encoding="utf-8",
            errors="replace",
            timeout=300,
        )
    except subprocess.TimeoutExpired:
        return -1, None, "impeccable detect timed out after 300s"
    except OSError as exc:
        return -1, None, f"failed to run impeccable: {exc}"

    stdout = proc.stdout.strip()
    stderr = proc.stderr.strip()
    combined_err = stderr or "no stderr"

    if not stdout:
        return proc.returncode, None, combined_err

    try:
        payload = json.loads(stdout)
    except json.JSONDecodeError:
        return proc.returncode, {"raw_stdout": stdout}, combined_err

    return proc.returncode, payload, combined_err


def count_findings(payload: Any) -> int:
    if isinstance(payload, list):
        return len(payload)
    if isinstance(payload, dict):
        for key in ("findings", "issues", "results"):
            val = payload.get(key)
            if isinstance(val, list):
                return len(val)
        if "finding_count" in payload:
            try:
                return int(payload["finding_count"])
            except (TypeError, ValueError):
                pass
    return 0


def build_report(
    *,
    theme_slug: str,
    scanned_paths: list[str],
    exit_code: int,
    payload: Any,
    stderr: str,
    skipped_reason: str | None,
) -> dict[str, Any]:
    if skipped_reason:
        return {
            "status": "skipped",
            "tool": "impeccable",
            "theme_slug": theme_slug,
            "scanned_paths": scanned_paths,
            "finding_count": 0,
            "findings": [],
            "exit_code": exit_code,
            "skipped_reason": skipped_reason,
            "generated_at": datetime.now(timezone.utc).isoformat(),
        }

    finding_count = count_findings(payload)
    status = "pass" if exit_code == 0 else "fail"

    report: dict[str, Any] = {
        "status": status,
        "tool": "impeccable",
        "theme_slug": theme_slug,
        "scanned_paths": scanned_paths,
        "finding_count": finding_count,
        "exit_code": exit_code,
        "skipped_reason": None,
        "generated_at": datetime.now(timezone.utc).isoformat(),
    }

    if isinstance(payload, dict):
        report["findings"] = payload.get("findings", payload.get("issues", payload))
    elif isinstance(payload, list):
        report["findings"] = payload
    else:
        report["findings"] = payload

    if stderr and status == "fail":
        report["stderr"] = stderr[:4000]

    return report


def main() -> int:
    ap = argparse.ArgumentParser(description="Impeccable detect wrapper for Teya AURA taste gate")
    ap.add_argument("--project-root", type=Path, default=None)
    ap.add_argument("--theme-slug", required=True)
    ap.add_argument(
        "-o",
        "--output",
        type=Path,
        default=None,
        help="Default: teya-memory/design/AURA_IMPECCABLE_REPORT.json",
    )
    ap.add_argument(
        "--include-design-preview",
        action="store_true",
        help="Also scan teya-memory/design/index.html (may be stale vs WP theme)",
    )
    args = ap.parse_args()

    root = resolve_project_root(args.project_root)
    theme_dir = resolve_theme_dir(root, args.theme_slug)
    out_path = args.output or (root / "teya-memory" / "design" / "AURA_IMPECCABLE_REPORT.json")

    scanned: list[str] = []
    targets: list[Path] = []

    if theme_dir.is_dir():
        targets.append(theme_dir)
        try:
            scanned.append(str(theme_dir.relative_to(root)))
        except ValueError:
            scanned.append(str(theme_dir))

    index_html = root / "teya-memory" / "design" / "index.html"
    if args.include_design_preview and index_html.is_file():
        targets.append(index_html)
        scanned.append(str(index_html.relative_to(root)))

    if not targets:
        report = build_report(
            theme_slug=args.theme_slug,
            scanned_paths=scanned,
            exit_code=-1,
            payload=None,
            stderr="",
            skipped_reason=f"theme directory not found: {theme_dir}",
        )
        out_path.parent.mkdir(parents=True, exist_ok=True)
        out_path.write_text(json.dumps(report, ensure_ascii=False, indent=2), encoding="utf-8")
        print(f"SKIP: {report['skipped_reason']}")
        print(f"Wrote {out_path}")
        return 0

    aggregate_exit = 0
    all_findings: list[Any] = []
    last_stderr = ""

    for target in targets:
        code, payload, stderr = run_impeccable_detect(target)
        last_stderr = stderr
        if code == -1:
            report = build_report(
                theme_slug=args.theme_slug,
                scanned_paths=scanned,
                exit_code=code,
                payload=None,
                stderr=stderr,
                skipped_reason=stderr,
            )
            out_path.parent.mkdir(parents=True, exist_ok=True)
            out_path.write_text(json.dumps(report, ensure_ascii=False, indent=2), encoding="utf-8")
            print(f"SKIP: {stderr}")
            print(f"Wrote {out_path}")
            return 0
        if code == 2:
            aggregate_exit = 2
        elif code != 0 and aggregate_exit == 0:
            aggregate_exit = code
        if isinstance(payload, dict) and isinstance(payload.get("findings"), list):
            all_findings.extend(payload["findings"])
        elif isinstance(payload, list):
            all_findings.extend(payload)
        elif payload is not None:
            all_findings.append({"target": str(target), "payload": payload})

    merged_payload: dict[str, Any] = {"findings": all_findings} if all_findings else {}
    report = build_report(
        theme_slug=args.theme_slug,
        scanned_paths=scanned,
        exit_code=aggregate_exit,
        payload=merged_payload or None,
        stderr=last_stderr,
        skipped_reason=None,
    )

    out_path.parent.mkdir(parents=True, exist_ok=True)
    out_path.write_text(json.dumps(report, ensure_ascii=False, indent=2), encoding="utf-8")

    print(f"status={report['status']} findings={report['finding_count']} exit={aggregate_exit}")
    print(f"Wrote {out_path}")
    return aggregate_exit if aggregate_exit in (0, 2) else 1


if __name__ == "__main__":
    sys.exit(main())
