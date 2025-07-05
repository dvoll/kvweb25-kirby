<?php

use dvll\Sitepackage\Helpers\ContactsFieldHelper;
use dvll\Sitepackage\Models\CustomBasePage;
use Kirby\Cms\Blocks;
use Kirby\Cms\File;
use Kirby\Cms\Files;

class BlogpostPage extends CustomBasePage
{
    /**
     * @return \Kirby\Cms\Collection<\Kirby\Content\Field>|null
     */
    public function myContacts(): ?\Kirby\Cms\Collection
    {
        /** @var \Kirby\Content\Field $contactsField */
        $contactsField = $this->content()->get('contactsSelect');
        return ContactsFieldHelper::getContacts($contactsField);
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

    public function getContentImage(): ?File
    {
        return $this->content()->get('image')->toFile();
    }
}
