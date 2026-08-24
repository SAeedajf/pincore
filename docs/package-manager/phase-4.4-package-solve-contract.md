# PHASE 4.4 — Package Solve Contract

## Objective

Define a stable package-level solve boundary before introducing multi-package backtracking.

## Implemented

- `CandidateProvider` port
- `PackageSolveRequest` and `PackageSolveResult`
- in-memory provider for deterministic verification
- package identity validation at the provider boundary
- single-package solution through the complete constraint-set path

## Boundary

Providers expose candidates only. They do not determine compatibility, select versions,
install packages, modify a registry, or access the network from the Solver domain.

## Next

PHASE 4.5 will add a dependency solve request, resolution graph traversal, and
backtracking-safe conflict propagation across packages.
