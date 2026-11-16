<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Query;

use Atoolo\GraphQL\Search\Input\MoreLikeThisInput;
use Atoolo\GraphQL\Search\Query\Context\ContextDispatcher;
use Atoolo\GraphQL\Search\Service\SolrInputGuard;
use Atoolo\Search\Dto\Search\Result\SearchResult;
use Exception;
use Overblog\GraphQLBundle\Annotation as GQL;
use Overblog\GraphQLBundle\Error\UserError;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

#[GQL\Provider]
class MoreLikeThis
{
    private readonly MoreLikeThisQueryFactory $factory;

    private readonly SolrInputGuard $inputGuard;

    public function __construct(
        private readonly \Atoolo\Search\MoreLikeThis $moreLikeThis,
        private readonly ContextDispatcher $contextDispatcher,
        private readonly LoggerInterface $logger = new NullLogger(),
    ) {
        $this->factory = new MoreLikeThisQueryFactory();
        $this->inputGuard = new SolrInputGuard();
    }

    #[GQL\Query(name: 'moreLikeThis', type: 'SearchResult!')]
    public function moreLikeThis(MoreLikeThisInput $input): SearchResult
    {
        $this->inputGuard->assertSafe($input);
        if ($input->context !== null) {
            $this->contextDispatcher->dispatch($input->context);
        }
        $query = $this->factory->create($input);
        try {
            return $this->moreLikeThis->moreLikeThis($query);
        } catch (Exception $e) {
            $this->logger->error(
                'moreLikeThis failed: ' . $e->getMessage(),
                ['exception' => $e],
            );
            throw new UserError(Search::ERROR_MESSAGE, 0, $e);
        }
    }
}
