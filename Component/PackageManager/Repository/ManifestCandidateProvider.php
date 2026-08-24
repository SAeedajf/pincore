<?php

declare(strict_types=1);

namespace Pinoox\Component\PackageManager\Repository;

use Pinoox\Component\PackageManager\Dependency\Solver\CandidateProvider;
use Pinoox\Component\PackageManager\Dependency\Solver\CandidateSet;

final class ManifestCandidateProvider implements CandidateProvider
{
    public function __construct(private readonly PackageManifestProvider $manifests)
    {
    }

    public function candidatesFor(string $package): CandidateSet
    {
        $candidates = [];

        foreach ($this->manifests->manifestsFor($package) as $manifest) {
            if (!$manifest instanceof PackageManifest) {
                throw new \LogicException('A manifest provider must return only package manifests.');
            }

            if ($manifest->package() !== $package) {
                throw new \LogicException('A manifest provider returned a manifest for the wrong package.');
            }

            $candidates[] = $manifest->candidate();
        }

        return new CandidateSet($candidates);
    }
}
