<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Query;

use Atoolo\GraphQL\Search\Input\SuggestInput;
use Atoolo\GraphQL\Search\Service\SolrInputGuard;
use Atoolo\Search\Dto\Search\Result\SuggestResult;
use Exception;
use Overblog\GraphQLBundle\Annotation as GQL;
use Overblog\GraphQLBundle\Error\UserError;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

#[GQL\Provider]
class Suggest
{
    private readonly SuggestQueryFactory $factory;

    private readonly SolrInputGuard $inputGuard;

    public function __construct(
        private readonly \Atoolo\Search\Suggest $suggest,
        private readonly LoggerInterface $logger = new NullLogger(),
    ) {
        $this->factory = new SuggestQueryFactory();
        $this->inputGuard = new SolrInputGuard();
    }

    #[GQL\Query(name: 'suggest', type: 'SuggestResult!')]
    public function suggest(SuggestInput $input): SuggestResult
    {
        $this->inputGuard->assertSafe($input);
        $query = $this->factory->create($input);
        try {
            return $this->suggest->suggest($query);
        } catch (Exception $e) {
            $this->logger->error(
                'suggest failed: ' . $e->getMessage(),
                ['exception' => $e],
            );
            throw new UserError(Search::ERROR_MESSAGE, 0, $e);
        }
    }
}
