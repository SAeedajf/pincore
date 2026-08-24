# PHASE 4.7 — Lock Snapshot Domain Model

## Scope

This phase converts a successful multi-package solve result into a deterministic, immutable in-memory lock snapshot. It deliberately does not parse or write lock files and it does not change repository, installer, lifecycle, filesystem, database, or network behavior.

## Contract

- `LockedRequirement` preserves the dependency source, package, and original constraint expression.
- `LockedPackage` preserves the selected package version and normalizes its requirements.
- `LockSnapshot` accepts only a satisfied solver result, normalizes package order, exposes a versioned data shape, and produces a SHA-256 fingerprint.

## Invariants

- An unsatisfied result cannot generate a lock snapshot.
- Package identifiers are unique in a snapshot.
- Package and requirement order are canonical, so equivalent selections have the same fingerprint.
- The original constraint expressions are retained for auditability; this phase does not re-resolve or mutate them.

## Next Boundary

The next phase can add a strict decoder/encoder around this data model, with atomic persistence as a separate concern.
