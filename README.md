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

Home follows the approved PDF structure: poster hero, story split, coastal slider, principles cards, and a four-image Gallery teaser. The shared footer includes an enquiry form, root-decorated contact strip, visit details and oversized logo. Experience and Reservation copy appears on Contact. Set `home.hero.video` in `inc/zingiber/content.php` to the approved MP4 media URL when available. Until then, the interior poster displays without a video request. Failed playback and reduced motion also retain the poster.

The phone number is intentionally left unset. Replace the empty `phone` value and the public `phone_label` in `inc/zingiber/content.php` only after the final business number is confirmed. Add a booking-provider or WhatsApp URL only when approved credentials and destinations are available.

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

The botanical footer asset `assets/images/zingiber/ginger-cluster.png` was created with the built-in Imagegen tool from the supplied ginger reference. Prompt: “Extract and reconstruct only the botanical ginger-root cluster from the supplied website screenshot as a standalone premium photorealistic PNG cutout on a fully transparent background. Match the reference's warm tan knobbly ginger rhizomes, one fresh pale-yellow cut end, elegant green lance-shaped ginger leaves and a few small cream/pale pink ginger flower buds. Compact diagonal botanical composition, lower left to upper right, softly lit detailed natural texture, clean edges. Entire cluster visible with slight transparent margin. Remove all webpage/browser UI, text, wood, black background, borders, and navigation. No text, no logo, no frame, no opaque background or baked checkerboard. This is a decorative asset to overlay on a restaurant footer, approximately 3:2 landscape composition.”
