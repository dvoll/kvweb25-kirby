<?php

use Kirby\Cms\Page;
use Kirby\Content\Field;

class ImagesPage extends Page
{
    /**
     * Override the page title to be static
     * to the template name
     */
    #[\Override]
    public function title(): Field
    {
        return new Field($this, 'title', 'Bilder');
    }
}
