<?php

use dvll\Sitepackage\Helpers\UuidSelectFieldHelper;
use dvll\Sitepackage\Models\CustomBasePage;
use Kirby\Cms\Blocks;
use Kirby\Cms\File;
use Kirby\Cms\Files;

class CampPage extends CustomBasePage
{
    #[\Override]
    public function myStageType(): ?string
    {
        return null;
    }

    #[\Override]
    public function myTeaserImage(): ?File
    {
        /** @var \Kirby\Content\Field $field */
        $field = $this->content()->get('heroImage');
        return $field->toFile();
    }

    #[\Override]
    public function myTitle(): ?string
    {
        $teaserTitle = $this->content()->get('teaserTitle');

        if ($teaserTitle->isNotEmpty() && $teaserTitle instanceof \Kirby\Content\Field) {
            return $teaserTitle->toHtml();
        }

        return $this->title()->toHtml();
    }

    #[\Override]
    public function myTeaserText(): ?string
    {
        /** @var \Kirby\Content\Field $field */
        $field = $this->content()->get('teaserDescription');
        return $field->toHtml();
    }
    #[\Override]
    public function shouldShowContactsInLayout(): array
    {
        return [
            'show' => false,
            'showGeneral' => false,
        ];
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
     * Get the link blocks from the field 'linksDownloads'
     * @return \Kirby\Cms\Blocks|\Kirby\Cms\Block[]|null
     */
    public function myLinksAndDownloads(): ?Blocks
    {
        /** @var \Kirby\Content\Field $linksField */
        $linksField = $this->content()->get('linksDownloads');
        return $linksField->toBlocks();
    }

    public function getLinksAndDownloadsTitle(): ?string
    {
        $blocks = $this->myLinksAndDownloads();
        $hasInternalPage = $blocks->findBy('itemtype', 'page');
        $hasExternalLink = $blocks->findBy('itemtype', 'external');
        $hasDownload = $blocks->findBy('itemtype', 'download');
        return ($hasInternalPage || $hasExternalLink) && $hasDownload
            ? 'Verlinkungen und Downloads'
            : ($hasDownload ? 'Downloads' : 'Verlinkungen');
    }
}
