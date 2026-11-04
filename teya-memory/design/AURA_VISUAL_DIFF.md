# AURA Visual Diff — Source vs Planned (design stage)

Сравнение на уровне спецификации (не pixel QA скриншотов).

## 1440px

| Zone | Globe Trekker intent | Theme / plan | Delta |
|------|---------------------|--------------|-------|
| Header | light sticky | ✅ | — |
| Hero | collage + CTA | ✅ | flight SVG OK |
| Dest | 4 vertical + tabs | ✅ 5 cards | OK |
| Steps | — | ✅ extra vs strict ref | acceptable product block |
| Editorial | 1+3 | ✅ | thumb assets variable |
| Testimonials | quotes | **CTA trust** planned | intentional anti-haltura |
| Form | — | ✅ | — |
| Subscribe | photo | ✅ | — |
| Footer | light | ✅ | — |

## 768px

- Dest: expect 2–3 columns or scroll — theme responsive rules in SCSS bottom section.
- Editorial: stack main above list.

## 375px

- Hero: single column; collage height reduced — verify CTA not covered by collage.
- Subscribe: form column; checkbox readable.
- Cookie banner: full width bottom — OK.

## Reference file

`teya/reference-globe-trekker.jpg` missing — pixel diff against JPG **blocked**; use GLOBE-TREKKER-REDESIGN screenshots when available on host.
