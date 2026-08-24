<?php

declare(strict_types=1);

namespace Pinoox\Component\PackageManager\Dependency\Solver;

use Pinoox\Component\PackageManager\Dependency\Constraint\VersionConstraint;

final class VersionSelector
{
    public function select(CandidateSet $candidates, VersionConstraint $constraint): SelectionResult
    {
        $matches = $candidates->matching($constraint);

        if ($matches === []) {
            return SelectionResult::unsatisfied('No available candidate satisfies the requested version constraint.');
        }

        return SelectionResult::selected($matches[0]);
    }
}
