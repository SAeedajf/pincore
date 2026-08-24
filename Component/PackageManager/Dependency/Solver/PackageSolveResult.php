<?php

declare(strict_types=1);

namespace Pinoox\Component\PackageManager\Dependency\Solver;

final class PackageSolveResult
{
    public function __construct(
        private readonly PackageSolveRequest $request,
        private readonly SelectionResult $selection
    ) {
    }

    public function package(): string
    {
        return $this->request->package();
    }

    public function selection(): SelectionResult
    {
        return $this->selection;
    }

    public function isSatisfied(): bool
    {
        return $this->selection->isSatisfied();
    }
}
