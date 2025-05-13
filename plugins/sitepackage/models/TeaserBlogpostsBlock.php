<?php

/**
 * @var \Kirby\Cms\Site $site
 */

namespace dvll\Sitepackage\Models;

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
        /** @var \Kirby\Content\Field $tags */
        $tags = $this->content()->get('tags');
        $source = $this->content()->get('source');

        if (!$blogPage) {
            return Pages::factory([]);
        }

        if ($source->isEmpty() || $source->toString() === 'newest' || $tags->isEmpty()) {
            return $blogPage->children()->listed()->sortBy('date', 'desc');
        }

        return $blogPage->children()
            ->listed()
            ->filter(function ($blogPage) use ($tags) {
                return array_intersect($blogPage->tags()->split(','), $tags->split(','));
            })
            ->sortBy('date', 'desc')
            ->limit(3);
    }
}
