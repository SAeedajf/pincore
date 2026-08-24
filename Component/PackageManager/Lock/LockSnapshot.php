<?php

declare(strict_types=1);

namespace Pinoox\Component\PackageManager\Lock;

use Pinoox\Component\PackageManager\Dependency\Solver\MultiPackageSolveResult;

final class LockSnapshot
{
    public const FORMAT_VERSION = 1;

    /** @var list<LockedPackage> */
    private array $packages;

    /** @param list<LockedPackage> $packages */
    private function __construct(array $packages)
    {
        foreach ($packages as $package) {
            if (!$package instanceof LockedPackage) {
                throw new \InvalidArgumentException('A lock snapshot may contain only locked packages.');
            }
        }

        usort($packages, static fn (LockedPackage $left, LockedPackage $right): int =>
            $left->package() <=> $right->package()
        );

        $names = array_map(static fn (LockedPackage $package): string => $package->package(), $packages);

        if (count($names) !== count(array_unique($names))) {
            throw new \InvalidArgumentException('A lock snapshot cannot contain the same package twice.');
        }

        $this->packages = $packages;
    }

    public static function fromSolveResult(MultiPackageSolveResult $result): self
    {
        if (!$result->isSatisfied()) {
            throw new \LogicException('An unsatisfied solve result cannot produce a lock snapshot.');
        }

        return new self(array_map(
            static fn ($candidate): LockedPackage => LockedPackage::fromCandidate($candidate),
            $result->selections()
        ));
    }

    /** @return list<LockedPackage> */
    public function packages(): array
    {
        return $this->packages;
    }

    /** @return array{format: int, packages: list<array{package: string, version: string, requirements: list<array{source: string, package: string, constraint: string}>}>} */
    public function toArray(): array
    {
        return [
            'format' => self::FORMAT_VERSION,
            'packages' => array_map(
                static fn (LockedPackage $package): array => $package->toArray(),
                $this->packages
            ),
        ];
    }

    public function fingerprint(): string
    {
        return hash('sha256', json_encode(
            $this->toArray(),
            JSON_THROW_ON_ERROR | JSON_UNESCAPED_SLASHES
        ));
    }
}
