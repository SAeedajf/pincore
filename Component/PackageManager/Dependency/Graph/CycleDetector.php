<?php

declare(strict_types=1);

namespace Pinoox\Component\PackageManager\Dependency\Graph;

final class CycleDetector
{
    public function hasCycle(DependencyGraph $graph): bool
    {
        return $this->detect($graph) !== [];
    }

    /**
     * @return list<GraphNode> The first deterministic cycle, closed by its initial node.
     */
    public function detect(DependencyGraph $graph): array
    {
        /** @var array<string, int> $states */
        $states = [];
        /** @var list<GraphNode> $path */
        $path = [];
        /** @var array<string, int> $positions */
        $positions = [];

        foreach ($graph->nodes() as $node) {
            if (($states[$node->id()] ?? GraphTraversalState::UNVISITED) !== GraphTraversalState::UNVISITED) {
                continue;
            }

            $cycle = $this->visit($node, $graph, $states, $path, $positions);

            if ($cycle !== []) {
                return $cycle;
            }
        }

        return [];
    }

    /**
     * @param array<string, int> $states
     * @param list<GraphNode> $path
     * @param array<string, int> $positions
     * @return list<GraphNode>
     */
    private function visit(
        GraphNode $node,
        DependencyGraph $graph,
        array &$states,
        array &$path,
        array &$positions
    ): array {
        $id = $node->id();
        $states[$id] = GraphTraversalState::VISITING;
        $positions[$id] = count($path);
        $path[] = $node;

        foreach ($graph->dependenciesOf($node) as $dependency) {
            $dependencyId = $dependency->id();
            $state = $states[$dependencyId] ?? GraphTraversalState::UNVISITED;

            if ($state === GraphTraversalState::VISITING) {
                $cycle = array_slice($path, $positions[$dependencyId]);
                $cycle[] = $dependency;

                return $cycle;
            }

            if ($state === GraphTraversalState::UNVISITED) {
                $cycle = $this->visit($dependency, $graph, $states, $path, $positions);

                if ($cycle !== []) {
                    return $cycle;
                }
            }
        }

        array_pop($path);
        unset($positions[$id]);
        $states[$id] = GraphTraversalState::VISITED;

        return [];
    }
}
