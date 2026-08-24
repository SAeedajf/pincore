<?php

namespace Pinoox\Component\PackageManager\Dependency\Graph;

final class GraphNode
{
    public function __construct(
        private readonly string $id
    ) {
    }

    public function id(): string
    {
        return $this->id;
    }
}
