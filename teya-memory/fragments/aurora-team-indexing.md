=== AURORA-TEAM-INDEXING (CRAWL/INDEX) ===
## Статус: ✅
Indexing map: teya-memory/wp/indexing-crawl-map.md
Canonical host: https://zhiraf-ufa.ru (site.inv public_site_url)
Robots: production template in inc/seo.php (Allow assets; Disallow admin/json/search); staging b2b-tour.ru Disallow: /
Sitemap: https://zhiraf-ufa.ru/sitemap_index.xml — url set from 06-url-map.csv; exclude search/admin/thank-you
Staging noindex: meta noindex,nofollow + X-Robots-Tag + robots.txt + WP blog_public=0 (bootstrap); canonical/schema → public host
AI crawlers: GPTBot, OAI-SearchBot, ChatGPT-User, ClaudeBot, PerplexityBot, Google-Extended, CCBot — Allow on production
Redirects: /kontacty→/kontakty/, /rasskazy→/blog/ (301)
Blockers: live verify robots+sitemap on zhiraf-ufa.ru post-cutover; llms.txt file not deployed yet; Yoast/RankMath sitemap config at deploy
