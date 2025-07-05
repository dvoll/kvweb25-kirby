<?php

namespace dvll\Sitepackage\Helpers;

use Kirby\Cms\Collection;
use Kirby\Toolkit\A;

class UuidSelectFieldHelper
{
    /**
     *
     * @param \Kirby\Content\Field $sourceStructureField
     * @param \Kirby\Content\Field $entriesFieldWithUuids
     * @param string|null $fieldToCheckNotEmpty
     * @param boolean $selectionIsEntryField
     * @return Collection<\Kirby\Content\Field>|null
     */
    public static function getCollectionForUuids(\Kirby\Content\Field $sourceStructureField, \Kirby\Content\Field $entriesFieldWithUuids, ?string $fieldToCheckNotEmpty = null, bool $selectionIsEntryField = true): ?Collection
    {
        if ($entriesFieldWithUuids->isEmpty()) {
            return null;
        }

        if ($selectionIsEntryField) {
            $entriesFieldWithUuids = $entriesFieldWithUuids->yaml();
        } else {
            $entriesFieldWithUuids = $entriesFieldWithUuids->split();
        }
        $resultArray = A::map($entriesFieldWithUuids, function ($entryItemUuid) use ($fieldToCheckNotEmpty, $sourceStructureField) {
            $matchedField = $sourceStructureField->toStructure()->findBy('customuuid', $entryItemUuid);
            if (!$matchedField || ($fieldToCheckNotEmpty !== null && $matchedField->content()->get($fieldToCheckNotEmpty)->isEmpty())) {
                return null;
            }
            return $matchedField;
        });
        return new Collection(A::filter($resultArray, function ($item) {
            return $item !== null;
        }));
    }
}
