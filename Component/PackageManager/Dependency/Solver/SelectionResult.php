<?php

declare(strict_types=1);

namespace Pinoox\Component\PackageManager\Dependency\Solver;

use Pinoox\Component\PackageManager\Dependency\Constraint\ConstraintRequirement;

final class SelectionResult
{
    private function __construct(
        private readonly ?Candidate $candidate,
        private readonly ?string $reason,
        private readonly ?ConstraintConflict $conflict = null
    ) {
    }

    public static function selected(Candidate $candidate): self
    {
        return new self($candidate, null);
    }

    /** @param list<ConstraintRequirement> $requirements */
    public static function unsatisfied(
        string $reason,
        ?string $package = null,
        array $requirements = []
    ): self
    {
        return new self(
            null,
            $reason,
            $requirements === [] ? null : new ConstraintConflict($package, $requirements)
        );
    }

    public function isSatisfied(): bool
    {
        return $this->candidate !== null;
    }

    public function candidate(): ?Candidate
    {
        return $this->candidate;
    }

    public function reason(): ?string
    {
        return $this->reason;
    }

    public function conflict(): ?ConstraintConflict
    {
        return $this->conflict;
    }
}
