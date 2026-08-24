<?php

declare(strict_types=1);

namespace Pinoox\Component\PackageManager\Dependency\Solver;

final class InMemoryCandidateProvider implements CandidateProvider
{
    /** @var array<string, CandidateSet> */
    private array $candidateSets;

    /** @param array<string, CandidateSet> $candidateSets */
    public function __construct(array $candidateSets)
    {
        $this->candidateSets = $candidateSets;
    }

    public function candidatesFor(string $package): CandidateSet
    {
        return $this->candidateSets[$package] ?? new CandidateSet([]);
    }
}
