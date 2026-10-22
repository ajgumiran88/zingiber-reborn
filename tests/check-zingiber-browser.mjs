// Run against local WordPress: ego-browser nodejs < tests/check-zingiber-browser.mjs
import assert from 'node:assert/strict';
import { mkdir } from 'node:fs/promises';

const task = await taskSpace('Zingiber PDF structure');
const page = task.page('p1');
const output = '/tmp/zingiber-pdf-structure';
await mkdir(output, { recursive: true });

for (const width of [375, 768, 1024, 1440]) {
    await page.cdp('Emulation.setDeviceMetricsOverride', { width, height: 900, deviceScaleFactor: 1, mobile: width === 375 });
    await page.goto('http://127.0.0.1:8080/');
    await page.waitForSelector('[data-zingiber-preloader]', { state: 'detached' });
    await page.evaluate(() => document.fonts.ready.then(() => true));

    const layout = await page.evaluate(() => {
        const box = (selector) => document.querySelector(selector).getBoundingClientRect().toJSON();
        return {
            overflow: document.documentElement.scrollWidth - innerWidth,
            sections: [...document.querySelectorAll('main > section')].map(e => e.id),
            poster: document.querySelector('.zingiber-hero__photo').currentSrc,
            video: { hidden: document.querySelector('video').hidden, source: document.querySelector('video').currentSrc },
            tiles: document.querySelectorAll('.zingiber-gallery-teaser__tile').length,
            book: box('.zingiber-header .zingiber-book-link'),
            logo: box('.zingiber-logo-link'),
            toggle: box('[data-zingiber-menu-toggle]'),
            cards: [...document.querySelectorAll('.zingiber-principle-card')].map(a => {
                const link = a.getBoundingClientRect();
                const copy = a.querySelector('.zingiber-principle-card__copy').getBoundingClientRect();
                return { inside: copy.left >= link.left && copy.right <= link.right + 1, width: link.width };
            }),
        };
    });
    assert.equal(layout.overflow, 0, `${width}: page overflow`);
    assert.deepEqual(layout.sections, ['hero', 'chef', 'coastal-regions', 'principles', 'gallery']);
    assert.ok(layout.poster.includes('interior-hero'), `${width}: approved poster`);
    assert.equal(layout.video.hidden, true);
    assert.equal(layout.video.source, '');
    assert.equal(layout.tiles, 4);
    assert.ok(layout.book.x < layout.logo.x && layout.logo.x < layout.toggle.x);
    assert.ok(Math.abs(layout.logo.x + layout.logo.width / 2 - width / 2) < 2, `${width}: centered logo`);
    assert.ok(layout.toggle.width >= 44 && layout.toggle.height >= 44);
    assert.ok(layout.cards.every(card => card.inside), `${width}: principle copy must fit inside its image card`);
    assert.ok(await page.evaluate(() => {
        const header = document.querySelector('header').getBoundingClientRect();
        return header.width < innerWidth - 16 && header.top >= 8;
    }), `${width}: header must float within the viewport`);

    await page.click('[data-zingiber-menu-toggle]');
    await page.waitForFunction(() => document.querySelector('dialog').open);
    assert.ok(await page.evaluate(() => {
        const menu = document.querySelector('dialog').getBoundingClientRect();
        return menu.width < innerWidth - 16 && !!document.querySelector('.zingiber-navigation__image img');
    }), `${width}: contained menu with food image`);
    assert.equal(await page.evaluate(() => document.activeElement.hasAttribute('data-zingiber-menu-close')), true);
    assert.equal(await page.evaluate(() => getComputedStyle(document.documentElement).overflow), 'hidden');
    await page.focus('.zingiber-navigation__social');
    await page.keyboard.press('Tab');
    assert.equal(await page.evaluate(() => document.activeElement.matches('dialog .zingiber-book-link')), true);
    await page.keyboard.press('Shift+Tab');
    assert.equal(await page.evaluate(() => document.activeElement.matches('.zingiber-navigation__social')), true);
    await page.keyboard.press('Escape');
    await page.waitForFunction(() => !document.querySelector('dialog').open
        && document.activeElement.hasAttribute('data-zingiber-menu-toggle')
        && !document.documentElement.classList.contains('zingiber-menu-open'));

    await page.evaluate(() => document.querySelector('.zingiber-coastal__controls').scrollIntoView({ behavior: 'instant', block: 'center' }));
    await page.click('[data-zingiber-coast-step="1"]');
    await page.waitForFunction(() => document.querySelector('[data-zingiber-coast-index]').textContent === '02');
    await page.focus('[data-zingiber-coast-track]');
    await page.keyboard.press('End');
    await page.waitForFunction(() => document.querySelector('[data-zingiber-coast-index]').textContent === '04');
    assert.equal(await page.evaluate(() => document.querySelector('[data-zingiber-coast-step="1"]').disabled), true);
    await page.keyboard.press('Home');
    await page.waitForFunction(() => document.querySelector('[data-zingiber-coast-index]').textContent === '01');

    await page.evaluate(() => document.querySelector('.zingiber-principles-controls').scrollIntoView({ behavior: 'instant', block: 'center' }));
    await page.click('[data-zingiber-principles-step="1"]');
    await page.waitForFunction(() => document.querySelector('.zingiber-principle-cards').scrollLeft > 10);
    await page.focus('.zingiber-principle-cards');
    await page.keyboard.press('End');
    await page.waitForFunction(() => document.querySelector('[data-zingiber-principles-step="1"]').disabled);
    await page.keyboard.press('Home');
    await page.waitForFunction(() => document.querySelector('[data-zingiber-principles-step="-1"]').disabled);

    // Visit each reveal target so full-page screenshots show actual scrolled content.
    await page.evaluate(async () => {
        for (const target of document.querySelectorAll('[data-zingiber-reveal], footer')) {
            target.scrollIntoView({ behavior: 'instant', block: 'center' });
            await new Promise(resolve => requestAnimationFrame(() => requestAnimationFrame(resolve)));
        }
        for (const track of document.querySelectorAll('.zingiber-principle-cards, .zingiber-coastal__track')) {
            for (const slide of track.children) {
                slide.scrollIntoView({ behavior: 'instant', block: 'center', inline: 'center' });
                await new Promise(resolve => requestAnimationFrame(() => requestAnimationFrame(resolve)));
            }
            track.scrollTo({ left: 0, behavior: 'instant' });
        }
        window.scrollTo({ top: 0, behavior: 'instant' });
        document.activeElement.blur();
    });
    await page.waitForFunction(() => [...document.querySelectorAll('[data-zingiber-reveal]')].every(e => getComputedStyle(e).opacity === '1'));
    await page.waitForFunction(() => [...document.images].every(img => img.complete));
    assert.deepEqual(await page.evaluate(() => [...document.images].filter(i => !i.naturalWidth).map(i => i.src)), []);
    await page.screenshot({ path: `${output}/home-${width}.png`, fullPage: true });
    console.log(`PASS ${width}px: structure, poster, layout, overlay focus/Escape, slider and images`);
}

// Shared chrome and existing supporting-page workflows must remain usable.
await page.cdp('Emulation.setDeviceMetricsOverride', { width: 375, height: 900, deviceScaleFactor: 1, mobile: true });
await page.cdp('Emulation.setEmulatedMedia', { features: [{ name: 'prefers-reduced-motion', value: 'reduce' }] });
await page.goto('http://127.0.0.1:8080/');
assert.equal(await page.evaluate(() => getComputedStyle(document.querySelector('.zingiber-circle-link__ring')).animationName), 'none');
await page.click('[data-zingiber-menu-toggle]');
assert.ok(await page.evaluate(() => parseFloat(getComputedStyle(document.querySelector('dialog')).transitionDuration) < 0.01));
await page.screenshot({ path: `${output}/overlay-375.png` });
await page.click('[data-zingiber-menu-close]');

// A real touch swipe exercises native scroll-snap and the scroll-driven counter.
await page.cdp('Emulation.setTouchEmulationEnabled', { enabled: true });
const swipe = await page.evaluate(() => {
    const track = document.querySelector('[data-zingiber-coast-track]');
    track.scrollIntoView({ behavior: 'instant', block: 'center' });
    const box = track.getBoundingClientRect();
    return { x: innerWidth * 0.85, y: Math.max(100, box.top + 100) };
});
await page.cdp('Input.dispatchTouchEvent', { type: 'touchStart', touchPoints: [{ x: swipe.x, y: swipe.y }] });
for (let step = 1; step <= 10; step++) {
    await page.cdp('Input.dispatchTouchEvent', { type: 'touchMove', touchPoints: [{ x: swipe.x - step * 25, y: swipe.y }] });
}
await page.cdp('Input.dispatchTouchEvent', { type: 'touchEnd', touchPoints: [] });
await page.waitForFunction(() => Number(document.querySelector('[data-zingiber-coast-index]').textContent) > 1);
await page.cdp('Emulation.setTouchEmulationEnabled', { enabled: false });
console.log('PASS mobile touch swipe and reduced motion');

for (const slug of ['about', 'menu', 'gallery', 'careers', 'contact']) {
    await page.goto(`http://127.0.0.1:8080/${slug}/`);
    assert.equal(await page.evaluate(() => document.querySelectorAll('main h1').length), 1, `${slug}: page title`);
    assert.equal(await page.evaluate(() => document.documentElement.scrollWidth - innerWidth), 0, `${slug}: overflow`);
    assert.equal(await page.evaluate(() => document.querySelectorAll('.zingiber-footer__wordmark').length), 1);
    await page.click('[data-zingiber-menu-toggle]');
    await page.click(`dialog nav a[href$="/${slug}/"]`);
    await page.waitForLoadState();
    await page.waitForFunction(() => document.querySelector('dialog') && !document.querySelector('dialog').open);
    if (slug === 'about') {
        assert.equal(await page.evaluate(() => document.querySelectorAll('.zingiber-page-principles li').length), 4);
    }
    if (slug === 'gallery') {
        assert.equal(await page.evaluate(() => document.querySelectorAll('[data-zingiber-gallery-item]').length), 10);
        await page.evaluate(() => document.querySelector('[data-zingiber-gallery-filters]').scrollIntoView({ behavior: 'instant', block: 'center' }));
        await page.click('[data-zingiber-gallery-filter="cuisine"]');
        assert.equal(await page.evaluate(() => document.querySelectorAll('[data-zingiber-gallery-item]:not([hidden])').length), 6);
        await page.click('[data-zingiber-gallery-item]:not([hidden]) [data-zingiber-gallery-open] >> nth=0');
        await page.waitForSelector('.zingiber-lightbox.is-open');
        await page.keyboard.press('ArrowRight');
        assert.equal(await page.evaluate(() => document.querySelector('.zingiber-lightbox__counter').textContent), '2 of 6');
        await page.keyboard.press('Escape');
        await page.waitForFunction(() => document.querySelector('.zingiber-lightbox').hidden);
    }
    if (slug === 'contact') {
        assert.equal(await page.evaluate(() => document.querySelector('#zingiber-enquiry a[href^="mailto:"]').getAttribute('href').split('?')[0]), 'mailto:reservations@zingiber.ae');
        assert.ok(await page.evaluate(() => document.querySelector('footer').textContent.replace(/\s+/g, ' ').includes('Opening hours coming soon')));
    }
    console.log(`PASS ${slug}: shared chrome and existing workflow`);
}

// An invalid real media source must restore the poster even after video was visible.
await page.cdp('Emulation.setEmulatedMedia', { features: [{ name: 'prefers-reduced-motion', value: 'no-preference' }] });
await page.evaluate(() => {
    const script = document.querySelector('script[src*="/assets/js/zingiber.js"]').src;
    const fixture = document.createElement('iframe');
    fixture.id = 'video-check';
    fixture.srcdoc = `<video playsinline muted loop preload="none" data-zingiber-hero-video><source src="data:video/webm;base64,AA==" type="video/webm"></video><script src="${script}"></script>`;
    document.body.append(fixture);
});
await page.waitForFunction(() => {
    const video = document.querySelector('#video-check').contentDocument.querySelector('video');
    return video && video.hidden && video.paused && video.networkState === 3;
});
await page.cdp('Emulation.setEmulatedMedia', { features: [{ name: 'prefers-reduced-motion', value: 'reduce' }] });
await page.evaluate(() => {
    const fixture = document.querySelector('#video-check');
    fixture.onload = () => { fixture.dataset.reloaded = 'true'; };
    fixture.srcdoc = fixture.srcdoc.replace('<video ', '<video hidden ');
});
await page.waitForFunction(() => document.querySelector('#video-check').dataset.reloaded === 'true');
assert.equal(await page.evaluate(() => {
    const video = document.querySelector('#video-check').contentDocument.querySelector('video');
    return video.hidden && video.paused && video.networkState !== 3;
}), true);
await page.evaluate(() => document.querySelector('#video-check').remove());
console.log('PASS failed-source poster fallback and reduced-motion playback suppression');

console.log(`Browser checks passed; screenshots: ${output}`);
