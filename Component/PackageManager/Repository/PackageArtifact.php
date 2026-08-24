<?php

declare(strict_types=1);

namespace Pinoox\Component\PackageManager\Repository;

final readonly class PackageArtifact
{
    public function __construct(
        public string $location,
        public ?string $hash = null,
    ) {
        if ($this->location === '') {
            throw new \InvalidArgumentException('Package artifact location cannot be empty.');
        }
    }
}
