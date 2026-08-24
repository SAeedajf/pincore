<?php

namespace Pinoox\Component\PackageManager\Dependency\Graph;

/**
 * Generates a safe dependency installation order from a directed graph.
 */
class TopologicalSorter
{
    /**
     * @return array<int, GraphNode>
     */
    public function sort(DependencyGraph $graph): array
    {
        return [];
    }
}
