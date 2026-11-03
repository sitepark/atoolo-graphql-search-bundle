<?php

declare(strict_types=1);

use Atoolo\Resource\Resource;

return Resource::create([
    'url' => '/site-section-page.php',
    'id' => 'site-section-page',
    'name' => 'site-section-page',
    'locale' => 'en_US',
    'base' => [
        'trees' => [
            'navigation' => [
                'parents' => [
                    'site-section' => [
                        'id' => 'site-section',
                        'url' => '/site-section.php',
                    ],
                ],
            ],
        ],
    ],
]);
