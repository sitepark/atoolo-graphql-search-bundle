<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Test\DependencyInjection\Compiler;

use Atoolo\GraphQL\Search\DependencyInjection\Compiler\GraphQLDocumentLinker;
use GraphQL\Language\Parser;
use GraphQL\Utils\BuildSchema;
use GraphQL\Validator\DocumentValidator;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\Exception\InvalidArgumentException;

#[CoversClass(GraphQLDocumentLinker::class)]
class GraphQLDocumentLinkerTest extends TestCase
{
    private const SCHEMA = <<<'GRAPHQL'
        type Query { search: Result }
        type Result { id: ID, name: String, link: Link }
        type Link { url: String, label: String }
        GRAPHQL;

    public function testSelfContainedFileIsUnchanged(): void
    {
        $source = <<<'GRAPHQL'
            query a { search { ...result } }
            fragment result on Result { id }
            GRAPHQL;

        $linked = $this->link(['a.graphql' => $source]);

        $this->assertSame($source, $linked['a.graphql']);
    }

    public function testAppendsFragmentOfOtherFile(): void
    {
        $linked = $this->link([
            'a.graphql' => 'query a { search { ...result } }',
            'b.graphql' => 'fragment result on Result { id }',
        ]);

        $this->assertSame(
            "query a { search { ...result } }\n"
                . "\n# fragment result from b.graphql\n"
                . "fragment result on Result { id }\n",
            $linked['a.graphql'],
        );
        $this->assertValid($linked['a.graphql']);
    }

    public function testResolvesFragmentsTransitivelyInScopeOfDefiningFile(): void
    {
        $linked = $this->link([
            'a.graphql' => 'query a { search { ...result } }',
            'b.graphql' => <<<'GRAPHQL'
                fragment result on Result { id link { ...link } }
                fragment link on Link { url }
                fragment unused on Link { label }
                GRAPHQL,
        ]);

        $this->assertStringContainsString('fragment result on Result', $linked['a.graphql']);
        $this->assertStringContainsString('fragment link on Link', $linked['a.graphql']);
        $this->assertStringNotContainsString('fragment unused', $linked['a.graphql']);
        $this->assertValid($linked['a.graphql']);
    }

    public function testLocalFragmentShadowsFragmentOfOtherFile(): void
    {
        $source = <<<'GRAPHQL'
            query a { search { ...result } }
            fragment result on Result { name }
            GRAPHQL;

        $linked = $this->link([
            'a.graphql' => $source,
            'b.graphql' => 'fragment result on Result { id }',
        ]);

        $this->assertSame($source, $linked['a.graphql']);
    }

    public function testAppendsFragmentUsedByMultipleFragmentsOnlyOnce(): void
    {
        $linked = $this->link([
            'a.graphql' => <<<'GRAPHQL'
                query a { search { ...result ...other } }
                fragment other on Result { link { ...link } }
                GRAPHQL,
            'b.graphql' => <<<'GRAPHQL'
                fragment result on Result { link { ...link } }
                fragment link on Link { url }
                GRAPHQL,
        ]);

        $this->assertSame(1, substr_count($linked['a.graphql'], 'fragment link on Link'));
        $this->assertValid($linked['a.graphql']);
    }

    public function testKeepsMultibyteCharacters(): void
    {
        $linked = $this->link([
            'a.graphql' => 'query a { search { ...result } }',
            'b.graphql' => <<<'GRAPHQL'
                # Überschrift für Ergebnisse
                fragment result on Result { id }
                GRAPHQL,
        ]);

        $this->assertStringEndsWith(
            "\nfragment result on Result { id }\n",
            $linked['a.graphql'],
        );
    }

    public function testPrintsFragmentWithoutLocation(): void
    {
        $sources = [
            'a.graphql' => 'query a { search { ...result } }',
            'b.graphql' => 'fragment result on Result { id }',
        ];

        $linked = (new GraphQLDocumentLinker())->link($sources, [
            'a.graphql' => Parser::parse($sources['a.graphql']),
            'b.graphql' => Parser::parse($sources['b.graphql'], ['noLocation' => true]),
        ]);

        $this->assertSame(
            "query a { search { ...result } }\n"
                . "\n# fragment result from b.graphql\n"
                . "fragment result on Result {\n  id\n}\n",
            $linked['a.graphql'],
        );
        $this->assertValid($linked['a.graphql']);
    }

    public function testUnknownFragmentIsLeftToValidation(): void
    {
        $source = 'query a { search { ...unknown } }';

        $linked = $this->link(['a.graphql' => $source]);

        $this->assertSame($source, $linked['a.graphql']);
    }

    public function testAmbiguousFragmentThrows(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('"result" used in file "a.graphql" is defined in multiple files');

        $this->link([
            'a.graphql' => 'query a { search { ...result } }',
            'b.graphql' => 'fragment result on Result { id }',
            'c.graphql' => 'fragment result on Result { name }',
        ]);
    }

    public function testLocalFragmentConflictingWithRequiredFragmentThrows(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('requires the fragment "link" from both "a.graphql" and "b.graphql"');

        $this->link([
            'a.graphql' => <<<'GRAPHQL'
                query a { search { ...result link { ...link } } }
                fragment link on Link { label }
                GRAPHQL,
            'b.graphql' => <<<'GRAPHQL'
                fragment result on Result { link { ...link } }
                fragment link on Link { url }
                GRAPHQL,
        ]);
    }

    public function testFragmentsOfDifferentFilesWithSameNameConflict(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('requires the fragment "link" from both "b.graphql" and "c.graphql"');

        $this->link([
            'a.graphql' => 'query a { search { ...result ...other } }',
            'b.graphql' => <<<'GRAPHQL'
                fragment result on Result { link { ...link } }
                fragment link on Link { url }
                GRAPHQL,
            'c.graphql' => <<<'GRAPHQL'
                fragment other on Result { link { ...link } }
                fragment link on Link { label }
                GRAPHQL,
        ]);
    }

    /**
     * @param array<string, string> $sources
     * @return array<string, string>
     */
    private function link(array $sources): array
    {
        return (new GraphQLDocumentLinker())->link(
            $sources,
            array_map(static fn(string $source) => Parser::parse($source), $sources),
        );
    }

    private function assertValid(string $source): void
    {
        $errors = DocumentValidator::validate(
            BuildSchema::build(self::SCHEMA),
            Parser::parse($source),
        );
        $this->assertSame(
            [],
            array_map(static fn($error) => $error->getMessage(), $errors),
        );
    }
}
