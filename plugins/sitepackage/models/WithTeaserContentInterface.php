<?php

namespace dvll\Sitepackage\Models;

use Kirby\Cms\File;

interface WithTeaserContentInterface
{
    public function myTeaserImage(): ?File;
    public function myTitle(): ?string;
    public function myTeaserText(): ?string;
    public function myStageType(): ?string;
}
