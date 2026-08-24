<?php

declare(strict_types=1);

namespace Pinoox\Component\PackageManager\Dependency\Solver;

final class MultiPackageSolveResult
{
    /** @var array<string, Candidate> */
    private array $selections;

    /** @param array<string, Candidate> $selections */
    private function __construct(
        array $selections,
        private readonly ?ConstraintConflict $conflict
    ) {
        foreach ($selections as $package => $candidate) {
            if (!$candidate instanceof Candidate || $package !== $candidate->package()) {
                throw new \InvalidArgumentException('Selections must be keyed by their candidate package.');
            }
        }

        $this->selections = $selections;
    }

    /** @param array<string, Candidate> $selections */
    public static function satisfied(array $selections): self
    {
        ksort($selections);

        return new self($selections, null);
    }

    public static function unsatisfied(ConstraintConflict $conflict): self
    {
        return new self([], $conflict);
    }

    public function isSatisfied(): bool
    {
        return $this->conflict === null;
    }

    /** @return array<string, Candidate> */
    public function selections(): array
    {
        return $this->selections;
    }

    public function conflict(): ?ConstraintConflict
    {
        return $this->conflict;
    }
}
