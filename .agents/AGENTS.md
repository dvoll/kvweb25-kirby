# KV Web 2025 (Kirby CMS) - AI Coding Guidelines & Reference

Welcome to the `kvweb25-kirby` workspace. This rules document defines standard workflows, directory structures, architectural patterns, and development environments. Always follow these rules when implementing features or fixing bugs in this repository.

---

## Project Philosophy & Constraints

- **Non-Profit, Free-Time Project**: This codebase is maintained in free time for a non-profit organization.
- **Irregular Contributions**: Development happens in waves with potentially long intervals in between. Therefore, **readability and ease of understanding** are the highest priority.
- **Modularity for Readability**: Modularize code only when it makes the architecture cleaner and easier to comprehend at a glance. Avoid abstracting too early or creating unnecessary layers of complexity.
- **Minimize Dependencies**: Prefer simple, native features and build lightweight solutions. Do not introduce new external libraries or dependencies unless absolutely necessary.
- **Clean Code & Best Practices**: Write clear, self-documenting code. Use comments/docstrings to explain non-obvious design choices or context, allowing someone returning to the code after months to understand it quickly.
- **Framework Standards**: Adhere as closely as possible to the official Kirby CMS documentation patterns and native structures. Avoid non-standard custom routing or abstractions where Kirby's native paradigms (such as page models, controllers, templates, snippets, and blueprint extends) exist. This facilitates easier upgrades and maintains clean, idiomatic code.

---

## 1. Development & Docker Environment

This project runs containerized using Docker Compose.

### Docker Services
- **PHP 8.4 Container**: Service name `php8-4`. This is the primary execution container. Note that older documentation may refer to it as `php`. Always use `php8-4` in commands.
- **Apache Web Server Container**: Service name `httpd`. It proxies traffic and runs on port `8000` (e.g. `http://localhost:8000`).
- **Mailpit Container**: Service name `mailhog`. Running on ports `1025` (SMTP) and `8025` (Web UI).

### Common Docker Commands
All PHP commands, composer installs, or CLI scripts must run within the `php8-4` container.
- **Composer commands**:
  ```bash
  docker compose exec php8-4 composer install
  docker compose exec php8-4 composer update
  ```
- **Kirby CLI commands**:
  - Scaffold pages/database seed:
    ```bash
    docker compose exec php8-4 /var/www/html/vendor/bin/kirby scaffold
    ```
  - Create TypeScript types for Kirby fields/blueprints:
    ```bash
    docker compose exec php8-4 /var/www/html/vendor/bin/kirby types:create
    ```
  - Flush Google Calendar cache:
    ```bash
    docker compose exec php8-4 /var/www/html/vendor/bin/kirby flush-event-cache
    ```
  - Run database migration (layouts to blocks):
    ```bash
    docker compose exec php8-4 /var/www/html/vendor/bin/kirby migration-layouts-to-blocks
    ```

---

## 2. Frontend Assets & Vite

The project uses Node.js, Vite, TailwindCSS v4, Swiper, and AlpineJS.

### Key Scripts
- **Development dev-server**: `npm run dev`
- **Build production assets**: `npm run build` (runs `tsc && vite build`)

### Asset Locations
- Source files: `src/` (main entrypoints: [src/main.ts](file:///home/dvoll/code/kv25/kvweb25-kirby/src/main.ts) and [src/main.css](file:///home/dvoll/code/kv25/kvweb25-kirby/src/main.css))
- Compiled output: `public/assets/dist/` (configured in [vite.config.mts](file:///home/dvoll/code/kv25/kvweb25-kirby/vite.config.mts) and [site/config/vite.config.php](file:///home/dvoll/code/kv25/kvweb25-kirby/site/config/vite.config.php))

### AlpineJS & Content Security Policy (CSP)
- The project loads AlpineJS from `@alpinejs/csp` to prevent CSP violations.
- Avoid using inline JS strings inside HTML `x-on` or `x-text` properties if they violate CSP. Rely on components/functions defined in external JS/TS files (like those in `src/components/`).
- Dispatched scroll events should be handled via event listeners in JS files rather than inline CSP attributes (e.g. `document.addEventListener('scroll-to-inhalt', ...)`).

---

## 3. Project Directory Structure

- **`content/`**: File-based Kirby content database.
- **`plugins/`**: Custom local Kirby plugins (configured via `path` repository in main [composer.json](file:///home/dvoll/code/kv25/kvweb25-kirby/composer.json)).
  - **`sitepackage/`**: General custom website blocks, templates, helpers, custom controllers, and hooks.
  - **`kirby-events/`**: Google Calendar event synchronization service, Models, templates, and panel areas.
- **`site/`**: Standard Kirby system directories:
  - **`blueprints/`**: Schemas defining the structure of fields in the CMS Panel (`pages/`, `tabs/`, `files/`, `users/`, `blocks/`).
  - **`commands/`**: Custom CLI commands registered to the Kirby CLI (scaffold, flush-event-cache, migration-layouts-to-blocks).
  - **`config/`**: Main system configuration ([config.php](file:///home/dvoll/code/kv25/kvweb25-kirby/site/config/config.php)), thumbnail presets ([thumbs.php](file:///home/dvoll/code/kv25/kvweb25-kirby/site/config/thumbs.php)).
  - **`controllers/`**: Controller logic for templates.
  - **`models/`**: Extends page model behaviors.
  - **`snippets/`**: Reusable PHP view components.
  - **`templates/`**: Page template PHP files.
- **`src/`**: CSS and TypeScript files compiled by Vite.

---

## 4. Custom Local Plugins Reference

### sitepackage (`plugins/sitepackage`)
- Registers several custom blueprints and block templates (e.g. gallery, heading, image, video, quote, teaser-events, text, facts, team).
- Automatically assigns unique UUIDs to `contacts` and `tags` structures in site updates via the hook `site.update:after`.
- Registers custom darkroom configurations: `CustomImageMagick` and `CustomGd`.
- Defines custom route redirections and event API endpoints.

### kirby-events (`plugins/kirby-events`)
- Integrates Google Calendar API using `google/apiclient`.
- Uses `GOOGLE_CALENDAR_ID` and other `GOOGLE_API_*` properties from `.env`.
- Syncs/caches events, exposing the option to invalidate cache via the Kirby Panel UI under "Termine aktualisieren" or the command line via `kirby flush-event-cache`.
- Implements `MockGoogleCalendarService` for local dev/testing without Google credentials when `KIRBY_EVENTS_USE_MOCK=true`.

---

## 5. Coding Standards & Code Quality

Always maintain high code quality and adhere to the project's formatting rules:

### PHP Quality Tools
- **PHP CS Fixer**: Configure rules based on [.php-cs-fixer.dist.php](file:///home/dvoll/code/kv25/kvweb25-kirby/.php-cs-fixer.dist.php).
  - Format site PHP files using:
    ```bash
    docker compose exec php8-4 ./vendor/bin/php-cs-fixer fix
    ```
- **PHPStan (Static Analysis)**: Run static analysis to detect type errors and bugs based on [phpstan.neon](file:///home/dvoll/code/kv25/kvweb25-kirby/phpstan.neon):
  ```bash
  docker compose exec php8-4 ./vendor/bin/phpstan analyse
  ```

---

## 6. Icons & Sprites

To add or modify icons:
1. Edit icons in Affinity Designer `kvweb25-icons.afdesign`.
2. Export changes to `icon-sprite-generator/icons`.
3. In `icon-sprite-generator`, run `npm run start` to build the new SVG sprite.
4. Copy the generated sprite into `site/snippets/core/svg-sprite.php`.
5. Convert all inline styles inside icons to Tailwind CSS classes to satisfy security headers.
