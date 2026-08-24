<?php

namespace Pinoox\Component\PackageManager\Dependency\Graph;

class CycleDetector
{
    public function hasCycle(DependencyGraph $graph): bool
    {
        $states = [];

        foreach ($graph->nodes() as $node) {
            if (($states[$node->id()] ?? GraphTraversalState::UNVISITED) === GraphTraversalState::UNVISITED) {
                if ($this->visit($node, $graph, $states)) {
                    return true;
                }
            }
        }

        return false;
    }

    private function visit(GraphNode $node, DependencyGraph $graph, array &$states): bool
    {
        $id = $node->id();
        $states[$id] = GraphTraversalState::VISITING;

        foreach ($graph->neighbors($node) as $neighbor) {
            $state = $states[$neighbor->id()] ?? GraphTraversalState::UNVISITED;

            if ($state === GraphTraversalState::VISITING) {
                return true;
            }

            if ($state === GraphTraversalState::UNVISITED && $this->visit($neighbor, $graph, $states)) {
                return true;
            }
        }

        $states[$id] = GraphTraversalState::VISITED;

        return false;
    }
}
