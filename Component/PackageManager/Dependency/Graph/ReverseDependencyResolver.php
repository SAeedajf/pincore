<?php

namespace Pinoox\Component\PackageManager\Dependency\Graph;

class ReverseDependencyResolver
{
    public function dependentsOf(DependencyGraph $graph, GraphNode $target): array
    {
        $dependents = [];

        foreach ($graph->edges() as $edge) {
            if ($edge->target()->id() === $target->id()) {
                $dependents[] = $edge->source();
            }
        }

        return $dependents;
    }
}
