<?php

namespace Pinoox\Component\PackageManager\Dependency\Graph;

final class GraphEdge
{
    public function __construct(
        private readonly string $from,
        private readonly string $to
    ) {
    }

    public function from(): string
    {
        return $this->from;
    }

    public function to(): string
    {
        return $this->to;
    }
}
