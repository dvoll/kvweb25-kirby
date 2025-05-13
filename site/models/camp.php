<?php

use dvll\Sitepackage\Models\ContactHelper;
use dvll\Sitepackage\Models\TeaserContentHelper;
use dvll\Sitepackage\Models\WithTeaserContentInterface;
use Kirby\Cms\File;
use Kirby\Cms\Page;

class CampPage extends Page implements WithTeaserContentInterface
{
    public function myStageType(): ?string
    {
        return null;
    }

    public function myTeaserImage(): ?File
    {
        return $this->content()->get('heroImage')->toFile();
    }

    public function myTitle(): ?string
    {
        return $this->content()->get('teaserTitle')->toHtml();
    }

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

}
