<?php

declare(strict_types=1);

namespace Pinoox\Component\PackageManager\Repository;

use Pinoox\Component\PackageManager\Domain\Package;
use Pinoox\Component\PackageManager\Domain\PackageIdentifier;

interface PackageRepositoryInterface
{
    public function find(PackageIdentifier $identifier): ?Package;

    /** @return list<Package> */
    public function versions(PackageIdentifier $identifier): array;
}
