<?php

declare(strict_types=1);

namespace Pinoox\Component\PackageManager\Dependency\Graph;

final class DependencyGraphAnalyzer
{
    public function analyze(DependencyGraph $graph): DependencyGraphAnalysisResult
    {
        $cycle = (new CycleDetector())->detect($graph);

        if ($cycle !== []) {
            return new DependencyGraphAnalysisResult(
                [],
                array_map(static fn (GraphNode $node): string => $node->id(), $cycle)
            );
        }

        return new DependencyGraphAnalysisResult(
            array_map(
                static fn (GraphNode $node): string => $node->id(),
                (new TopologicalSorter())->sort($graph)
            )
        );
    }
}
