<?php

declare(strict_types=1);

use Atoolo\GraphQL\Search\Test\TestResourceFactory;

return TestResourceFactory::create([
    'url' => '/changed-navigation-parent.php',
    'id' => 'changed-navigation-parent',
    'name' => 'changed-navigation-parent',
    'locale' => 'en_US',
    'base' => [
        'kicker' => 'Changed-Navigation-Parent-Kicker',
    ],
]);
