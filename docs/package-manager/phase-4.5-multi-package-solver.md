# PHASE 4.5 — Multi-Package Solver

## Scope

This phase adds an in-memory, deterministic multi-package solver above the existing candidate-provider port. It introduces no repository adapter, filesystem mutation, package installation, or lock-file format.

## Contract

- `PackageRequirement` identifies the requiring source, target package, and version constraint.
- A `Candidate` can declare package requirements while preserving its original two-argument construction contract.
- `MultiPackageSolveRequest` accepts the root requirements.
- `MultiPackageSolveResult` returns either package-keyed selections or a typed `ConstraintConflict`.
- `MultiPackageSolver` explores candidates in descending semantic-version order, backtracks when a transitive dependency is unsatisfied, and returns the propagated conflict context.

## Safety Invariants

- A provider may only return candidates for the requested package.
- A selected candidate is revalidated whenever a later dependency adds a constraint for its package.
- Cyclic requirements terminate because each package is selected at most once per solver branch; later constraints are validated against that selection.
- Solver execution has no repository, network, installer, or lock-file side effects.

## Deliberate Limits

- Constraints retain the current AND-only grammar; OR and wildcard syntax remain deferred.
- The solver returns one deterministic valid selection rather than an optimization score or an exhaustive solution set.
- Repository metadata parsing, lock-file persistence, and installation remain separate future phases.
