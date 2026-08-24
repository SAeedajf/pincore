<?php

declare(strict_types=1);

namespace Pinoox\Component\PackageManager\Domain;

/**
 * Core package entity used by the future universal package manager.
 *
 * This layer intentionally does not perform installation. It only models
 * package identity and metadata.
 */
final readonly class Package
{
    /**
     * @param list<Dependency> $dependencies
     * @param list<string> $capabilities
     */
    public function __construct(
        public string $name,
        public PackageVersion $version,
        public array $dependencies = [],
        public array $capabilities = [],
    ) {
        if ($this->name === '') {
            throw new \InvalidArgumentException('Package name cannot be empty.');
        }

        foreach ($this->dependencies as $dependency) {
            if (!$dependency instanceof Dependency) {
                throw new \InvalidArgumentException('Package dependencies must be Dependency objects.');
            }
        }
    }
}
