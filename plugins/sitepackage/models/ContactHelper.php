<?php

namespace dvll\Sitepackage\Models;

use Kirby\Cms\Page;
use Kirby\Content\Field;
use Kirby\Toolkit\A;

class ContactHelper
{
    /**
     *
     * @param Field $contactSelectField
     * @return array<string, mixed>|null
     */
    public static function getContacts(Field $contactSelectField): ?array
    {
        if ($contactSelectField->isEmpty()) {
            return [];
        }
        $contactIds = $contactSelectField->split(',');
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
