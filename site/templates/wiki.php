<?php

/**
 * Wiki template
 * Renders a fixed top nav, fixed left and right sidebars and a scrollable main content area.
 */

snippet('base-wiki', slots: true); ?>
<?php snippet('core/header-wiki') ?>

<?php // left sidebar
?>
<?php snippet('wiki/sidebar-left') ?>

<?php // right outline
?>
<?php snippet('wiki/sidebar-right') ?>

<main id="main" class="min-h-screen bg-white" role="main" style="--header-h:4rem; --left-w:18rem; --right-w:16rem;">
    <div class="pt-[var(--header-h)]">
        <div class="max-w-[96rem] mx-auto">
            <div class="relative">
                <article class="prose mx-auto max-w-none pl-[calc(var(--left-w)+1rem)] pr-[calc(var(--right-w)+1rem)]">
              <?php
                // Use the core layouts snippet to render blocks and preserve custom block layouts
                snippet('core/layouts');
              ?>
                </article>
            </div>
        </div>
    </div>
</main>

<?php endsnippet() ?>
