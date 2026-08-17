# Zingiber Home 2 Design Specification

## Objective

Transform the licensed Vonaco WordPress theme's Home 2 experience into a professional, visually refined website for Zingiber, a chef-led modern Indian coastal restaurant in JLT, Dubai. The finished theme must use Zingiber's supplied brand guidelines, approved website copy, and supplied food and interior imagery, while retaining the polished editorial rhythm of Vonaco Home 2.

## Deliverable

The GitHub repository will contain the customized WordPress theme as the project root. It will remain installable as a WordPress theme and include a Zingiber-specific Home 2 import path that does not depend on Vonaco's remote demo images.

The website will include these content destinations:

- Home
- About Zingiber
- Menu
- Gallery
- Careers
- Contact

## Visual Direction

The visual character is contemporary restaurant luxury: atmospheric, tactile, restrained, and editorial. It should feel warm and immersive without becoming theatrical or crowded.

The primary palette comes directly from the supplied brand guidelines:

- Deep Charcoal: `#0F0F0F`
- Burnt Red: `#701616`
- Terracotta: `#9C4722`
- Copper: `#B87333`
- Sand: `#EDE7E1`

Estratto Var is the preferred display typeface and Luxora Grotesk is the preferred supporting typeface. When the licensed font files are unavailable, use carefully selected web-safe or open alternatives with similar proportions and preserve the intended serif/grotesk contrast.

Use copper and terracotta as controlled accents. Sand provides light editorial surfaces. Deep Charcoal and Burnt Red create the immersive restaurant atmosphere. Avoid excessive cards, generic dashboard styling, heavy gradients, bright unrelated colors, and decorative effects that compete with the food and interiors.

## Home 2 Adaptation

Keep Home 2's broad narrative sequence while replacing all Vonaco-specific content and media:

1. Cinematic hero using a supplied interior or signature dish image, Zingiber branding, the approved hero copy, and clear Menu and Reservation calls to action.
2. Chef-led introduction presenting Zingiber's modern coastal Indian positioning and Chef Shankar Krishnamurthy.
3. Four concise principles: Authenticity, Innovation, Consistency, and Storytelling.
4. Coastal India feature section covering Goa, Kerala, Mangalore, and Tamil Nadu.
5. Signature menu showcase using the supplied food photography without inventing unsupported dish names or prices.
6. Restaurant experience section combining interior imagery and approved brand-story copy.
7. Gallery-led visual sequence using both food and spatial imagery.
8. Reservation/contact block with the supplied address, email, hours, and social channels. Keep the incomplete phone number clearly editable rather than fabricating a value.
9. Brand-consistent footer with navigation, contact information, and social links.

## Supporting Pages

Use the supplied SEO titles, descriptions, headings, and body copy as the factual authority for About, Menu, Gallery, Careers, and Contact. Build these pages with the same shared header, typography, palette, spacing, image treatment, and footer as Home 2.

The Menu page will describe the culinary journey and provide a polished visual menu introduction. It must not invent menu items, pricing, allergens, or operational details that were not supplied.

The Careers page will include a clear inquiry action. The Contact page will present all confirmed details and visibly mark incomplete operational information for later editing.

## Assets

Copy the supplied Zingiber food and interior images into a dedicated theme asset directory using short, descriptive filenames. Optimize oversized files for web delivery while preserving source masters outside the production asset directory.

Extract or derive the approved Zingiber logo artwork from the supplied brand guidelines without changing its proportions, colors, or required clear space. Use approved light and dark variations only.

All production images require meaningful alternative text. Avoid using the same image repeatedly unless it is intentionally serving as a shared background.

## WordPress Implementation

Modify the existing Vonaco theme directly, as requested. Preserve its required WordPress theme structure and compatibility code. Keep Zingiber-specific presentation rules isolated in dedicated stylesheet and script files where practical so the changes remain understandable.

Home 2 and its import data will be updated to use Zingiber content and packaged assets. The implementation must not leave active references to Vonaco's demo media, placeholder copy, sample offers, sample phone numbers, or unrelated restaurant names.

## Responsive and Accessibility Requirements

The design must work at desktop, tablet, and mobile widths. Navigation, headings, buttons, image crops, and section spacing must remain legible and balanced at each breakpoint.

Use semantic headings, keyboard-accessible navigation and controls, visible focus states, useful alt text, sufficient color contrast, and reduced-motion behavior where motion is added.

## Verification

Before delivery:

- Validate PHP syntax for every changed PHP file.
- Validate XML and JSON content introduced or modified for imports.
- Scan for unresolved placeholders and remaining Vonaco/demo references in Zingiber-facing content.
- Verify packaged image paths and production image dimensions.
- Inspect the primary pages at desktop and mobile sizes in a WordPress environment when the local runtime supports it.
- Confirm Git commits use the user's GitHub identity and do not name Codex as author or co-author.
- Push the completed `main` branch to `https://github.com/ajgumiran88/zingiber-reborn.git`.

## Out of Scope

- Inventing final phone numbers, dish names, menu prices, or booking-provider credentials.
- Deploying WordPress hosting or configuring a production database.
- Purchasing or redistributing third-party font licenses beyond the files already supplied.
- Reworking WooCommerce account, checkout, or product behavior beyond visual compatibility with the Zingiber theme.
