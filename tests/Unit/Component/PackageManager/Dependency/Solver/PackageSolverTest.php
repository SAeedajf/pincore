<?php

declare(strict_types=1);

namespace Pinoox\Tests\Unit\Component\PackageManager\Dependency\Solver;

use PHPUnit\Framework\TestCase;
use Pinoox\Component\PackageManager\Dependency\Constraint\ConstraintRequirement;
use Pinoox\Component\PackageManager\Dependency\Constraint\ConstraintSet;
use Pinoox\Component\PackageManager\Dependency\Constraint\SemanticVersion;
use Pinoox\Component\PackageManager\Dependency\Constraint\VersionConstraint;
use Pinoox\Component\PackageManager\Dependency\Solver\Candidate;
use Pinoox\Component\PackageManager\Dependency\Solver\CandidateSet;
use Pinoox\Component\PackageManager\Dependency\Solver\InMemoryCandidateProvider;
use Pinoox\Component\PackageManager\Dependency\Solver\PackageSolveRequest;
use Pinoox\Component\PackageManager\Dependency\Solver\PackageSolver;

final class PackageSolverTest extends TestCase
{
    public function test_it_solves_one_package_through_the_provider_boundary(): void
    {
        $solver = new PackageSolver(new InMemoryCandidateProvider([
            'payment' => new CandidateSet([
                new Candidate('payment', SemanticVersion::parse('1.5.0')),
                new Candidate('payment', SemanticVersion::parse('1.9.0')),
            ]),
        ]));

        $result = $solver->solve(new PackageSolveRequest('payment', new ConstraintSet([
            new ConstraintRequirement('com_shop', VersionConstraint::parse('^1.2.0')),
        ])));

        self::assertTrue($result->isSatisfied());
        self::assertSame('payment', $result->package());
        self::assertSame('1.9.0', (string) $result->selection()->candidate()?->version());
    }

    public function test_it_returns_an_unsatisfied_result_for_a_missing_package(): void
    {
        $result = (new PackageSolver(new InMemoryCandidateProvider([])))->solve(
            new PackageSolveRequest('payment', new ConstraintSet([]))
        );

        self::assertFalse($result->isSatisfied());
        self::assertSame('payment', $result->package());
    }

    public function test_it_rejects_a_provider_that_returns_candidates_for_another_package(): void
    {
        $solver = new PackageSolver(new InMemoryCandidateProvider([
            'payment' => new CandidateSet([
                new Candidate('other-package', SemanticVersion::parse('1.0.0')),
            ]),
        ]));

        $this->expectException(\LogicException::class);
        $solver->solve(new PackageSolveRequest('payment', new ConstraintSet([])));
    }
}
