# ZAM ZAM TRUCKING LLC — Website

Laravel 12 multi-page website for a dry van / full truckload carrier, built from
`zamzam-trucking-website.pdf`, with an admin panel for leads and content.

All front-end content is managed in the admin panel (page builder):

- **Pages** (`site_pages`): home, about, services, equipment, safety, careers,
  request-a-quote, contact, legal pages, plus any custom page (`/{slug}`).
- **Sections** (`sections`): each page is an ordered list of sections. Section
  types are defined in `App\Support\SectionTypes` (fields, item groups, defaults)
  and rendered by `resources/views/front/sections/{type}.blade.php`. Page
  `global` holds the header/menu, footer and form texts/options.
- **Items** (`section_items`): repeatable cards, stats, list entries, links…
- **Services** (`services`): each has its page at `/services/{slug}`.
- Text may contain placeholders like `{company_name}`, `{phone}`, `{usdot_number}`
  (any Company Settings field, plus `{phone_link}` and `{year}`); see `app/helpers.php`.
- Starter content: `database/seeders/ContentSeeder.php` (skips if content exists).

## Setup (XAMPP)

```bash
# 1. Create the database "zamzam" (phpMyAdmin, or:)
mysql -uroot -e "CREATE DATABASE zamzam"

# 2. Build tables and starter content (drops all tables!)
php artisan migrate:fresh --seed
```

Open `http://localhost/zamzam/`. The root `.htaccess` + `server.php` route
requests into `public/`.

## Admin

- URL: `/admin/login`, email `admin@example.com`, password `password`.
  Change both under **Profile** right away.

| Menu item | What it does |
|---|---|
| Dashboard | New/total leads, latest items, warning for unfilled placeholders |
| Quote Requests | Leads from "Request a Quote": status, details, delete, **Export CSV** |
| Driver Applications | Leads from "Quick Application": status, SMS-consent record, **Export CSV** |
| Company Settings | Every `[placeholder]` from the design: phone, email, address, USDOT, MC, insurance, fleet, pay |
| Theme Colors | Accent + dark base colour with live preview and presets; all shades and readable text colours are derived (`App\Support\Theme`) |
| Logo & Images | Logo, favicon, hero and About photos (defaults ship in `public/assets/front/img`; logo source in `resources/brand`) |
| Pages & Sections | Add/delete pages; add (16 types), edit, reorder, hide, duplicate and delete sections and their items. Legal pages are pages here (starter text — have it reviewed) |
| Services | Add/edit/reorder/hide/delete services (text, photo, icon, SEO) |
| Header & Menu / Footer / Forms & Labels | Menu links, header button, footer columns and links, form titles, options and messages, SMS consent text |

## Notifications

New quotes and applications are emailed to the **Dispatch email** from Company
Settings once it holds a real address. Mail uses `MAIL_*` in `.env`
(`MAIL_MAILER=log` writes emails to `storage/logs/laravel.log` instead of sending).

## Notes

- Forms are protected by rate limiting (5/min) and a hidden honeypot field.
- SMS opt-in is optional and stored with the exact wording shown and the IP (TCPA record).
- Stock photos are from Unsplash (free for commercial use under the Unsplash License).
- `composer.lock` was resolved on PHP 8.4; the app runs on XAMPP's PHP 8.2, but
  `composer update/require/remove` needs PHP 8.4. `srmklive/paypal` and
  `stripe/stripe-php` are unused leftovers that can be removed on PHP 8.4.
