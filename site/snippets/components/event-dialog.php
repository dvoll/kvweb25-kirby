<?php

/**
 * Shared event dialog template
 * Used by all event cards to display event details dynamically
 *
 * Info: Problem with alpine csp: x-if has no optimization so that "x != null && x.prop" is not working
 */

$showGoToOverviewButton = $showGoToOverviewButton ?? true;

?>
<div x-data="sharedEventDialog()">
    <dialog x-ref="eventModal"
        class="any-modal m-auto w-full max-w-[calc(100%-1rem)] md:max-w-[40rem] rounded-2xl shadow-gallery-image bg-baseline"
        x-on:close="closeModal()">

        <div class="pb-4" @click.outside="modalOpen ? closeModal() : null">
            <!-- Shared Header for all states -->
            <div class="bg-offwhite flex flex-col items-start pl-4 md:pl-12 pr-4 md:pr-5 pt-4 pb-4 gap-2 sticky top-0">
                <button x-ref="eventModalCloseButton" class="self-end btn btn--ghost hover:bg-tertiary hover:text-baseline" @click="closeModal()">
                    Schließen<?= snippet('elements/icon', ['icon' => 'external']) ?>
                </button>
                <!-- Title and location only shown when data is loaded -->
                <template x-if="eventData && !isLoading && !error">
                    <div class="w-full">
                        <h3 class="heading-h3 text-contrast" x-text="eventData.title"></h3>
                        <template x-if="eventData.location">
                            <p class="font-body text-sm text-gray-600 flex items-center gap-0.5">
                                <?= snippet('elements/icon', ['icon' => 'location', 'class' => 'size-4 shrink-0']) ?>
                                <span x-text="eventData.location"></span>
                            </p>
                        </template>
                    </div>
                </template>
            </div>

            <!-- Loading State -->
            <div x-show="isLoading" class="flex flex-col items-center justify-center p-8 min-h-[200px]">
                <div class="w-10 h-10 border-3 border-gray-300 border-t-tertiary rounded-full loading-spinner mb-4"></div>
                <p class="font-body text-sm text-contrast">Termindetails werden geladen...</p>
            </div>

            <!-- Error State -->
            <div x-show="error" class="flex flex-col items-center justify-center p-8 min-h-[200px] text-center">
                <div class="mb-4 text-red-500"><?= snippet('elements/icon', ['icon' => 'fact', 'class' => 'size-8']) ?></div>
                <p class="font-body text-sm text-contrast mb-4" x-text="error"></p>
                <button @click="fetchEventData()" class="btn btn--primary">Erneut versuchen</button>
            </div>

            <!-- Content State -->
            <div x-show="eventData && !isLoading && !error">

                <div class="flex flex-wrap md:flex-nowrap pl-4 md:pl-12 pr-4 md:pr-8 mt-6 gap-8">
                    <div class="flex flex-col gap-4 items-start shrink-0">
                        <!-- Date Information -->
                        <div class="font-style text-contrast text-base">
                            <template x-if="eventData != null ? eventData.isAllDay : false">
                                <div>
                                    <span class="font-semibold" x-text="eventData.startDate ? formatDate(eventData.startDate, { weekday: 'long', day: 'numeric', month: 'long' }) : ''"></span>
                                    <template x-if="eventData.hasMultipleDays">
                                        <div>
                                            <br>
                                            <span class="font-normal text-sm" x-text="eventData.endDateOnly ? 'bis ' + formatDate(eventData.endDateOnly, { weekday: 'long', day: 'numeric', month: 'long' }) : ''"></span>
                                        </div>
                                    </template>
                                </div>
                            </template>

                            <template x-if="eventData != null ? !eventData.isAllDay && eventData.startDate : false">
                                <div>
                                    <template x-if="eventData.hasMultipleDays">
                                        <div>
                                            <span class="font-semibold" x-text="eventData.startDate ? formatDate(eventData.startDate, { day: 'numeric', month: 'long', hour: '2-digit', minute: '2-digit' }) + ' Uhr' : ''"></span>
                                            <br>
                                            <span class="font-normal text-sm" x-text="eventData.endDate ? 'bis ' + formatDate(eventData.endDate, { day: 'numeric', month: 'long', hour: '2-digit', minute: '2-digit' }) + ' Uhr' : ''"></span>
                                        </div>
                                    </template>
                                    <template x-if="!eventData.hasMultipleDays">
                                        <div>
                                            <span class="font-semibold" x-text="eventData.startDate ? formatDate(eventData.startDate, { weekday: 'long', day: 'numeric', month: 'long' }) : ''"></span>
                                            <br>
                                            <span class="font-normal text-sm" x-text="eventData.startDate && eventData.endDate ? formatDate(eventData.startDate, { hour: '2-digit', minute: '2-digit' }) + ' Uhr bis ' + formatDate(eventData.endDate, { hour: '2-digit', minute: '2-digit' }) + ' Uhr' : ''"></span>
                                        </div>
                                    </template>
                                </div>
                            </template>
                        </div>

                        <!-- Tag Information -->
                        <template x-if="eventData != null ? eventData.tag : false">
                            <div>
                                <template x-if="eventData.tag.page">
                                    <a :href="eventData.tag.page.url" class="btn btn--secondary">
                                        <span x-text="eventData.tag.page.title"></span>
                                    </a>
                                </template>
                                <template x-if="!eventData.tag.page">
                                    <span class="block font-style text-sm text-contrast bg-secondary px-2 py-1 rounded-sm italic" x-text="eventData.tag.name"></span>
                                </template>
                            </div>
                        </template>
                    </div>

                    <template x-if="eventData != null">
                        <div class="w-full flex flex-col gap-4 items-start">
                            <!-- Description -->
                            <template x-if="eventData.description">
                                <p class="font-body text-base text-contrast" x-html="eventData.description"></p>
                            </template>

                            <!-- Calendar Links -->
                            <div class="flex flex-col gap-2 items-start">
                                <p class="font-style text-contrast text-sm font-semibold">Für den eigenen Kalender:</p>
                                <a :href="'/termine/' + eventData.slug + '.ics'"
                                    :download="eventData.slug + '.ics'"
                                    class="btn btn--secondary">
                                    Termin als Datei herunterladen<?= snippet('elements/icon', ['icon' => 'download']) ?>
                                </a>
                                <template x-if="eventData.calendarLinks">
                                    <template x-if="eventData.calendarLinks.google">
                                        <a :href="eventData.calendarLinks.google"
                                            target="_blank"
                                            class="btn btn--secondary">
                                            Zum Google Kalender hinzufügen<?= snippet('elements/icon', ['icon' => 'external']) ?>
                                        </a>
                                    </template>
                                    <template x-if="eventData.calendarLinks.outlook">
                                        <a :href="eventData.calendarLinks.outlook"
                                            target="_blank"
                                            class="btn btn--secondary">
                                            Zum Outlook Kalender hinzufügen<?= snippet('elements/icon', ['icon' => 'external']) ?>
                                        </a>
                                    </template>
                                </template>
                            </div>
                        </div>
                    </template>
                </div>

                <!-- Related Content -->
                <template x-if="hasBlogpostOrTags()">
                    <div>
                        <div class="mt-12 pl-4 md:pl-12 pr-4 md:pr-8">
                            <h3 class="heading-h3 text-contrast">Weitere Informationen</h3>
                        </div>
                        <div class="px-4 grid grid-cols-1 md:grid-cols-(--dvll-card-grid-cols) gap-4 md:justify-center mt-4">
                            <!-- Blogpost Cards -->
                            <template x-for="blogpost in eventData.blogposts || []" :key="blogpost.url">
                                <?= snippet('components/blogpost-card', ['dynamicContent' => true]) ?>
                            </template>

                            <!-- Tag Page Card -->
                            <template x-if="eventData.tag ? eventData.tag.page : false">
                                <?= snippet('components/teaser-card', ['dynamicContent' => true]) ?>
                            </template>
                        </div>
                    </div>
                </template>

                <?php if ($showGoToOverviewButton): ?>
                    <div class="flex flex-col px-4 mt-4">
                        <a <?= Html::attr([
                                'href' => '/termine',
                                'class' => 'btn btn--ghost self-center',
                            ]) ?>>Alle Termine ansehen<?= snippet('elements/icon') ?></a>
                    </div>
                <?php endif; ?>
            </div> <!-- End Content State -->
        </div>
    </dialog>
</div>
