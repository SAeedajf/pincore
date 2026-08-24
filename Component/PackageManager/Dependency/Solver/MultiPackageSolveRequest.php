<?php

declare(strict_types=1);

namespace Pinoox\Component\PackageManager\Dependency\Solver;

final class MultiPackageSolveRequest
{
    /** @var list<PackageRequirement> */
    private array $requirements;

    /** @param list<PackageRequirement> $requirements */
    public function __construct(array $requirements)
    {
        foreach ($requirements as $requirement) {
            if (!$requirement instanceof PackageRequirement) {
                throw new \InvalidArgumentException('A multi-package solve request must contain package requirements.');
            }
        }

        $identities = array_map(
            static fn (PackageRequirement $requirement): string => $requirement->package() . "\0" . $requirement->source(),
            $requirements
        );

        if (count($identities) !== count(array_unique($identities))) {
            throw new \InvalidArgumentException('A multi-package solve request cannot duplicate a requirement source for one package.');
        }

        $this->requirements = $requirements;
    }

    /** @return list<PackageRequirement> */
    public function requirements(): array
    {
        return $this->requirements;
    }
}
