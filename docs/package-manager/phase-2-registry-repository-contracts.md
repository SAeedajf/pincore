# PHASE 2 — Registry & Repository Contracts

## Goal

Introduce abstraction layers for installed package state and external package discovery without coupling the PackageManager to a specific marketplace.

## Registry

The registry represents installed package state.

Responsibilities:

- Find installed packages
- Register installed packages
- Remove package records
- Provide package inventory

## Repository

The repository abstraction represents package sources.

Possible implementations:

- Local repository
- Pinoox marketplace adapter
- Git repository
- Private enterprise repository

The resolver must depend on the interface, not an implementation.

## Compatibility Rules

- Existing Pinx installer remains unchanged.
- No runtime installation is performed in this phase.
- No dependency solving is introduced yet.
