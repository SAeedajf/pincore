<?php

declare(strict_types=1);

namespace Pinoox\Component\PackageManager\Dependency\Solver;

use Pinoox\Component\PackageManager\Dependency\Constraint\ConstraintRequirement;
use Pinoox\Component\PackageManager\Dependency\Constraint\ConstraintSet;

final class MultiPackageSolver
{
    public function __construct(private readonly CandidateProvider $candidates)
    {
    }

    public function solve(MultiPackageSolveRequest $request): MultiPackageSolveResult
    {
        /** @var array<string, list<ConstraintRequirement>> $requirements */
        $requirements = [];

        foreach ($request->requirements() as $requirement) {
            $requirements[$requirement->package()][] = new ConstraintRequirement(
                $requirement->source(),
                $requirement->constraint()
            );
        }

        return $this->resolve($requirements, []);
    }

    /**
     * @param array<string, list<ConstraintRequirement>> $requirements
     * @param array<string, Candidate> $selections
     */
    private function resolve(array $requirements, array $selections): MultiPackageSolveResult
    {
        ksort($requirements);

        foreach ($requirements as $package => $items) {
            $constraints = new ConstraintSet($items);

            if (isset($selections[$package]) && !$constraints->matches($selections[$package]->version())) {
                return MultiPackageSolveResult::unsatisfied(new ConstraintConflict($package, $items));
            }
        }

        $package = $this->nextUnselectedPackage($requirements, $selections);

        if ($package === null) {
            return MultiPackageSolveResult::satisfied($selections);
        }

        $items = $requirements[$package];
        $constraints = new ConstraintSet($items);
        $candidates = $this->candidates->candidatesFor($package);

        if ($candidates->package() !== null && $candidates->package() !== $package) {
            throw new \LogicException('A candidate provider returned candidates for the wrong package.');
        }

        $failure = null;

        foreach ($candidates->all() as $candidate) {
            if (!$constraints->matches($candidate->version())) {
                continue;
            }

            $nextRequirements = $requirements;

            foreach ($candidate->requirements() as $requirement) {
                $nextRequirements[$requirement->package()][] = new ConstraintRequirement(
                    $requirement->source(),
                    $requirement->constraint()
                );
            }

            $nextSelections = $selections;
            $nextSelections[$package] = $candidate;
            $attempt = $this->resolve($nextRequirements, $nextSelections);

            if ($attempt->isSatisfied()) {
                return $attempt;
            }

            $failure ??= $attempt;
        }

        return $failure ?? MultiPackageSolveResult::unsatisfied(new ConstraintConflict($package, $items));
    }

    /**
     * @param array<string, list<ConstraintRequirement>> $requirements
     * @param array<string, Candidate> $selections
     */
    private function nextUnselectedPackage(array $requirements, array $selections): ?string
    {
        foreach (array_keys($requirements) as $package) {
            if (!isset($selections[$package])) {
                return $package;
            }
        }

        return null;
    }
}
