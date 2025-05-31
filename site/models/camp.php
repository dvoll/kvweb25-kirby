<?php

use dvll\Sitepackage\Models\ContactHelper;
use dvll\Sitepackage\Models\CustomBasePage;
use dvll\Sitepackage\Models\WithTeaserContentInterface;
use Kirby\Cms\Blocks;
use Kirby\Cms\File;
use Kirby\Cms\Files;
use Kirby\Cms\Page;

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
        return $this->content()->get('heroImage')->toFile();
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
        return $this->content()->get('teaserDescription')->toHtml();
    }

    /**
     * @return array<string, mixed>|null
     */
    public function myContacts(): ?array
    {
        /** @var \Kirby\Content\Field $contactsField */
        $contactsField = $this->content()->get('contactsSelect');
        return ContactHelper::getContacts($contactsField);
    }

    /**
     * Get the link blocks from the field 'links'
     * @return \Kirby\Cms\Blocks|\Kirby\Cms\Block[]|null
     */
    public function myLinks(): ?Blocks
    {
        /** @var \Kirby\Content\Field $linksField */
        $linksField = $this->content()->get('links');
        return $linksField->toBlocks();
    }

    /**
     * @return \Kirby\Cms\Files|\Kirby\Cms\File[]|null
     */
    public function myDownloads(): ?Files
    {
        /** @var \Kirby\Content\Field $downloadsField */
        $downloadsField = $this->content()->get('downloads');
        return $downloadsField->toFiles();
    }
}
