<?php

declare(strict_types=1);

namespace Pinoox\Component\PackageManager\Lock;

use Pinoox\Component\PackageManager\Dependency\Solver\Candidate;

final class LockedPackage
{
    /** @var list<LockedRequirement> */
    private array $requirements;

    /** @param list<LockedRequirement> $requirements */
    public function __construct(
        private readonly string $package,
        private readonly string $version,
        array $requirements = [],
        private readonly ?array $artifact = null
    ) {
        if (trim($package) === '' || trim($version) === '') {
            throw new \InvalidArgumentException('A locked package needs a package identifier and version.');
        }

        foreach ($requirements as $requirement) {
            if (!$requirement instanceof LockedRequirement) {
                throw new \InvalidArgumentException('A locked package may contain only locked requirements.');
            }
        }

        usort($requirements, static fn (LockedRequirement $left, LockedRequirement $right): int => [
            $left->package(),
            $left->constraint(),
            $left->source(),
        ] <=> [
            $right->package(),
            $right->constraint(),
            $right->source(),
        ]);

        $this->requirements = $requirements;
    }

    public static function fromCandidate(Candidate $candidate): self
    {
        return new self(
            $candidate->package(),
            (string) $candidate->version(),
            array_map(
                static fn ($requirement): LockedRequirement => LockedRequirement::fromPackageRequirement($requirement),
                $candidate->requirements()
            ),
            $candidate->artifact()
        );
    }

    public function package(): string
    {
        return $this->package;
    }

    public function version(): string
    {
        return $this->version;
    }

    /** @return list<LockedRequirement> */
    public function requirements(): array
    {
        return $this->requirements;
    }

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        return [
            'package' => $this->package,
            'version' => $this->version,
            'requirements' => array_map(
                static fn (LockedRequirement $requirement): array => $requirement->toArray(),
                $this->requirements
            ),
            'artifact' => $this->artifact,
        ];
    }
}
