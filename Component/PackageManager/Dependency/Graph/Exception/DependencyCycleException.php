<?php

declare(strict_types=1);

namespace Pinoox\Component\PackageManager\Dependency\Graph\Exception;

use LogicException;

final class DependencyCycleException extends LogicException
{
    /**
     * @param list<string> $cycle
     */
    public function __construct(private readonly array $cycle)
    {
        parent::__construct(
            'Dependency graph contains a cycle: ' . implode(' -> ', $cycle)
        );
    }

    /**
     * @return list<string>
     */
    public function cycle(): array
    {
        return $this->cycle;
    }
}
