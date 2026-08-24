<?php

declare(strict_types=1);

namespace Pinoox\Component\PackageManager\Lock;

use Pinoox\Component\PackageManager\Dependency\Solver\PackageRequirement;

final class LockedRequirement
{
    public function __construct(
        private readonly string $source,
        private readonly string $package,
        private readonly string $constraint
    ) {
        if (trim($source) === '' || trim($package) === '' || trim($constraint) === '') {
            throw new \InvalidArgumentException('A locked requirement needs a source, package, and constraint.');
        }
    }

    public static function fromPackageRequirement(PackageRequirement $requirement): self
    {
        return new self(
            $requirement->source(),
            $requirement->package(),
            $requirement->constraint()->expression()
        );
    }

    public function source(): string
    {
        return $this->source;
    }

    public function package(): string
    {
        return $this->package;
    }

    public function constraint(): string
    {
        return $this->constraint;
    }

    /** @return array{source: string, package: string, constraint: string} */
    public function toArray(): array
    {
        return [
            'source' => $this->source,
            'package' => $this->package,
            'constraint' => $this->constraint,
        ];
    }
}
