<?php

/**
 * Compact header for wiki pages
 * @var dvll\Sitepackage\Models\CustomBasePage $page
 */

use dvll\KirbyWiki\WikiAccess;

$userCanAccessWiki = WikiAccess::currentUserCanAccess($kirby);
$hasGuestAccess = WikiAccess::hasGuestAccess($kirby);
?>

<header class="fixed w-full top-0 z-30 pointer-events-none bg-white/95 backdrop-blur-sm border-b border-gray-200">
    <div class="max-w-384 mx-auto dvll-container">
        <div class="flex items-center justify-between h-(--header-h) px-4 md:px-6 pointer-events-auto">
            <a href="<?= $site->find('wiki')->url() ?? $site->url() ?>" class="flex items-center gap-2 font-style font-semibold" title="<?= $site->title()->escape() ?>">
                <img src="/assets/logos/logo.svg" alt="<?= $site->title()->escape() ?>" class="w-24 md:w-28" />
                KV Wiki
            </a>
            <ul class="flex items-center gap-3">
                <li><a href="<?= $site->url() ?>" class="btn btn--ghost btn--icon-left" title="Seite im Panel bearbeiten"><?= snippet('elements/icon', ['icon' => 'external']) ?> Zur Hauptseite</a></li>
                <?php if ($userCanAccessWiki): ?>
                    <?php $panelUrl = $page->panel()->url() ?? '/panel' ?>
                    <li><a href="<?= $panelUrl ?>" class="btn btn--ghost" title="Seite im Panel bearbeiten">Seite bearbeiten</a></li>
                <?php elseif ($hasGuestAccess): ?>
                    <li><a href="/wiki-logout" class="btn btn--ghost" title="Wiki-Zugang beenden">Gast-Zugang beenden</a></li>
                <?php endif ?>
            </ul>
        </div>
    </div>
</header>
