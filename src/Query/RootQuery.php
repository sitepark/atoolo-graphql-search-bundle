<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Query;

use Overblog\GraphQLBundle\Annotation as GQL;

/**
 * TODO: This should be a general Root-Query for all GraphQL extensions and
 * should not be in this search bundle. However, there is no general Atoolo
 * GraphQL bundle yet and is therefore stored here for the time being.
 */
#[GQL\Type]
class RootQuery {}
