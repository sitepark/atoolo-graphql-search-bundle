<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Service;

use DateTimeInterface;
use InvalidArgumentException;
use UnitEnum;

/**
 * Rejects search input that tries to inject Solr syntax beyond plain
 * Lucene and function queries.
 *
 * Several input fields (e.g. filter queries, query templates, boost queries
 * and functions) are intentionally passed to Solr as raw expressions,
 * because frontends rely on Lucene and function query syntax. What must
 * never reach Solr from untrusted input are local params (`{!...}`), which
 * switch the query parser and allow e.g. joins across cores, and the
 * `_query_` magic field, which embeds such nested queries.
 *
 * Filter and facet keys are rendered as local param values
 * (`{!tag=...}`, `{!ex=...}`, `{!key=...}`) and therefore must not contain
 * characters that terminate or extend the local params.
 */
class SolrInputGuard
{
    public const ERROR_MESSAGE = 'Unsupported syntax in search input';

    private const FORBIDDEN_SEQUENCES = ['{!', '_query_'];

    private const KEY_PROPERTIES = ['key', 'excludeFilter'];

    private const FORBIDDEN_KEY_CHARACTERS = '/[\s{}\'"$\\\\]/u';

    /**
     * @throws InvalidArgumentException if the input contains forbidden syntax
     */
    public function assertSafe(object $input): void
    {
        $this->assertSafeValue($input, false);
    }

    private function assertSafeValue(mixed $value, bool $isKey): void
    {
        if (is_string($value)) {
            $this->assertSafeString($value, $isKey);
            return;
        }
        if (is_array($value)) {
            foreach ($value as $arrayKey => $item) {
                if (is_string($arrayKey)) {
                    $this->assertSafeString($arrayKey, false);
                }
                $this->assertSafeValue($item, $isKey);
            }
            return;
        }
        if (
            !is_object($value)
            || $value instanceof UnitEnum
            || $value instanceof DateTimeInterface
        ) {
            return;
        }
        foreach (get_object_vars($value) as $name => $propertyValue) {
            $this->assertSafeValue(
                $propertyValue,
                in_array($name, self::KEY_PROPERTIES, true),
            );
        }
    }

    private function assertSafeString(string $value, bool $isKey): void
    {
        foreach (self::FORBIDDEN_SEQUENCES as $sequence) {
            if (str_contains($value, $sequence)) {
                throw new InvalidArgumentException(self::ERROR_MESSAGE);
            }
        }
        if ($isKey && preg_match(self::FORBIDDEN_KEY_CHARACTERS, $value) !== 0) {
            throw new InvalidArgumentException(self::ERROR_MESSAGE);
        }
    }
}
