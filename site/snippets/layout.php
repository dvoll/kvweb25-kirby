<?php

/** @var Kirby\Cms\Page $page
 *  @var Kirby\Cms\Site $site
 *  @var Kirby\Cms\App $kirby */

use dvll\Sitepackage\Helper;

if (Helper::getEnv('PAGE_VIEW_LOGIN') && !$kirby->user()) {
    go('/panel');
}
?>

<!DOCTYPE html>
<html lang="<?= $kirby->language()?->code() ?>">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <?php snippet('seo/head') ?>

    <link rel="icon" type="image/png" href="/assets/favicons/favicon-96x96.png" sizes="96x96" />
    <link rel="icon" type="image/svg+xml" href="/assets/favicons/favicon.svg" />
    <link rel="shortcut icon" href="/assets/favicons/favicon.ico" />
    <link rel="apple-touch-icon" sizes="180x180" href="/assets/favicons/apple-touch-icon.png" />
    <meta name="apple-mobile-web-app-title" content="CVJM KV Bünde" />
    <link rel="manifest" href="/assets/favicons/site.webmanifest" />

    <?= vite()->css('src/main.ts') ?>
</head>

<body class="flex flex-col min-h-screen antialiased overflow-x-clip">
    <?php snippet('core/skip-nav') ?>
    <?php snippet('core/nav') ?>
    <main class="container flex-grow">
        <div id="main"></div>
        <?= $slot ?>
    </main>
    <?php snippet('core/footer') ?>
    <?= vite()->js('src/main.ts') ?>
    <?php snippet('seo/schemas') ?>
</body>

</html>
