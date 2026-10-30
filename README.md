# Zingiber Restaurant WordPress Theme

Zingiber is a custom restaurant experience built directly on the licensed Vonaco WordPress theme. The site uses the supplied Zingiber brand system, approved website copy, and packaged restaurant imagery while adapting the editorial rhythm of Vonaco Home 2.

## Install and activate

1. Copy this theme folder into `wp-content/themes/` or upload it as a theme archive in WordPress.
2. Activate **Zingiber Restaurant** in Appearance > Themes.
3. On activation, the theme creates any missing Home, About, Menu, Gallery, Careers, and Contact pages, assigns the appropriate Zingiber page templates, creates the primary navigation, and sets Home as the static front page.

The activation routine reuses matching pages and menu items instead of creating duplicates.

## Home 2 import

The bundled `dummy-data/homepage/home-2.xml` file contains a minimal, self-contained WordPress export for the Zingiber Home page. It assigns `template-zingiber-home.php` and has no Elementor or remote Vonaco media dependency.

## Content and media

- Canonical page copy and contact details: `inc/zingiber/content.php`
- Zingiber setup, page creation, assets, and SEO hooks: `inc/zingiber/setup.php`
- Brand and restaurant media: `assets/images/zingiber/`
- Zingiber presentation rules: `assets/css/zingiber.css`
- Full-screen navigation, native coastal slider and restrained reveal behaviour: `assets/js/zingiber.js`

Home follows the approved PDF structure: poster hero, story split, coastal slider, principles cards, and a four-image Gallery teaser. The shared footer includes an enquiry form, root-decorated contact strip, and a Ballena-inspired image-and-story split beside navigation and visit details, followed by an oversized Zingiber wordmark. Experience and Reservation copy appears on Contact. Set `home.hero.video` in `inc/zingiber/content.php` to the approved MP4 media URL when available. Until then, the interior poster displays without a video request. Failed playback and reduced motion also retain the poster.

The phone number is intentionally left unset; the requested +971 50 123 4567 is displayed as a non-dialable placeholder. Replace the empty `phone` value and the public `phone_label` in `inc/zingiber/content.php` only after the final business number is confirmed. Add a booking-provider or WhatsApp URL only when approved credentials and destinations are available.

## Validation

Run the standalone project checks from the theme root:

```bash
php tests/validate-zingiber-theme.php
xmllint --noout dummy-data/homepage/home-2.xml
```

With local WordPress serving this theme at `http://127.0.0.1:8080/`, run `ego-browser nodejs < tests/check-zingiber-browser.mjs` for responsive and interaction checks. Screenshots are saved under `/tmp/zingiber-pdf-structure/`.

For a focused check, use one of the validator groups: `content`, `assets`, `templates`, `brand`, `import`, or `placeholders`.

The official Zingiber Brand Guidelines (d1) specify Estratto Var for headlines and Luxora Grotesk for body, menus, and UI. Those are licensed faces and are not bundled. The theme loads Cormorant Garamond and Manrope as the closest open substitutes (oldstyle serif + minimal grotesk), and will use Estratto Var / Luxora Grotesk automatically if `.woff2` files are added to `assets/fonts/zingiber/`.

## Enquiry form

The shared form posts to Contact and uses WordPress `wp_mail` to send to the address in `inc/zingiber/content.php`. `inc/zingiber/enquiry.php` validates the nonce and fields, rejects the honeypot, and limits submissions to one attempt per connection address per minute. Errors preserve the draft; success redirects to prevent refresh resubmission. A successful submission is not a confirmed reservation. Configure and verify the host's mail transport before publishing; the form does not store enquiries in WordPress. Keep nonce-bearing pages out of long-lived full-page caches.

Run `php tests/check-zingiber-enquiry.php /path/to/local/wordpress/wp-load.php` against a local WordPress instance. It intercepts all mail, checks validation and failure paths, and cleans up its synthetic rate-limit entry. Run `ego-browser nodejs < tests/check-zingiber-footer.mjs` for footer/form checks at four widths and server error feedback without sending mail. Screenshots go to `/tmp/zingiber-footer/`.

## October 2026 revision pass

The supplied English logo is retained as vector artwork in `assets/svg/zingiber/english-logo.svg`; the light treatment and footer wordmark use the same source paths. Fine animated roots replace the ginger illustrations. Heading sizes, section spacing, glass navigation, menu transitions, visible principles and footer follow `Zingiber Website Revisions 30 oct 2026.pdf`.

The EN / العربية switch keeps the current page. Arabic uses `?lang=ar`, local draft copy in `inc/zingiber/content-ar.php` and `inc/zingiber/ui-ar.php`, RTL layout, and language-preserving links and enquiry redirects. Have an Arabic-speaking editor approve the draft before publication. Any page cache must include the `lang` query parameter in its key. Contact numbers and email stay unchanged. The video remains pending the client's asset; configure `home.hero.video` when supplied.

Additional checks: `php tests/check-zingiber-language.php` and `ego-browser nodejs < tests/check-zingiber-revisions.mjs`. Browser checks cover the revision requirements at 375, 768, 1024 and 1440 pixels, Arabic pages, language navigation and reduced motion. Screenshots go to `/tmp/zingiber-revisions/`.

The menu circle uses the supplied sketch-ring reference with a centered, translated label. Lofty Lab-inspired motion reuses the existing observer and Lenis: staggered lift/scale entrances, image entrances, and native scroll-linked image drift where supported. Reduced motion keeps all content visible and disables decorative movement. Run `ego-browser nodejs < tests/check-zingiber-motion.mjs` to check all six pages at 375/1440px; screenshots go to `/tmp/zingiber-motion/`.

Menu follows the JF Vegan Café reference with a centered title, a full-section sticky pill rail, horizontal rail arrows, shaped 3/2/1-column food cards, slide/rotation reveals, and a mobile Book Now dock. It retains Zingiber regions, images, copy and Contact booking route. Run `ego-browser nodejs < tests/check-zingiber-menu.mjs`; screenshots go to `/tmp/zingiber-menu/`.

All six pages use shared fluid gutters without a desktop content-width cap, including the enquiry and footer. The centered Ballena-inspired header retains its compact 56rem maximum width. The menu introduction leads equal square image frames; the final region is centered on desktop. The glass masthead stays above the sticky category bar, with solid fallbacks for reduced transparency. Menu checks also cover all page widths through 1920px, Arabic links, and sticky anchor selection.

About uses a bright split hero, an editorial story grid, rectangular food and interior photography, and a burgundy principles section. Its styles stay scoped to About; approved English and Arabic copy, page gutters, compact header and shared enquiry remain intact. Run `ego-browser nodejs < tests/check-zingiber-about.mjs` for responsive, image, RTL, keyboard and motion checks; screenshots go to `/tmp/zingiber-about/`.
