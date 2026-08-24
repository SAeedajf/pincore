<?php

declare(strict_types=1);

namespace Pinoox\Component\PackageManager\Domain;

/**
 * Represents a package dependency requirement.
 */
final readonly class Dependency
{
    public function __construct(
        public string $name,
        public ?string $constraint = null,
        public bool $optional = false,
    ) {
        if ($this->name === '') {
            throw new \InvalidArgumentException('Dependency name cannot be empty.');
        }
    }
}
