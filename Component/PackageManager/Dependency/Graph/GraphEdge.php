<?php

declare(strict_types=1);

namespace Pinoox\Component\PackageManager\Dependency\Graph;

final class GraphEdge
{
    public function __construct(
        private readonly GraphNode $source,
        private readonly GraphNode $target
    ) {
    }

    public function source(): GraphNode
    {
        return $this->source;
    }

    public function target(): GraphNode
    {
        return $this->target;
    }

    public function key(): string
    {
        return $this->source->id() . "\0" . $this->target->id();
    }
}
