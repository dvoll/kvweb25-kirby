<?php

namespace dvll\Sitepackage\Helpers;

class ContactsFieldHelper
{
    /**
     *
     * @param \Kirby\Content\Field $contactSelectField
     * @return \Kirby\Cms\Collection<\Kirby\Content\Field>|null
     */
    public static function getContacts(\Kirby\Content\Field $contactSelectField): ?\Kirby\Cms\Collection
    {
        if ($contactSelectField->isEmpty()) {
            return null;
        }
        $contacts = $contactSelectField->toEntries()->map(function ($contact) {
            $contactStructure = site()->contacts()->toStructure()->findBy('customuuid', $contact->value());
            if (!$contactStructure || $contactStructure->name()->isEmpty()) {
                return null;
            }
            return $contactStructure;
        });
        return $contacts;
    }
}
