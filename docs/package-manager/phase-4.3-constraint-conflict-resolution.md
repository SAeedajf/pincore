# PHASE 4.3 — Constraint Conflict Resolution

## Objective

Resolve a candidate version against every declared requirement for the same package,
without coupling version selection to a repository, installer, or runtime registry.

## Contracts

- `ConstraintRequirement` binds a parsed constraint to its declaring package/source.
- `ConstraintSet` represents the intersection of requirements and rejects duplicate sources.
- `ConstraintConflict` preserves the affected package and all contributing requirements.
- `VersionSelector::selectForConstraints()` selects the highest candidate that satisfies
  the whole set, or returns an explicit conflict result.

## Guarantees

- Candidate selection is deterministic and dependency-first ordering remains outside this layer.
- A candidate set cannot contain versions from multiple packages.
- An ordinary unsatisfied constraint is a result, not an exception.
- Conflict output contains source identifiers suitable for an API, CLI, or UI adapter.

## Exclusions

- No repository lookup or network access
- No package download, install, migration, or registry mutation
- No OR/wildcard range support yet
- No multi-package backtracking solver yet

## Next

PHASE 4.4 will introduce a package-level solve request/result contract, candidate-provider
port, and deterministic multi-package conflict propagation.
