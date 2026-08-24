<?php

declare(strict_types=1);

namespace Pinoox\Tests\Unit\Component\PackageManager\Dependency\Constraint;

use PHPUnit\Framework\TestCase;
use Pinoox\Component\PackageManager\Dependency\Constraint\Exception\InvalidVersionException;
use Pinoox\Component\PackageManager\Dependency\Constraint\SemanticVersion;
use Pinoox\Component\PackageManager\Dependency\Constraint\VersionConstraintMatcher;

final class VersionConstraintTest extends TestCase
{
    public function test_it_compares_semantic_versions_and_pre_releases(): void
    {
        self::assertGreaterThan(
            0,
            SemanticVersion::parse('1.0.0')->compareTo(SemanticVersion::parse('1.0.0-rc.1'))
        );
        self::assertLessThan(
            0,
            SemanticVersion::parse('2.0.0-beta.2')->compareTo(SemanticVersion::parse('2.0.0-beta.11'))
        );
    }

    public function test_it_matches_comparator_intersections(): void
    {
        $matcher = new VersionConstraintMatcher();

        self::assertTrue($matcher->matches('1.5.0', '>=1.2.0 <2.0.0'));
        self::assertFalse($matcher->matches('2.0.0', '>=1.2.0 <2.0.0'));
    }

    public function test_it_matches_caret_ranges_including_zero_major_versions(): void
    {
        $matcher = new VersionConstraintMatcher();

        self::assertTrue($matcher->matches('1.9.4', '^1.2.0'));
        self::assertFalse($matcher->matches('2.0.0', '^1.2.0'));
        self::assertTrue($matcher->matches('0.2.9', '^0.2.3'));
        self::assertFalse($matcher->matches('0.3.0', '^0.2.3'));
    }

    public function test_it_rejects_invalid_versions_and_unsupported_or_ranges(): void
    {
        $this->expectException(InvalidVersionException::class);
        SemanticVersion::parse('1.2');
    }

    public function test_it_rejects_or_ranges_until_the_solver_introduces_disjunctions(): void
    {
        $this->expectException(InvalidVersionException::class);
        (new VersionConstraintMatcher())->matches('1.5.0', '^1.2.0 || ^2.0.0');
    }
}
