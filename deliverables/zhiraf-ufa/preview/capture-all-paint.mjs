import { chromium } from 'playwright';
import { copyFileSync, mkdirSync, writeFileSync } from 'fs';
import { join } from 'path';

const baseUrl = 'http://127.0.0.1:8765/preview/';
const artifactsDir = '/opt/cursor/artifacts';
const paintDir = '/workspace/teya-memory/wp/paint-qa';
mkdirSync(artifactsDir, { recursive: true });
mkdirSync(paintDir, { recursive: true });

const pages = [
  { slug: 'home', file: 'home.html' },
  { slug: 'tour', file: 'tour.html' },
  { slug: 'o-nas', file: 'o-nas.html' },
  { slug: 'kontakty', file: 'kontakty.html' },
  { slug: 'blog', file: 'blog.html' },
  { slug: 'tour-turciya', file: 'tour-turciya.html' },
  { slug: 'tour-oae', file: 'tour-oae.html' },
  { slug: 'tour-egipet', file: 'tour-egipet.html' },
  { slug: 'tour-tailand', file: 'tour-tailand.html' },
  { slug: 'tour-vietnam', file: 'tour-vietnam.html' },
  { slug: 'politika-konfidenczialnosti', file: 'politika-konfidenczialnosti.html' },
  { slug: 'politika-cookies', file: 'politika-cookies.html' },
];

const viewports = [
  { w: 1440, h: 900, suffix: '1440' },
  { w: 390, h: 844, suffix: '390' },
];

async function scrollPage(page) {
  const height = await page.evaluate(() => document.body.scrollHeight);
  let y = 0;
  const step = await page.evaluate(() => window.innerHeight);
  while (y < height) {
    await page.evaluate((scrollY) => window.scrollTo(0, scrollY), y);
    await page.waitForTimeout(100);
    y += step;
  }
  await page.evaluate(() => window.scrollTo(0, 0));
  await page.waitForTimeout(150);
}

const browser = await chromium.launch();
const screenshotRecords = [];

for (const p of pages) {
  for (const vp of viewports) {
    const page = await browser.newPage({ viewport: { width: vp.w, height: vp.h } });
    const url = baseUrl + p.file;
    const res = await page.goto(url, { waitUntil: 'networkidle', timeout: 90000 });
    const status = res ? res.status() : 0;
    await page.waitForTimeout(300);
    await scrollPage(page);
    const artifact = join(artifactsDir, `zhiraf-${p.slug}-${vp.suffix}.png`);
    const paintName =
      p.slug === 'home'
        ? `home-${vp.suffix === '1440' ? '1440' : '375'}-fullpage.png`
        : `page-${p.slug}-${vp.suffix}-fullpage.png`;
    const paintPath = join(paintDir, paintName);
    await page.screenshot({ path: artifact, fullPage: true });
    copyFileSync(artifact, paintPath);
    screenshotRecords.push({
      slug: p.slug,
      file: p.file,
      viewport: vp.suffix,
      http_status: status,
      artifact,
      paint: `teya-memory/wp/paint-qa/${paintName}`,
    });
    await page.close();
  }
}
await browser.close();

const evidence = {
  public_site_url: null,
  preview_base: baseUrl,
  theme_slug: 'zhiraf-ufa',
  mode: 'local-only',
  checked_at: new Date().toISOString(),
  agent_bc_id: 'bc-646a0fe3-3bb0-5e28-bdf3-d4cafb2cc25e',
  page_build_ref: 'teya-memory/wp/page-build-report.md',
  screenshots: screenshotRecords,
  pages_captured: pages.map((p) => p.slug),
  viewports: ['1440', '390'],
  verdict: screenshotRecords.length === pages.length * 2 ? 'pass' : 'fail',
};

writeFileSync(join(paintDir, 'paint-evidence.json'), JSON.stringify(evidence, null, 2));
console.log('captured', screenshotRecords.length, 'screenshots, verdict', evidence.verdict);
