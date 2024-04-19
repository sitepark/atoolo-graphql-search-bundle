<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Test\Resolver;

use ArrayObject;
use Atoolo\GraphQL\Search\Resolver\ResolverMapRegistry;
use Atoolo\GraphQL\Search\Types\ArticleTeaser;
use Atoolo\GraphQL\Search\Types\Image;
use Atoolo\Resource\Resource;
use GraphQL\Type\Definition\ResolveInfo;
use Overblog\GraphQLBundle\Definition\ArgumentInterface;
use Overblog\GraphQLBundle\Resolver\ResolverMapInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(ResolverMapRegistry::class)]
class ResolverMapRegistryTest extends TestCase
{
    public function testResolveWithGetterWithArgsResolve(): void
    {
        $registry = new ResolverMapRegistry([
            new DummyTeaserGetterResolver()
        ]);
        $articleTeaser = $this->createStub(ArticleTeaser::class);
        $fn = $registry->resolve(
            'ArticleTeaser',
            ResolverMapInterface::RESOLVE_FIELD
        );
        $args = $this->createStub(ArgumentInterface::class);
        $context = $this->createStub(ArrayObject::class);
        $info = $this->createStub(ResolveInfo::class);
        $info->fieldName = 'fieldWithArgs';

        $value = $fn($articleTeaser, $args, $context, $info);
        $this->assertEquals(
            'fieldvalue',
            $value,
            'unexpected value'
        );
    }

    public function testResolveWithGetterWithoutArgsResolve(): void
    {
        $registry = new ResolverMapRegistry([
            new DummyTeaserGetterResolver()
        ]);
        $articleTeaser = $this->createStub(ArticleTeaser::class);
        $fn = $registry->resolve(
            'ArticleTeaser',
            ResolverMapInterface::RESOLVE_FIELD
        );
        $args = $this->createStub(ArgumentInterface::class);
        $context = $this->createStub(ArrayObject::class);
        $info = $this->createStub(ResolveInfo::class);
        $info->fieldName = 'fieldWithoutArgs';

        $value = $fn($articleTeaser, $args, $context, $info);
        $this->assertEquals(
            'fieldvalue',
            $value,
            'unexpected value'
        );
    }

    public function testResolvePropertyWithGetterResolve(): void
    {
        $registry = new ResolverMapRegistry([
            new DummyTeaserGetterResolver()
        ]);
        $articleTeaser = new ArticleTeaser(
            'url',
            'headline',
            'text',
            'kicker',
            null,
            $this->createStub(Resource::class)
        );
        $fn = $registry->resolve(
            'ArticleTeaser',
            ResolverMapInterface::RESOLVE_FIELD
        );
        $args = $this->createStub(ArgumentInterface::class);
        $context = $this->createStub(ArrayObject::class);
        $info = $this->createStub(ResolveInfo::class);
        $info->fieldName = 'headline';

        $value = $fn($articleTeaser, $args, $context, $info);
        $this->assertEquals(
            'headline',
            $value,
            'unexpected headline'
        );
    }

    public function testResolveTypeForTeaserInterface(): void
    {
        $registry = new ResolverMapRegistry([]);

        $fn = $registry->resolve('Teaser', ResolverMapInterface::RESOLVE_TYPE);

        $teaser = new ArticleTeaser(
            'url',
            'headline',
            'text',
            'kicker',
            null,
            $this->createStub(Resource::class)
        );

        $type = $fn($teaser);

        $this->assertEquals(
            'ArticleTeaser',
            $type,
            'unexpected type'
        );
    }

    public function testResolveTypeForAssetInterface(): void
    {
        $registry = new ResolverMapRegistry([]);

        $fn = $registry->resolve('Asset', ResolverMapInterface::RESOLVE_TYPE);

        $image = new Image(
            '',
            '',
            '',
            '',
            null,
            null,
            []
        );

        $type = $fn($image);

        $this->assertEquals(
            'Image',
            $type,
            'unexpected type'
        );
    }
}
