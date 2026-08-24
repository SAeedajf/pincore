# PHASE 4.1 — Constraint Foundation

## Scope

This phase creates a deterministic, dependency-free semantic-version and comparator layer.

Supported constraint forms:

- exact: `1.2.3`
- comparators: `>=1.2.0 <2.0.0`
- caret ranges: `^1.2.3`, including SemVer zero-major rules
- tilde ranges: `~1.2.3`

## Exclusions

The foundation intentionally does not yet support:

- OR/disjunction ranges
- wildcard ranges
- repository lookups
- package selection
- conflict explanations

Those responsibilities belong to the Solver layer, after the matching rules are verified.

## Contract

- `SemanticVersion` is immutable and compares pre-release identifiers according to SemVer precedence.
- `VersionConstraint` parses an intersection of comparators.
- `VersionConstraintMatcher` accepts parsed values or strings and has no repository dependency.

## Next

PHASE 4.2 will add candidate sets, deterministic highest-compatible selection, and an explicit unsatisfied-constraint result.
