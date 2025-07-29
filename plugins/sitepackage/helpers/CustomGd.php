<?php

namespace dvll\Sitepackage\Helpers;

use claviska\SimpleImage;

class CustomGd extends \Kirby\Image\Darkroom\GdLib
{

    /**
     * Applies the correct settings for grayscale images
     * @param array<string, mixed> $options
     */
    #[\Override]
    protected function grayscale(SimpleImage $image, array $options): SimpleImage
    {
        if ($options['grayscale'] === true) {
            return $image->desaturate();
            // return $image->brighten(80);
        }

        // workaround to manipulate parent in a minimal way
        if ($options['brighten'] === true) {
            return $image->colorize([30, 30, 30])->brighten(5)->contrast((int) -10);
        }

        return $image;
    }

    /**
     * Returns the default thumb settings
     * @return array<string, mixed>
     */
    #[\Override]
    protected function defaults(): array
    {
        $options = parent::defaults();
        $options['brighten'] = null;
        return $options;
    }
}
