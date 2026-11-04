=== AURORA-TEAM-CONVERSION (ФОРМЫ И ЦЕЛИ) ===
## Статус: ❌
Conversion map: teya-memory/wp/conversion-tracking-map.md
Forms: квиз 4 шага (`zhiraf_quiz`) + lead (`zhiraf_lead`) home/kontakty + subscribe hold; consent_pd + consent_marketing; honeypot + nonce + rate limit ✅ в PHP
Analytics goals: имена из funnel-map; разметка `data-track` / `[data-quiz-open]` есть — **reachGoal/gtag в main.js не подключены**; Metrika ID — needs_user_fact
Consent: legal pages в теме ✅; footer links ✅; forms 152-ФЗ ✅; cookie banner **без ссылки на политику конфиденциальности** ❌; storage `zhiraf_cookie_consent` ✅
Missing credentials: Metrika ID, GA4, SMTP, ZHIRAF_UON_API_*, newsletter confirm
Blockers: (1) cookie banner — две политики в тексте; (2) JS tracking; (3) production Metrika ID
