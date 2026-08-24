<?php

declare(strict_types=1);

namespace Pinoox\Component\PackageManager\Dependency\Graph;

/**
 * Immutable result of a structural dependency graph analysis.
 */
final class DependencyGraphAnalysisResult
{
    /**
     * @param list<string> $orderedNodeIds
     * @param list<string> $cycle
     */
    public function __construct(
        private readonly array $orderedNodeIds,
        private readonly array $cycle = []
    ) {
    }

    public function hasCycle(): bool
    {
        return $this->cycle !== [];
    }

    /** @return list<string> */
    public function orderedNodeIds(): array
    {
        return $this->orderedNodeIds;
    }

    /** @return list<string> */
    public function cycle(): array
    {
        return $this->cycle;
    }
}
