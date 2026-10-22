// Run: ego-browser nodejs < tests/check-zingiber-footer.mjs
import assert from 'node:assert/strict';
import { mkdir } from 'node:fs/promises';
const task = await taskSpace('Zingiber footer contact');
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
    assert.ok(await page.evaluate(() => document.querySelector('.zingiber-footer__roots').src.includes('ginger-cluster.png')), 'Footer uses the requested botanical ginger cutout');
    assert.ok(await page.evaluate(() => document.querySelectorAll('footer [data-zingiber-reveal]').length >= 5), 'Footer groups have entrance animations');
    assert.equal(layout.overflow, 0, `${width}: no page overflow`);
    assert.ok(layout.labels, 'Every form field has a visible label');
    assert.ok(layout.strip < 240, `${width}: compact footer contact strip`);
    assert.ok(layout.logo > width * .8, `${width}: oversized wordmark`);
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
await page.click('#zingiber-enquiry button[type=submit]');
await page.waitForSelector('#enquiry-feedback');
assert.ok(await page.evaluate(() => document.getElementById('enquiry-feedback').textContent.includes('refresh')));
assert.equal(await page.evaluate(() => document.getElementById('enquiry-message').value), 'Test enquiry');
console.log('PASS: invalid email, server rejection and retained draft; no email sent');
await page.cdp('Emulation.setEmulatedMedia', { features: [{ name: 'prefers-reduced-motion', value: 'reduce' }] });
await page.goto('http://127.0.0.1:8080/');
assert.ok(await page.evaluate(() => [...document.querySelectorAll('.zingiber-enquiry [data-zingiber-reveal], footer [data-zingiber-reveal]')].every(e => getComputedStyle(e).opacity === '1' && getComputedStyle(e).transform === 'none')), 'Reduced motion displays all new content immediately');
await page.cdp('Emulation.setEmulatedMedia', { features: [{ name: 'prefers-reduced-motion', value: 'no-preference' }] });
console.log('PASS: reduced-motion fallback');
