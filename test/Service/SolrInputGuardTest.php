<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Test\Service;

use Atoolo\GraphQL\Search\Input\InputBoosting;
use Atoolo\GraphQL\Search\Input\InputFacet;
use Atoolo\GraphQL\Search\Input\InputFilter;
use Atoolo\GraphQL\Search\Input\InputSortCriteria;
use Atoolo\GraphQL\Search\Input\InputSortCriteriaCustom;
use Atoolo\GraphQL\Search\Input\QueryTemplateInput;
use Atoolo\GraphQL\Search\Input\SearchInput;
use Atoolo\GraphQL\Search\Service\SolrInputGuard;
use Atoolo\GraphQL\Search\Types\SortDirection;
use DateTimeZone;
use InvalidArgumentException;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

#[CoversClass(SolrInputGuard::class)]
class SolrInputGuardTest extends TestCase
{
    private SolrInputGuard $guard;

    public function setUp(): void
    {
        $this->guard = new SolrInputGuard();
    }

    /**
     * @return array<string, array{SearchInput}>
     */
    public static function safeInputs(): array
    {
        return [
            'text' => [self::input(text: 'hello "exact phrase" -excluded')],
            'filter query' => [self::input(filter: [self::queryFilter('sp_startletter:A')])],
            'filter query with range' => [self::input(filter: [self::queryFilter('sp_date:[NOW TO *]')])],
            'filter query with exclusive range' => [self::input(filter: [self::queryFilter('sp_date:{a TO b}')])],
            'filter query with boost' => [
                self::input(filter: [self::queryFilter('sp_category_path:(1 OR 2)^=100')]),
            ],
            'filter values' => [self::input(filter: [self::valuesFilter(['["" TO *]'])])],
            'query template' => [
                self::input(filter: [self::templateFilter('sp_id:{id}', ['id' => '123'])]),
            ],
            'boost function' => [
                self::input(boosting: self::boosting(
                    boostFunctions: [
                        "if(termfreq(sp_objecttype,'news'),scale(sp_date,0,12),scale(sp_date,10,11))",
                    ],
                )),
            ],
            'query fields' => [self::input(boosting: self::boosting(queryFields: ['sp_title^1.4', 'content']))],
            'keys' => [
                self::input(
                    filter: [self::queryFilter('sp_startletter:A', 'contentType-pdf')],
                    facets: [self::facet('absoluteDateRangeFilter', ['domain', 'filter.flags'])],
                ),
            ],
            'custom sort' => [self::input(sort: [self::customSort('sp_name')])],
            'time zone' => [self::input(timeZone: new DateTimeZone('Europe/Berlin'))],
        ];
    }

    /**
     * @return array<string, array{SearchInput}>
     */
    public static function unsafeInputs(): array
    {
        return [
            'local params in text' => [self::input(text: 'x {!func}1')],
            'local params in filter query' => [
                self::input(filter: [self::queryFilter('{!join from=id to=id fromIndex=other}*:*')]),
            ],
            'magic query field in filter query' => [
                self::input(filter: [self::queryFilter('_query_:"{!lucene}a"')]),
            ],
            'magic query field without local params' => [self::input(filter: [self::queryFilter('_query_:a')])],
            'local params in filter values' => [self::input(filter: [self::valuesFilter(['a {!func}1'])])],
            'local params in nested filter' => [
                self::input(filter: [self::notFilter(self::orFilter([self::queryFilter('{!lucene}a')]))]),
            ],
            'local params in template query' => [
                self::input(filter: [self::templateFilter('{!lucene}{v}', ['v' => 'a'])]),
            ],
            'local params in template variable' => [
                self::input(filter: [self::templateFilter('sp_id:{v}', ['v' => '{!lucene}a'])]),
            ],
            'local params in nested template variable' => [
                self::input(filter: [self::templateFilter('sp_id:{v}', ['v' => ['x' => ['{!lucene}a']]])]),
            ],
            'local params in template variable name' => [
                self::input(filter: [self::templateFilter('sp_id:{v}', ['{!lucene}' => 'a'])]),
            ],
            'local params in facet query' => [self::input(facets: [self::facet('k', [], '{!lucene}a')])],
            'local params in boost query' => [
                self::input(boosting: self::boosting(boostQueries: ['{!lucene}a'])),
            ],
            'local params in boost function' => [
                self::input(boosting: self::boosting(boostFunctions: ['query({!lucene v=a})'])),
            ],
            'local params in query fields' => [self::input(boosting: self::boosting(queryFields: ['{!lucene}']))],
            'local params in custom sort' => [self::input(sort: [self::customSort('query({!lucene v=a})')])],
            'closing brace in filter key' => [self::input(filter: [self::queryFilter('a:b', 'x}*:*')])],
            'whitespace in filter key' => [self::input(filter: [self::queryFilter('a:b', 'x y')])],
            'quote in facet key' => [self::input(facets: [self::facet("x'", [])])],
            'dollar in exclude filter' => [self::input(facets: [self::facet('x', ['$x'])])],
            'backslash in exclude filter' => [self::input(facets: [self::facet('x', ['x\\'])])],
        ];
    }

    #[DataProvider('safeInputs')]
    public function testAcceptsSafeInput(SearchInput $input): void
    {
        $this->expectNotToPerformAssertions();
        $this->guard->assertSafe($input);
    }

    #[DataProvider('unsafeInputs')]
    public function testRejectsUnsafeInput(SearchInput $input): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(SolrInputGuard::ERROR_MESSAGE);
        $this->guard->assertSafe($input);
    }

    /**
     * @param InputFilter[]|null $filter
     * @param InputFacet[]|null $facets
     * @param InputSortCriteria[]|null $sort
     */
    private static function input(
        ?string $text = null,
        ?array $filter = null,
        ?array $facets = null,
        ?InputBoosting $boosting = null,
        ?array $sort = null,
        ?DateTimeZone $timeZone = null,
    ): SearchInput {
        $input = new SearchInput();
        $input->text = $text;
        $input->filter = $filter;
        $input->facets = $facets;
        $input->boosting = $boosting;
        $input->sort = $sort;
        $input->timeZone = $timeZone;
        return $input;
    }

    private static function queryFilter(string $query, ?string $key = null): InputFilter
    {
        $filter = new InputFilter();
        $filter->query = $query;
        $filter->key = $key;
        return $filter;
    }

    /**
     * @param string[] $values
     */
    private static function valuesFilter(array $values): InputFilter
    {
        $filter = new InputFilter();
        $filter->categories = $values;
        return $filter;
    }

    /**
     * @param array<string, mixed> $variables
     */
    private static function templateFilter(string $query, array $variables): InputFilter
    {
        $template = new QueryTemplateInput();
        $template->query = $query;
        $template->variables = $variables;
        $filter = new InputFilter();
        $filter->queryTemplate = $template;
        return $filter;
    }

    private static function notFilter(InputFilter $not): InputFilter
    {
        $filter = new InputFilter();
        $filter->not = $not;
        return $filter;
    }

    /**
     * @param InputFilter[] $or
     */
    private static function orFilter(array $or): InputFilter
    {
        $filter = new InputFilter();
        $filter->or = $or;
        return $filter;
    }

    /**
     * @param string[] $excludeFilter
     */
    private static function facet(string $key, array $excludeFilter, ?string $query = null): InputFacet
    {
        $facet = new InputFacet();
        $facet->key = $key;
        $facet->excludeFilter = $excludeFilter;
        $facet->query = $query;
        return $facet;
    }

    /**
     * @param string[]|null $queryFields
     * @param string[]|null $boostQueries
     * @param string[]|null $boostFunctions
     */
    private static function boosting(
        ?array $queryFields = null,
        ?array $boostQueries = null,
        ?array $boostFunctions = null,
    ): InputBoosting {
        $boosting = new InputBoosting();
        $boosting->queryFields = $queryFields;
        $boosting->boostQueries = $boostQueries;
        $boosting->boostFunctions = $boostFunctions;
        return $boosting;
    }

    private static function customSort(string $field): InputSortCriteria
    {
        $custom = new InputSortCriteriaCustom();
        $custom->field = $field;
        $custom->direction = SortDirection::ASC;
        $sort = new InputSortCriteria();
        $sort->custom = $custom;
        return $sort;
    }
}
