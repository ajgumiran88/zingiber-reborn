# Architecture Snapshot

## Repository Map

| Module | Language | Symbols | Exported |
|--------|----------|---------|----------|
| `.` | php | 0 | 0 |
| `inc` | php | 229 | 191 |
| `inc/customize-control` | php | 4 | 4 |
| `inc/dokan` | php | 10 | 9 |
| `inc/elementor` | php | 45 | 42 |
| `inc/elementor/custom-widgets` | php | 0 | 0 |
| `inc/elementor/elementor-control` | php | 10 | 9 |
| `inc/elementor/widgets` | php | 219 | 138 |
| `inc/header-footer-elementor` | php | 5 | 5 |
| `inc/megamenu` | php | 9 | 4 |
| `inc/megamenu/includes` | php | 18 | 18 |
| `inc/megamenu/includes/admin` | php | 9 | 8 |
| `inc/megamenu/templates` | php | 0 | 0 |
| `inc/merlin` | php | 85 | 43 |
| `inc/merlin/assets/images` | php | 0 | 0 |
| `inc/merlin/includes` | php | 85 | 59 |
| `inc/modules` | php | 4 | 4 |
| `inc/woocommerce` | php | 172 | 161 |
| `inc/zingiber` | php | 27 | 27 |
| `template-parts` | php | 0 | 0 |
| `template-parts/header` | php | 0 | 0 |
| `template-parts/posts-grid` | php | 0 | 0 |
| `tests` | php | 7 | 7 |
| `tests/fixtures` | php | 0 | 0 |
| `woocommerce` | php | 0 | 0 |
| `woocommerce/cart` | php | 0 | 0 |
| `woocommerce/checkout` | php | 0 | 0 |
| `woocommerce/loop` | php | 0 | 0 |
| `woocommerce/myaccount` | php | 0 | 0 |
| `woocommerce/order` | php | 0 | 0 |
| `woocommerce/single-product` | php | 0 | 0 |
| `woocommerce/single-product/tabs` | php | 0 | 0 |

## Extraction Quality

- Files parsed: **109** / 618 seen (22 file(s) + 3 directory tree(s) skipped by ignore globs)
- Parse errors: 0

## Architecture Pattern

_No specific architecture pattern detected._

## Entry Points

- **handler**: `vonaco_ajax_add_to_cart_handler` (inc/woocommerce/woocommerce-template-functions.php)
- **handler**: `zingiber_handle_enquiry` (inc/zingiber/enquiry.php)

## Dependency Rules

- `inc/dokan` -> `tests`
- `inc/elementor/elementor-control` -> `tests`
- `inc/elementor/widgets` -> `inc/elementor`
- `inc/elementor/widgets` -> `inc/woocommerce`
- `inc/elementor/widgets` -> `inc`
- `inc/elementor/widgets` -> `tests`
- `inc/elementor` -> `inc`
- `inc/elementor` -> `tests`
- `inc/header-footer-elementor` -> `tests`
- `inc/megamenu/includes/admin` -> `inc/megamenu/includes`
- `inc/megamenu/includes/admin` -> `tests`
- `inc/megamenu` -> `inc/megamenu/includes`
- `inc/megamenu` -> `tests`
- `inc/merlin/includes` -> `inc/woocommerce`
- `inc/merlin/includes` -> `tests`
- `inc/merlin` -> `inc/merlin/includes`
- `inc/merlin` -> `inc`
- `inc/merlin` -> `tests`
- `inc/modules` -> `tests`
- `inc/woocommerce` -> `inc`
- `inc/woocommerce` -> `tests`
- `inc/zingiber` -> `tests`
- `inc` -> `inc/merlin`
- `inc` -> `inc/woocommerce`
- `inc` -> `tests`

## Critical Modules

| Module | Fan-In | Fan-Out | Criticality |
|--------|--------|---------|-------------|
| `tests` | 13 | 0 | high |
| `inc` | 4 | 3 | medium |
| `inc/woocommerce` | 3 | 2 | medium |
| `inc/elementor/widgets` | 0 | 4 | low |
| `inc/merlin` | 1 | 3 | low |
| `inc/elementor` | 1 | 2 | low |
| `inc/merlin/includes` | 1 | 2 | low |
| `inc/megamenu` | 0 | 2 | low |
| `inc/megamenu/includes` | 2 | 0 | low |
| `inc/megamenu/includes/admin` | 0 | 2 | low |

## Risk Zones

- **Cyclic dependency detected (4 modules)** (confidence: 100%): The 4 modules inc, inc/merlin, inc/merlin/includes, inc/woocommerce depend on each other through more than one cycle. One of them: inc -> inc/merlin -> inc. This can cause initialization issues, make refactoring harder, and indicates tight coupling.

---

*Generated at 2026-11-10T12:12:15Z in 990.884959ms. 1253 facts, 108 insights.*
