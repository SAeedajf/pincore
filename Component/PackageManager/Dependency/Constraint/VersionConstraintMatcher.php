<?php

declare(strict_types=1);

namespace Pinoox\Component\PackageManager\Dependency\Constraint;

final class VersionConstraintMatcher
{
    public function matches(string|SemanticVersion $candidate, string|VersionConstraint $constraint): bool
    {
        $version = is_string($candidate) ? SemanticVersion::parse($candidate) : $candidate;
        $parsedConstraint = is_string($constraint) ? VersionConstraint::parse($constraint) : $constraint;

        return $parsedConstraint->matches($version);
    }
}
