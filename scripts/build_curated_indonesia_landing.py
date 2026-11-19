#!/usr/bin/env python3
"""Generate curated-indonesia-landing.php from curated-vietnam-landing.php."""
from __future__ import annotations

import re
from pathlib import Path

REPO = Path(__file__).resolve().parents[1]
SRC = REPO / "deliverables/zhiraf-ufa/wp-theme/zhiraf-ufa/inc/curated-vietnam-landing.php"
OUT = REPO / "deliverables/zhiraf-ufa/wp-theme/zhiraf-ufa/inc/curated-indonesia-landing.php"


def sub_fn(pattern: str, replacement: str, text: str) -> str:
    return re.sub(pattern, replacement, text, count=1, flags=re.S)


def main() -> None:
    out = SRC.read_text(encoding="utf-8")
    repls = [
        ("zhiraf_ufa_vietnam", "zhiraf_ufa_indonesia"),
        ("vietnam_hotels_25", "indonesia_hotels_25"),
        ("/tour/vietnam/", "/tour/indoneziya/"),
        ("zhiraf_ufa_get_country_min_tour( 'vietnam' )", "zhiraf_ufa_get_country_min_tour( 'indonesia' )"),
        ("zhiraf_ufa_get_hotel_min_tour( 'vietnam'", "zhiraf_ufa_get_hotel_min_tour( 'indonesia'"),
        ("'destination_slug' => 'vietnam'", "'destination_slug' => 'indoneziya'"),
        ("hotels/vietnam/", "hotels/indonesia/"),
        ("resort-vietnam-", "resort-indonesia-"),
        ("hero-vietnam.jpg", "country-indoneziya.jpg"),
        ("zhiraf_ufa_tourvisor_overlay_vietnam_card", "zhiraf_ufa_tourvisor_overlay_indonesia_card"),
        ("Vietnam (/tour/vietnam/)", "Indonesia / Bali (/tour/indoneziya/)"),
        ("Вьетнама", "Бали"),
        ("во Вьетнам", "на Бали"),
        ("во Вьетнаме", "на Бали"),
        ("Вьетнаму", "Бали"),
        ("Вьетнаме", "Бали"),
        ("Вьетнам", "Бали"),
        ("vietnam", "indonesia"),
    ]
    for a, b in repls:
        out = out.replace(a, b)

    out = out.replace("'VN-", "'ID-")
    out = out.replace("178000", "176800")
    out = out.replace("__( 'Старт по Бали", "__( 'Старт по Бали")  # noop after vietnam->bali
    out = out.replace("__( 'Старт по Бали", "__( 'Старт по Бали")

    out = sub_fn(
        r"function zhiraf_ufa_indonesia_resort_keys_from_row\( array \$row \) \{.*?\n\}",
        r'''function zhiraf_ufa_indonesia_resort_keys_from_row( array $row ) {
	$region = ! empty( $row['region'] ) ? sanitize_key( (string) $row['region'] ) : '';
	$alias  = array(
		'legian'   => 'seminyak',
		'canggu'   => 'seminyak',
		'benoa'    => 'nusa_dua',
		'jimbaran' => 'sanur',
	);
	if ( isset( $alias[ $region ] ) ) {
		$region = $alias[ $region ];
	}
	$keys = array();
	foreach ( array( 'nusa_dua', 'seminyak', 'sanur', 'ubud' ) as $allowed ) {
		if ( $region === $allowed ) {
			$keys[] = $allowed;
		}
	}

	return array_values( array_unique( $keys ) );
}''',
        out,
    )

    out = sub_fn(
        r"function zhiraf_ufa_indonesia_catalog_fill_resort_labels\(\) \{.*?\n\}",
        r'''function zhiraf_ufa_indonesia_catalog_fill_resort_labels() {
	return array(
		'resort-nusa_dua' => __( 'Нуса-Дуа', 'zhiraf-ufa' ),
		'resort-seminyak' => __( 'Семиньяке', 'zhiraf-ufa' ),
		'resort-sanur'    => __( 'Сануре', 'zhiraf-ufa' ),
		'resort-ubud'     => __( 'Убуде', 'zhiraf-ufa' ),
	);
}''',
        out,
    )

    out = sub_fn(
        r"function zhiraf_ufa_indonesia_need_tag_from_category\( \$category_slug \) \{.*?\n\}",
        r'''function zhiraf_ufa_indonesia_need_tag_from_category( $category_slug ) {
	$category_slug = sanitize_key( (string) $category_slug );
	switch ( $category_slug ) {
		case 'family':
			return 'need-family';
		case 'ubud':
			return 'need-nature';
		case 'seminyak_canggu':
			return 'need-urban';
		case 'sanur_jimbaran':
			return 'need-adults';
		case 'balance':
		default:
			return 'need-price';
	}
}''',
        out,
    )

    out = sub_fn(
        r"function zhiraf_ufa_indonesia_pool_need_badge\( \$category_slug \) \{.*?\n\}",
        r'''function zhiraf_ufa_indonesia_pool_need_badge( $category_slug ) {
	$category_slug = sanitize_key( (string) $category_slug );
	if ( 'family' === $category_slug ) {
		return array(
			'badge'     => __( 'Семья и океан', 'zhiraf-ufa' ),
			'badge_mod' => 'family',
		);
	}
	if ( 'ubud' === $category_slug ) {
		return array(
			'badge'     => __( 'Убуд / джунгли', 'zhiraf-ufa' ),
			'badge_mod' => 'nature',
		);
	}
	if ( 'seminyak_canggu' === $category_slug ) {
		return array(
			'badge'     => __( 'Семиньяк / Чангу', 'zhiraf-ufa' ),
			'badge_mod' => 'urban',
		);
	}
	if ( 'sanur_jimbaran' === $category_slug ) {
		return array(
			'badge'     => __( 'Санур / Джимбаран', 'zhiraf-ufa' ),
			'badge_mod' => 'adults',
		);
	}

	return array(
		'badge'     => __( 'Цена / качество', 'zhiraf-ufa' ),
		'badge_mod' => 'value',
	);
}''',
        out,
    )

    out = sub_fn(
        r"function zhiraf_ufa_indonesia_pool_price_rub\( \$row \) \{.*?\n\}",
        r'''function zhiraf_ufa_indonesia_pool_price_rub( $row ) {
	$cat = ! empty( $row['category_slug'] ) ? sanitize_key( (string) $row['category_slug'] ) : 'balance';
	switch ( $cat ) {
		case 'family':
			return 192000;
		case 'ubud':
			return 205000;
		case 'seminyak_canggu':
			return 188000;
		case 'sanur_jimbaran':
			return 198000;
		case 'balance':
		default:
			return 176800;
	}
}''',
        out,
    )

    out = sub_fn(
        r"function zhiraf_ufa_indonesia_pool_meal_concept\( \$name, \$cat \) \{.*?\n\}",
        r'''function zhiraf_ufa_indonesia_pool_meal_concept( $name, $cat ) {
	$name  = (string) $name;
	$cat   = sanitize_key( (string) $cat );
	$stars = '';
	if ( preg_match( '/\\b(\\d)\\s*★/u', $name, $m ) ) {
		$stars = $m[1] . '★';
	}
	if ( 'family' === $cat ) {
		return trim( $stars . ' ' . __( 'завтраки + бассейны', 'zhiraf-ufa' ) );
	}
	if ( 'ubud' === $cat ) {
		return trim( $stars . ' ' . __( 'вилла в джунглях', 'zhiraf-ufa' ) );
	}
	if ( 'seminyak_canggu' === $cat ) {
		return trim( $stars . ' ' . __( 'бич-клабы рядом', 'zhiraf-ufa' ) );
	}

	return trim( $stars . ' ' . __( 'на завтраках', 'zhiraf-ufa' ) );
}''',
        out,
    )

    out = sub_fn(
        r"\$labels = array\(\s*'cam_ranh'.*?\);\s*if \( ! empty\( \$keys\[0\] \)",
        r"""$labels = array(
		'nusa_dua' => __( 'Нуса-Дуа', 'zhiraf-ufa' ),
		'seminyak' => __( 'Семиньяк', 'zhiraf-ufa' ),
		'sanur'    => __( 'Санур', 'zhiraf-ufa' ),
		'ubud'     => __( 'Убуд', 'zhiraf-ufa' ),
	);
	if ( ! empty( $keys[0] )""",
        out,
    )

    out = sub_fn(
        r"\$id_fallbacks = array\(.*?\);\s*\$region_fb\s*=",
        r"""$id_fallbacks = array(
		1  => 'alt-indoneziya-beach-1.jpg',
		3  => 'alt-indoneziya-beach-1.jpg',
		5  => 'alt-indoneziya-beach-2.jpg',
		6  => 'alt-indoneziya-3.jpg',
		11 => 'alt-indoneziya-beach-2.jpg',
		21 => 'country-indoneziya.jpg',
	);
	$region_fb    =""",
        out,
    )

    out = sub_fn(
        r"\$region_fb\s*=\s*array\(.*?\);\s*\$fallback",
        r"""$region_fb    = array(
		'nusa_dua' => 'alt-indoneziya-beach-1.jpg',
		'seminyak' => 'alt-indoneziya-beach-2.jpg',
		'sanur'    => 'alt-indoneziya-beach-2.jpg',
		'ubud'     => 'alt-indoneziya-3.jpg',
	);
	$fallback""",
        out,
    )

    out = sub_fn(
        r"function zhiraf_ufa_indonesia_starter_definitions\(\) \{.*?\n\}",
        r'''function zhiraf_ufa_indonesia_starter_definitions() {
	return array(
		array( 'json_id' => 1, 'slot' => 'base' ),
		array( 'json_id' => 3, 'slot' => 'family-nusadua' ),
		array( 'json_id' => 5, 'slot' => 'family-sanur' ),
		array( 'json_id' => 11, 'slot' => 'urban' ),
		array( 'json_id' => 6, 'slot' => 'ubud' ),
		array( 'json_id' => 21, 'slot' => 'balance' ),
	);
}''',
        out,
    )

    out = sub_fn(
        r"function zhiraf_ufa_indonesia_starter_filter_tags\( \$hotel \) \{.*?\n\}",
        r'''function zhiraf_ufa_indonesia_starter_filter_tags( $hotel ) {
	$tags = array( 'starter' );
	if ( ! empty( $hotel['filter_tags'] ) && is_array( $hotel['filter_tags'] ) ) {
		foreach ( $hotel['filter_tags'] as $tag ) {
			if ( 'starter' !== $tag ) {
				$tags[] = (string) $tag;
			}
		}
	}
	$slot = ! empty( $hotel['slot'] ) ? (string) $hotel['slot'] : '';
	if ( in_array( $slot, array( 'family', 'family-nusadua', 'family-sanur' ), true ) ) {
		$tags[] = 'need-family';
	} elseif ( 'ubud' === $slot ) {
		$tags[] = 'need-nature';
	} elseif ( 'urban' === $slot ) {
		$tags[] = 'need-urban';
	} else {
		$tags[] = 'need-price';
	}

	return array_values( array_unique( $tags ) );
}''',
        out,
    )

    out = out.replace("__( 'Старт по Бали', 'zhiraf-ufa' )", "__( 'Старт по Бали', 'zhiraf-ufa' )")
    if "Старт по Бали" not in out:
        out = out.replace("__( 'Старт по Бали", "__( 'Старт по Бали")
    out = re.sub(
        r"\$card\['badge'\]\s*=\s*__\( '[^']+', 'zhiraf-ufa' \);",
        "$card['badge']          = __( 'Старт по Бали', 'zhiraf-ufa' );",
        out,
        count=1,
    )
    out = re.sub(
        r"__\( 'Отель %s, [^']+', 'zhiraf-ufa' \)",
        "__( 'Отель %s, Бали', 'zhiraf-ufa' )",
        out,
        count=1,
    )

    # Resort landing url comment
    out = out.replace(
        "cam_ranh|phu_quoc|nha_trang|muine|danang_hoian",
        "nusa_dua|seminyak|sanur|ubud",
    )

    OUT.write_text(out, encoding="utf-8")
    print("Wrote", OUT.relative_to(REPO))


if __name__ == "__main__":
    main()