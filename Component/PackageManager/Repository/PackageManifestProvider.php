<?php

declare(strict_types=1);

namespace Pinoox\Component\PackageManager\Repository;

interface PackageManifestProvider
{
    /** @return list<PackageManifest> */
    public function manifestsFor(string $package): array;
}
