<?php

use dvll\Sitepackage\Helpers\Helper;

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
        'profilePicture' => [
            '1x'  => ['width' => 75, 'height' => 75, 'crop' => true],
            '2x'  => ['width' => 150, 'height' => 150, 'crop' => true],
            '3x'  => ['width' => 225, 'height' => 225, 'crop' => true],
        ],
        'campLogo' => [
            '1x'  => ['width' => 420],
            '2x'  => ['width' => 840],
            '3x'  => ['width' => 1260],
        ],
        'stageHero' => [
            '300w'  => ['width' => 300, 'height' => 700, 'crop' => true],
            '600w'  => ['width' => 600, 'height' => 800, 'crop' => true],
            '900w'  => ['width' => 900, 'height' => 700, 'crop' => true],
            '1200w' => ['width' => 1200, 'height' => 900, 'crop' => true],
            '1500w' => ['width' => 1500, 'height' => 1200, 'crop' => true],
            '1920w' => ['width' => 1920, 'height' => 1200, 'crop' => true],
        ],
        'stageWelcome' => [
            '1x'  => ['width' => 145, 'height' => 145, 'crop' => true, 'brighten' => true],
            '2x'  => ['width' => 290, 'height' => 290, 'crop' => true, 'brighten' => true],
            '3x'  => ['width' => 435, 'height' => 435, 'crop' => true, 'brighten' => true],
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
