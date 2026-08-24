<?php

declare(strict_types=1);

namespace Pinoox\Component\PackageManager\Dependency\Graph;

use Pinoox\Component\PackageManager\Dependency\Graph\Exception\DependencyCycleException;

final class TopologicalSorter
{
    /**
     * Returns an install-safe order: every dependency is emitted before its dependent.
     *
     * @return list<GraphNode>
     */
    public function sort(DependencyGraph $graph): array
    {
        /** @var array<string, int> $remainingDependencies */
        $remainingDependencies = [];
        /** @var array<string, GraphNode> $ready */
        $ready = [];
        $result = [];

        foreach ($graph->nodes() as $node) {
            $remainingDependencies[$node->id()] = count($graph->dependenciesOf($node));

            if ($remainingDependencies[$node->id()] === 0) {
                $ready[$node->id()] = $node;
            }
        }

        while ($ready !== []) {
            ksort($ready, SORT_STRING);
            $node = array_shift($ready);
            $result[] = $node;

            foreach ($graph->dependentsOf($node) as $dependent) {
                $id = $dependent->id();
                --$remainingDependencies[$id];

                if ($remainingDependencies[$id] === 0) {
                    $ready[$id] = $dependent;
                }
            }
        }

        if (count($result) !== count($graph->nodes())) {
            $cycle = (new CycleDetector())->detect($graph);

            throw new DependencyCycleException(array_map(
                static fn (GraphNode $node): string => $node->id(),
                $cycle
            ));
        }

        return $result;
    }
}
