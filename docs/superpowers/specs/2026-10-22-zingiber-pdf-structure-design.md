# Zingiber PDF structure design

Approved for implementation by the user's “pls implement now” instruction on 2026-10-22.

## Authority and direction

Use Celina's seven-page **Design Direction for Zingiber.pdf**, supplied in Downloads, and the approved structure plan in the conversation. Port Ballena's header, overlay, story split and image cards, and JF Vegan Cafe's hero, coastal slider and footer rhythm. Preserve Zingiber imagery, approved copy, palette and type stacks. Desktop composition may change.

## Structure and interactions

1. Shared header: Book Now links to Contact, centered logo, hamburger at every width. A full-screen navigation overlay contains Home, About, Menu, Gallery, Careers, Contact and Instagram. Support close button, Escape, focus containment, focus restoration and background scroll locking.
2. Home hero: current interior poster, one approved headline and circular SEE MENU link. Include muted, looping, inline video with poster and no empty source request. Enable playback only when a real source exists; preserve the poster for failed playback and reduced motion.
3. Story: equal image and burnt-red/terracotta copy panels, approved chef story and VIEW FULL MENU link.
4. Coast: Goa, Kerala, Mangalore and Tamil Nadu in a native horizontal scroll-snap slider. Include current/total index, thin progress indicator, accessible previous/next controls and keyboard access. No automatic advance.
5. Principles: four image cards with existing titles and summaries and Explore links to About. Three cards plus a peek on desktop; one plus a peek on mobile.
6. Gallery: four-image teaser and View Gallery link. Keep the full Gallery page and its lightbox.
7. Shared footer: dark textured contact invitation, decorative ginger roots, sand visit/address/hours/social band and oversized Zingiber logo. Absorb the approved Experience and Reservation copy here and on Contact; remove both standalone Home sections. Avoid duplicate contact forms.

Home order: hero, story, coast, principles, gallery, footer. The old additional menu section is absorbed by story and coast.

## Constraints

- Palette: #0F0F0F, #701616, #9C4722, #B87333, #EDE7E1.
- Estratto Var/Cormorant Garamond display; Luxora Grotesk/Manrope body.
- Keep phone and hours explicitly unconfirmed until supplied; never invent a booking service or number.
- Reuse existing content, image helper, contact destination, gallery and intro curtain.
- Native HTML/CSS/JavaScript; no framework or new dependency.
- Visible focus, 44px controls, readable contrast, reduced-motion support and no page overflow.

## Acceptance

Run the standalone PHP validator and syntax checks. Verify local WordPress at 375, 768, 1024 and 1440px, including navigation focus/Escape, touch and button slider movement, poster fallback, supporting-page chrome and Gallery filtering/lightbox. Review screenshots against all relevant PDF pages. Leave changes on a feature branch, uncommitted; do not deploy.

## October 23 reference refinement

The latest user image replaces the outlined roots with a photorealistic ginger cluster, green ginger foliage and pale flower buds on transparency. Use this decorative asset above the footer contact strip and in the contact artwork. Add subtle entrance reveals using the existing observer, including staggered footer columns. Show all content immediately for reduced-motion users; keep text and links unobstructed. The user specifically requested the ginger treatment and entrance animations, so retain the existing Zingiber palette.
