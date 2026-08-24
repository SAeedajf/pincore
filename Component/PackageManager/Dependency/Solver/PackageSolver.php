<?php

declare(strict_types=1);

namespace Pinoox\Component\PackageManager\Dependency\Solver;

final class PackageSolver
{
    public function __construct(
        private readonly CandidateProvider $candidates,
        private readonly VersionSelector $selector = new VersionSelector()
    ) {
    }

    public function solve(PackageSolveRequest $request): PackageSolveResult
    {
        $candidates = $this->candidates->candidatesFor($request->package());

        if ($candidates->package() !== null && $candidates->package() !== $request->package()) {
            throw new \LogicException('A candidate provider returned candidates for the wrong package.');
        }

        return new PackageSolveResult(
            $request,
            $this->selector->selectForConstraints($candidates, $request->constraints())
        );
    }
}
