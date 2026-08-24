<?php

declare(strict_types=1);

namespace Pinoox\Tests\Unit\Component\PackageManager\Dependency\Graph;

use PHPUnit\Framework\TestCase;
use Pinoox\Component\PackageManager\Dependency\Graph\CycleDetector;
use Pinoox\Component\PackageManager\Dependency\Graph\DependencyGraph;
use Pinoox\Component\PackageManager\Dependency\Graph\DependencyGraphAnalyzer;
use Pinoox\Component\PackageManager\Dependency\Graph\Exception\GraphInvariantViolationException;
use Pinoox\Component\PackageManager\Dependency\Graph\GraphEdge;
use Pinoox\Component\PackageManager\Dependency\Graph\GraphNode;
use Pinoox\Component\PackageManager\Dependency\Graph\TopologicalSorter;

final class DependencyGraphTest extends TestCase
{
    public function test_it_orders_dependencies_before_dependents_deterministically(): void
    {
        $graph = new DependencyGraph();
        $storefront = new GraphNode('storefront');
        $shop = new GraphNode('shop');
        $payment = new GraphNode('payment');

        foreach ([$storefront, $shop, $payment] as $node) {
            $graph->addNode($node);
        }

        $graph->addEdge(new GraphEdge($storefront, $shop));
        $graph->addEdge(new GraphEdge($shop, $payment));

        self::assertSame(
            ['payment', 'shop', 'storefront'],
            array_map(
                static fn (GraphNode $node): string => $node->id(),
                (new TopologicalSorter())->sort($graph)
            )
        );
    }

    public function test_it_reports_a_closed_cycle_path(): void
    {
        $graph = new DependencyGraph();
        $a = new GraphNode('a');
        $b = new GraphNode('b');
        $c = new GraphNode('c');

        foreach ([$a, $b, $c] as $node) {
            $graph->addNode($node);
        }

        $graph->addEdge(new GraphEdge($a, $b));
        $graph->addEdge(new GraphEdge($b, $c));
        $graph->addEdge(new GraphEdge($c, $a));

        self::assertSame(
            ['a', 'b', 'c', 'a'],
            array_map(
                static fn (GraphNode $node): string => $node->id(),
                (new CycleDetector())->detect($graph)
            )
        );
    }

    public function test_it_exposes_reverse_dependencies_and_analysis_output(): void
    {
        $graph = new DependencyGraph();
        $shop = new GraphNode('shop');
        $payment = new GraphNode('payment');

        $graph->addNode($shop);
        $graph->addNode($payment);
        $graph->addEdge(new GraphEdge($shop, $payment));

        self::assertSame(['shop'], array_map(
            static fn (GraphNode $node): string => $node->id(),
            $graph->dependentsOf('payment')
        ));

        $analysis = (new DependencyGraphAnalyzer())->analyze($graph);

        self::assertFalse($analysis->hasCycle());
        self::assertSame(['payment', 'shop'], $analysis->orderedNodeIds());
    }

    public function test_it_rejects_edges_with_unregistered_endpoints(): void
    {
        $graph = new DependencyGraph();
        $known = new GraphNode('known');
        $unknown = new GraphNode('unknown');

        $graph->addNode($known);

        $this->expectException(GraphInvariantViolationException::class);
        $graph->addEdge(new GraphEdge($known, $unknown));
    }
}
