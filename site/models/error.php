<?php

use dvll\Sitepackage\Models\CustomBasePage;
use Kirby\Template\Template;

class ErrorPage extends CustomBasePage
{
    #[Override]
    public function template(): Template
    {
        return $this->kirby()->template('layout');
    }
}
