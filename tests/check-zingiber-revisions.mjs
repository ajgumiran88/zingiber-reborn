// Run: ego-browser nodejs < tests/check-zingiber-revisions.mjs
import assert from 'node:assert/strict';
import { mkdir } from 'node:fs/promises';
const task = await taskSpace('Zingiber revisions October 30');
const page = task.page('p1');
await mkdir('/tmp/zingiber-revisions', { recursive: true });
for (const width of [375, 768, 1024, 1440]) {
    await page.cdp('Emulation.setDeviceMetricsOverride', { width, height: 900, deviceScaleFactor: 1, mobile: width === 375 });
    await page.goto('http://127.0.0.1:8080/');
    await page.waitForSelector('[data-zingiber-preloader]', { state: 'detached' });
    await page.evaluate(() => document.fonts.ready.then(() => true));
    const result = await page.evaluate(() => {
        const rect = s => document.querySelector(s).getBoundingClientRect();
        const cards = [...document.querySelectorAll('.zingiber-principle-cards > li')];
        return {
            overflow: document.documentElement.scrollWidth - innerWidth,
            languages: document.querySelectorAll('header .zingiber-language a').length,
            langInHeader: !!document.querySelector('.zingiber-header .zingiber-language'),
            langAfterMenu: rect('.zingiber-language').left >= rect('[data-zingiber-menu-toggle]').right,
            languageTargets: [...document.querySelectorAll('header .zingiber-language a')].every(a => a.getBoundingClientRect().width >= 44 && a.getBoundingClientRect().height >= 44),
            languageSelectionContrast: getComputedStyle(document.querySelector('.zingiber-language a[aria-current]')).color === getComputedStyle(document.querySelector('.zingiber-language')).color,
            langNearMenu: (() => {
                const lang = document.querySelector('.zingiber-language').getBoundingClientRect();
                const menu = document.querySelector('[data-zingiber-menu-toggle]').getBoundingClientRect();
                return Math.abs(lang.right - menu.left) < 24 || Math.abs(lang.left - menu.right) < 24 || (lang.top < menu.bottom + 8 && Math.abs(lang.left - menu.left) < 220);
            })(),
            logo: document.querySelector('.zingiber-logo--light').src,
            circle: rect('.zingiber-hero__cta').width,
            heroStoryGap: document.querySelector('#chef').getBoundingClientRect().top - document.querySelector('#hero').getBoundingClientRect().bottom,
            circleLabel: document.querySelector('.zingiber-circle-link__label')?.textContent || '',
            sketchRings: document.querySelectorAll('.zingiber-hero__cta .zingiber-circle-link__ring path').length,
            headingLines: rect('#coast-title').height / parseFloat(getComputedStyle(document.querySelector('#coast-title')).lineHeight),
            cardsVisible: cards.filter(e => { const r = e.getBoundingClientRect(); const t = rect('.zingiber-principle-cards'); return r.left >= t.left - 1 && r.right <= t.right + 1; }).length,
            principlesPaged: document.querySelector('.zingiber-principle-cards').scrollWidth > document.querySelector('.zingiber-principle-cards').clientWidth,
            heroType: parseFloat(getComputedStyle(document.querySelector('.zingiber-hero h1')).fontSize),
            sectionSpace: parseFloat(getComputedStyle(document.querySelector('#gallery')).paddingTop),
            videoSource: document.querySelector('video source')?.src || '',
            dialablePlaceholder: !!document.querySelector('a[href="tel:+971501234567"]'),
            obsoleteArt: [...document.images].some(e => /ginger.*cluster|photoreal/.test(e.src)),
            photo: document.querySelector('.zingiber-enquiry__photo').src,
            headerHeight: rect('.zingiber-header').height,
            enquiryBottom: parseFloat(getComputedStyle(document.querySelector('.zingiber-enquiry')).paddingBottom),
            book: document.querySelector('.zingiber-footer__book').textContent.trim(),
            bookButton: getComputedStyle(document.querySelector('.zingiber-footer__book')).borderStyle !== 'none',
            phone: document.querySelector('footer').textContent.includes('+971 50 123 4567'),
            roots: document.querySelectorAll('.zingiber-root-motion').length,
            spices: [...document.querySelectorAll('#gallery .zingiber-spice')].filter(e => e.getBoundingClientRect().width > 0 && getComputedStyle(e).animationName.includes('zingiber-spice-float')).length,
            headerClear: getComputedStyle(document.querySelector('.zingiber-header')).backgroundColor === 'rgba(0, 0, 0, 0)',
            headerWidth: rect('.zingiber-header').width,
            bookGap: rect('.zingiber-header .zingiber-book-link').left - rect('.zingiber-header').left,
            pauseButtons: document.querySelectorAll('[data-zingiber-coast-toggle]').length,
            storyTitle: document.querySelector('#story-title').textContent.replace(/\s+/g, ' ').trim(),
            splitWords: document.querySelectorAll('#story-title .zingiber-split-word').length,
            headlineWeight: Number(getComputedStyle(document.querySelector('#story-title')).fontWeight),
            door: getComputedStyle(document.querySelector('.zingiber-enquiry__photo')).borderTopLeftRadius,
            rootsShare: rect('.zingiber-footer__roots').width / rect('.zingiber-footer').width,
            rootsRight: Math.abs(rect('.zingiber-footer__roots').right - rect('.zingiber-footer').right),
            footerType: [getComputedStyle(document.querySelector('.zingiber-footer__nav a')).fontSize, getComputedStyle(document.querySelector('.zingiber-footer__heading')).fontSize].map(parseFloat),
        };
    });
    assert.equal(result.languages, 2, `${width}: working English/Arabic selector`);
    assert.equal(result.overflow, 0, `${width}: no overflow`);
    assert.ok(result.langInHeader && result.langNearMenu, `${width}: language selector beside menu control`);
    assert.ok(result.langAfterMenu && result.languageTargets, `${width}: language follows hamburger with 44px targets`);
    assert.ok(result.languageSelectionContrast, `${width}: selected language retains light header contrast`);
    assert.ok(result.logo.includes('english-logo'), 'Supplied English logo');
    assert.ok(result.circle <= 110, 'Smaller menu circle');
    assert.ok(result.heroStoryGap >= 48, `${width}: white space between hero and story (PDF item 9)`);
    assert.match(result.circleLabel, /See Menu/i, 'Centered menu label');
    assert.equal(result.sketchRings, 3, 'Three sketch rings match supplied circle reference');
    assert.ok(result.headingLines <= 2.1, `${width}: coastal heading fits two lines`);
    assert.equal(result.cardsVisible, 2, `${width}: two principles at first glance`);
    assert.ok(result.principlesPaged, `${width}: remaining principles page in with next/previous`);
    assert.ok(result.heroType <= 50, `${width}: restrained hero type`);
    assert.ok(result.sectionSpace >= 40 && result.sectionSpace <= 90, `${width}: section space without excessive gaps (revision 2 #7): ${result.sectionSpace}`);
    assert.match(result.videoSource, /hero-placeholder\.mp4/, 'Existing placeholder video is retained');
    assert.equal(result.dialablePlaceholder, false, 'Placeholder phone cannot be dialed');
    assert.equal(result.obsoleteArt, false, 'Obsolete illustrations removed');
    assert.ok(result.photo.includes('interior-'), 'Restaurant enquiry photograph');
    if (width === 375) {
        assert.ok(result.headerHeight <= 60, `mobile masthead height: ${result.headerHeight}`);
        assert.ok(result.enquiryBottom <= 96, `mobile enquiry bottom spacing: ${result.enquiryBottom}`);
    }
    assert.match(result.book, /Book Now/);
    assert.ok(result.bookButton, 'Book Now is a defined button');
    assert.ok(result.phone, 'Requested phone placeholder');
    assert.ok(result.roots >= 1, 'Animated footer roots replace ginger illustrations');
    assert.equal(result.spices, width < 1024 ? 6 : 9, `${width}: Experience floats whole-spice art at every depth`);
    assert.ok(result.headerClear, 'Clear masthead over the hero (revision 2 #2)');
    if (width >= 768) assert.ok(Math.abs(result.headerWidth - Math.max(width * 0.7, 448)) < 2, `${width}: masthead is 70% of the page: ${result.headerWidth}`);
    assert.ok(result.bookGap >= 8, `${width}: room beside Book Now (revision 2 #3): ${result.bookGap}`);
    assert.equal(result.pauseButtons, 0, 'No slider pause buttons (revision 2 #6, #9)');
    assert.equal(result.storyTitle, 'Coastal India, Reimagined for Now', 'Word split keeps heading text intact');
    assert.ok(result.splitWords >= 5, 'Story headline animates word by word (revision 2 #4, #5)');
    assert.ok(result.headlineWeight >= 600, 'Bolder headlines (revision 2 #8)');
    assert.match(result.door, /^999px/, 'Get in Touch keeps the door arch (revision 2 #12)');
    if (width >= 768) assert.ok(Math.abs(result.rootsShare - 0.5) < 0.01 && result.rootsRight < 1, `${width}: roots cover the right half of the footer`);
    assert.ok(result.footerType[0] < result.footerType[1] && result.footerType[0] <= 13, `${width}: footer body below heading size (revision 2 #15)`);
    await page.evaluate(() => scrollTo(0, innerHeight));
    await page.waitForFunction(() => document.querySelector('.zingiber-header').classList.contains('is-sticky'));
    // Wait out the header's background transition before reading the colour.
    await page.waitForFunction(() => getComputedStyle(document.querySelector('.zingiber-header')).backgroundColor === 'rgba(0, 0, 0, 0)');
    await page.evaluate(() => scrollTo(0, 0));
    await page.screenshot({ path: `/tmp/zingiber-revisions/hero-${width}.png` });
    for (const selector of ['#principles', '#gallery', '#zingiber-enquiry', 'footer']) {
        await page.evaluate(s => document.querySelector(s).scrollIntoView({ behavior: 'instant', block: 'center' }), selector);
        // Mirror the reveal observer (8% bottom margin): fully visible items must have revealed, and every triggered item must finish.
        await page.waitForFunction(s => [...document.querySelector(s).querySelectorAll('[data-zingiber-reveal]')].filter(e => {const r=e.getBoundingClientRect();return e.classList.contains('is-visible')||(r.top>=0&&r.bottom<=innerHeight*.92);}).every(e => getComputedStyle(e).opacity === '1'), selector);
        await page.waitForFunction(s => [...document.querySelector(s).querySelectorAll('img')].every(i => i.complete && i.naturalWidth > 0), selector);
        await page.screenshot({ path: `/tmp/zingiber-revisions/${selector.replace(/[#.]/g, '')}-${width}.png` });
    }
    console.log(`PASS PDF revisions at ${width}px`);
}
await page.cdp('Emulation.setEmulatedMedia', { features: [{ name: 'prefers-reduced-motion', value: 'reduce' }] });
await page.goto('http://127.0.0.1:8080/');
assert.ok(await page.evaluate(() => [...document.querySelectorAll('.zingiber-root-motion, .zingiber-spice')].every(e => getComputedStyle(e).animationName === 'none')));
console.log('PASS roots respect reduced motion');
for (const width of [375, 1440]) {
    await page.cdp('Emulation.setDeviceMetricsOverride', { width, height: 900, deviceScaleFactor: 1, mobile: width === 375 });
    for (const slug of ['', 'about/', 'menu/', 'gallery/', 'careers/', 'contact/']) {
        await page.goto(`http://127.0.0.1:8080/${slug}?lang=ar`);
        await page.waitForSelector('[data-zingiber-preloader]', { state: 'detached' });
        assert.equal(await page.evaluate(() => document.documentElement.dir), 'rtl');
        assert.ok(await page.evaluate(() => /[\u0600-\u06ff]/.test(document.querySelector('h1').textContent)), 'Arabic heading');
        assert.equal(await page.evaluate(() => document.documentElement.scrollWidth - innerWidth), 0, `${slug} ${width}: Arabic overflow`);
        if (slug) assert.ok(await page.evaluate(() => document.querySelector('.zingiber-language').getBoundingClientRect().bottom < document.querySelector('.zingiber-page-hero__content .zingiber-eyebrow, .zingiber-page-menu__mast .zingiber-eyebrow').getBoundingClientRect().top), 'Language selector must not cover the page label');
        assert.ok(await page.evaluate(() => [...document.querySelectorAll('main a[href], footer a[href], header a[href], dialog a[href]')].filter(a => a.origin === location.origin && !a.pathname.includes('/assets/') && !a.closest('.zingiber-language')).every(a => new URL(a.href).searchParams.get('lang') === 'ar')), 'Arabic internal links preserve language');
        assert.ok(await page.evaluate(() => document.querySelector('#zingiber-enquiry form').action.includes('lang=ar')), 'Arabic enquiry action preserves language');
    }
    await page.screenshot({ path: `/tmp/zingiber-revisions/arabic-contact-${width}.png` });
}
await page.click('header .zingiber-language a[lang="en"]');
await page.waitForFunction(() => document.documentElement.lang.startsWith('en'));
assert.ok((await page.url()).includes('/contact/'), 'Language switch stays on the current page');
console.log('PASS Arabic: six pages, RTL, English return, internal links and enquiry action');
await page.cdp('Emulation.setEmulatedMedia', { features: [{ name: 'prefers-reduced-motion', value: 'no-preference' }] });
for (const width of [375, 1440]) {
    await page.cdp('Emulation.setDeviceMetricsOverride', { width, height: 900, deviceScaleFactor: 1, mobile: width === 375 });
    await page.goto('http://127.0.0.1:8080/menu/');
    await page.waitForSelector('[data-zingiber-preloader]', { state: 'detached' });
    const plates = await page.evaluate(() => document.querySelectorAll('.zingiber-menu-plate').length);
    assert.equal(plates, 4, 'Four regional menu plates');
    for (let index = 0; index < plates; index++) {
        await page.evaluate(i => document.querySelectorAll('.zingiber-menu-plate')[i].scrollIntoView({ behavior: 'instant', block: 'center' }), index);
        await page.waitForFunction(i => document.querySelectorAll('.zingiber-menu-plate')[i].classList.contains('is-visible'), index);
    }
    assert.equal(await page.evaluate(() => document.documentElement.scrollWidth - innerWidth), 0, `${width}: menu overflow`);
    await page.waitForFunction(() => getComputedStyle(document.querySelector('.zingiber-page-menu__tab[aria-current]')).color === 'rgb(237, 231, 225)');
    assert.equal(await page.evaluate(() => getComputedStyle(document.querySelector('.zingiber-page-menu__tab[aria-current]')).color), 'rgb(237, 231, 225)', `${width}: active menu region has readable text`);
    assert.ok(await page.evaluate(() => [...document.querySelectorAll('.zingiber-menu-plate')].every(p =>
        getComputedStyle(p).animationName === 'zingiber-menu-plate' &&
        getComputedStyle(p.querySelector('figure')).animationName === 'zingiber-menu-plate-float'
    )), `${width}: menu plate reveal and gentle float`);
    await page.evaluate(async () => {
        await Promise.all(document.getAnimations().filter(a => a.timeline === document.timeline && a.effect.getTiming().iterations !== Infinity).map(a => a.finished.catch(() => {})));
        await Promise.all([...document.querySelectorAll('.zingiber-menu-plate img')].map(i => i.decode()));
        document.querySelector('.zingiber-menu-plate').scrollIntoView({ behavior: 'instant', block: 'center' });
    });
    await page.screenshot({ path: `/tmp/zingiber-revisions/menu-${width}.png` });
    await page.cdp('Emulation.setEmulatedMedia', { features: [{ name: 'prefers-reduced-motion', value: 'reduce' }] });
    await page.waitForFunction(() => matchMedia('(prefers-reduced-motion: reduce)').matches && [...document.querySelectorAll('.zingiber-menu-plate, .zingiber-menu-plate figure, .zingiber-menu-plate img')].every(p => getComputedStyle(p).animationName === 'none' && getComputedStyle(p).opacity === '1'));
    assert.ok(await page.evaluate(() => [...document.querySelectorAll('.zingiber-menu-plate, .zingiber-menu-plate figure, .zingiber-menu-plate img')].every(p => getComputedStyle(p).animationName === 'none' && getComputedStyle(p).opacity === '1')), `${width}: reduced-motion menu stills`);
    await page.cdp('Emulation.setEmulatedMedia', { features: [{ name: 'prefers-reduced-motion', value: 'no-preference' }] });
    console.log(`PASS menu reveal, float and reduced-motion at ${width}px`);
}
await page.goto('http://127.0.0.1:8080/');
await page.cdp('Emulation.setEmulatedMedia', { features: [{ name: 'prefers-reduced-transparency', value: 'reduce' }] });
await page.waitForFunction(() => getComputedStyle(document.querySelector('.zingiber-header')).backgroundColor === 'rgb(15, 15, 15)');
assert.equal(await page.evaluate(() => getComputedStyle(document.querySelector('.zingiber-header')).backgroundColor), 'rgb(15, 15, 15)', 'Solid masthead fallback');
await page.evaluate(() => scrollTo(0, innerHeight));
await page.waitForFunction(() => document.querySelector('.zingiber-header').classList.contains('is-sticky'));
await page.waitForFunction(() => getComputedStyle(document.querySelector('.zingiber-header')).backgroundColor === 'rgb(237, 231, 225)');
assert.equal(await page.evaluate(() => getComputedStyle(document.querySelector('.zingiber-header')).backgroundColor), 'rgb(237, 231, 225)', 'Solid sticky sand fallback');
console.log('PASS reduced-transparency header contrast');
await task.finish({ keep: [] });
