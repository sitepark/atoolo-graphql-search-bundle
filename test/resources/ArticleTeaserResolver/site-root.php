<?php

declare(strict_types=1);

use Atoolo\Resource\Resource;

return Resource::create([
    'url' => '/site-root.php',
    'id' => 'site-root',
    'name' => 'site-root',
    'locale' => 'en_US',
    'home' => true,
    'base' => [
        'kicker' => 'Site-Root-Kicker',
    ],
]);
