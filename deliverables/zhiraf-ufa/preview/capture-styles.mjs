import { chromium } from 'playwright';

const url = 'http://127.0.0.1:8765/preview/home.html';
const browser = await chromium.launch();
const page = await browser.newPage({ viewport: { width: 1440, height: 900 } });
const reqs = [];
page.on('request', (r) => reqs.push(r.url()));
await page.goto(url, { waitUntil: 'networkidle' });
const styles = await page.evaluate(() => {
  const h1 = document.querySelector('h1');
  const btn = document.querySelector('.btn--primary');
  const body = document.body;
  const cs = (el) => (el ? getComputedStyle(el) : null);
  const h1s = cs(h1);
  const btns = cs(btn);
  const bodys = cs(body);
  return {
    h1_fontFamily: h1s?.fontFamily,
    h1_color: h1s?.color,
    body_fontFamily: bodys?.fontFamily,
    body_backgroundColor: bodys?.backgroundColor,
    cta_backgroundColor: btns?.backgroundColor,
    cta_color: btns?.color,
    meaningful_images: [...document.querySelectorAll('img')].filter((img) => {
      const alt = img.getAttribute('alt');
      return img.naturalWidth > 40 && img.naturalHeight > 40;
    }).length,
  };
});
console.log(JSON.stringify({ styles, css_loaded: reqs.some((u) => u.includes('style.css')), requests: reqs.length }, null, 2));
await browser.close();
