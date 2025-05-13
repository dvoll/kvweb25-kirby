<?php

namespace dvll\Sitepackage\Models;

use Kirby\Cms\Block;
use Kirby\Cms\File;
use Kirby\Cms\Page;
use Kirby\Content\Field;

class TeaserContentHelper
{
    public static function getTeaserImage(Page $page): ?File
    {
        $stage = self::getStageBlock($page);
        if ($stage->isEmpty() || !($image = $stage->content()->get('image')) instanceof Field) {
            return null;
        }
        return $image->toFile();
    }

    public static function getTitle(Page $page): ?string
    {
        $stage = self::getStageBlock($page);
        if ($stage->isEmpty()) {
            return $page->title();
        }
        /** @var Field $blockTitle */
        $blockTitle = $stage->content()->get('title');
        if ($blockTitle->isEmpty()) {
            return $page->title();
        }
        return $blockTitle->escape();
    }

    public static function getTeaserText(Page $page): ?string
    {
        $stage = self::getStageBlock($page);
        if ($stage->isEmpty() || !($description = $stage->content()->get('description')) instanceof Field) {
            return null;
        }
        return $description->escape();
    }

    public static function getStageType(Page $page): ?string
    {
        $stage = self::getStageBlock($page);
        if ($stage->isEmpty()) {
            return null;
        }
        return $stage->type();
    }

    private static function getStageBlock(Page $page): Block|Field
    {
        /** @var Field */
        $stage = $page->content()->get('stage');
        if (!$stage->exists() || $stage->isEmpty()) {
            return $stage;
        }
        return $stage->toBlocks()->first();
    }
}
