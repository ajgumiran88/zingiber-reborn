# PDF structure implementation

Spec: `docs/superpowers/specs/2026-10-22-zingiber-pdf-structure-design.md`.

## Global constraints

Implement the user's approved pattern port inline. Preserve unrelated `.cursor/` work. Use the feature branch and existing local WordPress theme symlink so the user can review the result. Do not commit, push or deploy. Keep approved data and supporting-page content. Use native interactions and existing helpers.

## Task 1: Shared header and overlay

Interfaces: header markup produces dialog/toggle hooks consumed by existing JavaScript and shared CSS.

1. Extend template validation for Book Now, centered logo and accessible overlay. Run `php tests/validate-zingiber-theme.php --group=templates`. Expected: missing overlay contract failures.
2. Implement header, native dialog interactions, focus restoration, scroll lock and reduced-motion transitions.
3. Run `php tests/validate-zingiber-theme.php`. Expected: all groups pass.

## Task 2: Poster-first video-ready hero

Interfaces: Home hero consumes current hero image; JavaScript enhances only a real video source.

1. Replace obsolete hero assertions with poster/video/circular CTA requirements. Run template group. Expected: missing video and circular CTA failures.
2. Implement hero and conditional playback; preserve poster on errors and reduced motion.
3. Run `php tests/validate-zingiber-theme.php`. Expected: all groups pass.

## Task 3: Story split

Interfaces: story consumes approved chef story and produces the Menu destination.

1. Add story split/Menu CTA assertion; run template group. Expected: missing split contract.
2. Replace story markup/styles with equal image and colored copy panels.
3. Run `php tests/validate-zingiber-theme.php`. Expected: all groups pass.

## Task 4: Coastal slider

Interfaces: regions collection produces scroll-snap slides; controls, index and progress consume the active slide.

1. Add slider markup contract and run template group. Expected: missing slider controls.
2. Implement native snap layout, bounded buttons, keyboard support and scroll-driven status; place before principles.
3. Run `php tests/validate-zingiber-theme.php`. Expected: all groups pass.

## Task 5: Principles image cards

Interfaces: principles and existing imagery produce About-linked cards; CSS provides native scrolling.

1. Add image-card strip contract; run template group. Expected: missing image strip.
2. Implement four cards with titles, short copy and Explore; preserve About-page principle layout.
3. Run `php tests/validate-zingiber-theme.php`. Expected: all groups pass.

## Task 6: Gallery teaser and root footer

Interfaces: footer consumes contact and absorbed Home copy; Gallery page retains its complete collection.

1. Update old section and mosaic assertions for the final order, four-tile teaser and footer contracts. Run validator. Expected: removed sections/old footer failures.
2. Remove obsolete Home sections, add teaser and root footer, and retire obsolete Home CSS. Keep contact placeholders explicit and reuse Contact.
3. Run `php tests/validate-zingiber-theme.php`. Expected: all groups pass.

## Task 7: Responsive and interaction verification

Interfaces: live WordPress renders all templates with shared assets.

1. Run PHP validation, changed-file PHP lint, JavaScript syntax checks and XML validation. Expected: exit 0.
2. Run retained browser assertions and inspect screenshots at 375/768/1024/1440. Cover overlay keyboard/scroll, slider buttons/swipe, video fallback, Contact and Gallery regression. Expected: no overflow, missing images or failed assertions.
3. Dispatch one fresh-context review of the complete working diff. Fix important findings with focused regression proof, record decisions, rerun required checks. Expected: no unresolved important findings.

## Review focus

Modal focus/inertness and scroll restoration across supporting pages; no empty video request; source/error/reduced-motion behavior; slider status after manual scrolling, resize and keyboard input; nested horizontal overflow and keyboard access; shared About/Gallery CSS; placeholder contact values; preservation of approved copy; valid ginger-root decoration (the October 23 user reference includes botanical foliage).

## Task 8: Reference footer and enquiry refinement

The user's October 22 reference screenshots supersede the first footer layout.

1. Add real submission and browser checks. Expected RED: missing enquiry handler and no form.
2. Add one shared split enquiry panel with labelled Name, Email and Comments / Event details fields, circular submit, existing food imagery and stone texture. Use WordPress nonce, validation, honeypot, rate limit and wp_mail; retain drafts on errors and redirect after success.
3. Make the root-decorated contact strip compact, followed by visit/hours/navigation/social columns and oversized logo. Move the approved Experience and Reservation paragraphs to Contact so the footer stays compact.
4. Run the PHP validator, enquiry checks with mail intercepted, and browser checks at 375/768/1024/1440. Inspect screenshots and review the working changes. No commit or deployment.

## Task 9: Botanical ginger and entrance animations

1. Derive a transparent photorealistic ginger cluster from the user's latest reference with built-in Imagegen; copy the asset into the theme.
2. Replace the outlined decoration, keep contact links unobstructed, and reuse existing reveal hooks with staggered footer columns.
3. Verify responsive rendering, reduced-motion visibility and animation hooks; rerun existing browser and PHP checks.
