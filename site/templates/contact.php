<?php

/**
 * @var Kirby\Cms\App $kirby
 * @var Kirby\Cms\Page $page
 * @var Kirby\Cms\Site $site
 */

/** @var \Kirby\Content\Field $contacts */
$contacts = $page->myContacts();

snippet('base', slots: true); ?>

<?php snippet('core/stage'); ?>

<?php snippet('core/layouts'); ?>
<section class="dvll-section">
    <div class="dvll-section__layout">
        <div class="dvll-block dvll-block--narrow flex items-center justify-center">
            <?php if ($contacts && $contacts->isNotEmpty()):
                $first = $contacts->first();
                /** @var \Kirby\Cms\File|null $image */
                $image = $first->photo()->isNotEmpty() ? $first->photo()->toFile() : null;
            ?>
                <div class="flex flex-col sm:flex-row gap-6 sm:gap-12 items-start">
                    <?php if ($image): ?>
                        <img
                            class="rounded-full w-[220px] h-[220px] object-cover"
                            alt="Profilbild von <?= $first->name()->escape() ?>"
                            src="<?= $image->thumb(['width' => 440, 'height' => 440, 'crop' => true])->url() ?>"
                            srcset="<?= $image->srcset('profilePicture') ?>"
                            width="220" height="220">
                    <?php else: ?>
                        <div class="rounded-full w-[220px] h-[220px] bg-gray-100 flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-16 h-16 text-gray-400">
                                <circle cx="12" cy="8" r="4" />
                                <path d="M12 14c-4.418 0-8 2.239-8 5v1h16v-1c0-2.761-3.582-5-8-5z" />
                            </svg>
                        </div>
                    <?php endif; ?>

                    <div class="flex-1 self-center sm:self-center">
                        <h2 class="heading-h2"><?= $first->name()->html() ?></h2>
                        <?php if ($first->subject()->isNotEmpty()): ?>
                            <p class="typo italic mb-2"><?= $first->subject()->html() ?></p>
                        <?php endif; ?>
                        <div class="flex flex-col gap-2">
                            <?php if ($first->email()->isNotEmpty()): ?>
                                <p class="text-base text-contrast flex flex-col">
                                    <span class="text-sm">E-Mail:</span>
                                    <a class="flex gap-1 items-center" href="mailto:<?= Str::encode($first->email()->html()) ?>" title="<?= Str::encode($first->email()->html()) ?>"><?= snippet('elements/icon', ['icon' => 'email', 'class' => 'size-4 shrink-0']) ?> <span class="dvll-link"><?= Str::encode($first->email()->html()) ?></span></a></p>
                            <?php endif ?>
                            <?php if ($first->phone()->isNotEmpty()): ?>
                                <p class="text-base text-contrast flex flex-col">
                                    <span class="text-sm">Tel.:</span>
                                    <a class="flex gap-1 text-base text-contrast py-0.5 text-center" href="tel:<?= Str::encode($first->phone()->html()) ?>"><?= snippet('elements/icon', ['icon' => 'mobilephone', 'class' => 'w-4 pt-1 shrink-0']) ?> <span class="dvll-link"><?= Str::encode($first->phone()->html()) ?></span></a>
                                </p>
                            <?php endif ?>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <div class="rounded-md bg-offwhite p-6">Keine Ansprechpartner vorhanden.</div>
            <?php endif; ?>
        </div>
    </div>
</section>
<section class="dvll-section">
    <div class="dvll-section__layout">
        <div class="dvll-block dvll-block--wide mb-6">
            <?php if ($success): ?>
                <div class="rounded-md bg-green-50 border border-green-100 p-4">
                    <p class="text-sm text-green-800"><?= $success ?></p>
                </div>
            <?php else: ?>
                <?php if (isset($alert['error'])): ?>
                    <div class="rounded-md bg-red-50 border border-red-100 p-4 mb-4">
                        <p class="text-sm text-tertiary"><?= $alert['error'] ?></p>
                    </div>
                <?php endif ?>

                <form method="post" action="<?= $page->url() ?>" class="space-y-6 bg-white p-6 rounded-lg shadow-sm" novalidate>
                    <p class="typo">Für eine möglichst einfache Kontaktaufnahme, schreibe uns Dein Anliegen gerne über folgendes Formular:</p>
                    <div class="sr-only">
                        <label for="contact-form-website">Website <abbr title="required">*</abbr></label>
                        <input type="url" id="contact-form-website" name="website" tabindex="-1" autocomplete="off">
                    </div>

                    <div>
                        <label for="contact-form-name" class="block text-sm font-semibold text-gray-700">
                            Name <abbr title="Erforderliches Feld" class="text-gray-500">*</abbr>
                        </label>
                        <?php $nameErr = $alert['name'] ?? null; ?>
                        <input
                            type="text"
                            id="contact-form-name"
                            name="name"
                            value="<?= esc($data['name'] ?? '', 'attr') ?>"
                            required
                            class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 bg-white text-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[var(--color-primary)] focus:border-[var(--color-primary)]"
                            aria-invalid="contact-form-<?= $nameErr ? 'true' : 'false' ?>"
                            <?= $nameErr ? 'aria-describedby="name-error"' : '' ?>>
                        <?php if ($nameErr): ?>
                            <p id="contact-form-name-error" class="mt-1 text-sm text-tertiary" role="alert"><?= esc($nameErr) ?></p>
                        <?php endif ?>
                    </div>

                    <div>
                        <label for="contact-form-email" class="block text-sm font-semibold text-gray-700">
                            Email <abbr title="Erforderliches Feld" class="text-gray-500">*</abbr>
                        </label>
                        <?php $emailErr = $alert['email'] ?? null; ?>
                        <input
                            type="email"
                            id="contact-form-email"
                            name="email"
                            value="<?= esc($data['email'] ?? '', 'attr') ?>"
                            required
                            class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 bg-white text-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[var(--color-primary)] focus:border-[var(--color-primary)]"
                            aria-invalid="contact-form-<?= $emailErr ? 'true' : 'false' ?>"
                            <?= $emailErr ? 'aria-describedby="email-error"' : '' ?>>
                        <?php if ($emailErr): ?>
                            <p id="contact-form-email-error" class="mt-1 text-sm text-tertiary" role="alert"><?= esc($emailErr) ?></p>
                        <?php endif ?>
                    </div>

                    <div>
                        <label for="contact-form-text" class="block text-sm font-semibold text-gray-700">
                            Nachricht <abbr title="Erforderliches Feld" class="text-gray-500">*</abbr>
                        </label>
                        <?php $textErr = $alert['text'] ?? null; ?>
                        <textarea
                            id="contact-form-text"
                            name="text"
                            rows="6"
                            required
                            class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 bg-white text-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[var(--color-primary)] focus:border-[var(--color-primary)] resize-vertical"
                            aria-invalid="contact-form-<?= $textErr ? 'true' : 'false' ?>"
                            <?= $textErr ? 'aria-describedby="text-error"' : '' ?>><?= esc($data['text'] ?? '') ?></textarea>
                        <?php if ($textErr): ?>
                            <p id="contact-form-text-error" class="mt-1 text-sm text-tertiary" role="alert"><?= esc($textErr) ?></p>
                        <?php endif ?>
                    </div>
                    <p class="font-body text-sm text-contrast">
                        Mit dem Absenden des Kontaktformulars erklären Sie sich damit einverstanden, dass Ihre angegebenen Daten zur Bearbeitung Ihrer Anfrage verarbeitet und gespeichert werden.
                        Weitere Informationen zum Umgang mit Ihren personenbezogenen Daten finden Sie auf unserer Seite zum <a class="dvll-link dvll-link--small" href="<?= site()->find('datenschutz')->url() ?>">Datenschutz.</a>
                    </p>

                    <div class="flex items-center justify-start">
                        <input type="submit" name="submit" value="Akzeptieren und Anfrage absenden" class="btn btn--primary">
                    </div>
                </form>
            <?php endif ?>
        </div>
    </div>
</section>

<?php endsnippet() ?>
