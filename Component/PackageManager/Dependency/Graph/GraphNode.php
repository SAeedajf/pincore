<?php

declare(strict_types=1);

namespace Pinoox\Component\PackageManager\Dependency\Graph;

use Pinoox\Component\PackageManager\Dependency\Graph\Exception\GraphInvariantViolationException;

final class GraphNode
{
    public function __construct(private readonly string $id)
    {
        if (trim($id) === '') {
            throw new GraphInvariantViolationException('A graph node id cannot be empty.');
        }
    }

    public function id(): string
    {
        return $this->id;
    }
}
