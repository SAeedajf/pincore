<?php

namespace Pinoox\Component\PackageManager\Domain;

/**
 * Describes a package requirement.
 *
 * A requirement can target a concrete package or a future capability.
 */
final class Requirement
{
    public function __construct(
        private readonly string $target,
        private readonly Constraint $constraint,
        private readonly bool $optional = false
    ) {
    }

    public function target(): string
    {
        return $this->target;
    }

    public function constraint(): Constraint
    {
        return $this->constraint;
    }

    public function isOptional(): bool
    {
        return $this->optional;
    }
}
