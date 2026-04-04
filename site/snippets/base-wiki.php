<?php

/** @var Kirby\Cms\Page $page
 *  @var Kirby\Cms\Site $site
 *  @var Kirby\Cms\App $kirby */

use dvll\Sitepackage\Helpers\Helper;
use dvll\Sitepackage\Helpers\WikiAccess;

if (Helper::getEnv('PAGE_VIEW_LOGIN') && !$kirby->user()) {
    go('/panel');
}

$showGuestLogin = false;
$guestLoginError = null;

if (WikiAccess::isWikiPage($page)) {
    $userCanAccessWiki = WikiAccess::currentUserCanAccess($kirby);

    if ($kirby->user() && $userCanAccessWiki === false) {
        go($site->url());
    }

    if ($userCanAccessWiki === false && WikiAccess::hasGuestAccess($kirby) === false) {
        if (WikiAccess::guestLoginEnabled() === false) {
            go($site->url());
        }

        if ($kirby->request()->is('POST') && get('wiki_guest_login')) {
            $password = (string)get('password', '');

            if (csrf(get('csrf')) !== true) {
                $guestLoginError = 'Die Anmeldung konnte nicht verarbeitet werden.';
            } elseif ($invalid = invalid(
                ['password' => $password],
                ['password' => ['required']],
                ['password' => 'Bitte geben Sie ein Passwort ein.']
            )) {
                $guestLoginError = reset($invalid) ?: 'Die Anmeldung konnte nicht verarbeitet werden.';
            } elseif (WikiAccess::verifyGuestPassword($password) === false) {
                $guestLoginError = 'Das Passwort ist ungültig.';
            } else {
                WikiAccess::activateGuestAccess($kirby);
                go($page->url());
            }
        }

        $showGuestLogin = true;
    }
}

$content = $showGuestLogin
    ? snippet('wiki/login-form', ['page' => $page, 'guestLoginError' => $guestLoginError], true)
    : $slot;
?>

<!DOCTYPE html>
<html lang="<?= $kirby->language()?->code() ?? 'de-DE' ?>" class="scroll-pt-16">

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

<body class="min-h-dvh antialiased bg-baseline layout-sidebar-wiki" style="--header-h:3.5rem; --left-w:18rem; --right-w:16rem;">
    <?php snippet('core/skip-nav') ?>
    <?php snippet('core/header-wiki') ?>
    <?= $content ?>
    <?= vite()->js('src/main.ts', ['async' => true, 'crossorigin' => 'anonymous']) ?>
    <?php snippet('core/svg-sprite') ?>
</body>

</html>
