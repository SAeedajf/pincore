<?php

declare(strict_types=1);

namespace Pinoox\Component\PackageManager\Repository;

use Pinoox\Component\PackageManager\Dependency\Constraint\SemanticVersion;
use Pinoox\Component\PackageManager\Dependency\Constraint\VersionConstraint;
use Pinoox\Component\PackageManager\Dependency\Solver\Candidate;
use Pinoox\Component\PackageManager\Dependency\Solver\PackageRequirement;

final class PackageManifest
{
    /** @var array<string, VersionConstraint> */
    private array $requirements;

    /** @param array<string, VersionConstraint> $requirements */
    public function __construct(
        private readonly string $package,
        private readonly SemanticVersion $version,
        array $requirements = []
    ) {
        if (trim($package) === '') {
            throw new \InvalidArgumentException('A package manifest requires a package identifier.');
        }

        foreach ($requirements as $dependency => $constraint) {
            if (!is_string($dependency) || trim($dependency) === '') {
                throw new \InvalidArgumentException('A package manifest dependency identifier cannot be empty.');
            }

            if (!$constraint instanceof VersionConstraint) {
                throw new \InvalidArgumentException('A package manifest dependency constraint must be a version constraint.');
            }
        }

        ksort($requirements);
        $this->requirements = $requirements;
    }

    public function package(): string
    {
        return $this->package;
    }

    public function version(): SemanticVersion
    {
        return $this->version;
    }

    /** @return array<string, VersionConstraint> */
    public function requirements(): array
    {
        return $this->requirements;
    }

    public function candidate(): Candidate
    {
        $source = $this->package . '@' . $this->version;
        $requirements = [];

        foreach ($this->requirements as $package => $constraint) {
            $requirements[] = new PackageRequirement($source, $package, $constraint);
        }

        return new Candidate($this->package, $this->version, $requirements);
    }
}
