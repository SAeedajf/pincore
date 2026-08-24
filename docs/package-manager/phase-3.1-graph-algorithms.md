# Phase 3.1 — Dependency Graph Algorithms

## Scope

This phase introduces graph algorithm foundations for the Package Manager.

## Components

- CycleDetector
- TopologicalSorter
- Reverse dependency analysis

## Rules

Algorithms must not:

- download packages
- install packages
- access repositories
- mutate runtime state

They only analyze dependency relationships.

## Future Usage

DependencyResolver will use these algorithms to create safe installation plans.
