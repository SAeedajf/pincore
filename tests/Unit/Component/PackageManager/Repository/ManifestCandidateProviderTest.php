<?php

declare(strict_types=1);

namespace Pinoox\Tests\Unit\Component\PackageManager\Repository;

use PHPUnit\Framework\TestCase;
use Pinoox\Component\PackageManager\Dependency\Constraint\SemanticVersion;
use Pinoox\Component\PackageManager\Dependency\Constraint\VersionConstraint;
use Pinoox\Component\PackageManager\Dependency\Solver\MultiPackageSolveRequest;
use Pinoox\Component\PackageManager\Dependency\Solver\MultiPackageSolver;
use Pinoox\Component\PackageManager\Dependency\Solver\PackageRequirement;
use Pinoox\Component\PackageManager\Repository\InMemoryPackageManifestProvider;
use Pinoox\Component\PackageManager\Repository\ManifestCandidateProvider;
use Pinoox\Component\PackageManager\Repository\PackageManifest;
use Pinoox\Component\PackageManager\Repository\PackageManifestProvider;

final class ManifestCandidateProviderTest extends TestCase
{
    public function test_it_exposes_manifests_as_descending_solver_candidates_with_dependencies(): void
    {
        $provider = new ManifestCandidateProvider(new InMemoryPackageManifestProvider([
            'application' => [
                new PackageManifest('application', SemanticVersion::parse('1.1.0'), [
                    'z-library' => VersionConstraint::parse('^1.0.0'),
                    'a-library' => VersionConstraint::parse('~2.0.0'),
                ]),
                new PackageManifest('application', SemanticVersion::parse('1.3.0')),
            ],
        ]));

        $candidates = $provider->candidatesFor('application')->all();

        self::assertSame(['1.3.0', '1.1.0'], array_map(
            static fn ($candidate): string => (string) $candidate->version(),
            $candidates
        ));
        self::assertSame(['a-library', 'z-library'], array_map(
            static fn ($requirement): string => $requirement->package(),
            $candidates[1]->requirements()
        ));
        self::assertSame('application@1.1.0', $candidates[1]->requirements()[0]->source());
    }

    public function test_it_returns_an_empty_candidate_set_when_a_manifest_is_missing(): void
    {
        $provider = new ManifestCandidateProvider(new InMemoryPackageManifestProvider([]));

        self::assertNull($provider->candidatesFor('missing')->package());
        self::assertSame([], $provider->candidatesFor('missing')->all());
    }

    public function test_it_rejects_manifests_stored_under_another_package(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        new InMemoryPackageManifestProvider([
            'application' => [new PackageManifest('other-package', SemanticVersion::parse('1.0.0'))],
        ]);
    }

    public function test_it_rejects_a_provider_that_returns_a_manifest_for_another_package(): void
    {
        $provider = new class implements PackageManifestProvider {
            public function manifestsFor(string $package): array
            {
                return [new PackageManifest('other-package', SemanticVersion::parse('1.0.0'))];
            }
        };

        $this->expectException(\LogicException::class);
        (new ManifestCandidateProvider($provider))->candidatesFor('application');
    }

    public function test_it_rejects_a_provider_that_returns_an_invalid_manifest_entry(): void
    {
        $provider = new class implements PackageManifestProvider {
            public function manifestsFor(string $package): array
            {
                return ['invalid'];
            }
        };

        $this->expectException(\LogicException::class);
        (new ManifestCandidateProvider($provider))->candidatesFor('application');
    }

    public function test_it_solves_transitive_manifests_without_coupling_to_the_installer(): void
    {
        $provider = new ManifestCandidateProvider(new InMemoryPackageManifestProvider([
            'application' => [new PackageManifest('application', SemanticVersion::parse('1.0.0'), [
                'library' => VersionConstraint::parse('^1.0.0'),
            ])],
            'library' => [new PackageManifest('library', SemanticVersion::parse('1.4.0'))],
        ]));

        $result = (new MultiPackageSolver($provider))->solve(new MultiPackageSolveRequest([
            new PackageRequirement('root', 'application', VersionConstraint::parse('^1.0.0')),
        ]));

        self::assertTrue($result->isSatisfied());
        self::assertSame('1.0.0', (string) $result->selections()['application']->version());
        self::assertSame('1.4.0', (string) $result->selections()['library']->version());
    }
}
