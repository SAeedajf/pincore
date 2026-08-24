# PHASE 3.9 — Buildable Graph Core Correction

## Reason

The initial graph prototype mixed two source roots and exposed incompatible edge APIs.
This prevented Composer PSR-4 discovery and made cycle, reverse-dependency and ordering
algorithms impossible to execute together.

## Corrected Contract

- Runtime classes live under `Component/PackageManager/Dependency/Graph`, which matches
  the repository's `Pinoox\\ => /` Composer autoload mapping.
- An edge is directed from a dependent package to the package it requires.
- Topological output is installation-safe: every required package appears before its dependent.
- Graph operations are deterministic by node identifier.
- Invalid endpoints and cycles use package-manager-specific exceptions.

## Validation Coverage

Unit coverage now verifies:

- deterministic dependency-first ordering
- closed cycle path reporting
- reverse dependency lookup
- graph analysis output
- rejection of unregistered edge endpoints

## Boundary

This correction deliberately remains inside structural dependency analysis. It does not
select versions, access repositories, download artifacts, mutate package state, or install packages.

## Next Phase

PHASE 4 — Constraint Solver can begin only after this branch is executed with the repository's
PHP 8.2 test environment and the graph unit suite passes.
