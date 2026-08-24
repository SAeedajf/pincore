<?php

namespace Pinoox\Component\PackageManager\Dependency\Graph;

/**
 * Immutable result container for graph analysis operations.
 */
final class DependencyGraphAnalysisResult
{
    /**
     * @param array<string> $orderedNodes
     * @param array<string> $cycles
     */
    public function __construct(
        private array $orderedNodes = [],
        private array $cycles = []
    ) {
    }

    public function hasCycle(): bool
    {
        return count($this->cycles) > 0;
    }

    public function orderedNodes(): array
    {
        return $this->orderedNodes;
    }

    public function cycles(): array
    {
        return $this->cycles;
    }
}
