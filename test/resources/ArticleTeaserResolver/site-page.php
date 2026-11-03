<?php

declare(strict_types=1);

use Atoolo\Resource\Resource;

return Resource::create([
    'url' => '/site-page.php',
    'id' => 'site-page',
    'name' => 'site-page',
    'locale' => 'en_US',
    'base' => [
        'kicker' => 'Site-Page-Kicker',
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
