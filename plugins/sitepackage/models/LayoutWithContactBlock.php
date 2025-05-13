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
        if($contactsField->isEmpty()) {
            return [];
        }
        $contactIds = $contactsField->split(',');
        $contacts = A::map($contactIds, function ($contactId) {
            $contactStructure = site()->contacts()->toStructure()->findBy('customuuid', $contactId);
            if (!$contactStructure || $contactStructure->contact()->isEmpty()) {
                return null;
            }
            return $contactStructure;
        });
        return array_filter($contacts, fn($contact) => $contact !== null);
    }
}
