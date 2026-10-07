# M4P Login Access Control for PrestaShop 8 & 9

**Hide prices and the add-to-cart button from visitors who are not logged in — the basic requirement of every B2B and wholesale shop.**

> **Meta description (152 chars):** Hide PrestaShop prices and the buy button from guests, or force login on the whole shop. Free MIT module for B2B and wholesale stores.

---

## Why a closed catalogue matters for wholesale

A wholesaler rarely sells to everyone at the same price. Net prices, tiered discounts and customer
groups are the point of the shop, and showing them to anonymous visitors has real consequences:

- **Competitors read your price list** — a public wholesale catalogue is a price list handed to
  everyone in your market
- **Retail customers get confused** — net prices next to a buy button lead to orders you cannot
  fulfil and to support tickets
- **Manufacturers require it** — many suppliers make hidden pricing a condition of the distribution
  agreement
- **Registrations go up** — a visitor who wants the price has a reason to create a company account,
  which is exactly the data your sales team needs

## What the module does

The module works on top of PrestaShop's own customer session: it hides prices and purchase controls
for guests, and can send every anonymous visitor to the login page. It adds no database tables and
overrides no core files.

### Key features

- **Prices hidden from guests** — the price block is replaced with a short note asking the visitor
  to log in, on product pages, listings and search results
- **Buy button removed** — add-to-cart controls are hidden, so nobody can start an order without an
  account
- **Optional shop-wide login** — with one switch the whole shop becomes private, and guests are
  redirected to the login page
- **Pages that stay public** — login, registration, password reset, CMS pages and the contact form
  remain reachable, so people can still register and contact you
- **Two independent switches** — hide pricing without closing the shop, or close the shop entirely

### What happens to search engines

With shop-wide login enabled, Googlebot sees the login page instead of your catalogue and the
catalogue drops out of the index. Hiding only prices keeps product pages public and indexable, so
that is the safer setting for a shop that also wants organic traffic.

## Compatibility

| | |
|---|---|
| PrestaShop | 1.7.6 – 9.x |
| PHP | 7.2.5+ |
| Requirements | none |
| Multistore | Configuration is shared across shops |
| Themes | Works with themes that use standard PrestaShop markup; see below |

The module performs no core overrides and adds no database tables.

## Installation

1. Upload and install the module from **Modules → Module Manager**.
2. Open the module configuration page.
3. Turn on **Block possible to buy products and see prices for guests**.
4. Turn on **Enforce login site-wide** only if the whole catalogue is meant to be private.

If prices are still visible after installing, your theme uses custom markup for the price block. Add
its CSS selectors to `views/css/loginaccess.css` — everything the module hides is defined in that
one file.

## Configuration options

| Setting | Description |
|---|---|
| **Enforce login site-wide** | Redirects every guest to the login page. Login, registration, password reset, CMS pages and the contact form stay public. |
| **Block possible to buy products and see prices for guests** | Hides prices and the add-to-cart button and shows a short note instead. The catalogue stays browsable. |

## Frequently asked questions

**Does it hide prices from search engines too?**
Yes — Googlebot is an anonymous visitor, so it sees the same note as a guest. That is usually what a
wholesaler wants, but it means the prices will not appear in Google Shopping or in rich results.

**Can customers still register?**
Yes. Registration, login, password reset, CMS pages and the contact form are always reachable, also
with shop-wide login enabled.

**Does it work with my theme?**
The module hides the standard PrestaShop selectors and prints its note through the
`displayProductPriceBlock` hook with `type="price"`. A customised theme may render its own price
block — then add your selectors to `views/css/loginaccess.css`, and if the theme already shows a
"log in to see prices" message, the module's own note simply does not appear.

**Can I undo it?**
Yes. Turn both switches off and everything is visible again, or uninstall the module — it removes
its own settings and leaves nothing behind.

---

**Keywords:** PrestaShop hide prices, B2B PrestaShop module, wholesale catalogue, login required
PrestaShop, hide add to cart, closed shop PrestaShop, prices for logged in customers.

## License

MIT — see [LICENSE](LICENSE). Free to use commercially, fork and modify; keep the copyright notice.

## Contributing

Bug reports and pull requests are welcome — see [CONTRIBUTING.md](CONTRIBUTING.md). For security
issues, follow [SECURITY.md](SECURITY.md) instead of opening a public issue.

---

Built by [Nice Code](https://nice-code.com/pl/produkty/sklep-b2b-prestashop) — we build B2B stores on PrestaShop.

© Nice Code sp. z o.o. (Modules4Presta) — released under the MIT license.
