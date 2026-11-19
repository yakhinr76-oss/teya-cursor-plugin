#!/usr/bin/env python3
"""Regenerate wave-3 curated landings using the same pipeline as wave-2 (Thailand etalon)."""
from __future__ import annotations

import importlib.util
from pathlib import Path

REPO = Path(__file__).resolve().parents[1]
OUT_DIR = REPO / "deliverables/zhiraf-ufa/wp-theme/zhiraf-ufa/inc"

spec = importlib.util.spec_from_file_location(
    "wave2_gen", REPO / "scripts/generate_wave2_curated_landings.py"
)
wave2 = importlib.util.module_from_spec(spec)
assert spec.loader is not None
spec.loader.exec_module(wave2)

th_to_country = wave2.th_to_country
_pool_resort_pill = wave2._pool_resort_pill
TEMPLATE = wave2.TEMPLATE


def kitaj() -> str:
    resort_keys = """/**
 * Hainan bays from JSON resort label.
 *
 * @param array<string,mixed> $row JSON row.
 * @return string[]
 */
function zhiraf_ufa_kitaj_resort_keys_from_row( array $row ) {
\t$resort = mb_strtolower( (string) ( $row['resort'] ?? '' ) );
\t$keys   = array();
\tif ( str_contains( $resort, 'дадунхай' ) ) {
\t\t$keys[] = 'dadonghai';
\t}
\tif ( str_contains( $resort, 'ялунван' ) ) {
\t\t$keys[] = 'yalong';
\t}
\tif ( str_contains( $resort, 'хайтан' ) ) {
\t\t$keys[] = 'haitang';
\t}
\treturn array_values( array_unique( $keys ) );
}
"""
    labels = """function zhiraf_ufa_kitaj_catalog_fill_resort_labels() {
\treturn array(
\t\t'resort-dadonghai' => __( 'Дадунхае', 'zhiraf-ufa' ),
\t\t'resort-yalong'    => __( 'Ялунване', 'zhiraf-ufa' ),
\t\t'resort-haitang'   => __( 'Хайтанге', 'zhiraf-ufa' ),
\t);
}
"""
    starters = """function zhiraf_ufa_kitaj_starter_definitions() {
\treturn array(
\t\tarray( 'json_id' => 1, 'slot' => 'base' ),
\t\tarray( 'json_id' => 2, 'slot' => 'yalong' ),
\t\tarray( 'json_id' => 5, 'slot' => 'dadonghai' ),
\t\tarray( 'json_id' => 7, 'slot' => 'haitang' ),
\t\tarray( 'json_id' => 9, 'slot' => 'family' ),
\t\tarray( 'json_id' => 21, 'slot' => 'balance' ),
\t);
}
"""
    filters = """function zhiraf_ufa_kitaj_catalog_filters() {
\treturn array(
\t\tarray( 'id' => 'starter', 'group' => 'default', 'label' => __( '⭐ Стартовый топ (6)', 'zhiraf-ufa' ), 'title' => __( 'Туры в Санью: 6 проверенных отелей', 'zhiraf-ufa' ), 'desc' => __( 'Дадунхай, Ялунвань и Хайтан — цены за двоих с перелётом (часто стыковка в Москве).', 'zhiraf-ufa' ) ),
\t\tarray( 'id' => 'resort-dadonghai', 'group' => 'resort', 'label' => __( '🏙 Дадунхай', 'zhiraf-ufa' ), 'title' => __( 'Дадунхай', 'zhiraf-ufa' ), 'desc' => __( 'Центр, кафе и инфраструктура.', 'zhiraf-ufa' ) ),
\t\tarray( 'id' => 'resort-yalong', 'group' => 'resort', 'label' => __( '🏝 Ялунвань', 'zhiraf-ufa' ), 'title' => __( 'Ялунвань', 'zhiraf-ufa' ), 'desc' => __( 'Широкий пляж и премиум-отели.', 'zhiraf-ufa' ) ),
\t\tarray( 'id' => 'resort-haitang', 'group' => 'resort', 'label' => __( '🌴 Хайтан', 'zhiraf-ufa' ), 'title' => __( 'Хайтан', 'zhiraf-ufa' ), 'desc' => __( 'Новые резорты и спокойный ритм.', 'zhiraf-ufa' ) ),
\t\tarray( 'id' => 'need-family', 'group' => 'need', 'label' => __( '👨‍👩‍👧 С детьми', 'zhiraf-ufa' ), 'title' => __( 'Семейные', 'zhiraf-ufa' ), 'desc' => __( 'Бассейны и анимация.', 'zhiraf-ufa' ) ),
\t\tarray( 'id' => 'need-price', 'group' => 'need', 'label' => __( '⚖️ Цена/качество', 'zhiraf-ufa' ), 'title' => __( 'Цена / качество', 'zhiraf-ufa' ), 'desc' => __( '4–5★ у моря.', 'zhiraf-ufa' ) ),
\t);
}
"""
    chips = """function zhiraf_ufa_kitaj_resort_chips() {
\treturn array(
\t\tarray( 'id' => 'starter', 'label' => __( 'Топ-6', 'zhiraf-ufa' ) ),
\t\tarray( 'id' => 'resort-dadonghai', 'label' => __( 'Дадунхай', 'zhiraf-ufa' ) ),
\t\tarray( 'id' => 'resort-yalong', 'label' => __( 'Ялунвань', 'zhiraf-ufa' ) ),
\t\tarray( 'id' => 'resort-haitang', 'label' => __( 'Хайтан', 'zhiraf-ufa' ) ),
\t);
}
"""
    picks = """function zhiraf_ufa_kitaj_resort_pick_cards() {
\t$counts = zhiraf_ufa_kitaj_resort_hotel_counts();
\t$guide = __( 'К отелям →', 'zhiraf-ufa' );
\treturn array(
\t\tarray( 'id' => 'dadonghai', 'filter_id' => 'resort-dadonghai', 'guide_url' => zhiraf_ufa_kitaj_resort_landing_url( 'dadonghai' ), 'guide_label' => $guide, 'name' => __( 'Дадунхай', 'zhiraf-ufa' ), 'subtitle' => __( 'Инфраструктура', 'zhiraf-ufa' ), 'tag' => __( 'Старт', 'zhiraf-ufa' ), 'tag_mod' => 'family', 'transfer' => __( 'Стыковка в Москве', 'zhiraf-ufa' ), 'featured' => true, 'hotel_count' => $counts['dadonghai'] ?? 0, 'accent_mod' => 'emerald', 'bullets' => array() ),
\t\tarray( 'id' => 'yalong', 'filter_id' => 'resort-yalong', 'guide_url' => zhiraf_ufa_kitaj_resort_landing_url( 'yalong' ), 'guide_label' => $guide, 'name' => __( 'Ялунвань', 'zhiraf-ufa' ), 'subtitle' => __( 'Пляж 7 км', 'zhiraf-ufa' ), 'tag' => __( 'Релакс', 'zhiraf-ufa' ), 'tag_mod' => 'nature', 'transfer' => __( 'Трансфер 40–50 мин', 'zhiraf-ufa' ), 'featured' => false, 'hotel_count' => $counts['yalong'] ?? 0, 'accent_mod' => 'teal', 'bullets' => array() ),
\t);
}
"""
    expert = """function zhiraf_ufa_kitaj_expert_nuances() {
\treturn array(
\t\tarray( 'icon' => '🛂', 'title' => __( 'Виза', 'zhiraf-ufa' ), 'text' => __( 'Групповая виза по прилёту или оформление заранее — уточним до оплаты.', 'zhiraf-ufa' ) ),
\t\tarray( 'icon' => '✈', 'title' => __( 'Вылет', 'zhiraf-ufa' ), 'text' => __( 'Из Уфы — стыковка в Москве; считаем багаж и время на пересадку.', 'zhiraf-ufa' ) ),
\t\tarray( 'icon' => '🌴', 'title' => __( 'Бухты', 'zhiraf-ufa' ), 'text' => __( 'Дадунхай — город и кафе; Ялунвань — широкий пляж; Хайтан — новые отели.', 'zhiraf-ufa' ) ),
\t);
}
"""
    pill = _pool_resort_pill("kitaj", {"dadonghai": "Дадунхай", "yalong": "Ялунвань", "haitang": "Хайтан"}, "Санья")
    text = th_to_country(
        TEMPLATE,
        func="kitaj",
        dest_slug="kitaj",
        country_bg="Китай (Хайнань)",
        title_en="China",
        label="Китай",
        label_gen="Китая",
        default_resort="dadonghai",
        base_price=150000,
        hero_img="resort-sanya-2.jpg",
        country_img="resort-sanya-2.jpg",
        uon="CN-",
        overlay="zhiraf_ufa_tourvisor_overlay_kitaj_card",
        resort_keys_php=resort_keys,
        catalog_labels_php=labels,
        pool_resort_pill_php=pill,
        starters_php=starters,
        filters_php=filters,
        chips_php=chips,
        pick_cards_php=picks,
        expert_php=expert,
    )
    return text.replace("zhiraf_ufa_hotels_json_path( 'thailand' )", "zhiraf_ufa_hotels_json_path( 'china' )")


def uzbekistan() -> str:
    resort_keys = """function zhiraf_ufa_uzbekistan_resort_keys_from_row( array $row ) {
\t$resort = mb_strtolower( (string) ( $row['resort'] ?? '' ) );
\t$keys   = array();
\tif ( str_contains( $resort, 'ташкент' ) ) {
\t\t$keys[] = 'tashkent';
\t}
\tif ( str_contains( $resort, 'самарканд' ) ) {
\t\t$keys[] = 'samarkand';
\t}
\tif ( str_contains( $resort, 'бухар' ) ) {
\t\t$keys[] = 'bukhara';
\t}
\tif ( str_contains( $resort, 'хив' ) ) {
\t\t$keys[] = 'khiva';
\t}
\treturn array_values( array_unique( $keys ) );
}
"""
    labels = """function zhiraf_ufa_uzbekistan_catalog_fill_resort_labels() {
\treturn array(
\t\t'resort-tashkent' => __( 'Ташкенте', 'zhiraf-ufa' ),
\t\t'resort-samarkand' => __( 'Самарканде', 'zhiraf-ufa' ),
\t\t'resort-bukhara' => __( 'Бухаре', 'zhiraf-ufa' ),
\t\t'resort-khiva' => __( 'Хиве', 'zhiraf-ufa' ),
\t);
}
"""
    starters = """function zhiraf_ufa_uzbekistan_starter_definitions() {
\treturn array(
\t\tarray( 'json_id' => 16, 'slot' => 'tashkent' ),
\t\tarray( 'json_id' => 19, 'slot' => 'tashkent' ),
\t\tarray( 'json_id' => 14, 'slot' => 'samarkand' ),
\t\tarray( 'json_id' => 8, 'slot' => 'bukhara' ),
\t\tarray( 'json_id' => 1, 'slot' => 'khiva' ),
\t\tarray( 'json_id' => 25, 'slot' => 'balance' ),
\t);
}
"""
    filters = """function zhiraf_ufa_uzbekistan_catalog_filters() {
\treturn array(
\t\tarray( 'id' => 'starter', 'group' => 'default', 'label' => __( '⭐ Стартовый топ (6)', 'zhiraf-ufa' ), 'title' => __( 'Туры в Узбекистан: 6 проверенных отелей', 'zhiraf-ufa' ), 'desc' => __( 'Ташкент, Самарканд, Бухара и Хива — экскурсионные маршруты из Уфы.', 'zhiraf-ufa' ) ),
\t\tarray( 'id' => 'resort-tashkent', 'group' => 'resort', 'label' => __( '🏙 Ташкент', 'zhiraf-ufa' ), 'title' => __( 'Ташкент', 'zhiraf-ufa' ), 'desc' => __( 'Старт маршрута.', 'zhiraf-ufa' ) ),
\t\tarray( 'id' => 'resort-samarkand', 'group' => 'resort', 'label' => __( '🕌 Самарканд', 'zhiraf-ufa' ), 'title' => __( 'Самарканд', 'zhiraf-ufa' ), 'desc' => __( 'Регистан и медресе.', 'zhiraf-ufa' ) ),
\t\tarray( 'id' => 'resort-bukhara', 'group' => 'resort', 'label' => __( '🏺 Бухара', 'zhiraf-ufa' ), 'title' => __( 'Бухара', 'zhiraf-ufa' ), 'desc' => __( 'Старый город.', 'zhiraf-ufa' ) ),
\t\tarray( 'id' => 'resort-khiva', 'group' => 'resort', 'label' => __( '🧱 Хива', 'zhiraf-ufa' ), 'title' => __( 'Хива', 'zhiraf-ufa' ), 'desc' => __( 'Ичан-Кала.', 'zhiraf-ufa' ) ),
\t\tarray( 'id' => 'need-price', 'group' => 'need', 'label' => __( '⚖️ Цена/качество', 'zhiraf-ufa' ), 'title' => __( 'Бутики 3–4★', 'zhiraf-ufa' ), 'desc' => __( 'Уютные отели в мединах.', 'zhiraf-ufa' ) ),
\t);
}
"""
    chips = """function zhiraf_ufa_uzbekistan_resort_chips() {
\treturn array(
\t\tarray( 'id' => 'starter', 'label' => __( 'Топ-6', 'zhiraf-ufa' ) ),
\t\tarray( 'id' => 'resort-samarkand', 'label' => __( 'Самарканд', 'zhiraf-ufa' ) ),
\t\tarray( 'id' => 'resort-bukhara', 'label' => __( 'Бухара', 'zhiraf-ufa' ) ),
\t);
}
"""
    picks = """function zhiraf_ufa_uzbekistan_resort_pick_cards() {
\t$counts = zhiraf_ufa_uzbekistan_resort_hotel_counts();
\t$guide = __( 'К отелям →', 'zhiraf-ufa' );
\treturn array(
\t\tarray( 'id' => 'samarkand', 'filter_id' => 'resort-samarkand', 'guide_url' => zhiraf_ufa_uzbekistan_resort_landing_url( 'samarkand' ), 'guide_label' => $guide, 'name' => __( 'Самарканд', 'zhiraf-ufa' ), 'subtitle' => __( 'Регистан', 'zhiraf-ufa' ), 'tag' => __( 'Must-see', 'zhiraf-ufa' ), 'tag_mod' => 'family', 'transfer' => __( 'Поезд Сапсан', 'zhiraf-ufa' ), 'featured' => true, 'hotel_count' => $counts['samarkand'] ?? 0, 'accent_mod' => 'emerald', 'bullets' => array() ),
\t\tarray( 'id' => 'bukhara', 'filter_id' => 'resort-bukhara', 'guide_url' => zhiraf_ufa_uzbekistan_resort_landing_url( 'bukhara' ), 'guide_label' => $guide, 'name' => __( 'Бухара', 'zhiraf-ufa' ), 'subtitle' => __( 'Медресе', 'zhiraf-ufa' ), 'tag' => __( 'История', 'zhiraf-ufa' ), 'tag_mod' => 'nature', 'transfer' => __( '2 ночи в маршруте', 'zhiraf-ufa' ), 'featured' => false, 'hotel_count' => $counts['bukhara'] ?? 0, 'accent_mod' => 'teal', 'bullets' => array() ),
\t);
}
"""
    expert = """function zhiraf_ufa_uzbekistan_expert_nuances() {
\treturn array(
\t\tarray( 'icon' => '✈', 'title' => __( 'Вылет из Уфы', 'zhiraf-ufa' ), 'text' => __( 'Прямые и стыковочные рейсы — подберём оптимальный блок.', 'zhiraf-ufa' ) ),
\t\tarray( 'icon' => '🚄', 'title' => __( 'Поезда', 'zhiraf-ufa' ), 'text' => __( 'Между городами — скоростной Afrosiyob или классика — заложим в маршрут.', 'zhiraf-ufa' ) ),
\t\tarray( 'icon' => '🛂', 'title' => __( 'Виза', 'zhiraf-ufa' ), 'text' => __( 'Для граждан РФ — безвиз до 60 дней, уточним срок поездки.', 'zhiraf-ufa' ) ),
\t);
}
"""
    pill = _pool_resort_pill(
        "uzbekistan",
        {"tashkent": "Ташкент", "samarkand": "Самарканд", "bukhara": "Бухара", "khiva": "Хива"},
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
        default_resort="samarkand",
        base_price=110000,
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
\t$resort = mb_strtolower( (string) ( $row['resort'] ?? '' ) );
\t$keys   = array();
\tif ( str_contains( $resort, 'ереван' ) ) {
\t\t$keys[] = 'yerevan';
\t}
\tif ( str_contains( $resort, 'севан' ) || str_contains( $resort, 'дилижан' ) || str_contains( $resort, 'цахкадзор' ) || str_contains( $resort, 'джермук' ) ) {
\t\t$keys[] = 'regions';
\t}
\treturn array_values( array_unique( $keys ) );
}
"""
    labels = """function zhiraf_ufa_armeniya_catalog_fill_resort_labels() {
\treturn array(
\t\t'resort-yerevan' => __( 'Ереване', 'zhiraf-ufa' ),
\t\t'resort-regions' => __( 'Загородом', 'zhiraf-ufa' ),
\t);
}
"""
    starters = """function zhiraf_ufa_armeniya_starter_definitions() {
\treturn array(
\t\tarray( 'json_id' => 22, 'slot' => 'yerevan' ),
\t\tarray( 'json_id' => 4, 'slot' => 'yerevan' ),
\t\tarray( 'json_id' => 21, 'slot' => 'yerevan' ),
\t\tarray( 'json_id' => 6, 'slot' => 'regions' ),
\t\tarray( 'json_id' => 12, 'slot' => 'regions' ),
\t\tarray( 'json_id' => 19, 'slot' => 'balance' ),
\t);
}
"""
    filters = """function zhiraf_ufa_armeniya_catalog_filters() {
\treturn array(
\t\tarray( 'id' => 'starter', 'group' => 'default', 'label' => __( '⭐ Стартовый топ (6)', 'zhiraf-ufa' ), 'title' => __( 'Туры в Армению: 6 проверенных отелей', 'zhiraf-ufa' ), 'desc' => __( 'Ереван и регионы — Севан, Цахкадзор. Вылет из Уфы.', 'zhiraf-ufa' ) ),
\t\tarray( 'id' => 'resort-yerevan', 'group' => 'resort', 'label' => __( '🏙 Ереван', 'zhiraf-ufa' ), 'title' => __( 'Ереван', 'zhiraf-ufa' ), 'desc' => __( 'Город и гастрономия.', 'zhiraf-ufa' ) ),
\t\tarray( 'id' => 'resort-regions', 'group' => 'resort', 'label' => __( '🏔 Регионы', 'zhiraf-ufa' ), 'title' => __( 'Загород', 'zhiraf-ufa' ), 'desc' => __( 'Севан и курорты.', 'zhiraf-ufa' ) ),
\t\tarray( 'id' => 'need-price', 'group' => 'need', 'label' => __( '⚖️ Цена/качество', 'zhiraf-ufa' ), 'title' => __( '3–4★', 'zhiraf-ufa' ), 'desc' => __( 'Бутики и сетевые отели.', 'zhiraf-ufa' ) ),
\t);
}
"""
    chips = """function zhiraf_ufa_armeniya_resort_chips() {
\treturn array(
\t\tarray( 'id' => 'starter', 'label' => __( 'Топ-6', 'zhiraf-ufa' ) ),
\t\tarray( 'id' => 'resort-yerevan', 'label' => __( 'Ереван', 'zhiraf-ufa' ) ),
\t);
}
"""
    picks = """function zhiraf_ufa_armeniya_resort_pick_cards() {
\t$counts = zhiraf_ufa_armeniya_resort_hotel_counts();
\t$guide = __( 'К отелям →', 'zhiraf-ufa' );
\treturn array(
\t\tarray( 'id' => 'yerevan', 'filter_id' => 'resort-yerevan', 'guide_url' => zhiraf_ufa_armeniya_resort_landing_url( 'yerevan' ), 'guide_label' => $guide, 'name' => __( 'Ереван', 'zhiraf-ufa' ), 'subtitle' => __( 'Столица', 'zhiraf-ufa' ), 'tag' => __( 'Старт', 'zhiraf-ufa' ), 'tag_mod' => 'family', 'transfer' => __( 'Прямой рейс', 'zhiraf-ufa' ), 'featured' => true, 'hotel_count' => $counts['yerevan'] ?? 0, 'accent_mod' => 'emerald', 'bullets' => array() ),
\t);
}
"""
    expert = """function zhiraf_ufa_armeniya_expert_nuances() {
\treturn array(
\t\tarray( 'icon' => '🛂', 'title' => __( 'Безвиз', 'zhiraf-ufa' ), 'text' => __( 'Паспорт РФ — въезд без визы.', 'zhiraf-ufa' ) ),
\t\tarray( 'icon' => '🍷', 'title' => __( 'Гастрономия', 'zhiraf-ufa' ), 'text' => __( 'Бронируем столики и маршруты с дегустациями.', 'zhiraf-ufa' ) ),
\t\tarray( 'icon' => '🏔', 'title' => __( 'Севан', 'zhiraf-ufa' ), 'text' => __( '1–2 ночи у озера — закладываем в пакет.', 'zhiraf-ufa' ) ),
\t);
}
"""
    pill = _pool_resort_pill("armeniya", {"yerevan": "Ереван", "regions": "Севан"}, "Армения")
    return th_to_country(
        TEMPLATE,
        func="armeniya",
        dest_slug="armeniya",
        country_bg="Армения",
        title_en="Armenia",
        label="Армения",
        label_gen="Армении",
        default_resort="yerevan",
        base_price=95000,
        hero_img="country-armeniya.jpg",
        country_img="country-armeniya.jpg",
        uon="AM-",
        overlay="zhiraf_ufa_tourvisor_overlay_armeniya_card",
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
\tforeach ( array( 'gagra', 'pitsunda', 'new_afon', 'sukhum' ) as $allowed ) {
\t\tif ( $region === $allowed ) {
\t\t\t$keys[] = $allowed;
\t\t}
\t}
\treturn $keys;
}
"""
    labels = """function zhiraf_ufa_abkhaziya_catalog_fill_resort_labels() {
\treturn array(
\t\t'resort-gagra' => __( 'Гагре', 'zhiraf-ufa' ),
\t\t'resort-pitsunda' => __( 'Пицунде', 'zhiraf-ufa' ),
\t);
}
"""
    starters = """function zhiraf_ufa_abkhaziya_starter_definitions() {
\treturn array(
\t\tarray( 'json_id' => 17, 'slot' => 'gagra' ),
\t\tarray( 'json_id' => 18, 'slot' => 'gagra' ),
\t\tarray( 'json_id' => 8, 'slot' => 'pitsunda' ),
\t\tarray( 'json_id' => 16, 'slot' => 'gagra' ),
\t\tarray( 'json_id' => 11, 'slot' => 'new_afon' ),
\t\tarray( 'json_id' => 19, 'slot' => 'balance' ),
\t);
}
"""
    filters = """function zhiraf_ufa_abkhaziya_catalog_filters() {
\treturn array(
\t\tarray( 'id' => 'starter', 'group' => 'default', 'label' => __( '⭐ Стартовый топ (6)', 'zhiraf-ufa' ), 'title' => __( 'Отдых в Абхазии: 6 проверенных отелей', 'zhiraf-ufa' ), 'desc' => __( 'Гагра и Пицунда — трансфер из Адлера.', 'zhiraf-ufa' ) ),
\t\tarray( 'id' => 'resort-gagra', 'group' => 'resort', 'label' => __( '🌊 Гагра', 'zhiraf-ufa' ), 'title' => __( 'Гагра', 'zhiraf-ufa' ), 'desc' => __( 'Ближе к границе.', 'zhiraf-ufa' ) ),
\t\tarray( 'id' => 'resort-pitsunda', 'group' => 'resort', 'label' => __( '🌲 Пицунда', 'zhiraf-ufa' ), 'title' => __( 'Пицунда', 'zhiraf-ufa' ), 'desc' => __( 'Сосны и чистое море.', 'zhiraf-ufa' ) ),
\t\tarray( 'id' => 'need-family', 'group' => 'need', 'label' => __( '👨‍👩‍👧 С детьми', 'zhiraf-ufa' ), 'title' => __( 'Семейные', 'zhiraf-ufa' ), 'desc' => __( 'Пологий заход.', 'zhiraf-ufa' ) ),
\t);
}
"""
    chips = """function zhiraf_ufa_abkhaziya_resort_chips() {
\treturn array(
\t\tarray( 'id' => 'starter', 'label' => __( 'Топ-6', 'zhiraf-ufa' ) ),
\t\tarray( 'id' => 'resort-gagra', 'label' => __( 'Гагра', 'zhiraf-ufa' ) ),
\t\tarray( 'id' => 'resort-pitsunda', 'label' => __( 'Пицунда', 'zhiraf-ufa' ) ),
\t);
}
"""
    picks = """function zhiraf_ufa_abkhaziya_resort_pick_cards() {
\t$counts = zhiraf_ufa_abkhaziya_resort_hotel_counts();
\t$guide = __( 'К отелям →', 'zhiraf-ufa' );
\treturn array(
\t\tarray( 'id' => 'gagra', 'filter_id' => 'resort-gagra', 'guide_url' => zhiraf_ufa_abkhaziya_resort_landing_url( 'gagra' ), 'guide_label' => $guide, 'name' => __( 'Гагра', 'zhiraf-ufa' ), 'subtitle' => __( 'Набережная', 'zhiraf-ufa' ), 'tag' => __( 'Популярно', 'zhiraf-ufa' ), 'tag_mod' => 'family', 'transfer' => __( 'Из Адлера', 'zhiraf-ufa' ), 'featured' => true, 'hotel_count' => $counts['gagra'] ?? 0, 'accent_mod' => 'emerald', 'bullets' => array() ),
\t\tarray( 'id' => 'pitsunda', 'filter_id' => 'resort-pitsunda', 'guide_url' => zhiraf_ufa_abkhaziya_resort_landing_url( 'pitsunda' ), 'guide_label' => $guide, 'name' => __( 'Пицунда', 'zhiraf-ufa' ), 'subtitle' => __( 'Тишина', 'zhiraf-ufa' ), 'tag' => __( 'С семьёй', 'zhiraf-ufa' ), 'tag_mod' => 'nature', 'transfer' => __( '1–1,5 ч от Адлера', 'zhiraf-ufa' ), 'featured' => false, 'hotel_count' => $counts['pitsunda'] ?? 0, 'accent_mod' => 'teal', 'bullets' => array() ),
\t);
}
"""
    expert = """function zhiraf_ufa_abkhaziya_expert_nuances() {
\treturn array(
\t\tarray( 'icon' => '🛂', 'title' => __( 'Паспорт РФ', 'zhiraf-ufa' ), 'text' => __( 'Внутренний паспорт и рубли — без заграна.', 'zhiraf-ufa' ) ),
\t\tarray( 'icon' => '🚐', 'title' => __( 'Трансфер', 'zhiraf-ufa' ), 'text' => __( 'Встреча в Адлере и довоз до отеля — согласуем до оплаты.', 'zhiraf-ufa' ) ),
\t\tarray( 'icon' => '🌲', 'title' => __( 'Гагра vs Пицунда', 'zhiraf-ufa' ), 'text' => __( 'Гагра — инфраструктура; Пицунда — сосны и спокойный пляж.', 'zhiraf-ufa' ) ),
\t);
}
"""
    pill = _pool_resort_pill("abkhaziya", {"gagra": "Гагра", "pitsunda": "Пицунда"}, "Абхазия")
    return th_to_country(
        TEMPLATE,
        func="abkhaziya",
        dest_slug="abkhaziya",
        country_bg="Абхазия",
        title_en="Abkhazia",
        label="Абхазия",
        label_gen="Абхазии",
        default_resort="gagra",
        base_price=65000,
        hero_img="dest-abkhazia.jpg",
        country_img="dest-abkhazia.jpg",
        uon="AB-",
        overlay="zhiraf_ufa_tourvisor_overlay_abkhaziya_card",
        resort_keys_php=resort_keys,
        catalog_labels_php=labels,
        pool_resort_pill_php=pill,
        starters_php=starters,
        filters_php=filters,
        chips_php=chips,
        pick_cards_php=picks,
        expert_php=expert,
    ).replace("zhiraf_ufa_hotels_json_path( 'thailand' )", "zhiraf_ufa_hotels_json_path( 'abkhazia' )")


def rossiya() -> str:
    resort_keys = """function zhiraf_ufa_rossiya_resort_keys_from_row( array $row ) {
\t$region = ! empty( $row['region'] ) ? sanitize_key( (string) $row['region'] ) : '';
\t$keys   = array();
\tif ( in_array( $region, array( 'sochi_coast', 'anapa', 'krasnaya_polyana' ), true ) ) {
\t\t$keys[] = 'south';
\t}
\tif ( in_array( $region, array( 'kmv', 'spb' ), true ) ) {
\t\t$keys[] = 'health';
\t}
\tif ( in_array( $region, array( 'kaliningrad_baltic', 'moscow' ), true ) ) {
\t\t$keys[] = 'city';
\t}
\treturn array_values( array_unique( $keys ) );
}
"""
    labels = """function zhiraf_ufa_rossiya_catalog_fill_resort_labels() {
\treturn array(
\t\t'resort-south' => __( 'Юг', 'zhiraf-ufa' ),
\t\t'resort-health' => __( 'Курорты', 'zhiraf-ufa' ),
\t);
}
"""
    starters = """function zhiraf_ufa_rossiya_starter_definitions() {
\treturn array(
\t\tarray( 'json_id' => 11, 'slot' => 'south' ),
\t\tarray( 'json_id' => 7, 'slot' => 'south' ),
\t\tarray( 'json_id' => 9, 'slot' => 'south' ),
\t\tarray( 'json_id' => 12, 'slot' => 'south' ),
\t\tarray( 'json_id' => 31, 'slot' => 'city' ),
\t\tarray( 'json_id' => 38, 'slot' => 'health' ),
\t);
}
"""
    filters = """function zhiraf_ufa_rossiya_catalog_filters() {
\treturn array(
\t\tarray( 'id' => 'starter', 'group' => 'default', 'label' => __( '⭐ Стартовый топ (6)', 'zhiraf-ufa' ), 'title' => __( 'Отдых в России: 6 проверенных отелей', 'zhiraf-ufa' ), 'desc' => __( 'Сочи, Красная Поляна и другие — пакет или только отель.', 'zhiraf-ufa' ) ),
\t\tarray( 'id' => 'resort-south', 'group' => 'resort', 'label' => __( '🌊 Юг', 'zhiraf-ufa' ), 'title' => __( 'Сочи и побережье', 'zhiraf-ufa' ), 'desc' => __( 'Море и горы.', 'zhiraf-ufa' ) ),
\t\tarray( 'id' => 'resort-health', 'group' => 'resort', 'label' => __( '💆 Курорты', 'zhiraf-ufa' ), 'title' => __( 'Санатории', 'zhiraf-ufa' ), 'desc' => __( 'Кавминводы и СПА.', 'zhiraf-ufa' ) ),
\t\tarray( 'id' => 'need-family', 'group' => 'need', 'label' => __( '👨‍👩‍👧 С детьми', 'zhiraf-ufa' ), 'title' => __( 'Семейные', 'zhiraf-ufa' ), 'desc' => __( 'С питанием и бассейном.', 'zhiraf-ufa' ) ),
\t);
}
"""
    chips = """function zhiraf_ufa_rossiya_resort_chips() {
\treturn array(
\t\tarray( 'id' => 'starter', 'label' => __( 'Топ-6', 'zhiraf-ufa' ) ),
\t\tarray( 'id' => 'resort-south', 'label' => __( 'Сочи', 'zhiraf-ufa' ) ),
\t);
}
"""
    picks = """function zhiraf_ufa_rossiya_resort_pick_cards() {
\t$counts = zhiraf_ufa_rossiya_resort_hotel_counts();
\t$guide = __( 'К отелям →', 'zhiraf-ufa' );
\treturn array(
\t\tarray( 'id' => 'sochi', 'filter_id' => 'resort-south', 'guide_url' => zhiraf_ufa_rossiya_resort_landing_url( 'south' ), 'guide_label' => $guide, 'name' => __( 'Сочи', 'zhiraf-ufa' ), 'subtitle' => __( 'Море', 'zhiraf-ufa' ), 'tag' => __( 'Лето', 'zhiraf-ufa' ), 'tag_mod' => 'family', 'transfer' => __( 'Прямой рейс из Уфы', 'zhiraf-ufa' ), 'featured' => true, 'hotel_count' => $counts['south'] ?? 0, 'accent_mod' => 'emerald', 'bullets' => array() ),
\t);
}
"""
    expert = """function zhiraf_ufa_rossiya_expert_nuances() {
\treturn array(
\t\tarray( 'icon' => '✈', 'title' => __( 'Перелёты по РФ', 'zhiraf-ufa' ), 'text' => __( 'Прямые рейсы в Сочи и регионы — или только отель.', 'zhiraf-ufa' ) ),
\t\tarray( 'icon' => '🏨', 'title' => __( 'Только проживание', 'zhiraf-ufa' ), 'text' => __( 'Блочные тарифы, если билеты уже есть.', 'zhiraf-ufa' ) ),
\t\tarray( 'icon' => '🏔', 'title' => __( 'Красная Поляна', 'zhiraf-ufa' ), 'text' => __( 'Горы и море — подберём связку.', 'zhiraf-ufa' ) ),
\t);
}
"""
    pill = _pool_resort_pill("rossiya", {"south": "Сочи", "health": "Курорты"}, "Россия")
    text = th_to_country(
        TEMPLATE,
        func="rossiya",
        dest_slug="rossiya",
        country_bg="Россия",
        title_en="Russia",
        label="Россия",
        label_gen="России",
        default_resort="south",
        base_price=55000,
        hero_img="hero-collage-1.jpg",
        country_img="hero-collage-1.jpg",
        uon="RU-",
        overlay="zhiraf_ufa_tourvisor_overlay_rossiya_card",
        resort_keys_php=resort_keys,
        catalog_labels_php=labels,
        pool_resort_pill_php=pill,
        starters_php=starters,
        filters_php=filters,
        chips_php=chips,
        pick_cards_php=picks,
        expert_php=expert,
    )
    return text.replace("zhiraf_ufa_hotels_json_path( 'thailand' )", "zhiraf_ufa_hotels_json_path( 'russia' )")


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