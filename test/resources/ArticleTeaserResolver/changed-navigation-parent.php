<?php

declare(strict_types=1);

use Atoolo\Resource\Resource;

return Resource::create([
    'url' => '/changed-navigation-parent.php',
    'id' => 'changed-navigation-parent',
    'name' => 'changed-navigation-parent',
    'locale' => 'en_US',
    'base' => [
        'kicker' => 'Changed-Navigation-Parent-Kicker',
    ],
]);
