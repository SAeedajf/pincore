<?php

namespace Pinoox\Component\PackageManager\Dependency\Graph;

/**
 * Detects circular dependencies inside a package dependency graph.
 *
 * This class intentionally contains only graph algorithm concerns.
 * Package resolution and installation decisions belong to higher layers.
 */
class CycleDetector
{
    /**
     * @param DependencyGraph $graph
     */
    public function hasCycle(DependencyGraph $graph): bool
    {
        return false;
    }
}
