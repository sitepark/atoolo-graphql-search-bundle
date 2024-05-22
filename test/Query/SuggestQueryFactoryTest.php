<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Test\Query;

use Atoolo\GraphQL\Search\Input\InputFilter;
use Atoolo\GraphQL\Search\Input\SuggestInput;
use Atoolo\GraphQL\Search\Query\SuggestQueryFactory;
use Atoolo\Resource\ResourceLanguage;
use Atoolo\Search\Dto\Search\Query\Filter\ArchiveFilter;
use Atoolo\Search\Dto\Search\Query\Filter\ObjectTypeFilter;
use Atoolo\Search\Dto\Search\Query\SuggestQuery;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(SuggestQueryFactory::class)]
class SuggestQueryFactoryTest extends TestCase
{
    public function testCreateWithText(): void
    {
        $input = new SuggestInput();
        $input->text = 'test';

        $factory = new SuggestQueryFactory();
        $query = $factory->create($input);

        $this->assertEquals(
            new SuggestQuery('test', ResourceLanguage::default()),
            $query,
            'unexpected text'
        );
    }

    public function testCreateWithFilter(): void
    {
        $input = new SuggestInput();
        $input->text = 'test';
        $inputFilter = new InputFilter();
        $inputFilter->objectTypes = ['content'];
        $input->filter = [$inputFilter];

        $factory = new SuggestQueryFactory();
        $query = $factory->create($input);

        $objectTypeFilter = new ObjectTypeFilter(['content']);
        $archiveFilter = new ArchiveFilter();

        $expected = new SuggestQuery(
            'test',
            ResourceLanguage::default(),
            [$objectTypeFilter, $archiveFilter]
        );

        $this->assertEquals(
            $expected,
            $query,
            'unexpected filter'
        );
    }

    public function testCreateWithLimit(): void
    {
        $input = new SuggestInput();
        $input->text = 'test';
        $input->limit = 10;

        $factory = new SuggestQueryFactory();
        $query = $factory->create($input);

        $this->assertEquals(
            new SuggestQuery(
                'test',
                ResourceLanguage::default(),
                [],
                10
            ),
            $query,
            'unexpected limit'
        );
    }
}
