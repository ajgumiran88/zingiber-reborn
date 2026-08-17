# Zingiber Home 2 Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task.

**Goal:** Convert the supplied Vonaco WordPress theme into a self-contained, professional Zingiber restaurant theme whose Home 2 experience and supporting pages use the approved brand, copy, and image library.

**Architecture:** Keep the existing Vonaco compatibility layer intact and add an isolated Zingiber presentation layer: a content source, theme setup module, dedicated header/footer, two page templates, scoped CSS/JavaScript, and packaged media. A deterministic standalone validator will check content coverage, assets, import XML, PHP syntax, brand tokens, and the absence of active demo placeholders.

**Tech Stack:** WordPress PHP, HTML5, CSS custom properties, vanilla JavaScript, WXR/XML import data, PHP CLI validation, Git/GitHub.

## Global Constraints

- Use the existing `/Users/arneljayvgumiran/Downloads/vonaco-full/vonaco` theme as the project root and modify it directly.
- Base the homepage composition on Vonaco Home 2, not another bundled demo.
- Treat the supplied brand guideline PDF, Google Doc copy, and Dropbox image library as the visual and factual authorities.
- Use Deep Charcoal `#0F0F0F`, Burnt Red `#701616`, Terracotta `#9C4722`, Copper `#B87333`, and Sand `#EDE7E1` as the production palette.
- Use a refined display serif plus a clean grotesk body face when the exact licensed Estratto Var and Luxora Grotesk files are unavailable.
- Do not invent a final phone number, menu items, prices, booking-provider credentials, allergens, or operating details.
- Do not leave active Vonaco demo copy, unrelated restaurant names, offers, stock phone numbers, or remote demo media in Zingiber-facing templates and import data.
- Do not add Codex as author, co-author, committer identity, or commit-trailer identity.
- Keep production assets inside the theme and avoid runtime dependence on Vonaco demo image URLs.

---

### Task 1: Add the Theme Validation Contract

**Files:**

- Create: `tests/validate-zingiber-theme.php`
- Create: `tests/fixtures/required-copy.php`

**Interfaces:**

- Consumes: project root path derived from `dirname(__DIR__)`.
- Produces: CLI process exit code `0` on success, non-zero on any failed requirement, with one readable line per failure.
- Produces: reusable validation groups named `content`, `assets`, `templates`, `brand`, `import`, and `placeholders` selected by `--group=<name>` or all groups by default.

- [ ] **Step 1: Write the required-copy fixture**

Create a fixture returning the required routes and phrases:

```php
<?php
return [
    'pages' => ['home', 'about', 'menu', 'gallery', 'careers', 'contact'],
    'phrases' => [
        'Meet the Chef',
        'Chef-Led. Story-Driven. Distinctly Coastal.',
        'A Modern Expression of Coastal India',
        'A Journey Across India’s Coastline',
        'The Zingiber Experience',
        'Join the Zingiber Journey',
        'Connect With Zingiber',
        'reservations@zingiber.ae',
    ],
];
```

- [ ] **Step 2: Write the standalone validator**

Implement assertion helpers that collect failures instead of stopping at the first one. Add checks for:

```php
$requiredFiles = [
    'inc/zingiber/content.php',
    'inc/zingiber/setup.php',
    'header-zingiber.php',
    'footer-zingiber.php',
    'template-zingiber-home.php',
    'template-zingiber-page.php',
    'assets/css/zingiber.css',
    'assets/js/zingiber.js',
    'assets/images/zingiber/zingiber-logo-dark.png',
    'assets/images/zingiber/zingiber-logo-light.png',
    'assets/images/zingiber/zingiber-mark.png',
];
```

The validator must require `inc/zingiber/content.php`, verify every required page and phrase, scan CSS for the five exact palette values, confirm every image referenced by content exists, parse `dummy-data/homepage/home-2.xml` with `DOMDocument`, and scan only Zingiber-facing files for forbidden active strings such as `Experience The Taste Of Italy`, `JOSEFINE HOELLER`, `30% Off`, and `demo2.wpopal.com/vonaco/wp-content/uploads`.

- [ ] **Step 3: Run the validator and confirm the contract fails before implementation**

Run:

```bash
php tests/validate-zingiber-theme.php
```

Expected: non-zero exit with missing Zingiber files and content listed.

- [ ] **Step 4: Commit the validation contract**

```bash
git add tests/validate-zingiber-theme.php tests/fixtures/required-copy.php
git commit -m "test: define Zingiber theme requirements"
```

---

### Task 2: Create the Canonical Zingiber Content Source

**Files:**

- Create: `inc/zingiber/content.php`
- Test: `tests/validate-zingiber-theme.php`

**Interfaces:**

- Produces: `zingiber_get_site_content(): array` when WordPress is loaded.
- Produces: the same array when the file is required by the standalone validator.
- Data shape for each page: `slug`, `title`, `seo_title`, `meta_description`, `template`, `hero`, and `sections`.
- Home-only keys: `principles`, `regions`, `featured_images`, and `calls_to_action`.

- [ ] **Step 1: Run the content validation group**

```bash
php tests/validate-zingiber-theme.php --group=content
```

Expected: failure because `inc/zingiber/content.php` is missing.

- [ ] **Step 2: Implement the content module**

Use a pure data function guarded against redeclaration:

```php
if (!function_exists('zingiber_get_site_content')) {
    function zingiber_get_site_content(): array {
        return [
            'home' => [
                'slug' => 'home',
                'title' => 'Zingiber',
                'seo_title' => 'Zingiber Dubai | Modern Indian Coastal Restaurant in JLT | By Chef Shankar',
                'meta_description' => 'Discover Zingiber, a chef-led modern Indian coastal restaurant in JLT, Dubai. Experience refined cuisine inspired by Goa, Kerala, Mangalore & Tamil Nadu.',
                'template' => 'template-zingiber-home.php',
                'hero' => [
                    'eyebrow' => 'Modern Indian Coastal Dining',
                    'heading' => 'Meet the Chef',
                    'subheading' => 'A modern coastal Indian dining experience, led by culinary mastery and shaped by storytelling.',
                ],
            ],
        ];
    }
}

return zingiber_get_site_content();
```

Expand the array with the complete approved copy for Home, About, Menu, Gallery, Careers, and Contact. Preserve the supplied British English spelling. Represent the phone as an empty value with a public label `Phone details coming soon`; do not retain `+971 XX XXX XXXX` as a clickable number.

- [ ] **Step 3: Run content validation**

```bash
php tests/validate-zingiber-theme.php --group=content
```

Expected: PASS for routes, required phrases, contact email, and absence of unsupported fabricated values.

- [ ] **Step 4: Commit the content source**

```bash
git add inc/zingiber/content.php
git commit -m "feat: add approved Zingiber website content"
```

---

### Task 3: Package and Validate Brand Media

**Files:**

- Create: `assets/images/zingiber/zingiber-logo-dark.png`
- Create: `assets/images/zingiber/zingiber-logo-light.png`
- Create: `assets/images/zingiber/zingiber-mark.png`
- Create: `assets/images/zingiber/interior-hero.jpg`
- Create: `assets/images/zingiber/interior-dining-room.jpg`
- Create: `assets/images/zingiber/interior-bar.jpg`
- Create: `assets/images/zingiber/interior-feature-wall.jpg`
- Create: `assets/images/zingiber/food-prawn-curry.jpg`
- Create: `assets/images/zingiber/food-grilled-lamb.jpg`
- Create: `assets/images/zingiber/food-coastal-thali.jpg`
- Create: `assets/images/zingiber/food-seafood-rice.jpg`
- Create: `assets/images/zingiber/food-dessert.jpg`
- Create: `assets/images/zingiber/food-signature-plate.jpg`
- Modify: `inc/zingiber/content.php`
- Test: `tests/validate-zingiber-theme.php`

**Interfaces:**

- Consumes: `/tmp/zingiber-brand-guidelines.pdf` and `/tmp/zingiber-image-library/` prepared from the user-supplied sources.
- Produces: optimized JPEG production images at a maximum long edge of 2400 pixels and quality near 82, preserving aspect ratio.
- Produces: PNG logo variants with transparent or brand-approved flat backgrounds and no distortion.
- Content image references are relative to `assets/images/zingiber/`.

- [ ] **Step 1: Run the asset validation group**

```bash
php tests/validate-zingiber-theme.php --group=assets
```

Expected: failure listing missing production media.

- [ ] **Step 2: Extract the approved logo artwork**

Render the brand-guideline logo pages at high resolution, crop only the approved combined logo and symbol variants, remove surrounding guide labels, and save dark/light raster variants. Confirm the logo remains level, undistorted, and padded according to the clear-space example.

- [ ] **Step 3: Optimize and rename selected source images**

Select distinct interior and food images from the supplied library that match the role in each template. Convert PNG food images to high-quality JPEG only where transparency is not present or required. Preserve source masters in the downloaded source location, not in the production theme directory.

- [ ] **Step 4: Wire media into the content source**

Use records such as:

```php
'image' => [
    'src' => 'assets/images/zingiber/interior-hero.jpg',
    'alt' => 'Warm terracotta interior of Zingiber restaurant in Dubai',
],
```

Every production image must have concise, meaningful alt text. Assign a distinct primary image to each major section where the library supports it.

- [ ] **Step 5: Run asset and content validation**

```bash
php tests/validate-zingiber-theme.php --group=assets
php tests/validate-zingiber-theme.php --group=content
```

Expected: PASS, with all files present, readable by `getimagesize()`, within the maximum dimensions, and referenced paths resolvable.

- [ ] **Step 6: Commit the media package**

```bash
git add assets/images/zingiber inc/zingiber/content.php
git commit -m "feat: package Zingiber brand and restaurant media"
```

---

### Task 4: Add Zingiber Theme Bootstrap and Page Setup

**Files:**

- Create: `inc/zingiber/setup.php`
- Modify: `functions.php`
- Modify: `style.css:1-20`
- Test: `tests/validate-zingiber-theme.php`

**Interfaces:**

- Consumes: `zingiber_get_site_content()` from `inc/zingiber/content.php`.
- Produces: `zingiber_theme_asset_url(string $path): string`.
- Produces: `zingiber_current_page_content(): array`.
- Produces: `zingiber_install_site_pages(): void` on `after_switch_theme`.
- Produces: enqueued handles `zingiber-fonts`, `zingiber-site`, and `zingiber-site` script.

- [ ] **Step 1: Run template and brand validation groups**

```bash
php tests/validate-zingiber-theme.php --group=templates
php tests/validate-zingiber-theme.php --group=brand
```

Expected: failure because setup and production CSS do not exist.

- [ ] **Step 2: Add the setup module**

Require the content source, register `after_switch_theme`, enqueue CSS/JS only on Zingiber page templates, and add body classes `zingiber-site` plus `zingiber-page-<slug>`.

`zingiber_install_site_pages()` must:

- Find existing pages by slug before creating anything.
- Create only missing pages.
- Apply the page template stored in content data.
- Set `show_on_front=page` and `page_on_front` to the Home page.
- Create or reuse a `Zingiber Primary` menu and add Home, About, Menu, Gallery, Careers, and Contact in that order without duplicate items.
- Assign the menu to the first registered theme menu location when no menu is currently assigned there.

- [ ] **Step 3: Load setup from functions.php**

Append one isolated require after the theme's compatibility requires:

```php
require_once get_theme_file_path('inc/zingiber/setup.php');
```

- [ ] **Step 4: Update theme metadata without changing the text domain**

Set `Theme Name: Zingiber Restaurant`, update the description for the Zingiber project, keep `Text Domain: vonaco`, keep the GPL information, and preserve version compatibility.

- [ ] **Step 5: Run PHP syntax and static validation**

```bash
php -l functions.php
php -l inc/zingiber/content.php
php -l inc/zingiber/setup.php
php tests/validate-zingiber-theme.php --group=templates
```

Expected: all PHP syntax checks PASS; template group may continue to report only the templates that belong to later tasks.

- [ ] **Step 6: Commit the bootstrap**

```bash
git add functions.php style.css inc/zingiber/setup.php
git commit -m "feat: bootstrap the Zingiber WordPress experience"
```

---

### Task 5: Build the Shared Zingiber Header and Footer

**Files:**

- Create: `header-zingiber.php`
- Create: `footer-zingiber.php`
- Create: `assets/css/zingiber.css`
- Create: `assets/js/zingiber.js`
- Test: `tests/validate-zingiber-theme.php`

**Interfaces:**

- Consumes: WordPress navigation and `zingiber_theme_asset_url()`.
- Produces: `.zingiber-header`, `.zingiber-primary-nav`, `.zingiber-mobile-toggle`, `.zingiber-footer`, and `.zingiber-reservation-cta` markup contracts used by both templates.
- JavaScript consumes `[data-zingiber-menu-toggle]` and `[data-zingiber-menu]` and controls `aria-expanded`, the `is-open` class, Escape-key dismissal, and focus restoration.

- [ ] **Step 1: Extend validation expectations for shared chrome**

Require one `<header>`, one `<nav aria-label="Primary navigation">`, a keyboard-operable menu button, one `<footer>`, and no inline hard-coded logo URLs.

- [ ] **Step 2: Run template validation**

```bash
php tests/validate-zingiber-theme.php --group=templates
```

Expected: failure listing missing shared chrome.

- [ ] **Step 3: Implement header-zingiber.php**

Include standard WordPress document setup, `wp_head()`, `wp_body_open()`, a skip link, the light/dark logo appropriate to the header surface, the primary menu, a visible `Reserve a Table` link to Contact, and the accessible mobile-menu control.

- [ ] **Step 4: Implement footer-zingiber.php**

Render the logo, short brand statement, core navigation, confirmed address, reservations email, opening-hours wording, and supplied social handles. Render `wp_footer()` immediately before closing `body`.

- [ ] **Step 5: Add base brand CSS and menu behavior**

Define the exact palette as custom properties:

```css
:root {
  --zingiber-charcoal: #0F0F0F;
  --zingiber-burnt-red: #701616;
  --zingiber-terracotta: #9C4722;
  --zingiber-copper: #B87333;
  --zingiber-sand: #EDE7E1;
}
```

Add reset-safe scoped typography, header/footer layout, buttons, focus styles, reduced-motion behavior, and mobile navigation. Use no generic card system.

- [ ] **Step 6: Run validation and PHP syntax checks**

```bash
php -l header-zingiber.php
php -l footer-zingiber.php
php tests/validate-zingiber-theme.php --group=brand
php tests/validate-zingiber-theme.php --group=templates
```

Expected: PASS for shared chrome and brand tokens.

- [ ] **Step 7: Commit the shared presentation layer**

```bash
git add header-zingiber.php footer-zingiber.php assets/css/zingiber.css assets/js/zingiber.js tests/validate-zingiber-theme.php
git commit -m "feat: add Zingiber navigation and footer"
```

---

### Task 6: Implement the Zingiber Home 2 Template

**Files:**

- Create: `template-zingiber-home.php`
- Modify: `assets/css/zingiber.css`
- Modify: `assets/js/zingiber.js`
- Test: `tests/validate-zingiber-theme.php`

**Interfaces:**

- Consumes: `zingiber_get_site_content()['home']` and shared header/footer contracts.
- Produces: section IDs `hero`, `chef`, `principles`, `coastal-regions`, `menu`, `experience`, `gallery`, and `reservations`.
- Produces: `[data-zingiber-reveal]` progressive-enhancement hooks; content remains visible when JavaScript is disabled.

- [ ] **Step 1: Add Home 2 structural assertions**

The validator must require all eight section IDs, exactly one H1, Menu and Contact calls to action, all four principles, and all four regional labels.

- [ ] **Step 2: Run Home template validation**

```bash
php tests/validate-zingiber-theme.php --group=templates
```

Expected: failure because `template-zingiber-home.php` is missing.

- [ ] **Step 3: Build the semantic Home 2 composition**

Use `get_header('zingiber')` and `get_footer('zingiber')`. Render all copy from the content source, escape URLs/text appropriately, and use figure/figcaption only where captions add meaning.

Keep the design rhythm:

- Full-bleed atmospheric hero with restrained overlay.
- Asymmetric chef story with large editorial typography.
- One horizontal principle band rather than four boxed cards.
- Alternating region narrative and food imagery.
- Two-column signature menu story using distinct food images.
- Dark immersive restaurant-experience section.
- Editorial gallery grid with varied aspect ratios.
- Sand reservation/contact close with one strong action.

- [ ] **Step 4: Style desktop and responsive compositions**

Use CSS Grid/Flexbox with `clamp()` for type and spacing. At widths below 900px, collapse asymmetric sections to one column without changing reading order. At widths below 640px, simplify the gallery and keep every tap target at least 44px high.

- [ ] **Step 5: Add restrained progressive enhancement**

Use `IntersectionObserver` to add `is-visible` to reveal hooks. Skip observers when reduced motion is requested and leave content visible if APIs are unavailable.

- [ ] **Step 6: Run Home validation and syntax checks**

```bash
php -l template-zingiber-home.php
php tests/validate-zingiber-theme.php --group=templates
php tests/validate-zingiber-theme.php --group=placeholders
```

Expected: PASS for Home structure and no active demo copy/URLs.

- [ ] **Step 7: Commit Home 2**

```bash
git add template-zingiber-home.php assets/css/zingiber.css assets/js/zingiber.js tests/validate-zingiber-theme.php
git commit -m "feat: transform Home 2 for Zingiber"
```

---

### Task 7: Implement Supporting Page Templates and SEO Metadata

**Files:**

- Create: `template-zingiber-page.php`
- Modify: `inc/zingiber/setup.php`
- Modify: `assets/css/zingiber.css`
- Test: `tests/validate-zingiber-theme.php`

**Interfaces:**

- Consumes: current page slug and matching record from `zingiber_get_site_content()`.
- Produces: page-appropriate hero and body layouts for About, Menu, Gallery, Careers, and Contact.
- Produces: `zingiber_filter_document_title()` and `zingiber_output_meta_description()` for Zingiber templates only.

- [ ] **Step 1: Add supporting-page assertions**

Require the dynamic template to handle every non-home slug, output a single H1, and provide page-specific semantic structures: principles on About, visual menu introduction on Menu, image grid on Gallery, mail action on Careers, and address/email/hours/socials on Contact.

- [ ] **Step 2: Run supporting-page validation**

```bash
php tests/validate-zingiber-theme.php --group=templates
```

Expected: failure because `template-zingiber-page.php` is missing.

- [ ] **Step 3: Implement template-zingiber-page.php**

Use a shared page-hero structure, then select page-specific section partial logic based on the content record. Do not invent menu details or job vacancies. Career and reservation actions must use the confirmed reservations email unless a distinct supplied address exists.

- [ ] **Step 4: Add page metadata hooks**

For Zingiber templates only, filter the document title from `seo_title` and render one escaped `<meta name="description">` from `meta_description`. Do not duplicate a description if a known SEO plugin has already emitted one.

- [ ] **Step 5: Run syntax, content, and template validation**

```bash
php -l template-zingiber-page.php
php -l inc/zingiber/setup.php
php tests/validate-zingiber-theme.php --group=content
php tests/validate-zingiber-theme.php --group=templates
```

Expected: PASS for all routes and SEO fields.

- [ ] **Step 6: Commit supporting pages**

```bash
git add template-zingiber-page.php inc/zingiber/setup.php assets/css/zingiber.css tests/validate-zingiber-theme.php
git commit -m "feat: add Zingiber supporting pages and SEO"
```

---

### Task 8: Replace the Home 2 Import Payload

**Files:**

- Modify: `dummy-data/homepage/home-2.xml`
- Test: `tests/validate-zingiber-theme.php`

**Interfaces:**

- Consumes: the WordPress WXR importer used by Vonaco's existing setup wizard.
- Produces: a valid WXR document that creates or updates the Zingiber Home page and sets `_wp_page_template` to `template-zingiber-home.php`.
- Does not produce remote media attachment dependencies.

- [ ] **Step 1: Run import validation**

```bash
php tests/validate-zingiber-theme.php --group=import
```

Expected: failure because Home 2 still contains Vonaco Elementor data and remote demo media.

- [ ] **Step 2: Replace the WXR payload**

Retain WordPress export namespaces and create a minimal published page item with slug `home`, title `Zingiber`, and this metadata:

```xml
<wp:postmeta>
  <wp:meta_key><![CDATA[_wp_page_template]]></wp:meta_key>
  <wp:meta_value><![CDATA[template-zingiber-home.php]]></wp:meta_value>
</wp:postmeta>
```

Do not include `_elementor_data`, Vonaco copy, demo attachment items, or remote `demo2.wpopal.com` media URLs.

- [ ] **Step 3: Validate XML and import contract**

```bash
xmllint --noout dummy-data/homepage/home-2.xml
php tests/validate-zingiber-theme.php --group=import
php tests/validate-zingiber-theme.php --group=placeholders
```

Expected: all checks PASS.

- [ ] **Step 4: Commit the import payload**

```bash
git add dummy-data/homepage/home-2.xml tests/validate-zingiber-theme.php
git commit -m "feat: replace Home 2 demo import with Zingiber"
```

---

### Task 9: Final Quality, Accessibility, and Repository Verification

**Files:**

- Modify as required by verification: `assets/css/zingiber.css`
- Modify as required by verification: `assets/js/zingiber.js`
- Modify as required by verification: Zingiber PHP templates and setup files
- Create: `README.md`
- Test: all changed PHP/XML/content/assets

**Interfaces:**

- Produces: an installable theme repository on `main` with documented setup and deterministic validation instructions.
- Produces: Git commits authored and committed by `AJGUMIRAN <46704962+ajgumiran88@users.noreply.github.com>`.

- [ ] **Step 1: Add concise project documentation**

Document theme installation, activation, automatic page creation, Home 2 import selection, where to replace phone/booking details, asset locations, and the validation command. Do not reproduce ThemeForest license keys or private source links.

- [ ] **Step 2: Run complete static verification**

```bash
find . -name '*.php' -not -path './inc/merlin/vendor/*' -print0 | xargs -0 -n1 php -l
php tests/validate-zingiber-theme.php
xmllint --noout dummy-data/homepage/home-2.xml
```

Expected: no PHP syntax errors, validator PASS for every group, XML valid.

- [ ] **Step 3: Scan active Zingiber surfaces for placeholders and demo dependencies**

```bash
rg -n "Experience The Taste Of Italy|JOSEFINE HOELLER|30% Off|Lorem ipsum|demo2\.wpopal\.com/vonaco/wp-content/uploads|\+84 \(800\)" header-zingiber.php footer-zingiber.php template-zingiber-home.php template-zingiber-page.php inc/zingiber assets/css/zingiber.css assets/js/zingiber.js dummy-data/homepage/home-2.xml
```

Expected: no matches.

- [ ] **Step 4: Inspect repository size and large files**

```bash
find . -type f -size +90M -print
du -sh .
```

Expected: no file approaches GitHub's 100 MB limit; repository remains practical for clone and deployment.

- [ ] **Step 5: Verify Git identity and history**

```bash
git var GIT_AUTHOR_IDENT
git log --format='%h %an <%ae> %s'
rg -n -i "codex|co-authored-by" .git/COMMIT_EDITMSG docs README.md
```

Expected: author identity is AJGUMIRAN, and no Codex author/co-author trailer exists.

- [ ] **Step 6: Commit final verification and documentation changes**

```bash
git add README.md assets/css/zingiber.css assets/js/zingiber.js header-zingiber.php footer-zingiber.php template-zingiber-home.php template-zingiber-page.php inc/zingiber tests dummy-data/homepage/home-2.xml style.css functions.php
git commit -m "docs: finalize the Zingiber restaurant theme"
```

- [ ] **Step 7: Push the completed main branch**

```bash
git push -u origin main
```

Expected: push succeeds to `https://github.com/ajgumiran88/zingiber-reborn.git`.

- [ ] **Step 8: Verify the remote branch head**

```bash
git ls-remote origin refs/heads/main
git rev-parse HEAD
```

Expected: both commands report the same commit SHA.
