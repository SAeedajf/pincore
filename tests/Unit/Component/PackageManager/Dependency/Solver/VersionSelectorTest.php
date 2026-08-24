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
use Pinoox\Component\PackageManager\Dependency\Solver\VersionSelector;

final class VersionSelectorTest extends TestCase
{
    public function test_it_selects_the_highest_compatible_candidate(): void
    {
        $candidates = new CandidateSet([
            new Candidate('payment', SemanticVersion::parse('1.9.0')),
            new Candidate('payment', SemanticVersion::parse('1.4.2')),
            new Candidate('payment', SemanticVersion::parse('2.0.0')),
        ]);

        $result = (new VersionSelector())->select(
            $candidates,
            VersionConstraint::parse('^1.2.0')
        );

        self::assertTrue($result->isSatisfied());
        self::assertSame('1.9.0', (string) $result->candidate()?->version());
    }

    public function test_it_returns_an_explicit_unsatisfied_result(): void
    {
        $result = (new VersionSelector())->select(
            new CandidateSet([new Candidate('payment', SemanticVersion::parse('1.9.0'))]),
            VersionConstraint::parse('^2.0.0')
        );

        self::assertFalse($result->isSatisfied());
        self::assertNull($result->candidate());
        self::assertNotEmpty($result->reason());
    }

    public function test_it_rejects_candidate_sets_that_mix_packages(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        new CandidateSet([
            new Candidate('payment', SemanticVersion::parse('1.0.0')),
            new Candidate('shop', SemanticVersion::parse('1.0.0')),
        ]);
    }

    public function test_it_selects_the_highest_candidate_satisfying_all_requirements(): void
    {
        $result = (new VersionSelector())->selectForConstraints(
            new CandidateSet([
                new Candidate('payment', SemanticVersion::parse('1.5.0')),
                new Candidate('payment', SemanticVersion::parse('1.8.0')),
                new Candidate('payment', SemanticVersion::parse('2.0.0')),
            ]),
            new ConstraintSet([
                new ConstraintRequirement('com_shop', VersionConstraint::parse('^1.2.0')),
                new ConstraintRequirement('com_checkout', VersionConstraint::parse('<1.8.0')),
            ])
        );

        self::assertTrue($result->isSatisfied());
        self::assertSame('1.5.0', (string) $result->candidate()?->version());
    }

    public function test_it_returns_conflict_context_when_requirements_are_incompatible(): void
    {
        $result = (new VersionSelector())->selectForConstraints(
            new CandidateSet([
                new Candidate('payment', SemanticVersion::parse('1.9.0')),
                new Candidate('payment', SemanticVersion::parse('2.0.0')),
            ]),
            new ConstraintSet([
                new ConstraintRequirement('com_shop', VersionConstraint::parse('^1.2.0')),
                new ConstraintRequirement('com_checkout', VersionConstraint::parse('>=2.0.0')),
            ])
        );

        self::assertFalse($result->isSatisfied());
        self::assertSame('payment', $result->conflict()?->package());
        self::assertSame(
            ['com_shop', 'com_checkout'],
            array_map(static fn (ConstraintRequirement $item): string => $item->source(), $result->conflict()?->requirements() ?? [])
        );
    }
}
