<?php

/**
 * @var \Kirby\Cms\Site $site
 */

namespace dvll\Sitepackage\Models;

use Kirby\Cms\Block;
use Kirby\Toolkit\A;

class LayoutWithContactBlock extends Block
{
    /**
    * @return array<string, mixed>
     */
    public function myContacts(): array
    {
        /** @var \Kirby\Content\Field $contactsField */
        $contactsField = $this->content()->get('contactsSelect');
        return ContactHelper::getContacts($contactsField);
    }
}
