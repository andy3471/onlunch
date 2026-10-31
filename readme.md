# LunchRota

Multi-tenant team scheduling for lunch bookings, task assignments, and time off.

## Stack

- PHP 8.4 / Laravel 12
- Inertia.js v1 + Vue 3 + TypeScript
- Tailwind CSS
- Laravel Sail
- Laravel Wayfinder + Spatie Laravel Data / TypeScript Transformer
- Pest v4 (Feature + Browser)

Sites are resolved from `{slug}.{APP_DOMAIN}` subdomains. Users are global; access is via site membership.

## Quick start (Sail)

```shell
git clone https://github.com/andy3471/lunchrota.git
cd lunchrota

cp .env.example .env
composer install --ignore-platform-reqs
vendor/bin/sail up -d
vendor/bin/sail artisan key:generate
vendor/bin/sail npm install
vendor/bin/sail npm run build
vendor/bin/sail artisan migrate
vendor/bin/sail open
```

Set `APP_DOMAIN` (default `localhost`) and create a site via the brochure `/register` flow, then open `http://{slug}.localhost`.

## Frontend

```shell
vendor/bin/sail npm run dev
vendor/bin/sail npm run typecheck
vendor/bin/sail npm run lint
```

Wayfinder output lives under `resources/js/Generated/` (aliased as `@/actions`, `@/routes`, `@/wayfinder`). Regenerate with Vite (plugin) or:

```shell
vendor/bin/sail artisan wayfinder:generate --path=resources/js/Generated
vendor/bin/sail artisan typescript:transform
```

## Testing

```shell
# Feature + unit (default)
composer test
# or
vendor/bin/sail artisan test --compact

# Browser (Playwright; install once inside Sail)
vendor/bin/sail npx playwright install chromium
vendor/bin/sail root-shell -c "npx playwright install-deps chromium"
vendor/bin/sail artisan test --compact --testsuite=Browser
```

Create a MySQL `testing` database matching `phpunit.xml` (`DB_DATABASE=testing`).

## Production notes

See `.env.example`: `APP_DEBUG=false`, `SESSION_ENCRYPT=true`, `SESSION_SECURE_COOKIE=true`, and set `TRUSTED_PROXIES` to your load-balancer CIDRs when not using `*`.
