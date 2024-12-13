<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Input;

use Overblog\GraphQLBundle\Annotation as GQL;

/**
 * @codeCoverageIgnore
 */
#[GQL\Input(name: "InputBoosting")]
class InputBoosting
{
    /**
     * List of fields and the "boosts" to associate with each of them when
     * building DisjunctionMaxQueries from the user's query.
     * @var ?array<string>
     */
    #[GQL\Field(type: "[String!]")]
    public ?array $queryFields = null;

    /**
     * This param can be used to "boost" the score of documents in cases
     * where all of the terms in the "q" param appear in close proximity.
     * @var ?array<string>
     */
    #[GQL\Field(type: "[String!]")]
    public ?array $phraseFields = null;

    /**
     * an additional, query clause that will be added to the main query to
     * influence the score.
     * @var ?array<string>
     */
    #[GQL\Field(type: "[String!]")]
    public ?array $boostQueries = null;

    /**
     * Functions (with optional boosts) that will be included in the query
     * to influence the score.
     * @var ?array<string>
     */
    #[GQL\Field(type: "[String!]")]
    public ?array $boostFunctions = null;

    /**
     * The tie parameter is used to control how much the score of the
     * non-phrase query should influence the score of the phrase query.
     */
    #[GQL\Field(type: "Float")]
    public ?float $tie = 0.0;
}
