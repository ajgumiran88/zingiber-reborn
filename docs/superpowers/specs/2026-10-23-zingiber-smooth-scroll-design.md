# Zingiber smooth scroll + reveal motion

Date: 2026-10-23  
Status: approved in chat; awaiting spec skim before implementation plan

## Goal

Match the Ballena Cabo scroll feel on Zingiber pages: soft wheel inertia plus calmer section reveals. Reference: https://ballenacabo.com/

## Scope

- In: `body.zingiber-site` only (Zingiber templates / enquiry / footer).
- In: Lenis smooth scroll + stronger `[data-zingiber-reveal]` CSS/IO timing.
- Out: GSAP, ScrollSmoother, SplitText, pinned parallax, Elementor global theme.

## Approach

Vendor Lenis (small, standard for this feel) + reuse existing IntersectionObserver reveal pipeline in `assets/js/zingiber.js`.

### A. Smooth scroll (Lenis)

- Load Lenis only when `prefers-reduced-motion: no-preference`.
- Init on `document.documentElement` / Zingiber body; RAF loop; destroy on teardown if needed.
- Keep native `scrollTo` / hash / sticky header listeners working (Lenis proxies scroll or we sync `scroll` events).
- Skip Lenis when reduced motion; leave `scroll-behavior: smooth` for anchors as today.

### B. Reveals

- Keep `[data-zingiber-reveal]` + `.zingiber-enhanced-motion` + IO (`is-visible`, unobserve once).
- Tune CSS: slightly larger `translateY` (~1.1–1.35rem), longer lux duration (~700–900ms) / softer ease.
- Slightly wider stagger on lists (footer grid, gallery, principles) — still under ~0.35s total lag.
- No-JS / reduced-motion: content stays visible (existing reduce rules remain).

## Files (expected)

- `assets/js/` — Lenis vendor or thin wrapper + wire in `zingiber.js`
- `inc/zingiber/setup.php` — enqueue Lenis only for Zingiber
- `assets/css/zingiber.css` — reveal timing tokens / rules
- Optional: Lenis CSS if required by chosen build

## Acceptance

1. Wheel/trackpad scroll feels inertia-smoothed on Zingiber home/page (desktop).
2. Sections still fade/rise on enter; motion longer/softer than current.
3. `prefers-reduced-motion: reduce` disables Lenis and reveal transforms.
4. Sticky header, menu dialog, gallery lightbox, enquiry form still work.
5. No new motion on non-Zingiber Vonaco pages.

## Non-goals (add later if asked)

- Scroll-scrubbed timelines, horizontal pin sections, character split headlines.
