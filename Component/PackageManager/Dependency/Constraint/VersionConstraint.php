<?php

declare(strict_types=1);

namespace Pinoox\Component\PackageManager\Dependency\Constraint;

use Pinoox\Component\PackageManager\Dependency\Constraint\Exception\InvalidVersionException;

final class VersionConstraint
{
    /**
     * @param list<array{operator: string, version: SemanticVersion}> $clauses
     */
    private function __construct(
        private readonly array $clauses,
        private readonly string $expression
    )
    {
    }

    public static function parse(string $constraint): self
    {
        $constraint = trim($constraint);

        if ($constraint === '' || str_contains($constraint, '||')) {
            throw new InvalidVersionException('A constraint must contain one non-empty comparator set.');
        }

        if ($constraint[0] === '^' || $constraint[0] === '~') {
            return self::fromCompatibilityRange($constraint);
        }

        $clauses = [];

        foreach (preg_split('/\\s+/', $constraint) as $token) {
            if (preg_match('/^(>=|<=|>|<|=)?(.+)$/', $token, $matches) !== 1) {
                throw new InvalidVersionException(sprintf('Invalid version constraint "%s".', $constraint));
            }

            $clauses[] = [
                'operator' => $matches[1] === '' ? '=' : $matches[1],
                'version' => SemanticVersion::parse($matches[2]),
            ];
        }

        return new self($clauses, $constraint);
    }

    public function matches(SemanticVersion $candidate): bool
    {
        foreach ($this->clauses as $clause) {
            $comparison = $candidate->compareTo($clause['version']);

            if (!match ($clause['operator']) {
                '=' => $comparison === 0,
                '>' => $comparison > 0,
                '>=' => $comparison >= 0,
                '<' => $comparison < 0,
                '<=' => $comparison <= 0,
            }) {
                return false;
            }
        }

        return true;
    }

    /**
     * @return list<array{operator: string, version: SemanticVersion}>
     */
    public function clauses(): array
    {
        return $this->clauses;
    }

    public function expression(): string
    {
        return $this->expression;
    }

    private static function fromCompatibilityRange(string $constraint): self
    {
        $operator = $constraint[0];
        $base = SemanticVersion::parse(substr($constraint, 1));

        if ($operator === '^') {
            $upper = match (true) {
                $base->major() > 0 => sprintf('%d.0.0', $base->major() + 1),
                $base->minor() > 0 => sprintf('0.%d.0', $base->minor() + 1),
                default => sprintf('0.0.%d', $base->patch() + 1),
            };
        } else {
            $upper = sprintf('%d.%d.0', $base->major(), $base->minor() + 1);
        }

        return new self([
            ['operator' => '>=', 'version' => $base],
            ['operator' => '<', 'version' => SemanticVersion::parse($upper)],
        ], $constraint);
    }
}
