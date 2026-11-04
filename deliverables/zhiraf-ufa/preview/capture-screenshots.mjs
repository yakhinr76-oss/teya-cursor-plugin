import { chromium } from 'playwright';
import { copyFileSync, mkdirSync } from 'fs';
import { prepareHomeBlogBlock, scrollFullPage } from './paint-scroll.mjs';

const url = 'http://127.0.0.1:8765/';
const outDir = '/opt/cursor/artifacts';
const paintDir = '/workspace/teya-memory/wp/paint-qa';
mkdirSync(outDir, { recursive: true });
mkdirSync(paintDir, { recursive: true });

const viewports = [
  { width: 1440, height: 900, artifact: 'zhiraf-home-1440.png', paint: 'home-1440-fullpage.png' },
  { width: 390, height: 844, artifact: 'zhiraf-home-390.png', paint: 'home-375-fullpage.png' },
];

const browser = await chromium.launch();
for (const vp of viewports) {
  const page = await browser.newPage({ viewport: { width: vp.width, height: vp.height } });
  await page.goto(url, { waitUntil: 'networkidle', timeout: 60000 });
  await page.waitForTimeout(400);
  await prepareHomeBlogBlock(page);
  await scrollFullPage(page);
  const artifactPath = `${outDir}/${vp.artifact}`;
  await page.screenshot({ path: artifactPath, fullPage: true });
  copyFileSync(artifactPath, `${paintDir}/${vp.paint}`);
  await page.close();
}
await browser.close();
console.log('screenshots ok');
