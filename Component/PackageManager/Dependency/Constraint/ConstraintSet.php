<?php

declare(strict_types=1);

namespace Pinoox\Component\PackageManager\Dependency\Constraint;

final class ConstraintSet
{
    /** @var list<ConstraintRequirement> */
    private array $requirements;

    /** @param list<ConstraintRequirement> $requirements */
    public function __construct(array $requirements)
    {
        $sources = array_map(
            static fn (ConstraintRequirement $requirement): string => $requirement->source(),
            $requirements
        );

        if (count($sources) !== count(array_unique($sources))) {
            throw new \InvalidArgumentException('A constraint set cannot contain duplicate requirement sources.');
        }

        $this->requirements = $requirements;
    }

    public function matches(SemanticVersion $candidate): bool
    {
        foreach ($this->requirements as $requirement) {
            if (!$requirement->constraint()->matches($candidate)) {
                return false;
            }
        }

        return true;
    }

    /** @return list<ConstraintRequirement> */
    public function requirements(): array
    {
        return $this->requirements;
    }
}
