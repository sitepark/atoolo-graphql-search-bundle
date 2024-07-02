<?php

declare(strict_types=1);

use Atoolo\GraphQL\Search\Test\TestResourceFactory;

return TestResourceFactory::create([
    'url' => '/parent.php',
    'id' => 'parent',
    'name' => 'parent',
    'locale' => 'en_US',
    'base' => [
        'kicker' => 'Parent-Kicker',
    ],
]);
