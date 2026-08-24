<?php

declare(strict_types=1);

namespace Pinoox\Component\PackageManager\Dependency\Solver;

use Pinoox\Component\PackageManager\Dependency\Constraint\ConstraintRequirement;

final class ConstraintConflict
{
    /** @param list<ConstraintRequirement> $requirements */
    public function __construct(
        private readonly ?string $package,
        private readonly array $requirements
    ) {
    }

    public function package(): ?string
    {
        return $this->package;
    }

    /** @return list<ConstraintRequirement> */
    public function requirements(): array
    {
        return $this->requirements;
    }
}
