# Phase 3.2 — Dependency Graph Algorithm Hardening

## Goal

Complete the graph processing layer before introducing dependency resolution.

## Scope

- Cycle detection strategy
- Topological installation ordering
- Reverse dependency analysis
- Deterministic graph traversal

## Design Rules

- Graph engine must not know about installation.
- Graph engine must not access repositories.
- Graph engine only transforms dependency relationships into deterministic results.

## Algorithms

### Cycle Detection

Use DFS state tracking:

- unvisited
- visiting
- visited

A node found while already visiting indicates a dependency cycle.

### Topological Ordering

Use Kahn algorithm:

1. Calculate incoming edges.
2. Start from zero dependency nodes.
3. Remove resolved nodes iteratively.
4. Return installation order.

### Reverse Dependency

Used for safe removal and impact analysis.

Example:

com_shop -> com_payment

Removing com_payment must report com_shop as dependent.
