# Phase 3 — Dependency Graph Engine

## Goal

Introduce a framework-independent dependency graph layer for package resolution.

The graph layer must not install packages. It only models relationships and provides algorithms required by future planners and solvers.

## Scope

Included:

- Graph nodes
- Graph edges
- Dependency relationships
- Cycle detection design
- Topological ordering design
- Reverse dependency lookup

Excluded:

- Downloading packages
- Version solving
- Installation
- Database persistence

## Design principles

- Keep graph engine independent from repositories.
- Keep graph engine independent from Pinx installer.
- Support package dependencies and future capability dependencies.
- Make algorithms testable with in-memory graphs.
