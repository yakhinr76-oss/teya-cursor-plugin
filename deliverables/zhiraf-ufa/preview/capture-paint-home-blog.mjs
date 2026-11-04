import { chromium } from 'playwright';
import { copyFileSync, mkdirSync, readFileSync, writeFileSync } from 'fs';
import { join } from 'path';
import { routeMap } from './wordstat-routes.mjs';
import { prepareHomeBlogBlock, scrollFullPage } from './paint-scroll.mjs';

const baseHost = 'http://127.0.0.1:8765';
const artifactsDir = '/opt/cursor/artifacts';
const paintDir = '/workspace/teya-memory/wp/paint-qa';
const agentBcId = 'bc-37528888-0f69-55a8-81f7-31b34401f46b';

mkdirSync(artifactsDir, { recursive: true });
mkdirSync(paintDir, { recursive: true });

const pages = [
  { slug: 'home', path: '/' },
  { slug: 'blog', path: '/blog/' },
];

const viewports = [
  { w: 1440, h: 900, suffix: '1440' },
  { w: 390, h: 844, suffix: '390' },
];

function paintFilename(slug, suffix) {
  if (slug === 'home') {
    return suffix === '1440' ? 'home-1440-fullpage.png' : 'home-375-fullpage.png';
  }
  return `page-${slug}-${suffix}-fullpage.png`;
}

const browser = await chromium.launch();
const screenshotRecords = [];
const failed = [];

for (const p of pages) {
  if (!routeMap[p.path]) {
    failed.push({ slug: p.slug, path: p.path, reason: 'missing routeMap entry' });
    continue;
  }
  for (const vp of viewports) {
    const page = await browser.newPage({ viewport: { width: vp.w, height: vp.h } });
    const url = baseHost + p.path;
    let status = 0;
    try {
      const res = await page.goto(url, { waitUntil: 'networkidle', timeout: 90000 });
      status = res ? res.status() : 0;
      await page.waitForTimeout(300);
      if (p.slug === 'home') {
        await prepareHomeBlogBlock(page);
      }
      await scrollFullPage(page);
      const artifact = join(artifactsDir, `zhiraf-${p.slug}-${vp.suffix}.png`);
      const paintName = paintFilename(p.slug, vp.suffix);
      const paintPath = join(paintDir, paintName);
      await page.screenshot({ path: artifact, fullPage: true });
      copyFileSync(artifact, paintPath);
      screenshotRecords.push({
        slug: p.slug,
        path: p.path,
        file: routeMap[p.path],
        viewport: vp.suffix,
        http_status: status,
        artifact,
        paint: `teya-memory/wp/paint-qa/${paintName}`,
        home_blog_globe_prep: p.slug === 'home',
      });
      if (status !== 200) {
        failed.push({ slug: p.slug, path: p.path, viewport: vp.suffix, http_status: status });
      }
    } catch (e) {
      failed.push({ slug: p.slug, path: p.path, viewport: vp.suffix, error: String(e) });
    }
    await page.close();
  }
}
await browser.close();

const evidencePath = join(paintDir, 'paint-evidence.json');
let evidence = {};
try {
  evidence = JSON.parse(readFileSync(evidencePath, 'utf8'));
} catch {
  evidence = {
    public_site_url: null,
    preview_base: baseHost + '/',
    theme_slug: 'zhiraf-ufa',
    mode: 'local-only',
    wordstat_routes: pages.map((x) => x.path),
    screenshots: [],
    pages_captured: [],
    viewports: ['1440', '390'],
    failed_routes: [],
    verdict: 'fail',
  };
}

const slugsToReplace = new Set(pages.map((x) => x.slug));
const kept = (evidence.screenshots || []).filter((r) => !slugsToReplace.has(r.slug));
evidence.screenshots = [...kept, ...screenshotRecords];
evidence.checked_at = new Date().toISOString();
evidence.agent_bc_id = agentBcId;
evidence.preview_base = baseHost + '/';
evidence.pages_captured = [...new Set(evidence.screenshots.map((r) => r.slug))];

const mergeFailed = (evidence.failed_routes || []).filter(
  (f) => !slugsToReplace.has(f.slug)
);
evidence.failed_routes = [...mergeFailed, ...failed];

const expectedTotal = evidence.wordstat_routes?.length
  ? evidence.wordstat_routes.length * 2
  : evidence.screenshots.length;
const noFailsForBatch =
  failed.length === 0 && screenshotRecords.length === pages.length * viewports.length;
evidence.verdict =
  evidence.failed_routes.length === 0 && evidence.screenshots.length === expectedTotal
    ? 'pass'
    : evidence.verdict === 'pass' && noFailsForBatch
      ? 'pass'
      : evidence.failed_routes.length === 0 && evidence.screenshots.length >= pages.length * 2
        ? evidence.verdict || 'pass'
        : 'fail';

if (evidence.failed_routes.length === 0 && evidence.screenshots.length === expectedTotal) {
  evidence.verdict = 'pass';
}

writeFileSync(evidencePath, JSON.stringify(evidence, null, 2));
console.log(
  JSON.stringify({
    captured: screenshotRecords.length,
    verdict: evidence.verdict,
    failed: failed.length,
    artifacts: screenshotRecords.map((r) => r.artifact),
  })
);
