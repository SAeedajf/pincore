<?php

namespace Pinoox\Component\PackageManager\Domain;

/**
 * Represents a package version constraint.
 *
 * This is intentionally a value object. Constraint parsing and solving
 * will be implemented in later phases by the solver layer.
 */
final class Constraint
{
    public function __construct(
        private readonly string $expression
    ) {
    }

    public function expression(): string
    {
        return $this->expression;
    }

    public function isEmpty(): bool
    {
        return trim($this->expression) === '';
    }
}
