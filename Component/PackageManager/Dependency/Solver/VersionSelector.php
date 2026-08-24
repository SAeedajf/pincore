<?php

declare(strict_types=1);

namespace Pinoox\Component\PackageManager\Dependency\Solver;

use Pinoox\Component\PackageManager\Dependency\Constraint\ConstraintSet;
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

    public function selectForConstraints(CandidateSet $candidates, ConstraintSet $constraints): SelectionResult
    {
        $matches = array_values(array_filter(
            $candidates->all(),
            static fn (Candidate $candidate): bool => $constraints->matches($candidate->version())
        ));

        if ($matches === []) {
            return SelectionResult::unsatisfied(
                'No available candidate satisfies all requested version constraints.',
                $candidates->package(),
                $constraints->requirements()
            );
        }

        return SelectionResult::selected($matches[0]);
    }
}
