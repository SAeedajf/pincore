# PHASE 4.6 — Manifest Repository Boundary

## Scope

This phase defines how package metadata reaches the dependency solver. It does not read existing application manifests, alter `AppDependency`, install archives, write a lock file, or make network requests.

## Contracts

- `PackageManifest` is immutable package metadata: package identifier, semantic version, and dependency constraints.
- `PackageManifestProvider` is the repository-facing port.
- `InMemoryPackageManifestProvider` is the reference implementation for tests and local composition.
- `ManifestCandidateProvider` adapts manifests to the existing solver `CandidateProvider` port.

## Invariants

- A manifest must be stored and returned under its own package identifier.
- Dependency names are non-empty and constraints are already parsed domain objects.
- Dependency order is normalized by package name so candidate construction is deterministic.
- Each generated transitive requirement is attributed to the exact `package@version` manifest that declared it.
- No legacy app-manifest, installer, lifecycle, database, filesystem, transport, or network path is touched.

## Next Boundary

The next phase can add an explicit external manifest decoder/validator at this port, then independently design lock-file persistence from a successful solver result.
