<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Test\Query;

use Atoolo\GraphQL\Search\Input\InputFilter;
use Atoolo\GraphQL\Search\Input\MoreLikeThisInput;
use Atoolo\GraphQL\Search\Query\MoreLikeThisQueryFactory;
use Atoolo\Resource\ResourceLanguage;
use Atoolo\Search\Dto\Search\Query\Filter\ObjectTypeFilter;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(MoreLikeThisQueryFactory::class)]
class MoreLikeThisQueryFactoryTest extends TestCase
{
    public function testCreateWithId(): void
    {
        $input = new MoreLikeThisInput();
        $input->id = '123';
        $factory = new MoreLikeThisQueryFactory();
        $query = $factory->create($input);

        $this->assertEquals(
            '123',
            $query->id,
            'id expected',
        );
    }

    public function testCreateWithLang(): void
    {
        $input = new MoreLikeThisInput();
        $input->id = '123';
        $input->lang = 'en';
        $factory = new MoreLikeThisQueryFactory();
        $query = $factory->create($input);

        $this->assertEquals(
            ResourceLanguage::of('en'),
            $query->lang,
            'lang expected',
        );
    }

    public function testCreateWithLimit(): void
    {
        $input = new MoreLikeThisInput();
        $input->id = '123';
        $input->limit = 50;
        $factory = new MoreLikeThisQueryFactory();
        $query = $factory->create($input);

        $this->assertEquals(
            50,
            $query->limit,
            'lang expected',
        );
    }

    public function testCreateWithFilter(): void
    {
        $input = new MoreLikeThisInput();
        $input->id = '123';
        $inputFilter = new InputFilter();
        $inputFilter->objectTypes = ['content'];
        $input->filter = [$inputFilter];
        $factory = new MoreLikeThisQueryFactory();
        $query = $factory->create($input);

        $expectedFilter = new ObjectTypeFilter(['content']);
        $this->assertEquals(
            [$expectedFilter],
            $query->filter,
            'lang expected',
        );
    }
}
