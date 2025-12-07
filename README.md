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
