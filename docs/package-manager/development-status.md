# Package Manager Development Status

## Current Phase

PHASE 4.7 — Lock Snapshot Domain Model

## Completed

- Repository audit and compatibility map
- Package-manager contracts and in-memory prototypes
- Buildable dependency graph with deterministic ordering and cycle reports
- Semantic-version parsing and constraint matching
- Highest compatible candidate selection
- Multi-requirement constraint intersection and conflict context
- Candidate-provider port and package-level solve request/result contract
- Multi-package candidate solving with deterministic backtracking and propagated conflicts
- Immutable package manifests and repository-to-solver candidate adaptation
- Canonical in-memory lock snapshot and deterministic selection fingerprint

## In Progress

- PHASE 4 solver expansion: strict manifest and lock-file codecs

## Next

- External manifest decoder/validator for repository payloads
- Strict lock-snapshot encoder/decoder and atomic persistence boundary
- Installation planning remains separate from solver selection

## Architecture Decisions

- The package manager is a universal PHP modular package system; Pinoox remains a compatibility adapter.
- Constraint, graph, solver, repository and installer responsibilities remain separate.
- Selection failures use typed result objects; only invalid domain input throws.

## Known Limits / Technical Debt

- OR and wildcard constraint syntax is intentionally deferred.
- Manifest and lock boundaries are intentionally in-memory; filesystem, database, and remote repository adapters are still deferred.
- Runtime PHP/Pest execution is available through CI; the current local workspace does not contain a PHP runtime.

## Test Status

- Local structural preflight: required before CI
- GitHub Actions Unit suite: required before merge
- GitHub Actions repository CI: required before merge
