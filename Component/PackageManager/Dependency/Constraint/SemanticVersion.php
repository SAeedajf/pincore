<?php

declare(strict_types=1);

namespace Pinoox\Component\PackageManager\Dependency\Constraint;

use Pinoox\Component\PackageManager\Dependency\Constraint\Exception\InvalidVersionException;

final class SemanticVersion
{
    /**
     * @param list<string>|null $preRelease
     */
    private function __construct(
        private readonly int $major,
        private readonly int $minor,
        private readonly int $patch,
        private readonly ?array $preRelease,
        private readonly ?string $buildMetadata
    ) {
    }

    public static function parse(string $version): self
    {
        $pattern = '/^v?(0|[1-9]\\d*)\\.(0|[1-9]\\d*)\\.(0|[1-9]\\d*)(?:-([0-9A-Za-z-]+(?:\\.[0-9A-Za-z-]+)*))?(?:\\+([0-9A-Za-z-]+(?:\\.[0-9A-Za-z-]+)*))?$/';

        if (preg_match($pattern, $version, $matches) !== 1) {
            throw new InvalidVersionException(sprintf('Invalid semantic version "%s".', $version));
        }

        $preRelease = ($matches[4] ?? '') === '' ? null : explode('.', $matches[4]);

        foreach ($preRelease ?? [] as $identifier) {
            if (ctype_digit($identifier) && strlen($identifier) > 1 && $identifier[0] === '0') {
                throw new InvalidVersionException(sprintf('Invalid numeric pre-release identifier in "%s".', $version));
            }
        }

        foreach ([$matches[1], $matches[2], $matches[3]] as $part) {
            if (strlen($part) > strlen((string) PHP_INT_MAX) || (
                strlen($part) === strlen((string) PHP_INT_MAX) && $part > (string) PHP_INT_MAX
            )) {
                throw new InvalidVersionException(sprintf('Version number is too large in "%s".', $version));
            }
        }

        return new self(
            (int) $matches[1],
            (int) $matches[2],
            (int) $matches[3],
            $preRelease,
            ($matches[5] ?? '') === '' ? null : $matches[5]
        );
    }

    public function major(): int
    {
        return $this->major;
    }

    public function minor(): int
    {
        return $this->minor;
    }

    public function patch(): int
    {
        return $this->patch;
    }

    public function compareTo(self $other): int
    {
        foreach (['major', 'minor', 'patch'] as $part) {
            $comparison = $this->{$part} <=> $other->{$part};

            if ($comparison !== 0) {
                return $comparison;
            }
        }

        if ($this->preRelease === null || $other->preRelease === null) {
            return match (true) {
                $this->preRelease === null && $other->preRelease === null => 0,
                $this->preRelease === null => 1,
                default => -1,
            };
        }

        $length = max(count($this->preRelease), count($other->preRelease));

        for ($index = 0; $index < $length; ++$index) {
            $left = $this->preRelease[$index] ?? null;
            $right = $other->preRelease[$index] ?? null;

            if ($left === $right) {
                continue;
            }

            if ($left === null) {
                return -1;
            }

            if ($right === null) {
                return 1;
            }

            $leftNumeric = ctype_digit($left);
            $rightNumeric = ctype_digit($right);

            if ($leftNumeric && $rightNumeric) {
                return strlen($left) <=> strlen($right) ?: $left <=> $right;
            }

            if ($leftNumeric !== $rightNumeric) {
                return $leftNumeric ? -1 : 1;
            }

            return $left <=> $right;
        }

        return 0;
    }

    public function __toString(): string
    {
        $version = sprintf('%d.%d.%d', $this->major, $this->minor, $this->patch);

        if ($this->preRelease !== null) {
            $version .= '-' . implode('.', $this->preRelease);
        }

        if ($this->buildMetadata !== null) {
            $version .= '+' . $this->buildMetadata;
        }

        return $version;
    }
}
