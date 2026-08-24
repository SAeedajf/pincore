<?php

declare(strict_types=1);

namespace Pinoox\Component\PackageManager\Dependency\Solver;

use Pinoox\Component\PackageManager\Dependency\Constraint\VersionConstraint;

final class CandidateSet
{
    /** @var list<Candidate> */
    private array $candidates;

    /** @param list<Candidate> $candidates */
    public function __construct(array $candidates)
    {
        $packages = array_values(array_unique(array_map(
            static fn (Candidate $candidate): string => $candidate->package(),
            $candidates
        )));

        if (count($packages) > 1) {
            throw new \InvalidArgumentException('A candidate set may contain versions of only one package.');
        }

        $this->candidates = $candidates;
    }

    public function package(): ?string
    {
        return $this->candidates[0]->package() ?? null;
    }

    /** @return list<Candidate> */
    public function all(): array
    {
        $candidates = $this->candidates;

        usort($candidates, static fn (Candidate $left, Candidate $right): int =>
            $right->version()->compareTo($left->version())
        );

        return $candidates;
    }

    /** @return list<Candidate> */
    public function matching(VersionConstraint $constraint): array
    {
        return array_values(array_filter(
            $this->all(),
            static fn (Candidate $candidate): bool => $constraint->matches($candidate->version())
        ));
    }
}
