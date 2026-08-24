# Package Manager Development Status

## Current Phase

PHASE 4.5 — Multi-Package Dependency Solver

## Completed

- Repository audit and compatibility map
- Package-manager contracts and in-memory prototypes
- Buildable dependency graph with deterministic ordering and cycle reports
- Semantic-version parsing and constraint matching
- Highest compatible candidate selection
- Multi-requirement constraint intersection and conflict context
- Candidate-provider port and package-level solve request/result contract
- Multi-package candidate solving with deterministic backtracking and propagated conflicts

## In Progress

- PHASE 4 solver expansion: repository metadata adapter boundary

## Next

- Repository metadata adapter and package manifest contract
- Lock-file data model and reproducible selection persistence
- Installation planning remains separate from solver selection

## Architecture Decisions

- The package manager is a universal PHP modular package system; Pinoox remains a compatibility adapter.
- Constraint, graph, solver, repository and installer responsibilities remain separate.
- Selection failures use typed result objects; only invalid domain input throws.

## Known Limits / Technical Debt

- OR and wildcard constraint syntax is intentionally deferred.
- The multi-package solver is intentionally in-memory and has no repository metadata adapter yet.
- Runtime PHP/Pest execution is available through CI; the current local workspace does not contain a PHP runtime.

## Test Status

- Local structural preflight: required before CI
- GitHub Actions Unit suite: required before merge
- GitHub Actions repository CI: required before merge
