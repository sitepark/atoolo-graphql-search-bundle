<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Query;

use Atoolo\GraphQL\Search\Input\SearchInput;
use Atoolo\GraphQL\Search\Query\Context\ContextDispatcher;
use Atoolo\GraphQL\Search\Service\SolrInputGuard;
use Atoolo\Search\Dto\Search\Result\SearchResult;
use Exception;
use Overblog\GraphQLBundle\Annotation as GQL;
use Overblog\GraphQLBundle\Error\UserError;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

#[GQL\Provider]
class Search
{
    /**
     * Returned to the client instead of the exception message, which may
     * contain backend details like the complete Solr request.
     */
    public const ERROR_MESSAGE = 'Search request failed';

    private readonly SearchQueryFactory $factory;

    private readonly SolrInputGuard $inputGuard;

    public function __construct(
        private readonly \Atoolo\Search\Search $search,
        private readonly ContextDispatcher $contextDispatcher,
        private readonly LoggerInterface $logger = new NullLogger(),
    ) {
        $this->factory = new SearchQueryFactory();
        $this->inputGuard = new SolrInputGuard();
    }

    #[GQL\Query(name: 'search', type: 'SearchResult!')]
    public function search(SearchInput $input): SearchResult
    {
        $this->inputGuard->assertSafe($input);
        if ($input->context !== null) {
            $this->contextDispatcher->dispatch($input->context);
        }
        $query = $this->factory->create($input);
        try {
            return $this->search->search($query);
        } catch (Exception $e) {
            $this->logger->error(
                'search failed: ' . $e->getMessage(),
                ['exception' => $e],
            );
            throw new UserError(self::ERROR_MESSAGE, 0, $e);
        }
    }
}
