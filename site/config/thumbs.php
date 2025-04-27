<?php

use dvll\Sitepackage\Helper;

return [
    'driver' => Helper::getEnv('IMAGE_DRIVER', 'gd'),
    'srcsets' => [
        'default' => [
            '400w'  => ['width' => 400],
            '600w'  => ['width' => 600],
            '900w'  => ['width' => 900],
            '1024w'  => ['width' => 1024],
            '1280w'  => ['width' => 1280],
            '1920w'  => ['width' => 1920],
        ],
        'teaser' => [
            '300w'  => ['width' => 300, 'height' => 300, 'crop' => true],
            '600w'  => ['width' => 600, 'height' => 600, 'crop' => true],
            '900w'  => ['width' => 900, 'height' => 900, 'crop' => true],
            '1200w' => ['width' => 1200, 'height' => 1200, 'crop' => true],
        ],
        // Currently not in use:
        'teaser4to3' => [
            '300w'  => ['width' => 300, 'height' => 225, 'crop' => true],
            '600w'  => ['width' => 600, 'height' => 450, 'crop' => true],
            '900w'  => ['width' => 900, 'height' => 675, 'crop' => true],
            '1200w' => ['width' => 1200, 'height' => 900, 'crop' => true],
            '1800w' => ['width' => 1800, 'height' => 1350, 'crop' => true]
        ],
    ]
];
