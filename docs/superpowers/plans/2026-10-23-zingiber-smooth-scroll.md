# Zingiber Smooth Scroll Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Ballena-like wheel inertia (Lenis) plus softer section reveals on Zingiber pages only.

**Architecture:** Vendor Lenis under `assets/js/vendor/`, enqueue as dependency of `zingiber-site`, init in `zingiber.js` when motion is allowed. Tune existing `[data-zingiber-reveal]` CSS tokens; keep IntersectionObserver.

**Tech Stack:** WordPress theme enqueue, Lenis 1.x, CSS transitions, IntersectionObserver.

## Global Constraints

- Zingiber templates only (`zingiber_is_site_template` / `body.zingiber-site`).
- `prefers-reduced-motion: reduce` disables Lenis and reveal transforms.
- No GSAP / ScrollSmoother / SplitText / parallax pins.
- Sticky header, menu dialog, gallery, enquiry must keep working.

---

### Task 1: Vendor Lenis + enqueue

**Files:**
- Create: `assets/js/vendor/lenis.min.js`
- Modify: `inc/zingiber/setup.php` (enqueue)

- [x] Add Lenis min bundle under `assets/js/vendor/`
- [x] `wp_enqueue_script('zingiber-lenis', …)` then `zingiber-site` depends on it
- [ ] Smoke: view source on Zingiber page shows both scripts

### Task 2: Init Lenis in zingiber.js

**Files:**
- Modify: `assets/js/zingiber.js`

- [x] If `!reducedMotion` and `window.Lenis`, construct Lenis + RAF
- [x] Soft defaults (`lerp: 0.09`, `autoRaf`)
- [x] No init when reduced motion; stop on menu/lightbox

### Task 3: Soften reveal CSS

**Files:**
- Modify: `assets/css/zingiber.css`

- [x] Bump default reveal `translateY` to ~1.25rem
- [x] Soften lux ease; duration 880ms
- [x] Slightly widen list stagger delays

### Task 4: Verify

- [ ] Desktop: inertia scroll + reveals on home
- [ ] Reduced motion: native scroll, no fade-in hide
- [ ] Menu open / sticky header / enquiry still OK
