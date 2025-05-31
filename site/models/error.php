<?php

use dvll\Sitepackage\Models\CustomBasePage;
use dvll\Sitepackage\Models\TeaserContentHelper;
use dvll\Sitepackage\Models\WithTeaserContentInterface;
use Kirby\Cms\File;
use Kirby\Cms\Page;
use Kirby\Template\Template;

class ErrorPage extends CustomBasePage
{
    #[\Override]
    public function template(): Template
    {
        return $this->kirby()->template('layout');
    }
}
