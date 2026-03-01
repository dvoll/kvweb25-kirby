<?php

/**
 * Compact header for wiki pages
 * @var dvll\Sitepackage\Models\CustomBasePage $page
 */
?>

<header class="fixed w-full top-0 z-30 pointer-events-none">
  <div class="max-w-[96rem] mx-auto dvll-container">
    <div class="flex items-center justify-between h-14 px-4 md:px-6 pointer-events-auto">
      <a href="<?= $site->url() ?>" class="flex items-center gap-2" title="<?= $site->title()->escape() ?>">
        <img src="/assets/logos/logo.svg" alt="<?= $site->title()->escape() ?>" class="w-28 md:w-32" />
      </a>
      <div class="flex items-center gap-3">
        <?php if ($kirby->user()): ?>
          <?php $panelUrl = $page->panel()->url() ?? '/panel' ?>
          <a href="<?= $panelUrl ?>" class="btn btn--ghost btn--small" title="Edit page in Panel">Edit</a>
        <?php endif ?>
      </div>
    </div>
  </div>
</header>
