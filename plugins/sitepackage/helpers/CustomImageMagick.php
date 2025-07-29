<?php

namespace dvll\Sitepackage\Helpers;

class CustomImageMagick extends \Kirby\Image\Darkroom\ImageMagick
{

    /**
     * Applies the correct settings for grayscale images
     * @param array<string, mixed> $options
     */
    #[\Override]
    protected function grayscale(string $file, array $options): string|null
    {
        // workaround to manipulate parent in a minimal way
        if ($options['grayscale'] === true) {
            return '-colorspace gray';
        }

        if ($options['brighten'] === true) {
            return '-evaluate Multiply 1.2';
        }

        return null;
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
