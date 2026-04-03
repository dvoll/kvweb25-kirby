<?php

/**
 * Wiki template
 * Renders a fixed top nav, fixed left and right sidebars and a scrollable main content area.
 */

snippet('base-wiki', slots: true); ?>

<div class="grid grid-cols-1 lg:grid-cols-[var(--left-w)_minmax(0,1fr)] xl:grid-cols-[var(--left-w)_minmax(0,1fr)_var(--right-w)] min-h-dvh pt-(--header-h)">

    <?php // Left sidebar — page navigation
    ?>
    <div class="max-lg:hidden">
        <div class="sticky h-full top-(--header-h) max-h-[calc(100dvh-var(--header-h))] overflow-y-auto">
            <?php snippet('wiki/sidebar-left') ?>
        </div>
    </div>

    <?php // Main content
    ?>
    <main id="main" class="min-w-0 bg-white">
        <article class="grow dvll-container dvll-container--wiki">
            <?php snippet('core/stage'); ?>
            <?php snippet('core/layouts') ?>
        </article>
    </main>

    <?php // Right sidebar — table of contents
    ?>
    <div class="max-xl:hidden">
        <div class="sticky h-full top-(--header-h) max-h-[calc(100dvh-var(--header-h))] overflow-y-auto">
            <?php snippet('wiki/sidebar-right') ?>
        </div>
    </div>

</div>

<?php endsnippet() ?>
