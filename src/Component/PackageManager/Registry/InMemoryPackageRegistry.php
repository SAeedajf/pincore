<?php

declare(strict_types=1);

namespace Pinoox\PackageManager\Registry;

/**
 * Temporary in-memory registry implementation.
 *
 * This implementation exists for domain verification and testing only.
 * Persistence adapters will be introduced in later phases.
 */
final class InMemoryPackageRegistry
{
    /** @var array<string, object> */
    private array $packages = [];

    public function has(string $identifier): bool
    {
        return isset($this->packages[$identifier]);
    }

    public function get(string $identifier): ?object
    {
        return $this->packages[$identifier] ?? null;
    }

    public function register(string $identifier, object $package): void
    {
        $this->packages[$identifier] = $package;
    }

    public function remove(string $identifier): void
    {
        unset($this->packages[$identifier]);
    }

    /** @return array<string, object> */
    public function all(): array
    {
        return $this->packages;
    }
}
