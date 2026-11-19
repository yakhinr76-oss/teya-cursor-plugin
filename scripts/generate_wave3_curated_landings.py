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
    return text[:start] + new_src.strip() + "\n" + text[end:]


def th_to_country(
    code: str,
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
    pool_resort_pill_php: str,
    starters_php: str,
    filters_php: str,
    chips_php: str,
    pick_cards_php: str,
    expert_php: str,
) -> str:
    # Rename functions
    code = code.replace("zhiraf_ufa_thailand_", f"zhiraf_ufa_{func}_")
    
    # Generic text replacements
    code = code.replace("thailand", func)
    code = code.replace("tailand", dest_slug)
    code = code.replace("Тайланда", label_gen)
    code = code.replace("Таиланд", label)
    code = code.replace("Таиланде", label)
    code = code.replace("Таиланда", label_gen)
    
    code = code.replace("phuket", default_resort)
    code = code.replace("198000", str(base_price))
    code = code.replace("dest-thailand.jpg", hero_img)
    code = code.replace("country-tailand.jpg", country_img)
    code = code.replace("TH-", uon)
    code = code.replace("zhiraf_ufa_tourvisor_overlay_thailand_card", overlay)
    
    # Function bodies
    code = replace_php_function(code, f"zhiraf_ufa_{func}_resort_keys_from_row", resort_keys_php)
    code = replace_php_function(code, f"zhiraf_ufa_{func}_catalog_default_copy", catalog_labels_php)
    code = replace_php_function(code, f"zhiraf_ufa_{func}_pool_resort_pill", pool_resort_pill_php)
    code = replace_php_function(code, f"zhiraf_ufa_{func}_starter_definitions", starters_php)
    code = replace_php_function(code, f"zhiraf_ufa_{func}_catalog_filters", filters_php)
    code = replace_php_function(code, f"zhiraf_ufa_{func}_resort_chips", chips_php)
    code = replace_php_function(code, f"zhiraf_ufa_{func}_resort_pick_cards", pick_cards_php)
    code = replace_php_function(code, f"zhiraf_ufa_{func}_expert_nuances", expert_php)

    
    return code


def _pool_resort_pill(func: str, keys: dict[str, str], name: str) -> str:
    php = f"function zhiraf_ufa_{func}_pool_resort_pill( $hotel ) {{\n\t$resort = $hotel['resort'] ?? '';\n"
    for k, v in keys.items():
        php += f"\tif ( str_contains( mb_strtolower( $resort ), mb_strtolower( '{v}' ) ) ) return '{k}';\n"
    php += f"\treturn '{list(keys.keys())[0]}';\n}}"
    return php


def kitaj() -> str:
    resort_keys = """function zhiraf_ufa_kitaj_resort_keys_from_row( array $row ) {
\t$region = ! empty( $row['region'] ) ? sanitize_key( (string) $row['region'] ) : '';
\t$keys   = array();
\tforeach ( array( 'dadonghai', 'yalong', 'haitang' ) as $allowed ) {
\t\tif ( $region === $allowed ) {
\t\t\t$keys[] = $allowed;
\t\t}
\t}
\treturn $keys;
}
"""
    labels = """function zhiraf_ufa_kitaj_catalog_default_copy() {
\treturn array( 'title' => __( 'Отели на Хайнане', 'zhiraf-ufa' ), 'subtitle' => __( 'Проверенные резорты для отдыха в Санье', 'zhiraf-ufa' ) );
}
"""
    starters = """function zhiraf_ufa_kitaj_starter_definitions() {
\treturn array(
\t\tarray( 'title' => __( 'Дадунхай', 'zhiraf-ufa' ), 'url' => home_url( '/tour/kitaj/#resort-dadonghai' ) ),
\t\tarray( 'title' => __( 'Ялунвань', 'zhiraf-ufa' ), 'url' => home_url( '/tour/kitaj/#resort-yalong' ) ),
\t\tarray( 'title' => __( 'Хайтанг', 'zhiraf-ufa' ), 'url' => home_url( '/tour/kitaj/#resort-haitang' ) ),
\t);
}
"""
    filters = """function zhiraf_ufa_kitaj_catalog_filters() {
\treturn array(
\t\t'resort-dadonghai' => array( 'label' => __( 'Дадунхай', 'zhiraf-ufa' ), 'category' => 'all' ),
\t\t'resort-yalong'    => array( 'label' => __( 'Ялунвань', 'zhiraf-ufa' ), 'category' => 'all' ),
\t\t'resort-haitang'   => array( 'label' => __( 'Хайтанг', 'zhiraf-ufa' ), 'category' => 'all' ),
\t);
}
"""
    chips = """function zhiraf_ufa_kitaj_resort_chips() {
\treturn array(
\t\t'dadonghai' => array( 'label' => __( 'Дадунхай', 'zhiraf-ufa' ), 'desc' => __( 'Центр, кафе и ТЦ.', 'zhiraf-ufa' ) ),
\t\t'yalong'    => array( 'label' => __( 'Ялунвань', 'zhiraf-ufa' ), 'desc' => __( 'Широкие пляжи.', 'zhiraf-ufa' ) ),
\t\t'haitang'   => array( 'label' => __( 'Хайтанг', 'zhiraf-ufa' ), 'desc' => __( 'Новые отели.', 'zhiraf-ufa' ) ),
\t);
}
"""
    picks = """function zhiraf_ufa_kitaj_resort_pick_cards( $counts = array() ) {
\t$guide = __( 'Гид по курорту', 'zhiraf-ufa' );
\treturn array(
\t\tarray( 'id' => 'dadonghai', 'filter_id' => 'resort-dadonghai', 'guide_url' => zhiraf_ufa_kitaj_resort_landing_url( 'dadonghai' ), 'guide_label' => $guide, 'name' => __( 'Дадунхай', 'zhiraf-ufa' ), 'subtitle' => __( 'Инфраструктура', 'zhiraf-ufa' ), 'tag' => __( 'Популярно', 'zhiraf-ufa' ), 'tag_mod' => 'family', 'transfer' => __( 'Прямой рейс', 'zhiraf-ufa' ), 'featured' => true, 'hotel_count' => $counts['dadonghai'] ?? 0, 'accent_mod' => 'emerald', 'bullets' => array() ),
\t\tarray( 'id' => 'yalong', 'filter_id' => 'resort-yalong', 'guide_url' => zhiraf_ufa_kitaj_resort_landing_url( 'yalong' ), 'guide_label' => $guide, 'name' => __( 'Ялунвань', 'zhiraf-ufa' ), 'subtitle' => __( 'Чистое море', 'zhiraf-ufa' ), 'tag' => __( 'Релакс', 'zhiraf-ufa' ), 'tag_mod' => 'nature', 'transfer' => __( 'Прямой рейс', 'zhiraf-ufa' ), 'featured' => false, 'hotel_count' => $counts['yalong'] ?? 0, 'accent_mod' => 'teal', 'bullets' => array() ),
\t);
}
"""
    expert = """function zhiraf_ufa_kitaj_expert_nuances() {
\treturn array(
\t\tarray( 'icon' => '🛂', 'title' => __( 'Виза по прилёту', 'zhiraf-ufa' ), 'text' => __( 'Оформляется групповая виза.', 'zhiraf-ufa' ) ),
\t);
}
"""
    pill = _pool_resort_pill(
        "kitaj",
        {"dadonghai": "Дадунхай", "yalong": "Ялунвань", "haitang": "Хайтанг"},
        "Китай",
    )
    return th_to_country(
        TEMPLATE,
        func="kitaj",
        dest_slug="kitaj",
        country_bg="Китай",
        title_en="China",
        label="Китай",
        label_gen="Китая",
        default_resort="dadonghai",
        base_price=150000,
        hero_img="resort-sanya-2.jpg",
        country_img="country-kitaj.jpg",
        uon="CN-",
        overlay="zhiraf_ufa_tourvisor_overlay_china_card",
        resort_keys_php=resort_keys,
        catalog_labels_php=labels,
        pool_resort_pill_php=pill,
        starters_php=starters,
        filters_php=filters,
        chips_php=chips,
        pick_cards_php=picks,
        expert_php=expert,
    )

def uzbekistan() -> str:
    resort_keys = """function zhiraf_ufa_uzbekistan_resort_keys_from_row( array $row ) {
\t$region = ! empty( $row['region'] ) ? sanitize_key( (string) $row['region'] ) : '';
\t$keys   = array();
\tforeach ( array( 'tashkent', 'samarkand', 'bukhara', 'khiva' ) as $allowed ) {
\t\tif ( $region === $allowed ) {
\t\t\t$keys[] = $allowed;
\t\t}
\t}
\treturn $keys;
}
"""
    labels = """function zhiraf_ufa_uzbekistan_catalog_default_copy() {
\treturn array( 'title' => __( 'Отели в Узбекистане', 'zhiraf-ufa' ), 'subtitle' => __( 'Экскурсионные и городские отели', 'zhiraf-ufa' ) );
}
"""
    starters = """function zhiraf_ufa_uzbekistan_starter_definitions() {
\treturn array(
\t\tarray( 'title' => __( 'Ташкент', 'zhiraf-ufa' ), 'url' => home_url( '/tour/uzbekistan/#resort-tashkent' ) ),
\t\tarray( 'title' => __( 'Самарканд', 'zhiraf-ufa' ), 'url' => home_url( '/tour/uzbekistan/#resort-samarkand' ) ),
\t);
}
"""
    filters = """function zhiraf_ufa_uzbekistan_catalog_filters() {
\treturn array(
\t\t'resort-tashkent'  => array( 'label' => __( 'Ташкент', 'zhiraf-ufa' ), 'category' => 'all' ),
\t\t'resort-samarkand' => array( 'label' => __( 'Самарканд', 'zhiraf-ufa' ), 'category' => 'all' ),
\t);
}
"""
    chips = """function zhiraf_ufa_uzbekistan_resort_chips() {
\treturn array(
\t\t'tashkent'  => array( 'label' => __( 'Ташкент', 'zhiraf-ufa' ), 'desc' => __( 'Столица.', 'zhiraf-ufa' ) ),
\t\t'samarkand' => array( 'label' => __( 'Самарканд', 'zhiraf-ufa' ), 'desc' => __( 'Регистан.', 'zhiraf-ufa' ) ),
\t);
}
"""
    picks = """function zhiraf_ufa_uzbekistan_resort_pick_cards( $counts = array() ) {
\t$guide = __( 'Гид по городу', 'zhiraf-ufa' );
\treturn array(
\t\tarray( 'id' => 'tashkent', 'filter_id' => 'resort-tashkent', 'guide_url' => zhiraf_ufa_uzbekistan_resort_landing_url( 'tashkent' ), 'guide_label' => $guide, 'name' => __( 'Ташкент', 'zhiraf-ufa' ), 'subtitle' => __( 'Столица', 'zhiraf-ufa' ), 'tag' => __( 'Старт', 'zhiraf-ufa' ), 'tag_mod' => 'family', 'transfer' => __( 'Прямой рейс', 'zhiraf-ufa' ), 'featured' => true, 'hotel_count' => $counts['tashkent'] ?? 0, 'accent_mod' => 'emerald', 'bullets' => array() ),
\t\tarray( 'id' => 'samarkand', 'filter_id' => 'resort-samarkand', 'guide_url' => zhiraf_ufa_uzbekistan_resort_landing_url( 'samarkand' ), 'guide_label' => $guide, 'name' => __( 'Самарканд', 'zhiraf-ufa' ), 'subtitle' => __( 'История', 'zhiraf-ufa' ), 'tag' => __( 'Экскурсии', 'zhiraf-ufa' ), 'tag_mod' => 'nature', 'transfer' => __( 'Поезд', 'zhiraf-ufa' ), 'featured' => false, 'hotel_count' => $counts['samarkand'] ?? 0, 'accent_mod' => 'teal', 'bullets' => array() ),
\t);
}
"""
    expert = """function zhiraf_ufa_uzbekistan_expert_nuances() {
\treturn array(
\t\tarray( 'icon' => '🕌', 'title' => __( 'Экскурсии', 'zhiraf-ufa' ), 'text' => __( 'Отлично для экскурсионного туризма.', 'zhiraf-ufa' ) ),
\t);
}
"""
    pill = _pool_resort_pill(
        "uzbekistan",
        {"tashkent": "Ташкент", "samarkand": "Самарканд"},
        "Узбекистан",
    )
    return th_to_country(
        TEMPLATE,
        func="uzbekistan",
        dest_slug="uzbekistan",
        country_bg="Узбекистан",
        title_en="Uzbekistan",
        label="Узбекистан",
        label_gen="Узбекистана",
        default_resort="tashkent",
        base_price=90000,
        hero_img="country-uzbekistan.jpg",
        country_img="country-uzbekistan.jpg",
        uon="UZ-",
        overlay="zhiraf_ufa_tourvisor_overlay_uzbekistan_card",
        resort_keys_php=resort_keys,
        catalog_labels_php=labels,
        pool_resort_pill_php=pill,
        starters_php=starters,
        filters_php=filters,
        chips_php=chips,
        pick_cards_php=picks,
        expert_php=expert,
    )


def armeniya() -> str:
    resort_keys = """function zhiraf_ufa_armeniya_resort_keys_from_row( array $row ) {
\t$region = ! empty( $row['region'] ) ? sanitize_key( (string) $row['region'] ) : '';
\t$keys   = array();
\tforeach ( array( 'yerevan', 'sevan' ) as $allowed ) {
\t\tif ( $region === $allowed ) {
\t\t\t$keys[] = $allowed;
\t\t}
\t}
\treturn $keys;
}
"""
    labels = """function zhiraf_ufa_armeniya_catalog_default_copy() {
\treturn array( 'title' => __( 'Отели в Армении', 'zhiraf-ufa' ), 'subtitle' => __( 'Отели в Ереване и загородом', 'zhiraf-ufa' ) );
}
"""
    starters = """function zhiraf_ufa_armeniya_starter_definitions() {
\treturn array(
\t\tarray( 'title' => __( 'Ереван', 'zhiraf-ufa' ), 'url' => home_url( '/tour/armeniya/#resort-yerevan' ) ),
\t\tarray( 'title' => __( 'Севан', 'zhiraf-ufa' ), 'url' => home_url( '/tour/armeniya/#resort-sevan' ) ),
\t);
}
"""
    filters = """function zhiraf_ufa_armeniya_catalog_filters() {
\treturn array(
\t\t'resort-yerevan' => array( 'label' => __( 'Ереван', 'zhiraf-ufa' ), 'category' => 'all' ),
\t\t'resort-sevan'   => array( 'label' => __( 'Севан', 'zhiraf-ufa' ), 'category' => 'all' ),
\t);
}
"""
    chips = """function zhiraf_ufa_armeniya_resort_chips() {
\treturn array(
\t\t'yerevan' => array( 'label' => __( 'Ереван', 'zhiraf-ufa' ), 'desc' => __( 'Столица.', 'zhiraf-ufa' ) ),
\t\t'sevan'   => array( 'label' => __( 'Севан', 'zhiraf-ufa' ), 'desc' => __( 'Озеро и природа.', 'zhiraf-ufa' ) ),
\t);
}
"""
    picks = """function zhiraf_ufa_armeniya_resort_pick_cards( $counts = array() ) {
\t$guide = __( 'Гид по региону', 'zhiraf-ufa' );
\treturn array(
\t\tarray( 'id' => 'yerevan', 'filter_id' => 'resort-yerevan', 'guide_url' => zhiraf_ufa_armeniya_resort_landing_url( 'yerevan' ), 'guide_label' => $guide, 'name' => __( 'Ереван', 'zhiraf-ufa' ), 'subtitle' => __( 'Столица', 'zhiraf-ufa' ), 'tag' => __( 'Популярно', 'zhiraf-ufa' ), 'tag_mod' => 'family', 'transfer' => __( 'Прямой рейс', 'zhiraf-ufa' ), 'featured' => true, 'hotel_count' => $counts['yerevan'] ?? 0, 'accent_mod' => 'emerald', 'bullets' => array() ),
\t);
}
"""
    expert = """function zhiraf_ufa_armeniya_expert_nuances() {
\treturn array(
\t\tarray( 'icon' => '🍷', 'title' => __( 'Гастрономия', 'zhiraf-ufa' ), 'text' => __( 'Вкусная еда и гостеприимство.', 'zhiraf-ufa' ) ),
\t);
}
"""
    pill = _pool_resort_pill(
        "armeniya",
        {"yerevan": "Ереван", "sevan": "Севан"},
        "Армения",
    )
    return th_to_country(
        TEMPLATE,
        func="armeniya",
        dest_slug="armeniya",
        country_bg="Армения",
        title_en="Armenia",
        label="Армения",
        label_gen="Армении",
        default_resort="yerevan",
        base_price=90000,
        hero_img="country-armeniya.jpg",
        country_img="country-armeniya.jpg",
        uon="AM-",
        overlay="zhiraf_ufa_tourvisor_overlay_armenia_card",
        resort_keys_php=resort_keys,
        catalog_labels_php=labels,
        pool_resort_pill_php=pill,
        starters_php=starters,
        filters_php=filters,
        chips_php=chips,
        pick_cards_php=picks,
        expert_php=expert,
    )


def abkhaziya() -> str:
    resort_keys = """function zhiraf_ufa_abkhaziya_resort_keys_from_row( array $row ) {
\t$region = ! empty( $row['region'] ) ? sanitize_key( (string) $row['region'] ) : '';
\t$keys   = array();
\tforeach ( array( 'gagra', 'picunda', 'sukhum', 'novyj_afon' ) as $allowed ) {
\t\tif ( $region === $allowed ) {
\t\t\t$keys[] = $allowed;
\t\t}
\t}
\treturn $keys;
}
"""
    labels = """function zhiraf_ufa_abkhaziya_catalog_default_copy() {
\treturn array( 'title' => __( 'Отели в Абхазии', 'zhiraf-ufa' ), 'subtitle' => __( 'Санатории и пансионаты', 'zhiraf-ufa' ) );
}
"""
    starters = """function zhiraf_ufa_abkhaziya_starter_definitions() {
\treturn array(
\t\tarray( 'title' => __( 'Гагра', 'zhiraf-ufa' ), 'url' => home_url( '/tour/abkhaziya/#resort-gagra' ) ),
\t\tarray( 'title' => __( 'Пицунда', 'zhiraf-ufa' ), 'url' => home_url( '/tour/abkhaziya/#resort-picunda' ) ),
\t);
}
"""
    filters = """function zhiraf_ufa_abkhaziya_catalog_filters() {
\treturn array(
\t\t'resort-gagra'   => array( 'label' => __( 'Гагра', 'zhiraf-ufa' ), 'category' => 'all' ),
\t\t'resort-picunda' => array( 'label' => __( 'Пицунда', 'zhiraf-ufa' ), 'category' => 'all' ),
\t);
}
"""
    chips = """function zhiraf_ufa_abkhaziya_resort_chips() {
\treturn array(
\t\t'gagra'   => array( 'label' => __( 'Гагра', 'zhiraf-ufa' ), 'desc' => __( 'Близко к границе.', 'zhiraf-ufa' ) ),
\t\t'picunda' => array( 'label' => __( 'Пицунда', 'zhiraf-ufa' ), 'desc' => __( 'Сосны и чистое море.', 'zhiraf-ufa' ) ),
\t);
}
"""
    picks = """function zhiraf_ufa_abkhaziya_resort_pick_cards( $counts = array() ) {
\t$guide = __( 'Гид по курорту', 'zhiraf-ufa' );
\treturn array(
\t\tarray( 'id' => 'gagra', 'filter_id' => 'resort-gagra', 'guide_url' => zhiraf_ufa_abkhaziya_resort_landing_url( 'gagra' ), 'guide_label' => $guide, 'name' => __( 'Гагра', 'zhiraf-ufa' ), 'subtitle' => __( 'Ближе к Сочи', 'zhiraf-ufa' ), 'tag' => __( 'Популярно', 'zhiraf-ufa' ), 'tag_mod' => 'family', 'transfer' => __( 'Переход границы', 'zhiraf-ufa' ), 'featured' => true, 'hotel_count' => $counts['gagra'] ?? 0, 'accent_mod' => 'emerald', 'bullets' => array() ),
\t);
}
"""
    expert = """function zhiraf_ufa_abkhaziya_expert_nuances() {
\treturn array(
\t\tarray( 'icon' => '🛂', 'title' => __( 'Российский паспорт', 'zhiraf-ufa' ), 'text' => __( 'Для въезда достаточно внутреннего паспорта.', 'zhiraf-ufa' ) ),
\t);
}
"""
    pill = _pool_resort_pill(
        "abkhaziya",
        {"gagra": "Гагра", "picunda": "Пицунда"},
        "Абхазия",
    )
    return th_to_country(
        TEMPLATE,
        func="abkhaziya",
        dest_slug="abkhaziya",
        country_bg="Абхазия",
        title_en="Abkhazia",
        label="Абхазия",
        label_gen="Абхазии",
        default_resort="gagra",
        base_price=60000,
        hero_img="dest-abkhazia.jpg",
        country_img="dest-abkhazia.jpg",
        uon="AB-",
        overlay="zhiraf_ufa_tourvisor_overlay_abkhazia_card",
        resort_keys_php=resort_keys,
        catalog_labels_php=labels,
        pool_resort_pill_php=pill,
        starters_php=starters,
        filters_php=filters,
        chips_php=chips,
        pick_cards_php=picks,
        expert_php=expert,
    )


def rossiya() -> str:
    resort_keys = """function zhiraf_ufa_rossiya_resort_keys_from_row( array $row ) {
\t$region = ! empty( $row['region'] ) ? sanitize_key( (string) $row['region'] ) : '';
\t$keys   = array();
\tforeach ( array( 'sochi', 'kavminvody', 'kaliningrad', 'karelia', 'baikal', 'dagestan' ) as $allowed ) {
\t\tif ( $region === $allowed ) {
\t\t\t$keys[] = $allowed;
\t\t}
\t}
\treturn $keys;
}
"""
    labels = """function zhiraf_ufa_rossiya_catalog_default_copy() {
\treturn array( 'title' => __( 'Отели в России', 'zhiraf-ufa' ), 'subtitle' => __( 'Лучшие санатории и отели', 'zhiraf-ufa' ) );
}
"""
    starters = """function zhiraf_ufa_rossiya_starter_definitions() {
\treturn array(
\t\tarray( 'title' => __( 'Сочи', 'zhiraf-ufa' ), 'url' => home_url( '/tour/rossiya/#resort-sochi' ) ),
\t\tarray( 'title' => __( 'Кавминводы', 'zhiraf-ufa' ), 'url' => home_url( '/tour/rossiya/#resort-kavminvody' ) ),
\t);
}
"""
    filters = """function zhiraf_ufa_rossiya_catalog_filters() {
\treturn array(
\t\t'resort-sochi'      => array( 'label' => __( 'Сочи', 'zhiraf-ufa' ), 'category' => 'all' ),
\t\t'resort-kavminvody' => array( 'label' => __( 'Кавминводы', 'zhiraf-ufa' ), 'category' => 'all' ),
\t\t'resort-kaliningrad'=> array( 'label' => __( 'Калининград', 'zhiraf-ufa' ), 'category' => 'all' ),
\t);
}
"""
    chips = """function zhiraf_ufa_rossiya_resort_chips() {
\treturn array(
\t\t'sochi'      => array( 'label' => __( 'Сочи', 'zhiraf-ufa' ), 'desc' => __( 'Море и горы.', 'zhiraf-ufa' ) ),
\t\t'kavminvody' => array( 'label' => __( 'Кавминводы', 'zhiraf-ufa' ), 'desc' => __( 'Санатории.', 'zhiraf-ufa' ) ),
\t);
}
"""
    picks = """function zhiraf_ufa_rossiya_resort_pick_cards( $counts = array() ) {
\t$guide = __( 'Гид по курорту', 'zhiraf-ufa' );
\treturn array(
\t\tarray( 'id' => 'sochi', 'filter_id' => 'resort-sochi', 'guide_url' => zhiraf_ufa_rossiya_resort_landing_url( 'sochi' ), 'guide_label' => $guide, 'name' => __( 'Сочи', 'zhiraf-ufa' ), 'subtitle' => __( 'Море', 'zhiraf-ufa' ), 'tag' => __( 'Популярно', 'zhiraf-ufa' ), 'tag_mod' => 'family', 'transfer' => __( 'Прямой рейс', 'zhiraf-ufa' ), 'featured' => true, 'hotel_count' => $counts['sochi'] ?? 0, 'accent_mod' => 'emerald', 'bullets' => array() ),
\t);
}
"""
    expert = """function zhiraf_ufa_rossiya_expert_nuances() {
\treturn array(
\t\tarray( 'icon' => '✈️', 'title' => __( 'Перелёт', 'zhiraf-ufa' ), 'text' => __( 'Прямые рейсы по РФ.', 'zhiraf-ufa' ) ),
\t);
}
"""
    pill = _pool_resort_pill(
        "rossiya",
        {"sochi": "Сочи", "kavminvody": "Кавминводы"},
        "Россия",
    )
    return th_to_country(
        TEMPLATE,
        func="rossiya",
        dest_slug="rossiya",
        country_bg="Россия",
        title_en="Russia",
        label="Россия",
        label_gen="России",
        default_resort="sochi",
        base_price=50000,
        hero_img="hero-collage-1.jpg",
        country_img="hero-collage-1.jpg",
        uon="RU-",
        overlay="zhiraf_ufa_tourvisor_overlay_russia_card",
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
        ("curated-kitaj-landing.php", kitaj),
        ("curated-uzbekistan-landing.php", uzbekistan),
        ("curated-armeniya-landing.php", armeniya),
        ("curated-abkhaziya-landing.php", abkhaziya),
        ("curated-rossiya-landing.php", rossiya),
    ]
    for name, fn in specs:
        path = OUT_DIR / name
        path.write_text(fn(), encoding="utf-8")
        print("Wrote", path.relative_to(REPO))


if __name__ == "__main__":
    main()
