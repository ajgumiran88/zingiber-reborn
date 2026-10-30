// Run: ego-browser nodejs < tests/check-zingiber-motion.mjs
import assert from 'node:assert/strict';
import { mkdir } from 'node:fs/promises';

const task = await taskSpace('Zingiber Lofty motion QA');
const page = task.page('p1');
const output = '/tmp/zingiber-motion';
await mkdir(output, { recursive: true });

for (const width of [375, 1440]) {
    await page.cdp('Emulation.setDeviceMetricsOverride', { width, height: 900, deviceScaleFactor: 1, mobile: width === 375 });
    await page.cdp('Emulation.setEmulatedMedia', { features: [{ name: 'prefers-reduced-motion', value: 'no-preference' }] });
    for (const slug of ['', 'about/', 'menu/', 'gallery/', 'careers/', 'contact/']) {
        await page.goto(`http://127.0.0.1:8080/${slug}`);
        await page.waitForSelector('[data-zingiber-preloader]', { state: 'detached' });
        await page.evaluate(() => document.fonts.ready.then(() => true));
        if (!slug) {
            assert.equal(await page.evaluate(() => document.querySelectorAll('.zingiber-hero__cta .zingiber-circle-link__ring path').length), 3, 'Three sketch rings');
            assert.equal(await page.evaluate(() => document.querySelectorAll('.zingiber-hero__cta textPath, .zingiber-hero__cta .zingiber-circle-link__arrow').length), 0, 'Centered label replaces orbit text and arrow');
            assert.match(await page.evaluate(() => document.querySelector('.zingiber-circle-link__label').textContent), /See Menu/i);
            assert.ok(await page.evaluate(() => {
                const label = document.querySelector('.zingiber-circle-link__label');
                const range = document.createRange();
                range.setStart(label.firstChild, label.textContent.lastIndexOf(' ') + 1);
                range.setEnd(label.firstChild, label.textContent.length);
                return range.getClientRects().length === 1;
            }), 'Menu label keeps each word on one line');
            assert.ok(await page.evaluate(() => document.querySelector('.zingiber-hero__cta').getBoundingClientRect().width <= 110));
            assert.ok(await page.evaluate(() => {
                const delay = selector => parseFloat(getComputedStyle(document.querySelector(selector)).transitionDelay);
                return delay('.zingiber-hero__intro') < delay('.zingiber-hero h1') && delay('.zingiber-hero h1') < delay('.zingiber-hero__cta');
            }), 'Hero cascades eyebrow, title, then CTA');
            await page.click('[data-zingiber-menu-toggle]');
            assert.ok(await page.evaluate(() => document.documentElement.classList.contains('lenis-stopped')), 'Lenis pauses under the overlay');
            await page.keyboard.press('Escape');
            await page.waitForFunction(() => !document.querySelector('dialog').open && !document.documentElement.classList.contains('lenis-stopped'));
        }
        if (slug === 'menu/') {
            assert.match(await page.evaluate(() => getComputedStyle(document.querySelector('.zingiber-page-menu__display')).transition), /0\.88s cubic-bezier\(0\.16, 1, 0\.3, 1\)/, 'Menu keeps pre-pass reveal timing');
        }
        assert.ok(await page.evaluate(() => document.querySelectorAll('[data-zingiber-reveal]').length >= 12), `${slug || 'home'}: shared reveal coverage`);
        assert.ok(await page.evaluate(() => !!document.querySelector('.zingiber-enquiry__art[data-zingiber-reveal="image"]')), 'Image entrance on every page');
        await page.evaluate(async () => {
            for (const target of document.querySelectorAll('[data-zingiber-reveal]')) {
                target.scrollIntoView({ behavior: 'instant', block: 'center' });
                await new Promise(requestAnimationFrame);
            }
        });
        await page.waitForFunction(() => [...document.querySelectorAll('[data-zingiber-reveal]')].every(e => getComputedStyle(e).opacity === '1'));
        assert.equal(await page.evaluate(() => document.documentElement.scrollWidth - innerWidth), 0, `${width} ${slug}: no motion overflow`);
        // Premium pass: every content block lands with motion (Menu tabs stay as patterned; gallery count is live filter text; footer small print sits below the reveal threshold).
        assert.deepEqual(await page.evaluate(() => [...document.querySelectorAll('main h1, main h2, main h3, main p, main img, main figure, footer a, footer p, footer address')]
            .filter(e => e.getBoundingClientRect().width && getComputedStyle(e).animationName === 'none'
                && !e.closest('[data-zingiber-reveal], .zingiber-hero__media, .zingiber-page-menu__tabs, .zingiber-gallery-count, .zingiber-footer__bottom, dialog, .zingiber-sr-only'))
            .map(e => e.className || e.tagName)), [], `${width} ${slug}: every content block has motion`);
        if (slug === 'contact/') {
            const imageMotion = await page.evaluate(() => ({
                supported: CSS.supports('animation-timeline: view()'),
                animation: getComputedStyle(document.querySelector('.zingiber-enquiry__photo')).animationName,
                translate: getComputedStyle(document.querySelector('.zingiber-enquiry__photo')).translate,
            }));
            if (imageMotion.supported) assert.ok(imageMotion.animation.includes('zingiber-scroll-image'), 'Contact image has scroll-linked motion');
            await page.evaluate(() => document.querySelector('.zingiber-enquiry__art').scrollIntoView({ behavior: 'instant', block: 'center' }));
            if (imageMotion.supported) await page.waitForFunction(previous => getComputedStyle(document.querySelector('.zingiber-enquiry__photo')).translate !== previous, imageMotion.translate);
            await page.waitForFunction(() => [...document.querySelectorAll('.zingiber-enquiry img')].every(i => i.complete && i.naturalWidth));
            await page.screenshot({ path: `${output}/contact-${width}.png` });
        }
        console.log(`PASS ${width}px ${slug || 'home'}: motion, visibility and overflow`);
    }
    await page.cdp('Emulation.setEmulatedMedia', { features: [{ name: 'prefers-reduced-motion', value: 'reduce' }] });
    for (const slug of ['', 'contact/']) {
        await page.goto(`http://127.0.0.1:8080/${slug}`);
        await page.waitForSelector('[data-zingiber-preloader]', { state: 'detached' });
        assert.ok(await page.evaluate(() => [...document.querySelectorAll('[data-zingiber-reveal]')].every(e => getComputedStyle(e).opacity === '1')), 'Reduced motion shows even offscreen content');
        assert.ok(await page.evaluate(() => [...document.querySelectorAll('.zingiber-circle-link__ring, .zingiber-enquiry__photo, .zingiber-root-motion, .zingiber-spice')].every(e => getComputedStyle(e).animationName === 'none')), 'Reduced motion disables decoration and parallax');
    }
    console.log(`PASS ${width}px reduced-motion stills`);
}
await task.finish({ keep: [] });
