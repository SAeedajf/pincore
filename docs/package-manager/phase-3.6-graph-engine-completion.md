# PHASE 3.6 — Graph Engine Completion

## Goal

Finalize the dependency graph layer before introducing constraint solving.

The graph engine must provide deterministic, explainable and safe dependency analysis.

## Scope

### Cycle Detection

Requirements:

- Detect circular dependencies
- Return the complete cycle path
- Provide actionable error information

Example:

```
com_shop
 -> com_payment
 -> com_user
 -> com_shop
```

## Topological Ordering

The install order generator must:

- Produce deterministic output
- Respect dependency precedence
- Reject cyclic graphs
- Support large dependency trees

Example:

```
Database
Payment
Shop
```

## Reverse Dependency Analysis

Required for safe removal and impact analysis.

Example:

```
remove com_payment

Affected:
- com_shop
- com_invoice
```

## Performance Requirements

The implementation should support:

- Large package graphs
- Cached traversal state
- Minimal repeated traversal
- Predictable memory usage

## Boundary Rules

Graph Engine must not know:

- Package download source
- Installation mechanism
- Storage implementation
- Runtime activation

It only analyzes dependency relationships.

## Next Phase

After completion:

PHASE 4 — Constraint Solver

Responsibilities:

- Version comparison
- Constraint matching
- Conflict resolution
- Version selection
