<?php

namespace Pinoox\Component\PackageManager\Domain;

/**
 * Immutable package metadata container.
 */
final class PackageMetadata
{
    /**
     * @param array<string,mixed> $metadata
     */
    public function __construct(
        private readonly array $metadata = []
    ) {
    }

    /**
     * @return array<string,mixed>
     */
    public function all(): array
    {
        return $this->metadata;
    }

    public function get(string $key, mixed $default = null): mixed
    {
        return $this->metadata[$key] ?? $default;
    }
}
