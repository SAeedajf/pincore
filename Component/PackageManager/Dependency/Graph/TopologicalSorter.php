<?php

namespace Pinoox\Component\PackageManager\Dependency\Graph;

class TopologicalSorter
{
    public function sort(DependencyGraph $graph): array
    {
        $inDegree = [];
        $queue = [];
        $result = [];

        foreach ($graph->nodes() as $node) {
            $inDegree[$node->id()] = 0;
        }

        foreach ($graph->edges() as $edge) {
            $inDegree[$edge->target()->id()]++;
        }

        foreach ($inDegree as $id => $degree) {
            if ($degree === 0) {
                $queue[] = $id;
            }
        }

        while ($queue) {
            $current = array_shift($queue);
            $node = $graph->node($current);
            $result[] = $node;

            foreach ($graph->neighbors($node) as $neighbor) {
                $inDegree[$neighbor->id()]--;

                if ($inDegree[$neighbor->id()] === 0) {
                    $queue[] = $neighbor->id();
                }
            }
        }

        if (count($result) !== count($graph->nodes())) {
            throw new \RuntimeException('Dependency graph contains a cycle.');
        }

        return $result;
    }
}
