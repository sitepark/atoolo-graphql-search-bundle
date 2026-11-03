<?php

declare(strict_types=1);

use Atoolo\Resource\Resource;

return Resource::create([
    'url' => '/site-section.php',
    'id' => 'site-section',
    'name' => 'site-section',
    'locale' => 'en_US',
    'base' => [
        'kicker' => 'Site-Section-Kicker',
        'trees' => [
            'navigation' => [
                'parents' => [
                    'site-root' => [
                        'id' => 'site-root',
                        'url' => '/site-root.php',
                    ],
                ],
            ],
        ],
    ],
]);
