<?php

declare(strict_types=1);


use Atoolo\Resource\Resource;

return Resource::create([
    'url' => '/parent.php',
    'id' => 'parent',
    'name' => 'parent',
    'locale' => 'en_US',
    'base' => [
        'kicker' => 'Parent-Kicker',
    ],
]);
