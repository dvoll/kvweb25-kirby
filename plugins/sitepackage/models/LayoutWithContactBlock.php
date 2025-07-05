<?php

/**
 * @var \Kirby\Cms\Site $site
 */

namespace dvll\Sitepackage\Models;

use dvll\Sitepackage\Helpers\UuidSelectFieldHelper;
use Kirby\Cms\Block;

class LayoutWithContactBlock extends Block
{
    /**
     * @return \Kirby\Cms\Collection<\Kirby\Content\Field>|null
     */
    public function myContacts(): ?\Kirby\Cms\Collection
    {
        /** @var \Kirby\Content\Field $contactsField */
        $contactsField = $this->content()->get('contactsSelect');
        return UuidSelectFieldHelper::getCollectionForUuids(site()->contacts(), $contactsField, 'name');
    }
}
