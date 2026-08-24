<?php

declare(strict_types=1);

namespace Pinoox\Component\PackageManager\Dependency\Solver;

use Pinoox\Component\PackageManager\Dependency\Constraint\SemanticVersion;

final class Candidate
{
    public function __construct(
        private readonly string $package,
        private readonly SemanticVersion $version
    ) {
        if (trim($package) === '') {
            throw new \InvalidArgumentException('A candidate package identifier cannot be empty.');
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
}
