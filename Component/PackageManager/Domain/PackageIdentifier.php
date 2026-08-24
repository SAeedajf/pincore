<?php

namespace Pinoox\Component\PackageManager\Domain;

/**
 * Immutable package identity representation.
 */
final class PackageIdentifier
{
    public function __construct(
        private readonly string $name
    ) {
        if (trim($name) === '') {
            throw new \InvalidArgumentException('Package name cannot be empty.');
        }
    }

    public function name(): string
    {
        return $this->name;
    }

    public function equals(PackageIdentifier $identifier): bool
    {
        return $this->name === $identifier->name();
    }
}
