<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Service;

class PhpLimitIncreaser
{
    private string $savedTimeLimit;
    private ?string $savedMemoryLimit = null;

    public function __construct(
        private readonly int $timeLimit,
        private readonly string $memoryLimit
    ) {
    }

    public function increase(): void
    {
        $this->savedTimeLimit = ini_get('max_execution_time') ?: '0';
        $this->savedMemoryLimit = ini_get('memory_limit') ?: null;

        if (
            ((int)$this->savedTimeLimit) < $this->timeLimit
        ) {
            set_time_limit($this->timeLimit);
        }

        if ($this->isLowerMemory($this->savedMemoryLimit, $this->memoryLimit)) {
            ini_set('memory_limit', $this->memoryLimit);
        }
    }

    public function reset(): void
    {
        set_time_limit((int)$this->savedTimeLimit);
        ini_set('memory_limit', $this->savedMemoryLimit);
    }

    private function isLowerMemory(string $current, string $limit): bool
    {
        return $this->toMemoryStringToInteger($current) <
            $this->toMemoryStringToInteger($limit);
    }

    private function toMemoryStringToInteger(string $memory): int
    {
        sscanf($memory, '%u%c', $number, $suffix);
        if (!is_string($suffix)) {
            return (int)$memory;
        }

        $pos = stripos(' KMG', $suffix);
        if (!is_int($pos) || !is_int($number)) {
            return 0;
        }
        return $number * (1024 ** $pos);
    }
}
