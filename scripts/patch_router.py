#!/usr/bin/env python3
from pathlib import Path
import re

p = Path("deliverables/zhiraf-ufa/wp-theme/zhiraf-ufa/inc/curated-destination-landing.php")
c = p.read_text("utf-8")

funcs = [
    ("zhiraf_ufa_curated_catalog_hotels", "catalog_hotels"),
    ("zhiraf_ufa_curated_catalog_default_copy", "catalog_default_copy"),
    ("zhiraf_ufa_curated_resort_pick_cards", "resort_pick_cards"),
    ("zhiraf_ufa_curated_resort_chips", "resort_chips"),
    ("zhiraf_ufa_curated_expert_nuances", "expert_nuances"),
    ("zhiraf_ufa_curated_catalog_filters", "catalog_filters"),
]

for func, suffix in funcs:
    block = ""
    for slug in ["kitaj", "uzbekistan", "armeniya", "abkhaziya", "rossiya"]:
        # only add if not already in THIS function body (roughly checking by the presence of suffix)
        if f"zhiraf_ufa_{slug}_{suffix}" not in c:
            block += f"\n\tif ( '{slug}' === $dest_slug && function_exists( 'zhiraf_ufa_{slug}_{suffix}' ) ) {{\n\t\treturn zhiraf_ufa_{slug}_{suffix}();\n\t}}"
    
    if block:
        # insert before the final "return apply_filters" or "return false" or "return array()"
        c = re.sub(
            rf"(function {func}\( \$dest_slug \) {{[\s\S]*?)(\n\t(?:/\*\*[\s\S]*?\*/\n\t)?return (?:apply_filters|array\(\)|false|\'\'|\"\").*?;)",
            rf"\1{block}\2",
            c
        )

p.write_text(c, "utf-8")
