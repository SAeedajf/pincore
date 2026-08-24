# PHASE 3.3 — Graph Algorithm Implementation

## Goal

Implement deterministic dependency graph algorithms for the Package Manager layer.

This phase must remain isolated from installation, repository access, persistence and runtime execution.

## Algorithms

### Cycle Detection

Purpose:

Detect invalid dependency loops before planning installation.

Example:

A -> B -> C -> A

Expected result:

Return the cycle path and prevent install planning.

Implementation approach:

- DFS traversal
- Node visitation states
- Cycle path extraction

## Topological Ordering

Purpose:

Generate deterministic installation order.

Example:

shop -> payment -> user

Result:

user
payment
shop

Implementation approach:

- Kahn algorithm
- Stable ordering
- Explicit failure on cyclic graphs

## Reverse Dependency Analysis

Purpose:

Identify impacted packages during removal or update.

Example:

Removing payment:

shop requires payment

Result:

Removal blocked or requires migration plan.

## Constraints

Do not introduce:

- Database access
- HTTP calls
- Installer calls
- Runtime hooks

The output of this layer must be consumed by future Resolver and Planner components.
