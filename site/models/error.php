<?php

use dvll\Sitepackage\Models\TeaserContentHelper;
use dvll\Sitepackage\Models\WithTeaserContentInterface;
use Kirby\Cms\File;
use Kirby\Cms\Page;
use Kirby\Template\Template;

class ErrorPage extends Page implements WithTeaserContentInterface
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

    #[\Override]
    public function template(): Template
    {
        return $this->kirby()->template('layout');
    }
}
