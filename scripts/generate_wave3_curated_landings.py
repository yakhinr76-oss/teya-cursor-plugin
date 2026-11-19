#!/usr/bin/env python3
"""Generate curated landing PHP for China, Uzbekistan, Armenia, Abkhazia, Russia from Thailand template."""
from __future__ import annotations

import re
from pathlib import Path

REPO = Path(__file__).resolve().parents[1]
TEMPLATE = (
    REPO / "deliverables/zhiraf-ufa/wp-theme/zhiraf-ufa/inc/curated-thailand-landing.php"
).read_text(encoding="utf-8")

OUT_DIR = REPO / "deliverables/zhiraf-ufa/wp-theme/zhiraf-ufa/inc"


def replace_php_function(text: str, func_name: str, new_src: str) -> str:
    needle = f"function {func_name}"
    start = text.find(needle)
    if start < 0:
        raise ValueError(f"Missing function {func_name}")
    brace = text.find("{", start)
    depth = 0
    end = brace
    for i in range(brace, len(text)):
        if text[i] == "{":
            depth += 1
        elif text[i] == "}":
            depth -= 1
            if depth == 0:
                end = i + 1
                break
    return text[:start] + new_src.strip() + "\n\n" + text[end:].lstrip()


def th_to_country(
    text: str,
    *,
    func: str,
    dest_slug: str,
    country_bg: str,
    title_en: str,
    label: str,
    label_gen: str,
    default_resort: str,
    base_price: int,
    hero_img: str,
    country_img: str,
    uon: str,
    overlay: str,
    resort_keys_php: str,
    catalog_labels_php: str,
    starters_php: str,
    filters_php: str,
    chips_php: str,
    pick_cards_php: str,
    expert_php: str,
    pool_resort_pill_php: str,
) -> str:
    text = text.replace("Таиланда", label_gen)
    text = text.replace("Таиланде", label_gen if label_gen.endswith("и") else label + "е")
    text = text.replace("Таиланд", label)
    text = text.replace("thailand", func)
    text = text.replace("Thailand", title_en)
    text = text.replace("tailand", dest_slug)
    text = text.replace("185000", str(base_price))
    text = text.replace("zhiraf_ufa_tourvisor_overlay_thailand_card", overlay)
    text = text.replace("'TH-", f"'{uon}")
    text = text.replace("dest-thailand.jpg", hero_img)
    text = text.replace("dest-thailand-new.jpg", country_img)
    text = text.replace("country-tailand.jpg", country_img)
    text = text.replace("hero-thailand.jpg", hero_img)
    text = text.replace("hotels/thailand/", f"hotels/{func}/")
    text = text.replace("/tour/tailand/", f"/tour/{dest_slug}/")
    text = text.replace(": 'phuket'", f": '{default_resort}'")
    text = text.replace("Старт по Таиланду", f"Старт по {label_gen}")
    text = text.replace(
        "редирект с /phuket/",
        "якорь каталога",
    )
    text = text.replace(
        "Под задачу: семейный Пхукет, аквапарк Паттайи, тихий Као-Лак или городской Патонг.",
        "Под задачу — семья, пары и честный BB по нашей базе 25 отелей.",
    )
    text = text.replace("стыковка в Москве", "вылет из Уфы")

    pfx = f"zhiraf_ufa_{func}"
    text = replace_php_function(text, f"{pfx}_resort_keys_from_row", resort_keys_php)
    text = replace_php_function(text, f"{pfx}_catalog_fill_resort_labels", catalog_labels_php)
    text = replace_php_function(text, f"{pfx}_pool_resort_pill", pool_resort_pill_php)
    text = replace_php_function(text, f"{pfx}_image_from_json_row", _image_fallbacks(func, hero_img, country_img))
    text = replace_php_function(text, f"{pfx}_starter_definitions", starters_php)
    text = replace_php_function(text, f"{pfx}_catalog_filters", filters_php)
    text = replace_php_function(text, f"{pfx}_resort_chips", chips_php)
    text = replace_php_function(text, f"{pfx}_resort_pick_cards", pick_cards_php)
    text = replace_php_function(text, f"{pfx}_resort_hotel_counts", _resort_counts(func, default_resort))
    text = replace_php_function(text, f"{pfx}_expert_nuances", expert_php)

    return text


def _resort_counts(func: str, keys: str) -> str:
    # keys like "'south_west', 'west_coast'" or use dynamic from hotels only
    return f"""function zhiraf_ufa_{func}_resort_hotel_counts() {{
\t$counts = array();
\tforeach ( zhiraf_ufa_{func}_catalog_hotels() as $hotel ) {{
\t\t$tags = ! empty( $hotel['filter_tags'] ) && is_array( $hotel['filter_tags'] ) ? $hotel['filter_tags'] : array();
\t\tforeach ( $tags as $tag ) {{
\t\t\tif ( 0 !== strpos( (string) $tag, 'resort-' ) ) {{
\t\t\t\tcontinue;
\t\t\t}}
\t\t\t$key = substr( (string) $tag, 7 );
\t\t\tif ( ! isset( $counts[ $key ] ) ) {{
\t\t\t\t$counts[ $key ] = 0;
\t\t\t}}
\t\t\t++$counts[ $key ];
\t\t}}
\t}}
\treturn $counts;
}}
"""


def _pool_resort_pill(func: str, labels: dict[str, str], fallback: str) -> str:
    lines = ",\n\t\t".join(f"'{k}' => __( '{v}', 'zhiraf-ufa' )" for k, v in labels.items())
    return f"""function zhiraf_ufa_{func}_pool_resort_pill( array $row ) {{
\t$resort = ! empty( $row['resort'] ) ? (string) $row['resort'] : '';
\tif ( '' !== $resort ) {{
\t\t$parts = explode( ',', $resort );
\t\treturn trim( $parts[0] );
\t}}
\t$keys = zhiraf_ufa_{func}_resort_keys_from_row( $row );
\t$labels = array(
\t\t{lines},
\t);
\tif ( ! empty( $keys[0] ) && isset( $labels[ $keys[0] ] ) ) {{
\t\treturn $labels[ $keys[0] ];
\t}}
\treturn __( '{fallback}', 'zhiraf-ufa' );
}}
"""


def _image_fallbacks(func: str, hero: str, country: str) -> str:
    return f"""function zhiraf_ufa_{func}_image_from_json_row( array $row ) {{
\t$pool_id = ! empty( $row['id'] ) ? (int) $row['id'] : 0;
\t$photos  = ! empty( $row['photos'] ) && is_array( $row['photos'] ) ? $row['photos'] : array();
\t$first   = isset( $photos[0] ) ? trim( (string) $photos[0] ) : '';
\tif ( preg_match( '#^https://#i', $first ) ) {{
\t\treturn array(
\t\t\t'image'        => $first,
\t\t\t'image_remote' => true,
\t\t\t'image_width'  => 0,
\t\t\t'image_height' => 0,
\t\t);
\t}}
\tif ( '' !== $first && defined( 'ZHIRAF_UFA_DIR' ) ) {{
\t\tforeach ( array( 'hotels/{func}/' . $first, $first ) as $rel ) {{
\t\t\tif ( is_readable( ZHIRAF_UFA_DIR . '/assets/images/' . $rel ) ) {{
\t\t\t\treturn array(
\t\t\t\t\t'image'        => $rel,
\t\t\t\t\t'image_remote' => false,
\t\t\t\t\t'image_width'  => 0,
\t\t\t\t\t'image_height' => 0,
\t\t\t\t);
\t\t\t}}
\t\t}}
\t}}
\t$fallback = '{hero}';
\tif ( ! is_readable( ZHIRAF_UFA_DIR . '/assets/images/' . $fallback ) ) {{
\t\t$fallback = '{country}';
\t}}
\treturn array(
\t\t'image'        => $fallback,
\t\t'image_remote' => false,
\t\t'image_width'  => 0,
\t\t'image_height' => 0,
\t);
}}
"""


def sri_lanka() -> str:
    resort_keys = """/**
 * Resort filter keys (south_west, west_coast, east_coast).
 *
 * @param array<string,mixed> $row JSON row.
 * @return string[]
 */
function zhiraf_ufa_sri_lanka_resort_keys_from_row( array $row ) {
\t$region = ! empty( $row['region'] ) ? sanitize_key( (string) $row['region'] ) : '';
\t$keys   = array();
\tforeach ( array( 'south_west', 'west_coast', 'east_coast' ) as $allowed ) {
\t\tif ( $region === $allowed ) {
\t\t\t$keys[] = $allowed;
\t\t}
\t}
\treturn array_values( array_unique( $keys ) );
}
"""
    labels = """function zhiraf_ufa_sri_lanka_catalog_fill_resort_labels() {
\treturn array(
\t\t'resort-south_west' => __( 'юго-западе', 'zhiraf-ufa' ),
\t\t'resort-west_coast' => __( 'Бентоте', 'zhiraf-ufa' ),
\t\t'resort-east_coast' => __( 'Пасикуде', 'zhiraf-ufa' ),
\t);
}
"""
    starters = """function zhiraf_ufa_sri_lanka_starter_definitions() {
\treturn array(
\t\tarray( 'json_id' => 1, 'slot' => 'base' ),
\t\tarray( 'json_id' => 3, 'slot' => 'family-east' ),
\t\tarray( 'json_id' => 6, 'slot' => 'couples' ),
\t\tarray( 'json_id' => 11, 'slot' => 'bentota' ),
\t\tarray( 'json_id' => 16, 'slot' => 'urban' ),
\t\tarray( 'json_id' => 21, 'slot' => 'balance' ),
\t);
}
"""
    filters = """function zhiraf_ufa_sri_lanka_catalog_filters() {
\treturn array(
\t\tarray( 'id' => 'starter', 'group' => 'default', 'label' => __( '⭐ Стартовый топ (6)', 'zhiraf-ufa' ), 'title' => __( 'Туры на Шри-Ланку: 6 проверенных отелей', 'zhiraf-ufa' ), 'desc' => __( 'Унаватуна, Бентота, Пасикуда — сезон побережья и честный BB. Цены за двоих с перелётом из Уфы.', 'zhiraf-ufa' ) ),
\t\tarray( 'id' => 'resort-south_west', 'group' => 'resort', 'label' => __( '🌴 Юго-запад', 'zhiraf-ufa' ), 'title' => __( 'Отели юго-запада', 'zhiraf-ufa' ), 'desc' => __( 'Унаватуна и Мирисса — зимний хит.', 'zhiraf-ufa' ) ),
\t\tarray( 'id' => 'resort-west_coast', 'group' => 'resort', 'label' => __( '🏖 Запад', 'zhiraf-ufa' ), 'title' => __( 'Бентота и Берувала', 'zhiraf-ufa' ), 'desc' => __( 'Широкий песок западного побережья.', 'zhiraf-ufa' ) ),
\t\tarray( 'id' => 'resort-east_coast', 'group' => 'resort', 'label' => __( '🌅 Восток', 'zhiraf-ufa' ), 'title' => __( 'Пасикуда', 'zhiraf-ufa' ), 'desc' => __( 'Мелководная лагуна — альтернатива муссону.', 'zhiraf-ufa' ) ),
\t\tarray( 'id' => 'need-family', 'group' => 'need', 'label' => __( '👨‍👩‍👧 С детьми', 'zhiraf-ufa' ), 'title' => __( 'Семейные отели', 'zhiraf-ufa' ), 'desc' => __( 'Рифы и пологий заход.', 'zhiraf-ufa' ) ),
\t\tarray( 'id' => 'need-adults', 'group' => 'need', 'label' => __( '🥂 Для пар', 'zhiraf-ufa' ), 'title' => __( 'Для пар', 'zhiraf-ufa' ), 'desc' => __( 'Хиккадува и бунгало у моря.', 'zhiraf-ufa' ) ),
\t\tarray( 'id' => 'need-price', 'group' => 'need', 'label' => __( '⚖️ Цена/качество', 'zhiraf-ufa' ), 'title' => __( 'Хиты «цена / качество»', 'zhiraf-ufa' ), 'desc' => __( '4★ у воды без переплаты.', 'zhiraf-ufa' ) ),
\t);
}
"""
    chips = """function zhiraf_ufa_sri_lanka_resort_chips() {
\treturn array(
\t\tarray( 'id' => 'starter', 'label' => __( 'Топ-6', 'zhiraf-ufa' ) ),
\t\tarray( 'id' => 'resort-south_west', 'label' => __( 'Юго-запад', 'zhiraf-ufa' ) ),
\t\tarray( 'id' => 'resort-west_coast', 'label' => __( 'Запад', 'zhiraf-ufa' ) ),
\t\tarray( 'id' => 'resort-east_coast', 'label' => __( 'Восток', 'zhiraf-ufa' ) ),
\t);
}
"""
    picks = """function zhiraf_ufa_sri_lanka_resort_pick_cards() {
\t$counts = zhiraf_ufa_sri_lanka_resort_hotel_counts();
\t$guide = __( 'К отелям →', 'zhiraf-ufa' );
\treturn array(
\t\tarray( 'id' => 'south_west', 'filter_id' => 'resort-south_west', 'guide_url' => zhiraf_ufa_sri_lanka_resort_landing_url( 'south_west' ), 'guide_label' => $guide, 'name' => __( 'Юго-запад', 'zhiraf-ufa' ), 'subtitle' => __( 'Унаватуна и риф', 'zhiraf-ufa' ), 'tag' => __( 'Семейный хит', 'zhiraf-ufa' ), 'tag_mod' => 'family', 'transfer' => __( 'Вылет из Уфы', 'zhiraf-ufa' ), 'featured' => true, 'hotel_count' => $counts['south_west'] ?? 0, 'accent_mod' => 'emerald', 'bullets' => array( array( 'type' => 'pro', 'text' => __( 'Бухты с пологим заходом — зима', 'zhiraf-ufa' ) ), array( 'type' => 'info', 'text' => __( 'Летом — муссон, смотрим восток', 'zhiraf-ufa' ) ) ) ),
\t\tarray( 'id' => 'west_coast', 'filter_id' => 'resort-west_coast', 'guide_url' => zhiraf_ufa_sri_lanka_resort_landing_url( 'west_coast' ), 'guide_label' => $guide, 'name' => __( 'Запад (Бентота)', 'zhiraf-ufa' ), 'subtitle' => __( 'Широкий песок', 'zhiraf-ufa' ), 'tag' => __( 'Релакс', 'zhiraf-ufa' ), 'tag_mod' => 'nature', 'transfer' => __( '~2 ч от CMB', 'zhiraf-ufa' ), 'featured' => false, 'hotel_count' => $counts['west_coast'] ?? 0, 'accent_mod' => 'teal', 'bullets' => array( array( 'type' => 'pro', 'text' => __( 'Река и пляж в одном курорте', 'zhiraf-ufa' ) ) ) ),
\t\tarray( 'id' => 'east_coast', 'filter_id' => 'resort-east_coast', 'guide_url' => zhiraf_ufa_sri_lanka_resort_landing_url( 'east_coast' ), 'guide_label' => $guide, 'name' => __( 'Восток (Пасикуда)', 'zhiraf-ufa' ), 'subtitle' => __( 'Мелководная лагуна', 'zhiraf-ufa' ), 'tag' => __( 'Летний сезон', 'zhiraf-ufa' ), 'tag_mod' => 'adults', 'transfer' => __( 'Длинный трансфер', 'zhiraf-ufa' ), 'featured' => false, 'hotel_count' => $counts['east_coast'] ?? 0, 'accent_mod' => 'amber', 'bullets' => array( array( 'type' => 'pro', 'text' => __( 'Безопасное купание для малышей', 'zhiraf-ufa' ) ) ) ),
\t);
}
"""
    expert = """function zhiraf_ufa_sri_lanka_expert_nuances() {
\treturn array(
\t\tarray( 'icon' => '✈', 'title' => __( 'Вылет из Уфы', 'zhiraf-ufa' ), 'text' => __( 'Пакеты идут со стыковкой в Москве. Считаем суммарное время, багаж и заселение после ночного прилёта в Коломбо.', 'zhiraf-ufa' ) ),
\t\tarray( 'icon' => '🌧', 'title' => __( 'Два муссона', 'zhiraf-ufa' ), 'text' => __( 'Зимой — юго-запад (Унаватуна, Бентота). С мая — дожди на западе; для июля–августа смотрим Пасикуду на востоке.', 'zhiraf-ufa' ) ),
\t\tarray( 'icon' => '🛂', 'title' => __( 'ETA до вылета', 'zhiraf-ufa' ), 'text' => __( 'Электронное разрешение ETA оформляется онлайн заранее. Менеджер напомнит сроки и проверит паспорт.', 'zhiraf-ufa' ) ),
\t);
}
"""
    pill = _pool_resort_pill(
        "sri_lanka",
        {
            "south_west": "Юго-запад",
            "west_coast": "Бентота",
            "east_coast": "Пасикуда",
        },
        "Шри-Ланка",
    )
    return th_to_country(
        TEMPLATE,
        func="sri_lanka",
        dest_slug="shri-lanka",
        country_bg="Шри-Ланка",
        title_en="Sri Lanka",
        label="Шри-Ланка",
        label_gen="Шри-Ланки",
        default_resort="south_west",
        base_price=198000,
        hero_img="country-shri-lanka.jpg",
        country_img="alt-shri-lanka-beach-1.jpg",
        uon="SL-",
        overlay="zhiraf_ufa_tourvisor_overlay_sri_lanka_card",
        resort_keys_php=resort_keys,
        catalog_labels_php=labels,
        pool_resort_pill_php=pill,
        starters_php=starters,
        filters_php=filters,
        chips_php=chips,
        pick_cards_php=picks,
        expert_php=expert,
    )


def indonesia() -> str:
    resort_keys = """/**
 * Bali regions; canggu/legian → seminyak, benoa/jimbaran → nusa_dua.
 *
 * @param array<string,mixed> $row JSON row.
 * @return string[]
 */
function zhiraf_ufa_indonesia_resort_keys_from_row( array $row ) {
\t$region = ! empty( $row['region'] ) ? sanitize_key( (string) $row['region'] ) : '';
\t$alias  = array(
\t\t'canggu'  => 'seminyak',
\t\t'legian'  => 'seminyak',
\t\t'benoa'   => 'nusa_dua',
\t\t'jimbaran'=> 'nusa_dua',
\t);
\tif ( isset( $alias[ $region ] ) ) {
\t\t$region = $alias[ $region ];
\t}
\t$keys = array();
\tforeach ( array( 'nusa_dua', 'seminyak', 'sanur', 'ubud' ) as $allowed ) {
\t\tif ( $region === $allowed ) {
\t\t\t$keys[] = $allowed;
\t\t}
\t}
\treturn array_values( array_unique( $keys ) );
}
"""
    labels = """function zhiraf_ufa_indonesia_catalog_fill_resort_labels() {
\treturn array(
\t\t'resort-nusa_dua' => __( 'Нуса-Дуа', 'zhiraf-ufa' ),
\t\t'resort-seminyak' => __( 'Семиньяке', 'zhiraf-ufa' ),
\t\t'resort-sanur'    => __( 'Сануре', 'zhiraf-ufa' ),
\t\t'resort-ubud'     => __( 'Убуде', 'zhiraf-ufa' ),
\t);
}
"""
    starters = """function zhiraf_ufa_indonesia_starter_definitions() {
\treturn array(
\t\tarray( 'json_id' => 1, 'slot' => 'base' ),
\t\tarray( 'json_id' => 3, 'slot' => 'family' ),
\t\tarray( 'json_id' => 6, 'slot' => 'ubud' ),
\t\tarray( 'json_id' => 11, 'slot' => 'urban' ),
\t\tarray( 'json_id' => 16, 'slot' => 'sanur' ),
\t\tarray( 'json_id' => 21, 'slot' => 'balance' ),
\t);
}
"""
    filters = """function zhiraf_ufa_indonesia_catalog_filters() {
\treturn array(
\t\tarray( 'id' => 'starter', 'group' => 'default', 'label' => __( '⭐ Стартовый топ (6)', 'zhiraf-ufa' ), 'title' => __( 'Туры на Бали: 6 проверенных отелей', 'zhiraf-ufa' ), 'desc' => __( 'Нуса-Дуа, Семиньяк, Санур и Убуд. Цены за двоих с перелётом из Уфы.', 'zhiraf-ufa' ) ),
\t\tarray( 'id' => 'resort-nusa_dua', 'group' => 'resort', 'label' => __( '🏝 Нуса-Дуа', 'zhiraf-ufa' ), 'title' => __( 'Нуса-Дуа', 'zhiraf-ufa' ), 'desc' => __( 'Спокойное море и резорты 5★.', 'zhiraf-ufa' ) ),
\t\tarray( 'id' => 'resort-seminyak', 'group' => 'resort', 'label' => __( '🌅 Семиньяк', 'zhiraf-ufa' ), 'title' => __( 'Семиньяк / Чангу', 'zhiraf-ufa' ), 'desc' => __( 'Закаты и кафе.', 'zhiraf-ufa' ) ),
\t\tarray( 'id' => 'resort-sanur', 'group' => 'resort', 'label' => __( '🌊 Санур', 'zhiraf-ufa' ), 'title' => __( 'Санур', 'zhiraf-ufa' ), 'desc' => __( 'Семейный восточный пляж.', 'zhiraf-ufa' ) ),
\t\tarray( 'id' => 'resort-ubud', 'group' => 'resort', 'label' => __( '🌿 Убуд', 'zhiraf-ufa' ), 'title' => __( 'Убуд', 'zhiraf-ufa' ), 'desc' => __( 'Джунгли без моря.', 'zhiraf-ufa' ) ),
\t\tarray( 'id' => 'need-family', 'group' => 'need', 'label' => __( '👨‍👩‍👧 С детьми', 'zhiraf-ufa' ), 'title' => __( 'Семейные', 'zhiraf-ufa' ), 'desc' => __( 'Нуса-Дуа и Санур.', 'zhiraf-ufa' ) ),
\t\tarray( 'id' => 'need-price', 'group' => 'need', 'label' => __( '⚖️ Цена/качество', 'zhiraf-ufa' ), 'title' => __( 'Цена / качество', 'zhiraf-ufa' ), 'desc' => __( 'Честные 4★.', 'zhiraf-ufa' ) ),
\t);
}
"""
    chips = """function zhiraf_ufa_indonesia_resort_chips() {
\treturn array(
\t\tarray( 'id' => 'starter', 'label' => __( 'Топ-6', 'zhiraf-ufa' ) ),
\t\tarray( 'id' => 'resort-nusa_dua', 'label' => __( 'Нуса-Дуа', 'zhiraf-ufa' ) ),
\t\tarray( 'id' => 'resort-seminyak', 'label' => __( 'Семиньяк', 'zhiraf-ufa' ) ),
\t\tarray( 'id' => 'resort-sanur', 'label' => __( 'Санур', 'zhiraf-ufa' ) ),
\t\tarray( 'id' => 'resort-ubud', 'label' => __( 'Убуд', 'zhiraf-ufa' ) ),
\t);
}
"""
    picks = """function zhiraf_ufa_indonesia_resort_pick_cards() {
\t$counts = zhiraf_ufa_indonesia_resort_hotel_counts();
\t$guide = __( 'К отелям →', 'zhiraf-ufa' );
\treturn array(
\t\tarray( 'id' => 'nusa_dua', 'filter_id' => 'resort-nusa_dua', 'guide_url' => zhiraf_ufa_indonesia_resort_landing_url( 'nusa_dua' ), 'guide_label' => $guide, 'name' => __( 'Нуса-Дуа', 'zhiraf-ufa' ), 'subtitle' => __( 'Спокойное море', 'zhiraf-ufa' ), 'tag' => __( 'С детьми', 'zhiraf-ufa' ), 'tag_mod' => 'family', 'transfer' => __( 'Вылет из Уфы', 'zhiraf-ufa' ), 'featured' => true, 'hotel_count' => $counts['nusa_dua'] ?? 0, 'accent_mod' => 'emerald', 'bullets' => array( array( 'type' => 'pro', 'text' => __( 'Пологий заход и отели-клубы', 'zhiraf-ufa' ) ) ) ),
\t\tarray( 'id' => 'seminyak', 'filter_id' => 'resort-seminyak', 'guide_url' => zhiraf_ufa_indonesia_resort_landing_url( 'seminyak' ), 'guide_label' => $guide, 'name' => __( 'Семиньяк', 'zhiraf-ufa' ), 'subtitle' => __( 'Закаты и кафе', 'zhiraf-ufa' ), 'tag' => __( 'Для пар', 'zhiraf-ufa' ), 'tag_mod' => 'adults', 'transfer' => __( 'Серф и вечер', 'zhiraf-ufa' ), 'featured' => false, 'hotel_count' => $counts['seminyak'] ?? 0, 'accent_mod' => 'teal', 'bullets' => array( array( 'type' => 'info', 'text' => __( 'Волны — купание по флажкам', 'zhiraf-ufa' ) ) ) ),
\t\tarray( 'id' => 'sanur', 'filter_id' => 'resort-sanur', 'guide_url' => zhiraf_ufa_indonesia_resort_landing_url( 'sanur' ), 'guide_label' => $guide, 'name' => __( 'Санур', 'zhiraf-ufa' ), 'subtitle' => __( 'Семейный пляж', 'zhiraf-ufa' ), 'tag' => __( 'Релакс', 'zhiraf-ufa' ), 'tag_mod' => 'nature', 'transfer' => __( 'Близко к аэропорту', 'zhiraf-ufa' ), 'featured' => false, 'hotel_count' => $counts['sanur'] ?? 0, 'accent_mod' => 'amber', 'bullets' => array() ),
\t\tarray( 'id' => 'ubud', 'filter_id' => 'resort-ubud', 'guide_url' => zhiraf_ufa_indonesia_resort_landing_url( 'ubud' ), 'guide_label' => $guide, 'name' => __( 'Убуд', 'zhiraf-ufa' ), 'subtitle' => __( 'Джунгли', 'zhiraf-ufa' ), 'tag' => __( 'Без моря', 'zhiraf-ufa' ), 'tag_mod' => 'value', 'transfer' => __( 'Комбо с пляжем', 'zhiraf-ufa' ), 'featured' => false, 'hotel_count' => $counts['ubud'] ?? 0, 'accent_mod' => 'lux', 'bullets' => array() ),
\t);
}
"""
    expert = """function zhiraf_ufa_indonesia_expert_nuances() {
\treturn array(
\t\tarray( 'icon' => '✈', 'title' => __( 'Вылет из Уфы', 'zhiraf-ufa' ), 'text' => __( 'Долгий перелёт со стыковкой — закладываем день на дорогу и отдых в первый день.', 'zhiraf-ufa' ) ),
\t\tarray( 'icon' => '🛂', 'title' => __( 'VoA и сбор Бали', 'zhiraf-ufa' ), 'text' => __( 'Виза по прилёту или e-VoA, плюс туристический сбор острова — напомним до оплаты тура.', 'zhiraf-ufa' ) ),
\t\tarray( 'icon' => '🌊', 'title' => __( 'Где купаться', 'zhiraf-ufa' ), 'text' => __( 'Спокойная вода — Нуса-Дуа и Санур. Семиньяк и Чангу — серф; для детей чаще Нуса-Дуа.', 'zhiraf-ufa' ) ),
\t);
}
"""
    pill = _pool_resort_pill(
        "indonesia",
        {
            "nusa_dua": "Нуса-Дуа",
            "seminyak": "Семиньяк",
            "sanur": "Санур",
            "ubud": "Убуд",
        },
        "Бали",
    )
    return th_to_country(
        TEMPLATE,
        func="indonesia",
        dest_slug="indoneziya",
        country_bg="Бали",
        title_en="Indonesia",
        label="Индонезия",
        label_gen="Индонезии",
        default_resort="nusa_dua",
        base_price=248000,
        hero_img="country-indoneziya.jpg",
        country_img="country-indoneziya.jpg",
        uon="ID-",
        overlay="zhiraf_ufa_tourvisor_overlay_indonesia_card",
        resort_keys_php=resort_keys,
        catalog_labels_php=labels,
        pool_resort_pill_php=pill,
        starters_php=starters,
        filters_php=filters,
        chips_php=chips,
        pick_cards_php=picks,
        expert_php=expert,
    )


def india() -> str:
    resort_keys = """/**
 * Goa regions (south_goa, north_goa).
 *
 * @param array<string,mixed> $row JSON row.
 * @return string[]
 */
function zhiraf_ufa_india_resort_keys_from_row( array $row ) {
\t$region = ! empty( $row['region'] ) ? sanitize_key( (string) $row['region'] ) : '';
\t$keys   = array();
\tforeach ( array( 'south_goa', 'north_goa' ) as $allowed ) {
\t\tif ( $region === $allowed ) {
\t\t\t$keys[] = $allowed;
\t\t}
\t}
\treturn array_values( array_unique( $keys ) );
}
"""
    labels = """function zhiraf_ufa_india_catalog_fill_resort_labels() {
\treturn array(
\t\t'resort-south_goa' => __( 'Южном Гоа', 'zhiraf-ufa' ),
\t\t'resort-north_goa' => __( 'Северном Гоа', 'zhiraf-ufa' ),
\t);
}
"""
    starters = """function zhiraf_ufa_india_starter_definitions() {
\treturn array(
\t\tarray( 'json_id' => 1, 'slot' => 'base' ),
\t\tarray( 'json_id' => 3, 'slot' => 'family-south' ),
\t\tarray( 'json_id' => 6, 'slot' => 'north' ),
\t\tarray( 'json_id' => 11, 'slot' => 'budget-north' ),
\t\tarray( 'json_id' => 16, 'slot' => 'premium-south' ),
\t\tarray( 'json_id' => 21, 'slot' => 'balance' ),
\t);
}
"""
    filters = """function zhiraf_ufa_india_catalog_filters() {
\treturn array(
\t\tarray( 'id' => 'starter', 'group' => 'default', 'label' => __( '⭐ Стартовый топ (6)', 'zhiraf-ufa' ), 'title' => __( 'Туры в Гоа: 6 проверенных отелей', 'zhiraf-ufa' ), 'desc' => __( 'Южный и Северный Гоа — пляж и BB. Цены за двоих с перелётом из Уфы.', 'zhiraf-ufa' ) ),
\t\tarray( 'id' => 'resort-south_goa', 'group' => 'resort', 'label' => __( '🏖 Юг Гоа', 'zhiraf-ufa' ), 'title' => __( 'Южный Гоа', 'zhiraf-ufa' ), 'desc' => __( 'Тишина и длинные пляжи.', 'zhiraf-ufa' ) ),
\t\tarray( 'id' => 'resort-north_goa', 'group' => 'resort', 'label' => __( '🌴 Север Гоа', 'zhiraf-ufa' ), 'title' => __( 'Северный Гоа', 'zhiraf-ufa' ), 'desc' => __( 'Кафе и вечерняя жизнь.', 'zhiraf-ufa' ) ),
\t\tarray( 'id' => 'need-family', 'group' => 'need', 'label' => __( '👨‍👩‍👧 С детьми', 'zhiraf-ufa' ), 'title' => __( 'Семейные', 'zhiraf-ufa' ), 'desc' => __( 'Южные бухты.', 'zhiraf-ufa' ) ),
\t\tarray( 'id' => 'need-price', 'group' => 'need', 'label' => __( '⚖️ Цена/качество', 'zhiraf-ufa' ), 'title' => __( 'Цена / качество', 'zhiraf-ufa' ), 'desc' => __( '3–4★ у моря.', 'zhiraf-ufa' ) ),
\t);
}
"""
    chips = """function zhiraf_ufa_india_resort_chips() {
\treturn array(
\t\tarray( 'id' => 'starter', 'label' => __( 'Топ-6', 'zhiraf-ufa' ) ),
\t\tarray( 'id' => 'resort-south_goa', 'label' => __( 'Юг Гоа', 'zhiraf-ufa' ) ),
\t\tarray( 'id' => 'resort-north_goa', 'label' => __( 'Север Гоа', 'zhiraf-ufa' ) ),
\t);
}
"""
    picks = """function zhiraf_ufa_india_resort_pick_cards() {
\t$counts = zhiraf_ufa_india_resort_hotel_counts();
\t$guide = __( 'К отелям →', 'zhiraf-ufa' );
\treturn array(
\t\tarray( 'id' => 'south_goa', 'filter_id' => 'resort-south_goa', 'guide_url' => zhiraf_ufa_india_resort_landing_url( 'south_goa' ), 'guide_label' => $guide, 'name' => __( 'Южный Гоа', 'zhiraf-ufa' ), 'subtitle' => __( 'Тихие пляжи', 'zhiraf-ufa' ), 'tag' => __( 'Семья', 'zhiraf-ufa' ), 'tag_mod' => 'family', 'transfer' => __( 'Вылет из Уфы', 'zhiraf-ufa' ), 'featured' => true, 'hotel_count' => $counts['south_goa'] ?? 0, 'accent_mod' => 'emerald', 'bullets' => array( array( 'type' => 'pro', 'text' => __( 'Бенаулим и Кавелоссим', 'zhiraf-ufa' ) ) ) ),
\t\tarray( 'id' => 'north_goa', 'filter_id' => 'resort-north_goa', 'guide_url' => zhiraf_ufa_india_resort_landing_url( 'north_goa' ), 'guide_label' => $guide, 'name' => __( 'Северный Гоа', 'zhiraf-ufa' ), 'subtitle' => __( 'Рынки и кафе', 'zhiraf-ufa' ), 'tag' => __( 'Активно', 'zhiraf-ufa' ), 'tag_mod' => 'adults', 'transfer' => __( 'Ближе к аэропорту GOI', 'zhiraf-ufa' ), 'featured' => false, 'hotel_count' => $counts['north_goa'] ?? 0, 'accent_mod' => 'teal', 'bullets' => array( array( 'type' => 'info', 'text' => __( 'Сезон: ноябрь — апрель', 'zhiraf-ufa' ) ) ) ),
\t);
}
"""
    expert = """function zhiraf_ufa_india_expert_nuances() {
\treturn array(
\t\tarray( 'icon' => '✈', 'title' => __( 'Вылет из Уфы', 'zhiraf-ufa' ), 'text' => __( 'Стыковка в Москве или Дубае — считаем время и багаж до брони.', 'zhiraf-ufa' ) ),
\t\tarray( 'icon' => '🛂', 'title' => __( 'e-Visa', 'zhiraf-ufa' ), 'text' => __( 'Туристическая e-Visa оформляется онлайн до вылета — проверим данные паспорта.', 'zhiraf-ufa' ) ),
\t\tarray( 'icon' => '🏖', 'title' => __( 'Юг или Север', 'zhiraf-ufa' ), 'text' => __( 'Юг — длинный песок и тишина. Север — шаки, рынки и вечер. Подберём под состав и месяц.', 'zhiraf-ufa' ) ),
\t);
}
"""
    pill = _pool_resort_pill(
        "india",
        {"south_goa": "Юг Гоа", "north_goa": "Север Гоа"},
        "Гоа",
    )
    return th_to_country(
        TEMPLATE,
        func="india",
        dest_slug="indiya",
        country_bg="Гоа",
        title_en="India",
        label="Индия",
        label_gen="Индии",
        default_resort="south_goa",
        base_price=188000,
        hero_img="resort-severnyj-goa.jpg",
        country_img="resort-severnyj-goa.jpg",
        uon="IN-",
        overlay="zhiraf_ufa_tourvisor_overlay_india_card",
        resort_keys_php=resort_keys,
        catalog_labels_php=labels,
        pool_resort_pill_php=pill,
        starters_php=starters,
        filters_php=filters,
        chips_php=chips,
        pick_cards_php=picks,
        expert_php=expert,
    )


def seychelles() -> str:
    resort_keys = """/**
 * Island keys mahe, praslin, la_digue from JSON island field.
 *
 * @param array<string,mixed> $row JSON row.
 * @return string[]
 */
function zhiraf_ufa_seychelles_resort_keys_from_row( array $row ) {
\t$region = ! empty( $row['island'] ) ? sanitize_key( (string) $row['island'] ) : '';
\t$keys   = array();
\tforeach ( array( 'mahe', 'praslin', 'la_digue' ) as $allowed ) {
\t\tif ( $region === $allowed ) {
\t\t\t$keys[] = $allowed;
\t\t}
\t}
\treturn array_values( array_unique( $keys ) );
}
"""
    labels = """function zhiraf_ufa_seychelles_catalog_fill_resort_labels() {
\treturn array(
\t\t'resort-mahe'     => __( 'Маэ', 'zhiraf-ufa' ),
\t\t'resort-praslin'  => __( 'Праслине', 'zhiraf-ufa' ),
\t\t'resort-la_digue' => __( 'Ла-Диг', 'zhiraf-ufa' ),
\t);
}
"""
    starters = """function zhiraf_ufa_seychelles_starter_definitions() {
\treturn array(
\t\tarray( 'json_id' => 1, 'slot' => 'base' ),
\t\tarray( 'json_id' => 3, 'slot' => 'mahe' ),
\t\tarray( 'json_id' => 6, 'slot' => 'mahe-beach' ),
\t\tarray( 'json_id' => 11, 'slot' => 'la_digue' ),
\t\tarray( 'json_id' => 16, 'slot' => 'mahe-villa' ),
\t\tarray( 'json_id' => 21, 'slot' => 'balance' ),
\t);
}
"""
    filters = """function zhiraf_ufa_seychelles_catalog_filters() {
\treturn array(
\t\tarray( 'id' => 'starter', 'group' => 'default', 'label' => __( '⭐ Стартовый топ (6)', 'zhiraf-ufa' ), 'title' => __( 'Туры на Сейшелы: 6 проверенных отелей', 'zhiraf-ufa' ), 'desc' => __( 'Маэ, Праслин и Ла-Диг — вылет из Уфы.', 'zhiraf-ufa' ) ),
\t\tarray( 'id' => 'resort-mahe', 'group' => 'resort', 'label' => __( '🏝 Маэ', 'zhiraf-ufa' ), 'title' => __( 'Маэ', 'zhiraf-ufa' ), 'desc' => __( 'Аэропорт и Бо-Валлон.', 'zhiraf-ufa' ) ),
\t\tarray( 'id' => 'resort-praslin', 'group' => 'resort', 'label' => __( '🌴 Праслин', 'zhiraf-ufa' ), 'title' => __( 'Праслин', 'zhiraf-ufa' ), 'desc' => __( 'Валлон де Мэ и паром.', 'zhiraf-ufa' ) ),
\t\tarray( 'id' => 'resort-la_digue', 'group' => 'resort', 'label' => __( '🚲 Ла-Диг', 'zhiraf-ufa' ), 'title' => __( 'Ла-Диг', 'zhiraf-ufa' ), 'desc' => __( 'Велосипеды и гранит.', 'zhiraf-ufa' ) ),
\t\tarray( 'id' => 'need-family', 'group' => 'need', 'label' => __( '👨‍👩‍👧 С детьми', 'zhiraf-ufa' ), 'title' => __( 'Семейные', 'zhiraf-ufa' ), 'desc' => __( 'Пологие бухты Маэ.', 'zhiraf-ufa' ) ),
\t\tarray( 'id' => 'need-price', 'group' => 'need', 'label' => __( '⚖️ Цена/качество', 'zhiraf-ufa' ), 'title' => __( 'Цена / качество', 'zhiraf-ufa' ), 'desc' => __( 'Селф-кейтеринг и 4★.', 'zhiraf-ufa' ) ),
\t);
}
"""
    chips = """function zhiraf_ufa_seychelles_resort_chips() {
\treturn array(
\t\tarray( 'id' => 'starter', 'label' => __( 'Топ-6', 'zhiraf-ufa' ) ),
\t\tarray( 'id' => 'resort-mahe', 'label' => __( 'Маэ', 'zhiraf-ufa' ) ),
\t\tarray( 'id' => 'resort-praslin', 'label' => __( 'Праслин', 'zhiraf-ufa' ) ),
\t\tarray( 'id' => 'resort-la_digue', 'label' => __( 'Ла-Диг', 'zhiraf-ufa' ) ),
\t);
}
"""
    picks = """function zhiraf_ufa_seychelles_resort_pick_cards() {
\t$counts = zhiraf_ufa_seychelles_resort_hotel_counts();
\t$guide = __( 'К отелям →', 'zhiraf-ufa' );
\treturn array(
\t\tarray( 'id' => 'mahe', 'filter_id' => 'resort-mahe', 'guide_url' => zhiraf_ufa_seychelles_resort_landing_url( 'mahe' ), 'guide_label' => $guide, 'name' => __( 'Маэ', 'zhiraf-ufa' ), 'subtitle' => __( 'Бо-Валлон', 'zhiraf-ufa' ), 'tag' => __( 'Старт', 'zhiraf-ufa' ), 'tag_mod' => 'family', 'transfer' => __( 'Вылет из Уфы', 'zhiraf-ufa' ), 'featured' => true, 'hotel_count' => $counts['mahe'] ?? 0, 'accent_mod' => 'emerald', 'bullets' => array( array( 'type' => 'pro', 'text' => __( 'Без парома после прилёта', 'zhiraf-ufa' ) ) ) ),
\t\tarray( 'id' => 'praslin', 'filter_id' => 'resort-praslin', 'guide_url' => zhiraf_ufa_seychelles_resort_landing_url( 'praslin' ), 'guide_label' => $guide, 'name' => __( 'Праслин', 'zhiraf-ufa' ), 'subtitle' => __( 'Валлон де Мэ', 'zhiraf-ufa' ), 'tag' => __( 'Природа', 'zhiraf-ufa' ), 'tag_mod' => 'nature', 'transfer' => __( 'Паром или перелёт', 'zhiraf-ufa' ), 'featured' => false, 'hotel_count' => $counts['praslin'] ?? 0, 'accent_mod' => 'teal', 'bullets' => array() ),
\t\tarray( 'id' => 'la_digue', 'filter_id' => 'resort-la_digue', 'guide_url' => zhiraf_ufa_seychelles_resort_landing_url( 'la_digue' ), 'guide_label' => $guide, 'name' => __( 'Ла-Диг', 'zhiraf-ufa' ), 'subtitle' => __( 'Гранитные валуны', 'zhiraf-ufa' ), 'tag' => __( 'Релакс', 'zhiraf-ufa' ), 'tag_mod' => 'adults', 'transfer' => __( 'Паром с Праслина', 'zhiraf-ufa' ), 'featured' => false, 'hotel_count' => $counts['la_digue'] ?? 0, 'accent_mod' => 'amber', 'bullets' => array() ),
\t);
}
"""
    expert = """function zhiraf_ufa_seychelles_expert_nuances() {
\treturn array(
\t\tarray( 'icon' => '✈', 'title' => __( 'Вылет из Уфы', 'zhiraf-ufa' ), 'text' => __( 'Долгий перелёт со стыковкой — планируем ночной прилёт на Маэ и трансфер до отеля.', 'zhiraf-ufa' ) ),
\t\tarray( 'icon' => '🛂', 'title' => __( 'ETA онлайн', 'zhiraf-ufa' ), 'text' => __( 'Безвизовый въезд с электронным разрешением — оформляем до вылета.', 'zhiraf-ufa' ) ),
\t\tarray( 'icon' => '⛴', 'title' => __( 'Острова и паромы', 'zhiraf-ufa' ), 'text' => __( 'Праслин и Ла-Диг — паром или внутренний рейс. Считаем логистику в пакете.', 'zhiraf-ufa' ) ),
\t);
}
"""
    pill = _pool_resort_pill(
        "seychelles",
        {"mahe": "Маэ", "praslin": "Праслин", "la_digue": "Ла-Диг"},
        "Сейшелы",
    )
    return th_to_country(
        TEMPLATE,
        func="seychelles",
        dest_slug="sejshely",
        country_bg="Сейшелы",
        title_en="Seychelles",
        label="Сейшелы",
        label_gen="Сейшелам",
        default_resort="mahe",
        base_price=328000,
        hero_img="country-sejshely.jpg",
        country_img="country-sejshely.jpg",
        uon="SC-",
        overlay="zhiraf_ufa_tourvisor_overlay_seychelles_card",
        resort_keys_php=resort_keys,
        catalog_labels_php=labels,
        pool_resort_pill_php=pill,
        starters_php=starters,
        filters_php=filters,
        chips_php=chips,
        pick_cards_php=picks,
        expert_php=expert,
    )


def main() -> None:
    specs = [
        ("curated-sri-lanka-landing.php", sri_lanka),
        ("curated-indonesia-landing.php", indonesia),
        ("curated-india-landing.php", india),
        ("curated-seychelles-landing.php", seychelles),
    ]
    for name, fn in specs:
        path = OUT_DIR / name
        path.write_text(fn(), encoding="utf-8")
        print("Wrote", path.relative_to(REPO))


if __name__ == "__main__":
    main()