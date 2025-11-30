<?php

namespace dvll\Sitepackage\Models;

use dvll\Sitepackage\Helpers\UuidSelectFieldHelper;
use dvll\Sitepackage\Models\TeaserContentHelper;
use dvll\Sitepackage\Models\WithTeaserContentInterface;
use Kirby\Cms\File;
use Kirby\Cms\Page;

class CustomBasePage extends Page implements WithTeaserContentInterface
{
    public function myStageType(): ?string
    {
        return TeaserContentHelper::getStageType($this);
    }

    public function myTeaserImage(): ?File
    {
        return TeaserContentHelper::getTeaserImage($this);
    }

    public function myTitle(): ?string
    {
        return TeaserContentHelper::getTitle($this);
    }

    public function myTeaserText(): ?string
    {
        return TeaserContentHelper::getTeaserText($this);
    }

    /**
     * @return \Kirby\Cms\Collection<\Kirby\Content\Field>|null
     */
    public function myContacts(): ?\Kirby\Cms\Collection
    {
        /** @var \Kirby\Content\Field $contactsField */
        $contactsField = $this->content()->get('contactsSelect');
        return UuidSelectFieldHelper::getCollectionForUuids(site()->contacts(), $contactsField, 'name');
    }

    /**
     * @return array<string, bool>
     */
    public function getContactsDisplayInLayoutOptions(): array
    {
        /** @var \Kirby\Content\Field $contactsField */
        $contactsField = $this->content()->get('contactsSelect');
        /** @var \Kirby\Content\Field $showContactOptions */
        $showContactOptions = $this->content()->get('showContactOptions');

        if ($showContactOptions->isEmpty() && $contactsField->isNotEmpty()) {
            return [
                'show' => true,
                'showPartGeneral' => true,
            ]; // Default to true if not set and contacts are available
        }
        if ($showContactOptions->isEmpty()) {
            return [
                'show' => false,
                'showPartGeneral' => false,
            ]; // Default to false if not set and no contacts
        }
        if ($showContactOptions->value() === 'always'
         || ($showContactOptions->value() === 'default' && $contactsField->isNotEmpty())
        ) {
            return [
                'show' => true,
                'showPartGeneral' => true,
            ];
        }
        if ($showContactOptions->value() === 'selected' && $contactsField->isNotEmpty()) {
            return [
                'show' => true,
                'showPartGeneral' => false,
            ];
        }
        return [
            'show' => false,
            'showPartGeneral' => false,
        ];
    }

    public function metaDefaults(?string $lang = null): array
    {
        return [
            // you can use field names (from blueprint)
            'metaDescription' => $this->myTeaserText(),
            'title' => $this->myTitle(),
        ];
    }
}
