# Workspace Architecture & Guidelines

This document provides a technical walkthrough of the `kvweb25-kirby` repository architecture, local environment configuration, asset pipelines, and custom Kirby CMS modules.

---

## Project Philosophy & Constraints

- **Non-Profit, Free-Time Project**: This codebase is maintained in free time for a non-profit organization.
- **Irregular Contributions**: Development happens in waves with potentially long intervals in between. Therefore, **readability and ease of understanding** are the highest priority.
- **Modularity for Readability**: Modularize code only when it makes the architecture cleaner and easier to comprehend at a glance. Avoid abstracting too early or creating unnecessary layers of complexity.
- **Minimize Dependencies**: Prefer simple, native features and build lightweight solutions. Do not introduce new external libraries or dependencies unless absolutely necessary.
- **Clean Code & Best Practices**: Write clear, self-documenting code. Use comments/docstrings to explain non-obvious design choices or context, allowing someone returning to the code after months to understand it quickly.
- **Framework Standards**: Adhere as closely as possible to the official Kirby CMS documentation patterns and native structures. Avoid non-standard custom routing or abstractions where Kirby's native paradigms (such as page models, controllers, templates, snippets, and blueprint extends) exist. This facilitates easier upgrades and maintains clean, idiomatic code.

---

## 1. System Topology & Docker Environment

The local development environment is containerized using Docker Compose:

- **Services**:
  - `php8-4` (PHP 8.4 runtime): The primary PHP worker container where composer, git, and custom Kirby commands are executed.
  - `httpd` (Apache server): Serves the frontend application on `http://localhost:8000`.
  - `mailhog` (Mailpit service): Captures outbound email messages on ports `1025` (SMTP) and `8025` (Web GUI dashboard).
- **Service Name Reference**: Always run CLI actions on the `php8-4` service (e.g. `docker compose exec php8-4 ...`). Avoid executing against `php` as it is not declared as a service name in [docker-compose.yml](file:///home/dvoll/code/kv25/kvweb25-kirby/docker-compose.yml).

---

## 2. Frontend Pipeline & Build Steps

The frontend uses Vite to bundle modern CSS and TypeScript assets.

- **Dependencies**:
  - Vite is configured using [vite.config.mts](file:///home/dvoll/code/kv25/kvweb25-kirby/vite.config.mts).
  - TailwindCSS v4 acts as the styling engine.
  - AlpineJS (specifically the CSP-compatible build `@alpinejs/csp`) handles dynamic client behavior.
  - Swiper drives carousel/slider controls.
- **Entry Points**:
  - TS Entrypoint: [src/main.ts](file:///home/dvoll/code/kv25/kvweb25-kirby/src/main.ts)
  - CSS Entrypoint: [src/main.css](file:///home/dvoll/code/kv25/kvweb25-kirby/src/main.css)
- **Output Directory**: Assets are compiled into [public/assets/dist/](file:///home/dvoll/code/kv25/kvweb25-kirby/public/assets/dist/).
- **Commands**:
  - Live dev server (with CSS hot-reloading and Kirby file watching): `npm run dev`
  - Production build (combines TypeScript compilation and asset bundling): `npm run build`

---

## 3. Kirby CMS Configurations

Kirby is configured as a file-based CMS with custom programmatic plugins:

- **Configuration File**: [site/config/config.php](file:///home/dvoll/code/kv25/kvweb25-kirby/site/config/config.php)
  - Loads environment settings via `vlucas/phpdotenv`.
  - Sets up image thumbnail options via `thumbs.php` helper config.
  - Integrates SEO defaults (using `tobimori/kirby-seo`).
  - Sets up caching rules for general pages and UUID lookup tables.
- **CMS Blueprints**: Defined in `site/blueprints/`.
  - [site.yml](file:///home/dvoll/code/kv25/kvweb25-kirby/site/blueprints/site.yml) structures the main admin Panel into tabs: `main` (Navigation tree), `global` (Global Settings), `seo` (SEO properties), and `admin` (Admin Tools).
  - Subfolders define content configurations: `pages/`, `fields/`, `files/`, `users/`, `blocks/`, `sections/`.

---

## 4. Custom Local Plugins

To extend CMS core behaviors, two custom plugins are registered under `plugins/` via local path dependencies:

### A. Sitepackage (`plugins/sitepackage`)
Located in [plugins/sitepackage/](file:///home/dvoll/code/kv25/kvweb25-kirby/plugins/sitepackage/).
- **Blocks & Snippets**: Registers custom templates and visual blocks (gallery, quote, image, heading, teaser-events, text, facts, team, spacer).
- **Models**: Defines block models in [plugins/sitepackage/models/](file:///home/dvoll/code/kv25/kvweb25-kirby/plugins/sitepackage/models/) (e.g. `TeaserBlogpostsBlock`, `TeaserEventsBlock`).
- **Autoloading**: Utilizes PSR-4 autoloading mapped to `dvll\\Sitepackage\\Helpers\\` and `dvll\\Sitepackage\\Models\\`.
- **Hooks**: Implements `site.update:after` to automatically inject unique custom UUIDs into `contacts` and `tags` structures.

### B. Kirby Events (`plugins/kirby-events`)
Located in [plugins/kirby-events/](file:///home/dvoll/code/kv25/kvweb25-kirby/plugins/kirby-events/).
- **Google Calendar Sync**: Integrates standard Google Calendar API v3 to pull calendar events.
- **Local Mock Support**: When `KIRBY_EVENTS_USE_MOCK=true` is defined in `.env`, the sync uses `MockGoogleCalendarService` instead of triggering external network calls.
- **Caching**: Events data is cached in the `dvll.kirby-events` cache.
- **Cache Invalidation**:
  - Through Panel UI: Click "Termine aktualisieren" in the Admin Tools section.
  - Through CLI: Run `docker compose exec php8-4 /var/www/html/vendor/bin/kirby flush-event-cache`.

---

## 5. Code Style & Quality Control

To maintain code standards, two tools are used:
- **Code Formatter**: PHP CS Fixer. Runs based on rules in [.php-cs-fixer.dist.php](file:///home/dvoll/code/kv25/kvweb25-kirby/.php-cs-fixer.dist.php).
  - Execute: `docker compose exec php8-4 ./vendor/bin/php-cs-fixer fix`
- **Static Analysis**: PHPStan (Level 6). Runs based on [phpstan.neon](file:///home/dvoll/code/kv25/kvweb25-kirby/phpstan.neon).
  - Execute: `docker compose exec php8-4 ./vendor/bin/phpstan analyse`
