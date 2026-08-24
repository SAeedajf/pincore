<?php

declare(strict_types=1);

namespace Pinoox\Component\PackageManager\Dependency\Graph;

final class GraphTraversalState
{
    public const UNVISITED = 0;

    public const VISITING = 1;

    public const VISITED = 2;
}
