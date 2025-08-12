# KV Website 2025 - Kirby CMS Development Guide

## Architecture Overview

This is a **Kirby CMS 5.x** website with a **custom plugin-based architecture**:
- **Main plugin**: `plugins/sitepackage/` - Contains all custom blocks, models, helpers, and functionality
- **Specific event plugin**: `plugins/kirby-events/` - Handles event management functionality
- **Frontend**: TypeScript + Vite + TailwindCSS 4.x + Alpine.js + Swiper
- **Environment**: Docker-based development with PHP 8.4, Apache, and MailHog

## Development Workflow

### Docker Commands (Required)
```bash
# Start development (with optional Xdebug)
XDEBUG_MODE=debug docker compose up

# Install dependencies
docker compose exec -it php8-4 composer install

# Generate types for better IDE support
docker compose exec -it php8-4 /var/www/html/vendor/bin/kirby types:create

# Scaffold initial pages
docker compose exec -it php8-4 /var/www/html/vendor/bin/kirby scaffold
```

### Frontend Build Process
```bash
# Development with hot reload
npm run dev

# Production build
npm run build

# TypeScript compilation + Vite build
npm run build
```

## Custom Block System

**Key Pattern**: All content blocks follow a consistent structure in `plugins/sitepackage/blocks/`:
- `{block-name}/` directory contains both `.yml` blueprint and `.php` snippet
- Blocks are registered in `plugins/sitepackage/index.php` under both `blueprints` and `snippets`
- Complex blocks may have dedicated models in `plugins/sitepackage/models/`

**Example Block Structure**:
```
plugins/sitepackage/blocks/gallery/
├── gallery.yml    # Panel blueprint
└── gallery.php    # Frontend snippet
```

**Custom Block Models**: `TeaserBlogpostsBlock`, `LayoutWithContactBlock` extend base `Block` class for complex logic.

## Site Structure & Page Models

**Content Architecture**:
- `content/1_home/` - Homepage with special welcome stage
- `content/2_blog/` - Blog with custom `BlogpostPage` model
- `content/3_termine/` - Events using `kirby-events` plugin
- `content/4_freizeiten/` - Camps with `CampPage` model
- `content/images/` - Special unlisted page for shared images

**Page Model Pattern**: Custom pages extend `CustomBasePage` which implements `WithTeaserContentInterface` for consistent teaser content across the site.

## Environment & Configuration

**Environment Variables** (loaded via `Helper::getEnv()`):
- `APP_URL` - Base URL for the site
- `KIRBY_DEBUG` - Debug mode toggle
- `KIRBY_CACHE` - Cache enable/disable
- `KIRBY_MAIL_*` - SMTP configuration
- `PAGE_VIEW_LOGIN` - Force login to view pages

**Custom Helper Usage**: Use `Helper::getEnv()` instead of direct `getenv()` - it handles boolean conversion and defaults.

## Frontend Integration

**Vite + Kirby Integration**:
- Entry point: `src/main.ts`
- Vite config watches PHP templates/snippets for hot reload
- Assets output to `public/assets/dist/`
- Use `vite()->css()` and `vite()->js()` in templates

**Alpine.js Components**: Located in `src/components/` (e.g., `gallery.ts` for Swiper integration)

**TailwindCSS**: Custom config with reversed breakpoints (max-width approach) and Inter font

## UUID System & Structured Fields

**Important Pattern**: Site uses custom UUIDs for structured fields (`contacts`, `tags`) to enable multiselect functionality:
- UUIDs auto-generated via `Helper::ensureUniqueCustomUuids()` on site save
- Use `UuidSelectFieldHelper::getCollectionForUuids()` to resolve selections
- Never manually manage UUIDs - they're handled automatically

## Plugin Development

**sitepackage Plugin Structure**:
- `/helpers/` - Utility classes (Helper, Menu, UuidSelectFieldHelper)
- `/models/` - Custom page and block models
- `/blocks/` - Content blocks (blueprint + snippet pairs)
- `/blueprints/` - Panel blueprints and tabs

**Programmatic Blueprints**: Admin-only blueprints loaded conditionally via functions in plugin registration.

## Key File Locations

- **Main config**: `site/config/config.php`
- **Custom commands**: `site/commands/` (scaffold, migrations)
- **Core snippets**: `site/snippets/core/` (header, footer, layout)
- **Templates**: `site/templates/` (mostly extend base `layout.php`)
- **Panel customization**: `site/blueprints/` for content structure

## Route Handling

Custom routes in `plugins/sitepackage/index.php`:
- Legacy redirects for "freizeiten" slugs
- Event search with pagination for "termine" page
- Use `$this->next()` to continue route chain

**Never edit core Kirby files** - all customization goes through the plugin system or site config.
