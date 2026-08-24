# Package Manager Development Status

## Current Phase

PHASE 4.4 — Package Solve Contract

## Completed

- Repository audit and compatibility map
- Package-manager contracts and in-memory prototypes
- Buildable dependency graph with deterministic ordering and cycle reports
- Semantic-version parsing and constraint matching
- Highest compatible candidate selection
- Multi-requirement constraint intersection and conflict context
- Candidate-provider port and package-level solve request/result contract

## In Progress

- PHASE 4 solver expansion: multi-package dependency request and backtracking contract

## Next

- Candidate-provider port
- Package-level solve request/result
- Propagated conflict explanations
- Multi-package solving without repository or installer coupling

## Architecture Decisions

- The package manager is a universal PHP modular package system; Pinoox remains a compatibility adapter.
- Constraint, graph, solver, repository and installer responsibilities remain separate.
- Selection failures use typed result objects; only invalid domain input throws.

## Known Limits / Technical Debt

- OR and wildcard constraint syntax is intentionally deferred.
- The current selector solves one package candidate set at a time.
- Runtime PHP/Pest execution is available through CI; the current local workspace does not contain a PHP runtime.

## Test Status

- Local structural preflight: required before CI
- GitHub Actions Unit suite: required before merge
- GitHub Actions repository CI: required before merge
