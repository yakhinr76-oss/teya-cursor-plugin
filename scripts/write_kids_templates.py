#!/usr/bin/env python3
from pathlib import Path

base = Path(__file__).resolve().parents[1] / "deliverables/zhiraf-ufa/wp-theme/zhiraf-ufa/template-parts/sections"
th = (base / "kids-thailand.php").read_text(encoding="utf-8")
configs = [
    ("kids-sri-lanka.php", "Шри-Ланке", "Шри-Ланку", "Шри-Ланка, семейный подбор", "3 семейных отеля на Шри-Ланке", "Унаватуна: риф и пологий заход.", "Летом смотрим восток (Пасикуда) или переносим даты."),
    ("kids-indonesia.php", "Бали", "Бали", "Бали, семейный подбор", "3 семейных отеля на Бали", "Нуса-Дуа: спокойное море для малышей.", "Семиньяк — волны; для купания берём Нуса-Дуа или Санур."),
    ("kids-india.php", "Гоа", "Гоа", "Гоа, семейный подбор", "3 семейных отеля в Гоа", "Южный Гоа: длинный песок и тишина.", "Север — шумнее; с детьми чаще южные пляжи."),
    ("kids-seychelles.php", "Сейшелах", "Сейшелы", "Сейшелы, семейный подбор", "3 семейных отеля на Сейшелах", "Маэ: пологие бухты без парома.", "Праслин — дольше логистика; планируем ночной прилёт."),
]
for fname, where, title_country, lead_tour, lead_title, li1, li2 in configs:
    t = th.replace("kids-thailand", fname[:-4])
    t = t.replace("Таиланд с детьми из Уфы", f"{title_country} с детьми из Уфы")
    prep = "в" if where in ("Гоа", "Шри-Ланке") else "на"
    t = t.replace("тур в Таиланд", f"тур {prep} {where}")
    t = t.replace("Таиланд, семейный подбор", lead_tour)
    t = t.replace("3 семейных отеля в Таиланде", lead_title)
    t = t.replace(
        "Пхукет: пологий песок в Ката/Карон — летом смотрим флаги из-за волн на западе.",
        li1,
    )
    t = t.replace(
        "Паттайя: аквапарки и спокойнее море в Сиамском заливе летом.",
        li2,
    )
    (base / fname).write_text(t, encoding="utf-8")
    print("wrote", fname)