<?php

declare(strict_types=1);

namespace Pinoox\Component\PackageManager\Lock;

final class LockSnapshotCodec
{
    public function encode(LockSnapshot $snapshot): string
    {
        return json_encode($snapshot->toArray(), JSON_THROW_ON_ERROR | JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n";
    }

    public function decode(string $payload): LockSnapshot
    {
        $data = json_decode($payload, true, 512, JSON_THROW_ON_ERROR);

        if (!is_array($data) || ($data['format'] ?? null) !== LockSnapshot::FORMAT_VERSION || !isset($data['packages']) || !is_array($data['packages'])) {
            throw new \InvalidArgumentException('Invalid lock snapshot format.');
        }

        $packages = [];
        foreach ($data['packages'] as $item) {
            if (!is_array($item) || !is_string($item['package'] ?? null) || !is_string($item['version'] ?? null) || !is_array($item['requirements'] ?? null)) {
                throw new \InvalidArgumentException('Invalid locked package entry.');
            }
            $requirements = [];
            foreach ($item['requirements'] as $requirement) {
                if (!is_array($requirement) || !is_string($requirement['source'] ?? null) || !is_string($requirement['package'] ?? null) || !is_string($requirement['constraint'] ?? null)) {
                    throw new \InvalidArgumentException('Invalid locked requirement entry.');
                }
                $requirements[] = new LockedRequirement($requirement['source'], $requirement['package'], $requirement['constraint']);
            }
            $packages[] = new LockedPackage($item['package'], $item['version'], $requirements, $item['artifact'] ?? null);
        }

        return LockSnapshot::fromPackages($packages);
    }
}
