<?php

declare(strict_types=1);

namespace Pinoox\Component\PackageManager\Dependency\Solver;

interface CandidateProvider
{
    public function candidatesFor(string $package): CandidateSet;
}
