<?php

declare(strict_types=1);

namespace Pinoox\Component\PackageManager\Dependency\Graph;

final class ReverseDependencyResolver
{
    /**
     * @return list<GraphNode>
     */
    public function dependentsOf(DependencyGraph $graph, GraphNode|string $target): array
    {
        return $graph->dependentsOf($target);
    }
}
