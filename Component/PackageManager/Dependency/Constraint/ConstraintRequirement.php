<?php

declare(strict_types=1);

namespace Pinoox\Component\PackageManager\Dependency\Constraint;

final class ConstraintRequirement
{
    public function __construct(
        private readonly string $source,
        private readonly VersionConstraint $constraint
    ) {
        if (trim($source) === '') {
            throw new \InvalidArgumentException('A constraint requirement source cannot be empty.');
        }
    }

    public function source(): string
    {
        return $this->source;
    }

    public function constraint(): VersionConstraint
    {
        return $this->constraint;
    }
}
