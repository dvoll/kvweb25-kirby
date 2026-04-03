<?php

/** @var Kirby\Cms\Page $page
 *  @var Kirby\Cms\Site $site
 *  @var Kirby\Cms\App $kirby
 */

$class = $class ?? '';
$breadcrumb = $site->breadcrumb();

if ($breadcrumb->has('wiki')) {
    // trim breadcrumb to wiki root
    $breadcrumb = $breadcrumb->offset(1);
}
?>

<?php if ($breadcrumb->count() > 1): ?>

<nav aria-label="breadcrumb" class="<?= $class ?>">
    <ol class="flex flex-wrap gap-0 text-style opacity-70">
        <?php foreach ($breadcrumb as $crumb): ?>
            <li <?php e($crumb->isActive(), 'aria-current="location"') ?> class="after:content-['›'] after:mx-2 last:after:hidden <?= $crumb->isActive() ? 'italic' : '' ?>"><a href=" <?= $crumb->url() ?>"><?= $crumb->title()->html() ?></a></li>
        <?php endforeach; ?>
    </ol>
</nav>

<?php endif; ?>
