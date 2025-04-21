<?php

use Kirby\Cms\File;
use Kirby\Cms\Page;

class DefaultPage extends Page
{
    /**
     * This method is now available for all pages
     * unless they have their own page model.
     */
    public function myTeaserImage(): ?File
    {
        $stage = $this->getStageBlock();
        if ($stage === null) {
            return null;
        }
        $image = $stage->content()->get('image')->toFile();

        return $image;
    }

    public function myTitle(): ?string
    {
        $stage = $this->getStageBlock();
        if ($stage === null) {
            return $this->title();
        }
        $blockTitle = $stage->content()->get('title');
        if ($blockTitle->isEmpty()) {
            return $this->title();
        }
        return $stage->content()->get('title')->escape();
    }

    public function myTeaserText(): ?string
    {
        $stage = $this->getStageBlock();
        if ($stage === null) {
            return null;
        }
        return $stage->content()->get('description')->escape();
    }

    private function getStageBlock(): ?Kirby\Cms\Block
    {
        /** @var Kirby\Content\Field */
        $stage = $this->content()->get('stage');
        if (!$stage->exists() || $stage->isEmpty()) {
            return null;
        }
        return $stage->toBlocks()->first();
    }
}
