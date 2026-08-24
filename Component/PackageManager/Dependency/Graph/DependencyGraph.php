<?php

declare(strict_types=1);

namespace Pinoox\Component\PackageManager\Dependency\Graph;

use Pinoox\Component\PackageManager\Dependency\Graph\Exception\GraphInvariantViolationException;

final class DependencyGraph
{
    /** @var array<string, GraphNode> */
    private array $nodes = [];

    /** @var array<string, GraphEdge> */
    private array $edges = [];

    public function addNode(GraphNode $node): void
    {
        $existing = $this->nodes[$node->id()] ?? null;

        if ($existing !== null && $existing !== $node) {
            throw new GraphInvariantViolationException(
                sprintf('A graph node with id "%s" already exists.', $node->id())
            );
        }

        $this->nodes[$node->id()] = $node;
    }

    public function addEdge(GraphEdge $edge): void
    {
        $source = $edge->source();
        $target = $edge->target();

        if (!$this->hasNode($source) || !$this->hasNode($target)) {
            throw new GraphInvariantViolationException(
                'Both endpoints of a graph edge must be registered before the edge is added.'
            );
        }

        if ($source->id() === $target->id()) {
            throw new GraphInvariantViolationException('A graph edge cannot reference the same node twice.');
        }

        $this->edges[$edge->key()] = $edge;
    }

    public function hasNode(GraphNode|string $node): bool
    {
        return isset($this->nodes[$this->nodeId($node)]);
    }

    public function node(string $id): ?GraphNode
    {
        return $this->nodes[$id] ?? null;
    }

    /** @return array<string, GraphNode> */
    public function nodes(): array
    {
        $nodes = $this->nodes;
        ksort($nodes, SORT_STRING);

        return $nodes;
    }

    /** @return list<GraphEdge> */
    public function edges(): array
    {
        $edges = $this->edges;
        ksort($edges, SORT_STRING);

        return array_values($edges);
    }

    /** @return list<GraphNode> */
    public function dependenciesOf(GraphNode|string $node): array
    {
        $id = $this->nodeId($node);
        $dependencies = [];

        foreach ($this->edges as $edge) {
            if ($edge->source()->id() === $id) {
                $dependencies[$edge->target()->id()] = $edge->target();
            }
        }

        ksort($dependencies, SORT_STRING);

        return array_values($dependencies);
    }

    /** @return list<GraphNode> */
    public function dependentsOf(GraphNode|string $node): array
    {
        $id = $this->nodeId($node);
        $dependents = [];

        foreach ($this->edges as $edge) {
            if ($edge->target()->id() === $id) {
                $dependents[$edge->source()->id()] = $edge->source();
            }
        }

        ksort($dependents, SORT_STRING);

        return array_values($dependents);
    }

    private function nodeId(GraphNode|string $node): string
    {
        return $node instanceof GraphNode ? $node->id() : $node;
    }
}
