<?php

/**
 * @var \Kirby\Cms\Site $site
 */

namespace dvll\Sitepackage\Models;

use dvll\Sitepackage\Helpers\UuidSelectFieldHelper;
use Kirby\Cms\Block;
use Kirby\Cms\Pages;

class TeaserBlogpostsBlock extends Block
{
    /**
     * @return \Kirby\Cms\Pages
     */
    public function myBlogposts(): Pages
    {
        $blogPage = kirby()->site()->find('blog');
        /** @var \Kirby\Content\Field $tagField */
        $tagField = $this->content()->get('tags');
        $source = $this->content()->get('source');

        if (!$blogPage) {
            return Pages::factory([]);
        }

        if ($source->isEmpty() || $source->toString() === 'newest' || $tagField->isEmpty()) {
            return $blogPage->children()->listed()->sortBy('date', 'desc');
        }

        return $blogPage->children()
            ->listed()
            ->filter(function ($blogPage) use ($tagField) {
                return array_intersect($blogPage->tags()->yaml(), $tagField->split());
                // return
            })
            ->sortBy('date', 'desc')
            ->limit(3);
    }
}
