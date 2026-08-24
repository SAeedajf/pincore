<?php

declare(strict_types=1);

namespace Pinoox\Component\PackageManager\Repository;

final class ArtifactDescriptor
{
    public function __construct(
        private readonly string $uri,
        private readonly string $sha256,
        private readonly int $size
    ) {
        if (filter_var($uri, FILTER_VALIDATE_URL) === false || !in_array(parse_url($uri, PHP_URL_SCHEME), ['https', 'file'], true)) {
            throw new \InvalidArgumentException('An artifact URI must use the https or file scheme.');
        }

        if (preg_match('/^[a-f0-9]{64}$/', $sha256) !== 1 || $size < 0) {
            throw new \InvalidArgumentException('An artifact needs a lowercase SHA-256 digest and non-negative size.');
        }
    }

    /** @return array{uri: string, sha256: string, size: int} */
    public function toArray(): array
    {
        return ['uri' => $this->uri, 'sha256' => $this->sha256, 'size' => $this->size];
    }
}
