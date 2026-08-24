<?php

declare(strict_types=1);

namespace Pinoox\Component\PackageManager\Domain;

/**
 * Immutable package version representation.
 *
 * Keeps Pinoox version-code compatibility while allowing future semantic
 * version constraints in the PackageManager layer.
 */
final readonly class PackageVersion
{
    public function __construct(
        public string $name,
        public int $code,
    ) {
        if ($this->name === '') {
            throw new \InvalidArgumentException('Package version name cannot be empty.');
        }

        if ($this->code < 0) {
            throw new \InvalidArgumentException('Package version code must be positive.');
        }
    }

    public function equals(self $version): bool
    {
        return $this->name === $version->name && $this->code === $version->code;
    }
}
