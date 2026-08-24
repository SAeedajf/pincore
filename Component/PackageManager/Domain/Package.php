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
     * @param list<Capability> $capabilities
     */
    public function __construct(
        public PackageIdentifier $identifier,
        public PackageVersion $version,
        public array $dependencies = [],
        public array $capabilities = [],
        public ?PackageMetadata $metadata = null,
    ) {
        foreach ($this->dependencies as $dependency) {
            if (!$dependency instanceof Dependency) {
                throw new \InvalidArgumentException('Package dependencies must be Dependency objects.');
            }
        }

        foreach ($this->capabilities as $capability) {
            if (!$capability instanceof Capability) {
                throw new \InvalidArgumentException('Package capabilities must be Capability objects.');
            }
        }
    }

    public function name(): string
    {
        return $this->identifier->name();
    }
}
