<?php

declare(strict_types=1);

namespace Pinoox\Component\PackageManager\Registry;

use Pinoox\Component\PackageManager\Domain\Package;
use Pinoox\Component\PackageManager\Domain\PackageIdentifier;

interface PackageRegistryInterface
{
    public function find(PackageIdentifier $identifier): ?Package;

    public function has(PackageIdentifier $identifier): bool;

    /** @return list<Package> */
    public function all(): array;

    public function register(Package $package): void;

    public function remove(PackageIdentifier $identifier): void;
}
