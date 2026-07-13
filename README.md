# KV Website 2025 powered by Kirby CMS

## Scaffolding

`docker compose exec -it php /var/www/html/vendor/bin/kirby scaffold`

`docker compose exec -it php composer install`

`docker compose exec -it php /var/www/html/vendor/bin/kirby types:create`

## Debugging

Start with Xdebug enabled:

`XDEBUG_MODE=debug docker compose up -`

## Favicon

```bash
magick --% .\favicon-rounded-96x96.png -background transparent ( -clone 0 -resize 16x16 -extent 16x16 ) ( -clone 0 -resize 32x32 -extent 32x32 ) ( -clone 0 -resize 48x48 -extent 48x48 ) -delete 0 favicon.ico
```

## Icon Sprite

Steps to change or add icons:
* Edit icons in project folders kvweb25-icons.afdesign file
* Export icons with affinity export function to `icon-sprite-generator/icons`
* Run `npm run start` in `icon-sprite-generator` dir
* Copy sprinte from `icon-sprite-generator\dist` to `site/snippets/core/svg-sprite.php`
* Because of `securityheaders` change all occurrences of style to tailwind classes

## Testing

The project uses **Pest** (built on top of PHPUnit) for unit and integration testing. 

### Isolated Environment
All tests run in a completely isolated filesystem environment under `tests/tmp`. The testing bootstrap dynamically mocks Kirby's content, accounts, cache, storage, and sessions directories. This ensures that tests do not modify your local development database, configurations, or media assets.

### Running the Tests
You can run the tests directly in the `php8-4` primary execution container:

```bash
# Run the entire test suite
docker compose exec php8-4 ./vendor/bin/pest

# Run only unit tests
docker compose exec php8-4 ./vendor/bin/pest tests/Unit

# Run only integration tests
docker compose exec php8-4 ./vendor/bin/pest tests/Integration
```

### VS Code Tasks
Three VS Code tasks have been added to run tests directly from the editor:
- **PHP: Run Pest Tests (All)**
- **PHP: Run Pest Tests (Unit)**
- **PHP: Run Pest Tests (Integration)**

You can trigger them using the VS Code Command Palette: `Ctrl+Shift+P` (or `Cmd+Shift+P`) $\rightarrow$ `Tasks: Run Task`.
