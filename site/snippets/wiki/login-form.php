<?php

/** @var Kirby\Cms\Page $page */
/** @var string|null $guestLoginError */
?>

<main id="main" class="min-h-[calc(100dvh-var(--header-h))] pt-(--header-h) grid place-items-center px-4 py-8 md:px-6">
    <section class="w-full max-w-[40rem] rounded-lg bg-white p-6 md:p-8 shadow-md border border-gray-200">
        <div class="space-y-2">
            <p class="text-sm font-semibold text-gray-600 uppercase">Geschuetzter Bereich</p>
            <h1 class="heading-h2">Wiki-Zugang</h1>
            <p class="typo text-gray-700">Diese Wiki-Seite ist geschuetzt. Bitte geben Sie das Gast-Passwort ein, um fortzufahren.</p>
        </div>

        <?php if ($guestLoginError): ?>
            <div class="mt-6 rounded-md border border-tertiary bg-offwhite px-4 py-3 text-sm font-semibold text-tertiary">
                <?= esc($guestLoginError) ?>
            </div>
        <?php endif ?>

        <form method="post" action="<?= $page->url() ?>" class="mt-6 space-y-6">
            <input type="hidden" name="csrf" value="<?= csrf() ?>">
            <input type="hidden" name="wiki_guest_login" value="1">

            <div class="space-y-2">
                <label for="wiki-password" class="font-style font-semibold text-sm">Passwort</label>
                <input
                    id="wiki-password"
                    name="password"
                    type="password"
                    class="w-full rounded-md border border-gray-300 bg-white px-4 py-3 text-base focus:outline-none focus:border-[var(--color-primary)] focus:ring-2 focus:ring-[var(--color-primary)]"
                    autocomplete="current-password"
                    required
                    autofocus
                >
            </div>

            <div class="flex flex-wrap items-center gap-3">
                <button type="submit" class="btn btn--primary btn--medium">Wiki entsperren</button>
                <a href="<?= $page->site()->url() ?>" class="btn btn--ghost">Zur Hauptseite</a>
            </div>
        </form>
    </section>
</main>
