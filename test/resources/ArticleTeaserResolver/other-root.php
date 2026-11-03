<?php

declare(strict_types=1);

use Atoolo\Resource\Resource;

return Resource::create([
    'url' => '/other-root.php',
    'id' => 'other-root',
    'name' => 'other-root',
    'locale' => 'en_US',
    'home' => true,
    'base' => [
        'kicker' => 'Other-Root-Kicker',
    ],
]);
