// Run: ego-browser nodejs < tests/check-zingiber-menu.mjs
import assert from 'node:assert/strict';
import { mkdir } from 'node:fs/promises';
const task = await taskSpace('Zingiber full width menu');
const page = task.page('p1');
await mkdir('/tmp/zingiber-menu', { recursive: true });
for (const width of [375, 768, 1024, 1440, 1920]) {
    await page.cdp('Emulation.setDeviceMetricsOverride', { width, height: 900, deviceScaleFactor: 1, mobile: width === 375 });
    await page.goto('http://127.0.0.1:8080/menu/');
    await page.waitForSelector('[data-zingiber-preloader]', { state: 'detached' });
    const layout = await page.evaluate(() => {
        const header = document.querySelector('.zingiber-header');
        const grid = document.querySelector('.zingiber-page-menu__images');
        const intro = document.querySelector('.zingiber-page-menu__intro');
        return {
            clear: getComputedStyle(header).backdropFilter === 'none' && getComputedStyle(header).backgroundColor === 'rgba(0, 0, 0, 0)',
            headerWidth: header.offsetWidth,
            gridWidth: grid.offsetWidth,
            introBeforeCards: intro.offsetTop < grid.offsetTop,
            photoSizes: [...grid.querySelectorAll('figure')].map(e => [e.offsetWidth, e.offsetHeight]),
        };
    });
    assert.ok(layout.clear, 'Masthead is fully transparent (Revision 3)');
    assert.ok(Math.abs(layout.headerWidth - (width < 768 ? width - 20 : Math.max(width * 0.7, 448))) <= 1, `${width}: masthead is 70% of the page`);
    assert.ok(layout.gridWidth >= width * .92, `${width}: full-width menu content`);
    assert.ok(layout.introBeforeCards, 'Introduction leads the food grid');
    assert.ok(layout.photoSizes.every(([w, h]) => Math.abs(w - h) <= 1 && Math.abs(w - layout.photoSizes[0][0]) <= 1), 'All food frames have equal square dimensions');
    assert.equal(await page.evaluate(() => document.querySelectorAll('.zingiber-page-hero').length), 0, 'JF menu opens with title and pills');
    assert.equal(await page.evaluate(() => document.querySelectorAll('main h1').length), 1, 'One menu title');
    assert.equal(await page.evaluate(() => getComputedStyle(document.querySelector('.zingiber-page-menu__images')).gridTemplateColumns.split(' ').length), width < 600 ? 1 : 2, 'JF food grid');
    await page.evaluate(() => document.querySelector('.zingiber-page-menu__images').scrollIntoView({ behavior: 'instant', block: 'start' }));
    if (width >= 600) {
        assert.ok(await page.evaluate(() => {
            const grid = document.querySelector('.zingiber-page-menu__images');
            return grid.offsetHeight <= innerHeight;
        }), `${width}: 2x2 menu fits in the viewport`);
    }
    await page.waitForFunction(() => document.querySelector('[data-zingiber-menu-tabs]').classList.contains('is-stuck'));
    assert.ok(await page.evaluate(() => {
        const nav = document.querySelector('[data-zingiber-menu-tabs]');
        return Math.abs(nav.getBoundingClientRect().top - parseFloat(getComputedStyle(nav).top)) <= 1 && nav.getBoundingClientRect().top > document.querySelector('header').getBoundingClientRect().bottom && getComputedStyle(nav).backdropFilter.includes('blur');
    }), 'Glass category bar sticks below the masthead');
    const lastTab = '.zingiber-page-menu__tab:last-child';
    await page.click(lastTab);
    await page.waitForFunction(() => document.querySelector('.zingiber-page-menu__tab:last-child').hasAttribute('aria-current'));
    await page.waitForFunction(() => {
        const target = document.querySelectorAll('[data-zingiber-menu-region]')[3].getBoundingClientRect();
        return target.top >= document.querySelector('[data-zingiber-menu-tabs]').getBoundingClientRect().bottom - 2 && target.top < innerHeight;
    });
    assert.equal(await page.evaluate(() => document.documentElement.scrollWidth - innerWidth), 0, 'No page overflow');
    assert.equal(await page.evaluate(() => document.querySelectorAll('.zingiber-page-menu__tab[aria-current]').length), 1, 'One active region');
    if (width === 375) {
        assert.ok(await page.evaluate(() => getComputedStyle(document.querySelector('.zingiber-page-menu__dock')).position === 'fixed'), 'JF mobile booking dock');
        assert.ok(await page.evaluate(() => document.querySelector('.zingiber-page-menu__dock a').pathname === '/contact/'), 'Booking dock uses existing contact route');
        assert.ok(await page.evaluate(() => !document.querySelector('[data-zingiber-menu-step="-1"]').hidden), 'Mobile rail arrows visible');
        await page.click('[data-zingiber-menu-step="-1"]');
        await page.waitForFunction(() => document.querySelector('[data-zingiber-menu-rail]').scrollLeft === 0);
        assert.ok(await page.evaluate(() => document.querySelector('[data-zingiber-menu-step="-1"]').disabled), 'Left edge disables previous arrow');
        await page.focus('.zingiber-page-menu__tab:first-child');
        await page.keyboard.press('Enter');
        await page.waitForFunction(() => document.querySelector('.zingiber-page-menu__tab:first-child').hasAttribute('aria-current'));
    }
    await page.evaluate(async () => {
        for (const target of document.querySelectorAll('.zingiber-menu-plate')) {
            target.scrollIntoView({ behavior: 'instant', block: 'center' });
            await new Promise(requestAnimationFrame);
        }
        await Promise.all(document.getAnimations().filter(a => a.timeline === document.timeline && a.effect.getTiming().iterations !== Infinity).map(a => a.finished.catch(() => {})));
        await Promise.all([...document.querySelectorAll('.zingiber-menu-plate img')].map(i => i.decode()));
        document.querySelector('.zingiber-menu-plate').scrollIntoView({ behavior: 'instant', block: 'center' });
    });
    await page.screenshot({ path: `/tmp/zingiber-menu/cards-${width}.png` });
    console.log(`PASS ${width}px: JF layout, sticky pills, anchors, active region and overflow`);
}
for (const language of ['', '?lang=ar']) {
    await page.cdp('Emulation.setDeviceMetricsOverride', { width: 375, height: 900, deviceScaleFactor: 1, mobile: true });
    await page.cdp('Emulation.setEmulatedMedia', { features: [{ name: 'prefers-reduced-motion', value: 'reduce' }] });
    await page.goto(`http://127.0.0.1:8080/menu/${language}`);
    await page.waitForSelector('[data-zingiber-preloader]', { state: 'detached' });
    await page.click('.zingiber-page-menu__tab:last-child');
    assert.equal(await page.evaluate(() => document.documentElement.scrollWidth - innerWidth), 0, 'RTL/reduced-motion overflow');
    assert.ok(await page.evaluate(() => [...document.querySelectorAll('.zingiber-menu-plate, .zingiber-menu-plate figure, .zingiber-menu-plate img')].every(e => getComputedStyle(e).animationName === 'none' && getComputedStyle(e).opacity === '1')), 'Reduced-motion cards remain still and readable');
}
const pageLayouts = {
    '': '#principles > .zingiber-container',
    'about/': '.zingiber-page-editorial > .zingiber-container',
    'menu/': '.zingiber-page-menu__images',
    'gallery/': '.zingiber-page-gallery__grid',
    'careers/': '.zingiber-page-careers__grid',
    'contact/': '.zingiber-page-contact__grid',
};
for (const width of [375, 768, 1440, 1920]) {
    await page.cdp('Emulation.setDeviceMetricsOverride', { width, height: 1000, deviceScaleFactor: 1, mobile: width === 375 });
    for (const [slug, selector] of Object.entries(pageLayouts)) {
        await page.goto(`http://127.0.0.1:8080/${slug}${width === 768 ? '?lang=ar' : ''}`);
        await page.waitForSelector('[data-zingiber-preloader]', { state: 'detached' });
        const layout = await page.evaluate(selector => ({
            overflow: document.documentElement.scrollWidth - innerWidth,
            widths: [selector, '.zingiber-enquiry__grid', '.zingiber-footer__grid', '.zingiber-footer__wordmark'].map(s => document.querySelector(s).offsetWidth),
            headerWidth: document.querySelector('.zingiber-header').offsetWidth,
            languageLinks: [...document.querySelectorAll('main a, footer a')].filter(a => a.origin === location.origin && !a.pathname.includes('/assets/')).every(a => new URL(a.href).searchParams.get('lang') === 'ar'),
        }), selector);
        assert.equal(layout.overflow, 0, `${slug} ${width}: no overflow`);
        assert.ok(layout.widths.every(w => w >= width * .92 && w <= width), `${slug} ${width}: content, enquiry and footer fill the page: ${layout.widths}`);
        assert.ok(Math.abs(layout.headerWidth - (width < 768 ? width - 20 : Math.max(width * 0.7, 448))) <= 1, `${slug} ${width}: masthead is 70% of the page`);
        if (width === 768) assert.ok(layout.languageLinks, `${slug}: Arabic internal links retain language`);
        assert.ok(await page.evaluate(() => {
            const rect = s => document.querySelector(s).getBoundingClientRect();
            return rect('header .zingiber-book-link').right < rect('header .zingiber-logo-link').left && rect('header .zingiber-logo-link').right < rect('header [data-zingiber-menu-toggle]').left;
        }), `${slug} ${width}: masthead controls do not overlap`);
        await page.screenshot({ path: `/tmp/zingiber-menu/page-${slug.replace('/', '') || 'home'}-${width}.png` });
        if (width === 375 || width === 1440) {
            await page.click('[data-zingiber-menu-toggle]');
            await page.waitForFunction(() => document.querySelector('dialog').open);
            await page.keyboard.press('Escape');
            await page.waitForFunction(() => !document.querySelector('dialog').open && document.activeElement.hasAttribute('data-zingiber-menu-toggle'));
        }
    }
    console.log(`PASS ${width}px: all six pages use full-width layouts without overflow`);
}
await page.goto('http://127.0.0.1:8080/menu/');
await page.cdp('Emulation.setEmulatedMedia', { features: [{ name: 'prefers-reduced-transparency', value: 'reduce' }] });
await page.waitForFunction(() => ['.zingiber-header', '.zingiber-page-menu__tabs'].every(s => {
    const style = getComputedStyle(document.querySelector(s));
    return style.backdropFilter === 'none' && style.backgroundColor === 'rgb(237, 231, 225)';
}));
console.log('PASS reduced transparency fallback');
await page.cdp('Emulation.setEmulatedMedia', { features: [] });
await task.finish({ keep: [] });
