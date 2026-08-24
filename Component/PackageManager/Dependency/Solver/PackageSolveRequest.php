<?php

declare(strict_types=1);

namespace Pinoox\Component\PackageManager\Dependency\Solver;

use Pinoox\Component\PackageManager\Dependency\Constraint\ConstraintSet;

final class PackageSolveRequest
{
    public function __construct(
        private readonly string $package,
        private readonly ConstraintSet $constraints
    ) {
        if (trim($package) === '') {
            throw new \InvalidArgumentException('A package solve request requires a package identifier.');
        }
    }

    public function package(): string
    {
        return $this->package;
    }

    public function constraints(): ConstraintSet
    {
        return $this->constraints;
    }
}
