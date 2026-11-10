// Run: ego-browser nodejs < tests/check-zingiber-revision3.mjs
import assert from 'node:assert/strict';
import { mkdir } from 'node:fs/promises';
const task = await taskSpace('Zingiber Revision 3');
const page = task.page('p1');
await mkdir('/tmp/zingiber-revision3', { recursive: true });
await page.cdp('Emulation.setEmulatedMedia', { features: [{ name: 'prefers-reduced-motion', value: 'reduce' }] });
for (const width of [375, 768, 1024, 1440, 1920]) {
    await page.cdp('Emulation.setDeviceMetricsOverride', { width, height: 1000, deviceScaleFactor: 1, mobile: width === 375 });
    for (const language of ['en', 'ar']) {
        await page.goto(`http://127.0.0.1:8080/${language === 'ar' ? '?lang=ar' : ''}`);
        await page.waitForSelector('[data-zingiber-preloader]', { state: 'detached' });
        await page.evaluate(() => document.fonts.ready.then(() => true));
        assert.ok(await page.evaluate(() => matchMedia('(prefers-reduced-motion: reduce)').matches), 'Reduced-motion emulation is active');
        const layout = await page.evaluate(() => {
            const rect = s => document.querySelector(s).getBoundingClientRect();
            const style = s => getComputedStyle(document.querySelector(s));
            const lines = s => rect(s).height / parseFloat(style(s).lineHeight);
            const social = document.querySelector('.zingiber-footer__social');
            return {
                overflow: document.documentElement.scrollWidth - innerWidth,
                header: rect('.zingiber-header').width,
                clear: style('.zingiber-header').backgroundColor === 'rgba(0, 0, 0, 0)' && style('.zingiber-header').backdropFilter === 'none',
                storyTop: Math.abs(rect('.zingiber-story__copy').top - rect('.zingiber-story__image').top),
                storyBorder: getComputedStyle(document.querySelector('.zingiber-story__image'), '::after').content,
                coastLines: lines('#coast-title'),
                coastBodyLines: lines('.zingiber-coastal__copy p'),
                enquiryLines: lines('#zingiber-enquiry-title'),
                enquiryOutline: style('.zingiber-enquiry__photo').outlineStyle,
                roundCaptions: [...document.querySelectorAll('.zingiber-coastal__caption')].every(e => parseFloat(getComputedStyle(e).borderRadius) === 20),
                coastNamesFit: [...document.querySelectorAll('.zingiber-coastal__caption h3')].every(e => e.scrollWidth <= e.clientWidth + 1 && e.getBoundingClientRect().height / parseFloat(getComputedStyle(e).lineHeight) < 1.1),
                principleTop: rect('.zingiber-principle-card h3').top - rect('.zingiber-principle-card').top,
                principleHeight: rect('.zingiber-principle-card').height,
                principleWeight: Number(style('.zingiber-principle-card h3').fontWeight),
                principleSize: parseFloat(style('.zingiber-principle-card h3').fontSize),
                socialInStory: social?.parentElement.classList.contains('zingiber-footer__story'),
                socialTop: social?.getBoundingClientRect().top,
                photoTop: rect('.zingiber-footer__photo').top,
                photoHeight: rect('.zingiber-footer__photo').height,
                socialAfterPhoto: document.documentElement.dir === 'rtl'
                    ? social?.getBoundingClientRect().right <= rect('.zingiber-footer__photo').left
                    : social?.getBoundingClientRect().left >= rect('.zingiber-footer__photo').right,
                socialIcons: social?.querySelectorAll('svg').length,
                labelledSocials: social && [...social.querySelectorAll('a, [role="img"]')].every(e => e.getAttribute('aria-label')),
                socialTargets: social && [...social.querySelectorAll('a')].every(e => e.offsetWidth >= 44 && e.offsetHeight >= 44),
                ginger: document.querySelector('.zingiber-footer__ginger image')?.getAttribute('href'),
                formCount: document.querySelectorAll('#zingiber-enquiry form').length,
            };
        });
        const label = `${language} ${width}px`;
        assert.equal(layout.overflow, 0, `${label}: no horizontal overflow`);
        assert.ok(Math.abs(layout.header - (width < 768 ? width - 20 : Math.max(width * .7, 448))) < 2, `${label}: wider masthead`);
        assert.ok(layout.clear, `${label}: fully transparent masthead`);
        if (width >= 768) {
            assert.ok(layout.storyTop < 2, `${label}: story starts at image top`);
            assert.ok(layout.coastLines < 1.1, `${label}: culinary heading on one line`);
            assert.ok(layout.enquiryLines < 1.1, `${label}: enquiry heading on one line`);
        }
        if (width >= 1024 && language === 'en') assert.ok(layout.coastBodyLines > 1.9 && layout.coastBodyLines < 2.1, `${label}: first culinary paragraph in two lines`);
        assert.equal(layout.storyBorder, 'none', `${label}: no inner photo border`);
        assert.equal(layout.enquiryOutline, 'none', `${label}: no outer enquiry border`);
        assert.ok(layout.roundCaptions && layout.coastNamesFit, `${label}: rounded captions and single-line region names`);
        assert.ok(layout.principleTop >= layout.principleHeight * .2 && layout.principleTop < layout.principleHeight * .4 && layout.principleWeight >= 600 && layout.principleSize >= 14, `${label}: lower, larger, bolder principle titles`);
        assert.ok(layout.socialInStory && layout.socialAfterPhoto && layout.socialTop >= layout.photoTop - 2 && layout.socialTop - layout.photoTop < layout.photoHeight * .3, `${label}: compact Follow Along group balanced beside photo`);
        assert.equal(layout.socialIcons, 3, `${label}: three platform icons`);
        assert.ok(layout.labelledSocials && layout.socialTargets, `${label}: named icons and usable targets`);
        assert.match(new URL(layout.ginger).pathname, /english-logo\.svg$/, `${label}: original vector ginger`);
        assert.equal(layout.formCount, 1, `${label}: enquiry form retained`);
        for (const selector of ['#chef', '#coastal-regions', '#principles', '#zingiber-enquiry', '.zingiber-footer__grid', '.zingiber-footer__wordmark']) {
            await page.evaluate(s => document.querySelector(s).scrollIntoView({ behavior: 'instant', block: 'start' }), selector);
            await page.waitForFunction(s => [...document.querySelector(s).querySelectorAll('img')].filter(e => {
                const r = e.getBoundingClientRect();
                return r.top < innerHeight && r.bottom > 0 && r.left < innerWidth && r.right > 0;
            }).every(e => e.complete && e.naturalWidth > 0), selector);
            await page.waitForFunction(s => [...document.querySelector(s).querySelectorAll('[data-zingiber-reveal]')].filter(e => {
                const r = e.getBoundingClientRect();
                return r.top >= 0 && r.bottom <= innerHeight;
            }).every(e => getComputedStyle(e).opacity === '1'), selector);
            if (selector === '#zingiber-enquiry') {
                await page.waitForFunction(() => document.querySelector('.zingiber-enquiry__photo').naturalWidth > 0);
                await page.evaluate(() => document.querySelector('.zingiber-enquiry__photo').decode());
                assert.equal(await page.evaluate(() => getComputedStyle(document.querySelector('.zingiber-enquiry__art')).opacity), '1', `${label}: enquiry photograph stays visible`);
            }
            await page.evaluate(() => new Promise(resolve => requestAnimationFrame(() => requestAnimationFrame(resolve))));
            if ([375, 1440].includes(width)) await page.screenshot({ path: `/tmp/zingiber-revision3/${language}-${width}-${selector.replace(/[^a-z0-9-]/gi, '')}.png` });
        }
        assert.ok(await page.evaluate(() => {
            const c = getComputedStyle(document.querySelector('.zingiber-header'));
            return document.querySelector('.zingiber-header').classList.contains('is-sticky') && c.backgroundColor === 'rgba(0, 0, 0, 0)' && c.backdropFilter === 'none';
        }), `${label}: header stays transparent after scrolling`);
        await page.click('[data-zingiber-menu-toggle]');
        await page.waitForFunction(() => document.querySelector('dialog').open);
        await page.keyboard.press('Escape');
        await page.waitForFunction(() => !document.querySelector('dialog').open && document.activeElement.hasAttribute('data-zingiber-menu-toggle'));
        console.log(`PASS Revision 3 ${label}: layout, typography, borders, footer, transparency and keyboard navigation`);
    }
}
await task.finish({ keep: [] });
