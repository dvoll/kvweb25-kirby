<?php

use dvll\Sitepackage\Helpers\Helper;

return [
    'driver' => Helper::getEnv('IMAGE_DRIVER', 'gd'),
    'srcsets' => [
        'default' => [
            '400w'  => ['width' => 400, 'quality' => 80],
            '600w'  => ['width' => 600, 'quality' => 80],
            '900w'  => ['width' => 900, 'quality' => 80],
            '1024w'  => ['width' => 1024, 'quality' => 80],
            '1280w'  => ['width' => 1280, 'quality' => 80],
            '1920w'  => ['width' => 1920, 'quality' => 80],
        ],
        'teaser' => [
            '200w'  => ['width' => 200, 'height' => 200, 'crop' => true, 'quality' => 60],
            '300w'  => ['width' => 300, 'height' => 300, 'crop' => true, 'quality' => 60],
            '600w'  => ['width' => 600, 'height' => 600, 'crop' => true, 'quality' => 60],
            '900w'  => ['width' => 900, 'height' => 900, 'crop' => true, 'quality' => 60],
            '1200w' => ['width' => 1200, 'height' => 1200, 'crop' => true, 'quality' => 60],
        ],
        'profilePicture' => [
            '1x'  => ['width' => 75, 'height' => 75, 'crop' => true, 'quality' => 50],
            '2x'  => ['width' => 150, 'height' => 150, 'crop' => true, 'quality' => 50],
            '3x'  => ['width' => 225, 'height' => 225, 'crop' => true, 'quality' => 50],
        ],
        'profilePictureBigger' => [
            '1x'  => ['width' => 100, 'height' => 100, 'crop' => true, 'quality' => 50],
            '2x'  => ['width' => 200, 'height' => 200, 'crop' => true, 'quality' => 50],
            '3x'  => ['width' => 300, 'height' => 300, 'crop' => true, 'quality' => 50],
        ],
        'campLogo' => [
            '1x'  => ['width' => 420],
            '2x'  => ['width' => 840],
            '3x'  => ['width' => 1260],
        ],
        'stageHero' => [
            '300w'  => ['width' => 300, 'height' => 400, 'crop' => true, 'quality' => 60],
            '600w'  => ['width' => 500, 'height' => 400, 'crop' => true, 'quality' => 60],
            '900w'  => ['width' => 800, 'height' => 600, 'crop' => true, 'quality' => 60],
            '1200w' => ['width' => 900, 'height' => 700, 'crop' => true, 'quality' => 60],
            '1500w' => ['width' => 1200, 'height' => 750, 'crop' => true, 'quality' => 60],
            '1920w' => ['width' => 1620, 'height' => 800, 'crop' => true, 'quality' => 60],
        ],
        'stageWelcomeBrighten' => [
            '50w'  => ['width' => 50, 'height' => 50, 'crop' => true, 'brighten' => true, 'quality' => 60],
            '90w'  => ['width' => 90, 'height' => 90, 'crop' => true, 'brighten' => true, 'quality' => 60],
            '150w'  => ['width' => 145, 'height' => 145, 'crop' => true, 'brighten' => true, 'quality' => 60],
            '290w'  => ['width' => 290, 'height' => 290, 'crop' => true, 'brighten' => true, 'quality' => 60],
            '400w'  => ['width' => 435, 'height' => 435, 'crop' => true, 'brighten' => true, 'quality' => 60],
        ],
        'stageWelcome' => [
            '50w'  => ['width' => 50, 'height' => 50, 'crop' => true, 'quality' => 60],
            '90w'  => ['width' => 90, 'height' => 90, 'crop' => true, 'quality' => 60],
            '150w'  => ['width' => 145, 'height' => 145, 'crop' => true, 'quality' => 60],
            '290w'  => ['width' => 290, 'height' => 290, 'crop' => true, 'quality' => 60],
            '400w'  => ['width' => 435, 'height' => 435, 'crop' => true, 'quality' => 60],
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
