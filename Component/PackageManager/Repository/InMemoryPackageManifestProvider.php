<?php

declare(strict_types=1);

namespace Pinoox\Component\PackageManager\Repository;

final class InMemoryPackageManifestProvider implements PackageManifestProvider
{
    /** @var array<string, list<PackageManifest>> */
    private array $manifests;

    /** @param array<string, list<PackageManifest>> $manifests */
    public function __construct(array $manifests)
    {
        foreach ($manifests as $package => $items) {
            if (!is_string($package) || trim($package) === '') {
                throw new \InvalidArgumentException('A manifest-provider package identifier cannot be empty.');
            }

            foreach ($items as $manifest) {
                if (!$manifest instanceof PackageManifest) {
                    throw new \InvalidArgumentException('A manifest provider may contain only package manifests.');
                }

                if ($manifest->package() !== $package) {
                    throw new \InvalidArgumentException('A manifest must be stored under its own package identifier.');
                }
            }
        }

        $this->manifests = $manifests;
    }

    public function manifestsFor(string $package): array
    {
        return $this->manifests[$package] ?? [];
    }
}
