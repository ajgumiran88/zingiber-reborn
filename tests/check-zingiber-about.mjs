// Run: ego-browser nodejs < tests/check-zingiber-about.mjs
import assert from 'node:assert/strict';
import { mkdir } from 'node:fs/promises';
const task = await taskSpace('Zingiber premium About');
const page = task.page('p1');
await mkdir('/tmp/zingiber-about', { recursive: true });
for (const width of [375, 768, 1024, 1440, 1920]) {
    await page.cdp('Emulation.setDeviceMetricsOverride', { width, height: 1000, deviceScaleFactor: 1, mobile: width === 375 });
    await page.cdp('Emulation.setEmulatedMedia', { features: [{ name: 'prefers-reduced-motion', value: 'reduce' }] });
    await page.goto('http://127.0.0.1:8080/about/');
    await page.waitForSelector('[data-zingiber-preloader]', { state: 'detached' });
    const layout = await page.evaluate(() => ({
        headings: document.querySelectorAll('main h1').length,
        hero: !!document.querySelector('.zingiber-about-hero'),
        overflow: document.documentElement.scrollWidth - innerWidth,
        width: document.querySelector('.zingiber-page-editorial > .zingiber-container').offsetWidth,
        principles: document.querySelectorAll('.zingiber-page-principles li').length,
        storyParagraphs: document.querySelectorAll('.zingiber-about__columns > p').length,
        heritageParagraphs: document.querySelectorAll('.zingiber-about__statement > p:not(.zingiber-eyebrow)').length,
        header: document.querySelector('header').offsetWidth,
        forms: document.querySelectorAll('#zingiber-enquiry form').length,
    }));
    assert.ok(layout.hero, 'About uses its own editorial hero');
    assert.equal(layout.headings, 1);
    assert.equal(layout.overflow, 0, `${width}: no horizontal overflow`);
    assert.ok(layout.width >= width * .92, `${width}: retained full-width page margins`);
    assert.equal(layout.principles, 4);
    assert.equal(layout.storyParagraphs, 2);
    assert.equal(layout.heritageParagraphs, 2);
    assert.equal(layout.forms, 1);
    assert.ok(await page.evaluate(() => {
        const frame = document.querySelector('.zingiber-about-hero .zingiber-page-hero__media').getBoundingClientRect();
        const photo = document.querySelector('.zingiber-about-hero img').getBoundingClientRect();
        const title = document.querySelector('main h1').getBoundingClientRect();
        return photo.left >= frame.left - 1 && photo.right <= frame.right + 1 && (innerWidth < 768 ? title.bottom < frame.top : title.right < frame.left);
    }), 'Hero typography and photography stay in their own frames');
    assert.ok(Math.abs(layout.header - (width < 768 ? width - 20 : Math.max(width / 2, 496))) <= 1, 'Masthead is half the page (revision 2 #2)');
    await page.evaluate(() => document.fonts.ready.then(() => true));
    for (const [name, selector] of Object.entries({ hero: '.zingiber-about-hero', story: '.zingiber-about__story', heritage: '.zingiber-about__pair', principles: '.zingiber-page-principles' })) {
        await page.evaluate(s => document.querySelector(s).scrollIntoView({ behavior: 'instant', block: 'start' }), selector);
        await page.waitForFunction(s => [...document.querySelector(s).querySelectorAll('img')].every(i => i.complete && i.naturalWidth > 0), selector);
        await page.screenshot({ path: `/tmp/zingiber-about/${name}-${width}.png` });
    }
    console.log(`PASS premium About ${width}px: copy, images, layout and compact navigation`);
}
for (const width of [375, 1440]) {
    await page.cdp('Emulation.setDeviceMetricsOverride', { width, height: 1000, deviceScaleFactor: 1, mobile: width === 375 });
    await page.goto('http://127.0.0.1:8080/about/?lang=ar');
    await page.waitForSelector('[data-zingiber-preloader]', { state: 'detached' });
    assert.equal(await page.evaluate(() => document.documentElement.scrollWidth - innerWidth), 0, `${width}: Arabic fits`);
    assert.equal(await page.evaluate(() => document.documentElement.dir), 'rtl');
    assert.ok(await page.evaluate(() => [...document.querySelectorAll('main a[href], footer a[href]')].filter(a => a.origin === location.origin && !a.pathname.includes('/assets/')).every(a => new URL(a.href).searchParams.get('lang') === 'ar')), 'Arabic links retain language');
    await page.screenshot({ path: `/tmp/zingiber-about/arabic-${width}.png` });
    await page.click('[data-zingiber-menu-toggle]');
    await page.waitForFunction(() => document.querySelector('dialog').open);
    await page.keyboard.press('Escape');
    await page.waitForFunction(() => !document.querySelector('dialog').open && document.activeElement.hasAttribute('data-zingiber-menu-toggle'));
    console.log(`PASS Arabic About ${width}px and keyboard navigation`);
}
await page.cdp('Emulation.setEmulatedMedia', { features: [{ name: 'prefers-reduced-motion', value: 'no-preference' }] });
await page.goto('http://127.0.0.1:8080/about/');
await page.waitForSelector('[data-zingiber-preloader]', { state: 'detached' });
for (const selector of ['.zingiber-about__story', '.zingiber-about__pair', '.zingiber-about__statement', '.zingiber-page-principles']) {
    await page.evaluate(s => document.querySelector(s).scrollIntoView({ behavior: 'instant', block: 'center' }), selector);
    await page.waitForFunction(s => [...document.querySelector(s).querySelectorAll('[data-zingiber-reveal]'), document.querySelector(s)].filter(e => { const r = e.getBoundingClientRect(); return r.top < innerHeight * .9 && r.bottom > 0; }).every(e => getComputedStyle(e).opacity === '1'), selector);
}
console.log('PASS About entrance reveals and reduced-motion fallback');
await task.finish({ keep: [] });
