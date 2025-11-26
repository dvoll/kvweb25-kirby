<?php

/** @var Kirby\Cms\Page $page
 *  @var Kirby\Cms\Site $site
 *  @var Kirby\Cms\App $kirby */

use dvll\Sitepackage\Helpers\Helper;

if (Helper::getEnv('PAGE_VIEW_LOGIN') && !$kirby->user()) {
    go('/panel');
}
?>

<!DOCTYPE html>
<html lang="<?= $kirby->language()?->code() ?? 'de-DE' ?>">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <?php snippet('seo/head') ?>

    <link rel="icon" type="image/png" href="/assets/favicons/favicon-96x96.png" sizes="96x96" />
    <link rel="icon" type="image/svg+xml" href="/assets/favicons/favicon.svg" />
    <link rel="shortcut icon" href="/assets/favicons/favicon.ico" />
    <link rel="apple-touch-icon" sizes="180x180" href="/assets/favicons/apple-touch-icon.png" />
    <meta name="apple-mobile-web-app-title" content="CVJM KV Bünde" />
    <link rel="manifest" href="/assets/favicons/site.webmanifest" />

    <link rel="preload" href="<?= vite()->file('src/fonts/roboto/RobotoFlex-VariableFont_GRAD,XOPQ,XTRA,YOPQ,YTAS,YTDE,YTFI,YTLC,YTUC,opsz,slnt,wdth,wght.woff2') ?>" as="font" type="font/woff2" crossorigin>
    <link rel="preload" href="<?= vite()->file('src/fonts/sourcesanspro/SourceSansPro-Regular.woff2') ?>" as="font" type="font/woff2" crossorigin>
    <link rel="preload" href="<?= vite()->file('src/fonts/sourcesanspro/SourceSansPro-Bold.woff2') ?>" as="font" type="font/woff2" crossorigin>

    <link rel="preload" href="<?= vite()->file('src/main.ts') ?>" as="script" type="text/javascript" crossorigin>
    <link rel="preload" href="<?= vite()->file('src/main.css') ?>" as="style" type="text/css" crossorigin>

    <?= vite()->css('src/main.css', ['crossorigin' => 'anonymous']) ?>
</head>

<body class="flex flex-col min-h-screen antialiased overflow-x-clip bg-baseline">
    <?php snippet('core/skip-nav') ?>
    <?php snippet('core/header') ?>
    <main id="main" class="flex-grow dvll-container">
        <?= $slot ?>
    </main>
    <?php snippet('core/footer') ?>
    <?= vite()->js('src/main.ts', ['defer' => true, 'crossorigin' => 'anonymous']) ?>
    <?php snippet('seo/schemas') ?>
    <?php snippet('core/svg-sprite') ?>
</body>

</html>
