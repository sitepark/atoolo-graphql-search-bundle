<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\DependencyInjection\Compiler;

use GraphQL\Language\AST\DocumentNode;
use GraphQL\Language\AST\FragmentDefinitionNode;
use GraphQL\Language\AST\FragmentSpreadNode;
use GraphQL\Language\AST\Node;
use GraphQL\Language\AST\NodeKind;
use GraphQL\Language\Printer;
use GraphQL\Language\Visitor;
use Symfony\Component\DependencyInjection\Exception\InvalidArgumentException;

/**
 * Allows GraphQL files to use fragments that are defined in other files.
 *
 * Each file is completed with the fragments it uses but does not define
 * itself, so that every operation can still be executed and validated as a
 * self-contained document. A fragment is resolved in the scope of the file
 * that uses it:
 *
 * - a fragment defined in the same file always wins
 * - otherwise the fragment must be defined in exactly one other file; its
 *   own fragment spreads are resolved in the scope of that file
 * - unknown fragments are left as they are, so the validation reports them
 */
class GraphQLDocumentLinker
{
    /**
     * @var array<string, array<string, FragmentDefinitionNode>>
     *     fragments by their name and the file that defines them
     */
    private array $fragments = [];

    /**
     * @var array<string, string>
     */
    private array $sources = [];

    /**
     * @param array<string, string> $sources the GraphQL sources keyed by their file
     * @param array<string, DocumentNode> $documents the parsed sources keyed by their file
     * @return array<string, string> the linked sources keyed by their file
     * @throws InvalidArgumentException if a fragment cannot be resolved unambiguously
     */
    public function link(array $sources, array $documents): array
    {
        $this->sources = $sources;
        $this->fragments = [];
        foreach ($documents as $file => $document) {
            foreach ($document->definitions as $definition) {
                if ($definition instanceof FragmentDefinitionNode) {
                    $this->fragments[$definition->name->value][$file] ??= $definition;
                }
            }
        }

        $linked = [];
        foreach ($documents as $file => $document) {
            $linked[$file] = $this->linkFile($file, $document);
        }
        return $linked;
    }

    private function linkFile(string $file, DocumentNode $document): string
    {
        /** @var array<string, string> $included file of each included fragment */
        $included = [];
        $appendix = '';
        /** @var list<array{string, string}> $pending fragment name and the file using it */
        $pending = array_map(
            static fn(string $name): array => [$name, $file],
            $this->collectFragmentSpreads($document),
        );
        while ($pending !== []) {
            [$name, $scope] = array_shift($pending);
            $definingFile = $this->resolve($name, $scope);
            if ($definingFile === null || $definingFile === $file) {
                continue;
            }
            if (isset($included[$name])) {
                if ($included[$name] !== $definingFile) {
                    throw $this->conflict($name, $file, $included[$name], $definingFile);
                }
                continue;
            }
            if (isset($this->fragments[$name][$file])) {
                throw $this->conflict($name, $file, $file, $definingFile);
            }
            $included[$name] = $definingFile;
            $fragment = $this->fragments[$name][$definingFile];
            $appendix .= sprintf(
                "\n# fragment %s from %s\n%s\n",
                $name,
                $definingFile,
                $this->getSourceText($definingFile, $fragment),
            );
            foreach ($this->collectFragmentSpreads($fragment) as $spread) {
                $pending[] = [$spread, $definingFile];
            }
        }

        $source = $this->sources[$file];
        return $appendix === '' ? $source : $source . "\n" . $appendix;
    }

    /**
     * @return string|null the file that defines the fragment
     *     or null if the fragment is unknown
     */
    private function resolve(string $name, string $scope): ?string
    {
        $definingFiles = array_keys($this->fragments[$name] ?? []);
        if (in_array($scope, $definingFiles, true)) {
            return $scope;
        }
        if (count($definingFiles) > 1) {
            throw new InvalidArgumentException(
                sprintf(
                    'GraphQL fragment "%s" used in file "%s" is defined in'
                        . ' multiple files: "%s". Either define it in that'
                        . ' file or make its name unique.',
                    $name,
                    $scope,
                    implode('", "', $definingFiles),
                ),
            );
        }
        return $definingFiles[0] ?? null;
    }

    private function conflict(
        string $name,
        string $file,
        string $firstFile,
        string $secondFile,
    ): InvalidArgumentException {
        return new InvalidArgumentException(
            sprintf(
                'GraphQL file "%s" requires the fragment "%s" from both "%s"'
                    . ' and "%s". Fragment names within one document must be'
                    . ' unique; rename one of them.',
                $file,
                $name,
                $firstFile,
                $secondFile,
            ),
        );
    }

    /**
     * @return list<string> the names of all fragments spread within the node
     */
    private function collectFragmentSpreads(Node $node): array
    {
        $names = [];
        $collectSpread = static function (Node $spread) use (&$names): void {
            if ($spread instanceof FragmentSpreadNode) {
                $names[$spread->name->value] = true;
            }
        };
        Visitor::visit($node, [NodeKind::FRAGMENT_SPREAD => $collectSpread]);
        return array_keys($names);
    }

    private function getSourceText(
        string $file,
        FragmentDefinitionNode $fragment,
    ): string {
        $location = $fragment->loc;
        if ($location === null) {
            return Printer::doPrint($fragment);
        }
        // the locations count characters, not bytes
        return mb_substr(
            $this->sources[$file],
            $location->start,
            $location->end - $location->start,
            'UTF-8',
        );
    }
}
