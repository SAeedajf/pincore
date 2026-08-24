<?php

declare(strict_types=1);

namespace Pinoox\Component\PackageManager\Dependency\Solver;

final class SelectionResult
{
    private function __construct(
        private readonly ?Candidate $candidate,
        private readonly ?string $reason
    ) {
    }

    public static function selected(Candidate $candidate): self
    {
        return new self($candidate, null);
    }

    public static function unsatisfied(string $reason): self
    {
        return new self(null, $reason);
    }

    public function isSatisfied(): bool
    {
        return $this->candidate !== null;
    }

    public function candidate(): ?Candidate
    {
        return $this->candidate;
    }

    public function reason(): ?string
    {
        return $this->reason;
    }
}
