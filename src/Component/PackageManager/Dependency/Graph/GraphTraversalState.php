<?php

namespace Pinoox\Component\PackageManager\Dependency\Graph;

/**
 * Traversal states used by graph algorithms.
 */
final class GraphTraversalState
{
    public const UNVISITED = 0;

    public const VISITING = 1;

    public const VISITED = 2;
}
