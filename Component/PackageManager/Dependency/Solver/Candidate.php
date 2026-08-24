<?php

declare(strict_types=1);

namespace Pinoox\Component\PackageManager\Dependency\Solver;

use Pinoox\Component\PackageManager\Dependency\Constraint\SemanticVersion;

final class Candidate
{
    /** @param list<PackageRequirement> $requirements */
    public function __construct(
        private readonly string $package,
        private readonly SemanticVersion $version,
        private readonly array $requirements = [],
        private readonly ?array $artifact = null
    ) {
        if (trim($package) === '') {
            throw new \InvalidArgumentException('A candidate package identifier cannot be empty.');
        }

        foreach ($requirements as $requirement) {
            if (!$requirement instanceof PackageRequirement) {
                throw new \InvalidArgumentException('A candidate requirement must be a package requirement.');
            }
        }

        $identities = array_map(
            static fn (PackageRequirement $requirement): string => $requirement->package() . "\0" . $requirement->source(),
            $requirements
        );

        if (count($identities) !== count(array_unique($identities))) {
            throw new \InvalidArgumentException('A candidate cannot duplicate a requirement source for one package.');
        }

        if ($artifact !== null && (!isset($artifact['uri'], $artifact['sha256'], $artifact['size'])
            || !is_string($artifact['uri']) || preg_match('/^[a-f0-9]{64}$/', $artifact['sha256']) !== 1
            || !is_int($artifact['size']) || $artifact['size'] < 0)) {
            throw new \InvalidArgumentException('A candidate artifact is invalid.');
        }
    }

    public function package(): string
    {
        return $this->package;
    }

    public function version(): SemanticVersion
    {
        return $this->version;
    }

    /** @return list<PackageRequirement> */
    public function requirements(): array
    {
        return $this->requirements;
    }

    /** @return array{uri: string, sha256: string, size: int}|null */
    public function artifact(): ?array
    {
        return $this->artifact;
    }
}
