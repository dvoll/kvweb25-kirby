<?php

use dvll\Sitepackage\Helpers\UuidSelectFieldHelper;
use dvll\Sitepackage\Models\CustomBasePage;
use Kirby\Cms\Blocks;
use Kirby\Cms\File;
use Kirby\Cms\Files;

class BlogpostPage extends CustomBasePage
{
    #[\Override]
    public function getContactsDisplayInLayoutOptions(): array
    {
        return [
            'show' => false,
            'showGeneral' => false,
        ];
    }

    /**
     * @return \Kirby\Cms\Collection<\Kirby\Content\Field>|null
     */
    public function selectedTags(): ?\Kirby\Cms\Collection
    {
        /** @var \Kirby\Content\Field $tagsField */
        $tagsField = $this->content()->get('tags');
        return UuidSelectFieldHelper::getCollectionForUuids(site()->tags(), $tagsField, 'name');
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

    public function getContentImage(): ?File
    {
        /** @var \Kirby\Content\Field $imageField */
        $imageField = $this->content()->get('image');
        return $imageField->toFile();
    }
}
