<?php

declare(strict_types=1);

namespace Pinoox\Tests\Unit\Component\PackageManager\Dependency\Solver;

use PHPUnit\Framework\TestCase;
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
}
