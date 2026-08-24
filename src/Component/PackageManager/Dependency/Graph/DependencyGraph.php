<?php

namespace Pinoox\Component\PackageManager\Dependency\Graph;

final class DependencyGraph
{
    /** @var array<string, GraphNode> */
    private array $nodes = [];

    /** @var list<GraphEdge> */
    private array $edges = [];

    public function addNode(GraphNode $node): void
    {
        $this->nodes[$node->id()] = $node;
    }

    public function addEdge(GraphEdge $edge): void
    {
        $this->edges[] = $edge;
    }

    /** @return array<string, GraphNode> */
    public function nodes(): array
    {
        return $this->nodes;
    }

    /** @return list<GraphEdge> */
    public function edges(): array
    {
        return $this->edges;
    }
}
