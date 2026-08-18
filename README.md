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
- Mobile navigation and restrained reveal behaviour: `assets/js/zingiber.js`

The phone number is intentionally left unset. Replace the empty `phone` value and the public `phone_label` in `inc/zingiber/content.php` only after the final business number is confirmed. Add a booking-provider or WhatsApp URL only when approved credentials and destinations are available.

## Validation

Run the standalone project checks from the theme root:

```bash
php tests/validate-zingiber-theme.php
xmllint --noout dummy-data/homepage/home-2.xml
```

For a focused check, use one of the validator groups: `content`, `assets`, `templates`, `brand`, `import`, or `placeholders`.

The official Zingiber Brand Guidelines (d1) specify Estratto Var for headlines and Luxora Grotesk for body, menus, and UI. Those are licensed faces and are not bundled. The theme loads Cormorant Garamond and Manrope as the closest open substitutes (oldstyle serif + minimal grotesk), and will use Estratto Var / Luxora Grotesk automatically if `.woff2` files are added to `assets/fonts/zingiber/`.
