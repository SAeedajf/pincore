<?php

declare(strict_types=1);

namespace Pinoox\Component\PackageManager\Dependency\Solver;

use Pinoox\Component\PackageManager\Dependency\Constraint\VersionConstraint;

final class PackageRequirement
{
    public function __construct(
        private readonly string $source,
        private readonly string $package,
        private readonly VersionConstraint $constraint
    ) {
        if (trim($source) === '') {
            throw new \InvalidArgumentException('A package requirement source cannot be empty.');
        }

        if (trim($package) === '') {
            throw new \InvalidArgumentException('A package requirement package cannot be empty.');
        }
    }

    public function source(): string
    {
        return $this->source;
    }

    public function package(): string
    {
        return $this->package;
    }

    public function constraint(): VersionConstraint
    {
        return $this->constraint;
    }
}
