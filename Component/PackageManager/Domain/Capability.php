<?php

declare(strict_types=1);

namespace Pinoox\Component\PackageManager\Domain;

/**
 * Represents a provided capability instead of a concrete package dependency.
 */
final readonly class Capability
{
    public function __construct(
        public string $name,
        public ?string $versionConstraint = null,
    ) {
        if ($this->name === '') {
            throw new \InvalidArgumentException('Capability name cannot be empty.');
        }
    }
}
