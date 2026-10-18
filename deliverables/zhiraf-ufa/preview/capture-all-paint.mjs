import { chromium } from 'playwright';
import { copyFileSync, mkdirSync, writeFileSync } from 'fs';
import { join } from 'path';
import { routeMap } from './wordstat-routes.mjs';
import { prepareHomeBlogBlock, scrollFullPage } from './paint-scroll.mjs';

const baseHost = 'http://127.0.0.1:8765';
const artifactsDir = '/opt/cursor/artifacts';
const paintDir = '/workspace/teya-memory/wp/paint-qa';
mkdirSync(artifactsDir, { recursive: true });
mkdirSync(paintDir, { recursive: true });

/** @type {{ slug: string, path: string }[]} */
const pages = [
  { slug: 'home', path: '/' },
  { slug: 'tour', path: '/tour/' },
  { slug: 'goryashchie-tury', path: '/goryashchie-tury/' },
  { slug: 'vse-vklyucheno', path: '/vse-vklyucheno/' },
  { slug: 'tour-turciya', path: '/tour/turciya/' },
  { slug: 'tour-oae', path: '/tour/oae/' },
  { slug: 'tour-egipet', path: '/tour/egipet/' },
  { slug: 'tour-tailand', path: '/tour/tailand/' },
  { slug: 'tour-vietnam', path: '/tour/vietnam/' },
  { slug: 'tour-turciya-antalya', path: '/tour/turciya/antalya/' },
  { slug: 'tour-egipet-hurgada', path: '/tour/egipet/hurgada/' },
  { slug: 'tour-egipet-sharm-el-sheyh', path: '/tour/egipet/sharm-el-sheyh/' },
  { slug: 'tour-vietnam-nyachang', path: '/tour/vietnam/nyachang/' },
  { slug: 'tour-vietnam-fukuok', path: '/tour/vietnam/fukuok/' },
  { slug: 'o-nas', path: '/o-nas/' },
  { slug: 'kontakty', path: '/kontakty/' },
  { slug: 'blog', path: '/blog/' },
  { slug: 'politika-konfidenczialnosti', path: '/politika-konfidenczialnosti/' },
  { slug: 'politika-cookies', path: '/politika-cookies/' },
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

const evidence = {
  public_site_url: null,
  preview_base: baseHost + '/',
  theme_slug: 'zhiraf-ufa',
  mode: 'local-only',
  checked_at: new Date().toISOString(),
  agent_bc_id: 'bc-6dc66391-136a-53f5-9722-9896f4c3dbc6',
  page_build_ref: 'teya-memory/wp/page-build-report.md',
  wordstat_routes: pages.map((p) => p.path),
  screenshots: screenshotRecords,
  pages_captured: [...new Set(screenshotRecords.map((r) => r.slug))],
  viewports: ['1440', '390'],
  failed_routes: failed,
  verdict:
    failed.length === 0 && screenshotRecords.length === pages.length * 2 ? 'pass' : 'fail',
};

writeFileSync(join(paintDir, 'paint-evidence.json'), JSON.stringify(evidence, null, 2));
console.log(
  JSON.stringify({
    captured: screenshotRecords.length,
    verdict: evidence.verdict,
    failed: failed.length,
  })
);
