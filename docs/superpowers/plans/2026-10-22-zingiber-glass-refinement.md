# PDF glass and motion refinement

Spec: `docs/superpowers/specs/2026-10-22-zingiber-pdf-structure-design.md`, amended by the user's request for glassmorphism and animation.

## Global constraints

Retain the approved PDF structure, brand, copy and current dirty feature branch. Implement inline with CSS and existing reveal hooks. No dependencies, commits or deployment. Dark translucent surfaces must retain readable contrast; provide opaque fallbacks and respect reduced motion and reduced transparency.

## Task 1: Refine surfaces and motion

Interfaces: shared header/dialog and Home image captions consume CSS; existing reveal observer consumes Home data attributes.

1. Inspect PDF pages and existing styles. Record the absent initial header/caption blur as the baseline.
2. Add frosted header, translucent navigation, circular CTA, coastal captions and principle panels. Add staggered navigation/reveals and restrained image/arrow feedback using CSS.
3. Run `php tests/validate-zingiber-theme.php` and `ego-browser nodejs < tests/check-zingiber-browser.mjs`. Expected: all checks pass at 375, 768, 1024 and 1440px, including keyboard and reduced-motion behavior. Inspect desktop/mobile screenshots and verify reduced-transparency styles.
4. Obtain one fresh-context read-only review. Resolve significant issues, preserving unrelated work.

## Review focus

Readable glass over bright photography, no caption clipping, reduced-motion and reduced-transparency fallbacks, keyboard navigation, and shared-header regressions.
