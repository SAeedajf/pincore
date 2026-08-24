<?php

declare(strict_types=1);

namespace Pinoox\PackageManager\Repository;

/**
 * Local repository implementation used for testing resolver flows.
 *
 * It intentionally does not download or install packages.
 */
final class InMemoryPackageRepository
{
    /** @var array<string, array<int, object>> */
    private array $packages = [];

    public function add(string $identifier, object $package): void
    {
        $this->packages[$identifier][] = $package;
    }

    /** @return array<int, object> */
    public function find(string $identifier): array
    {
        return $this->packages[$identifier] ?? [];
    }
}
