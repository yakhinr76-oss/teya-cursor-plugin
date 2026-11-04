/** Shared full-page scroll + home blog / editorial-globe prep for paint QA. */

export async function prepareHomeBlogBlock(page) {
  const blogSection = page.locator('.editorial-blog-teaser, .section--editorial').first();
  await blogSection.waitFor({ state: 'visible', timeout: 30000 });
  const globe = page.locator('.editorial-globe').first();
  await globe.scrollIntoViewIfNeeded();
  await page.waitForTimeout(400);
  await page.evaluate(() => {
    const el = document.querySelector('.editorial-globe');
    if (el) el.classList.add('is-visible', 'reveal--visible');
  });
  await page.waitForTimeout(200);
}

export async function scrollFullPage(page) {
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
