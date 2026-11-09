// Run: ego-browser nodejs < tests/check-zingiber-footer.mjs
import assert from 'node:assert/strict';
import { mkdir } from 'node:fs/promises';
const task = await taskSpace('Zingiber Ballena footer');
const page = task.page('p1');
await mkdir('/tmp/zingiber-footer', { recursive: true });
for (const width of [375, 768, 1024, 1440]) {
    await page.cdp('Emulation.setDeviceMetricsOverride', { width, height: 1000, deviceScaleFactor: 1, mobile: width === 375 });
    await page.goto('http://127.0.0.1:8080/contact/');
    await page.waitForSelector('[data-zingiber-preloader]', { state: 'detached' });
    await page.evaluate(() => document.fonts.ready.then(() => true));
    const layout = await page.evaluate(() => ({
        forms: document.querySelectorAll('#zingiber-enquiry form').length,
        overflow: document.documentElement.scrollWidth - innerWidth,
        labels: [...document.querySelectorAll('#zingiber-enquiry input:not([type=hidden]), #zingiber-enquiry textarea')].every(e => e.labels.length),
        strip: document.querySelector('.zingiber-footer__invitation').getBoundingClientRect().height,
        logo: document.querySelector('.zingiber-footer__wordmark').getBoundingClientRect().width,
    }));
    assert.equal(layout.forms, 1, 'Exactly one working enquiry form per page');
    assert.ok(await page.evaluate(() => document.querySelector('.zingiber-footer__roots').src.includes('roots.webp')), 'Footer uses fine roots without ginger illustrations');
    assert.ok(await page.evaluate(() => document.querySelectorAll('footer [data-zingiber-reveal]').length >= 5), 'Footer groups have entrance animations');
    assert.equal(layout.overflow, 0, `${width}: no page overflow`);
    assert.ok(layout.labels, 'Every form field has a visible label');
    assert.ok(layout.strip < 240, `${width}: compact footer contact strip`);
    assert.ok(layout.logo > width * .8, `${width}: oversized wordmark`);
    const columns = await page.evaluate(() => {
        const rect = selector => document.querySelector(selector).getBoundingClientRect();
        const brand = rect('.zingiber-footer__brand');
        const nav = rect('.zingiber-footer__nav');
        const photo = rect('.zingiber-footer__photo');
        return {
            imageIsPortrait: photo.height > photo.width,
            storyBeforeNav: document.querySelector('.zingiber-footer__brand').offsetTop <= document.querySelector('.zingiber-footer__nav').offsetTop,
            detailsAlongsideNav: document.querySelector('.zingiber-footer__nav').offsetTop === document.querySelector('.zingiber-footer__contact').offsetTop,
            desktopSplit: brand.width > nav.width * 1.7 && document.querySelector('.zingiber-footer__brand').offsetTop === document.querySelector('.zingiber-footer__nav').offsetTop,
        };
    });
    assert.ok(columns.imageIsPortrait && columns.storyBeforeNav, `${width}: portrait and introduction lead the footer`);
    assert.ok(columns.detailsAlongsideNav, `${width}: navigation and visit details share a row`);
    if (width > 1024) assert.ok(columns.desktopSplit, 'Desktop gives half the footer to the image and story');
    assert.ok(await page.evaluate(() => {
        const roots = document.querySelector('.zingiber-footer__roots').getBoundingClientRect();
        const strip = document.querySelector('.zingiber-footer__invitation').getBoundingClientRect();
        return roots.bottom <= strip.bottom;
    }), `${width}: roots must not cover the footer details`);
    await page.evaluate(() => document.getElementById('zingiber-enquiry').scrollIntoView({ behavior: 'instant', block: 'start' }));
    await page.waitForFunction(() => [...document.querySelectorAll('.zingiber-enquiry img')].every(image => image.complete && image.naturalWidth > 0));
    await page.evaluate(async () => {
        await Promise.all([...document.querySelectorAll('.zingiber-enquiry img')].map(image => image.decode()));
        await new Promise(requestAnimationFrame);
    });
    await page.screenshot({ path: `/tmp/zingiber-footer/contact-${width}.png` });
    await page.evaluate(async () => {
        for (const target of document.querySelectorAll('footer [data-zingiber-reveal]')) {
            target.scrollIntoView({ behavior: 'instant', block: 'center' });
            await new Promise(resolve => requestAnimationFrame(() => requestAnimationFrame(resolve)));
        }
        window.scrollTo({ top: document.body.scrollHeight, behavior: 'instant' });
    });
    await page.waitForFunction(() => [...document.querySelectorAll('footer [data-zingiber-reveal]')].every(e => getComputedStyle(e).opacity === '1'));
    await page.waitForFunction(() => [...document.querySelectorAll('footer img')].every(image => image.complete && image.naturalWidth > 0));
    await page.evaluate(() => document.querySelector('.zingiber-footer__brand').scrollIntoView({ behavior: 'instant', block: 'start' }));
    await page.screenshot({ path: `/tmp/zingiber-footer/columns-${width}.png` });
    await page.evaluate(() => scrollTo({ top: document.body.scrollHeight, behavior: 'instant' }));
    await page.screenshot({ path: `/tmp/zingiber-footer/footer-${width}.png` });
    console.log(`PASS: footer/contact layout ${width}px`);
}
await page.fill('#enquiry-name', 'Test Guest');
await page.fill('#enquiry-email', 'invalid');
await page.fill('#enquiry-message', 'Test enquiry');
assert.equal(await page.evaluate(() => document.getElementById('enquiry-email').validity.valid), false);
await page.fill('#enquiry-email', 'guest@example.test');
// A bad nonce exercises real server feedback without delivering an email.
await page.evaluate(() => { document.querySelector('[name=zingiber_nonce]').value = 'invalid'; });
await page.focus('#zingiber-enquiry button[type=submit]');
await page.keyboard.press('Enter');
await page.waitForSelector('#enquiry-feedback');
assert.ok(await page.evaluate(() => document.getElementById('enquiry-feedback').textContent.includes('refresh')));
assert.equal(await page.evaluate(() => document.getElementById('enquiry-message').value), 'Test enquiry');
console.log('PASS: invalid email, server rejection and retained draft; no email sent');
await page.cdp('Emulation.setEmulatedMedia', { features: [{ name: 'prefers-reduced-motion', value: 'reduce' }] });
await page.goto('http://127.0.0.1:8080/');
assert.ok(await page.evaluate(() => [...document.querySelectorAll('.zingiber-enquiry [data-zingiber-reveal], footer [data-zingiber-reveal]')].every(e => getComputedStyle(e).opacity === '1' && getComputedStyle(e).transform === 'none')), 'Reduced motion displays all new content immediately');
console.log('PASS: reduced-motion fallback');

for (const width of [375, 1440]) {
    await page.cdp('Emulation.setDeviceMetricsOverride', { width, height: 1000, deviceScaleFactor: 1, mobile: width === 375 });
    await page.goto('http://127.0.0.1:8080/?lang=ar');
    await page.waitForSelector('[data-zingiber-preloader]', { state: 'detached' });
    assert.equal(await page.evaluate(() => document.documentElement.scrollWidth - innerWidth), 0, `${width}: Arabic footer fits`);
    assert.ok(await page.evaluate(() => /[\u0600-\u06ff]/.test(document.querySelector('.zingiber-footer__story').textContent)), 'Footer introduction uses translated copy');
    assert.ok(await page.evaluate(() => [...document.querySelectorAll('footer a')].filter(a => a.origin === location.origin).every(a => new URL(a.href).searchParams.get('lang') === 'ar')), 'Footer links preserve Arabic');
    await page.focus('.zingiber-footer__story a');
    await page.keyboard.press('Shift+Tab');
    await page.keyboard.press('Tab');
    assert.ok(await page.evaluate(() => {
        const style = getComputedStyle(document.activeElement);
        return document.activeElement.matches('.zingiber-footer__story a') && style.outlineStyle !== 'none' && parseFloat(style.outlineWidth) > 0;
    }), 'Story link has visible keyboard focus');
    await page.screenshot({ path: `/tmp/zingiber-footer/arabic-${width}.png` });
    console.log(`PASS: Arabic footer and keyboard focus ${width}px`);
}
await page.cdp('Emulation.setEmulatedMedia', { features: [{ name: 'prefers-reduced-motion', value: 'no-preference' }] });

await task.finish({ keep: [] });
