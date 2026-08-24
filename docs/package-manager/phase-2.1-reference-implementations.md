# PHASE 2.1 — Registry & Repository Reference Implementations

## Purpose

This phase introduces lightweight reference implementations for validating Package Manager contracts before connecting to persistent storage or external repositories.

## Implementations

### InMemoryPackageRegistry

Responsibilities:

- store discovered package instances temporarily
- verify registry contract behavior
- support resolver tests

Non-goals:

- database persistence
- filesystem scanning
- production package state management

### InMemoryPackageRepository

Responsibilities:

- provide local package metadata during tests
- simulate repository lookup
- validate repository abstraction

Non-goals:

- package download
- signature verification
- remote communication

## Architecture Rule

Future implementations must depend on these contracts, not replace them.

Planned adapters:

- DatabasePackageRegistry
- LocalFilesystemRepository
- MarketRepository
- PrivateRepository
