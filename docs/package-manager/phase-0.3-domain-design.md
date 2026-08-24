# PHASE 0.3 — Universal Package Manager Domain Design

## Purpose

Define the domain model before implementation. This phase does not introduce runtime behavior or replace the existing Pinx system.

The goal is to create a framework-level package orchestration system that can support Pinoox and other modular PHP platforms.

## Design Principles

- Keep existing AppDependency, AppProvisioner and Pinx lifecycle compatibility.
- Introduce new capabilities through contracts and adapters.
- Avoid coupling resolver logic to Market, filesystem or a specific installer.
- Keep domain logic independent from transport and UI layers.

---

# Domain Model

## Package Entity

Represents an installable modular unit.

Responsibilities:

- Identity
- Publisher information
- Versions
- Metadata
- Requirements
- Capabilities

Example:

```
com_shop
version: 3.2.1
requires:
  com_payment ^2.0
provides:
  capability.shop
```

---

# PackageVersion Value Object

Contains immutable version information.

Fields:

- version name
- version code
- release metadata
- checksum
- signature information

Must support semantic versioning without removing current version-code compatibility.

---

# Dependency Value Object

Represents a requirement between packages.

Supports:

- required dependency
- optional dependency
- version constraints
- capability requirements

Examples:

```
com_payment ^2.0
capability.payment ^1
```

---

# Constraint Model

Responsible for version rules.

Must support:

```
>=2.0
^2.0
~2.4
2.*
>=2 <3
```

The solver must be independent from package storage.

---

# Capability Model

Allows packages to depend on features instead of concrete implementations.

Example:

```
requires:
 capability.payment
```

Providers:

```
com_zarinpal
com_stripe
com_local_payment
```

This enables interchangeable modules.

---

# Package Registry

Responsible for installed package state.

Stores:

- installed packages
- active versions
- dependency graph state
- package integrity information

---

# Repository Contract

Package discovery must use an abstraction.

Possible implementations:

- Pinoox Market repository
- Local repository
- Git repository
- Enterprise private repository
- Cloud object storage repository

Resolver must not know the source.

---

# Install Plan Model

Before execution, the system creates a deterministic plan.

Example:

```
INSTALL
- com_user 5.0
- com_payment 2.4

UPDATE
- com_inventory 1.8

TARGET
- com_shop 3.2
```

---

# Transaction Model

Installation must be atomic.

Required concepts:

- transaction id
- operation history
- checkpoints
- rollback strategy
- failure recovery

---

# Runtime Integration

The future orchestrator should delegate execution to existing mechanisms:

```
PackageManager
      |
      v
Pinx Installer
      |
      v
AppProvisioner
      |
      v
Lifecycle / Migration / Cache
```

---

# Implementation Order

1. Domain entities and contracts
2. Registry abstraction
3. Dependency graph engine
4. Constraint solver
5. Repository abstraction
6. Install planner
7. Transaction engine
8. CLI integration
9. Existing Pinx integration
10. UI/API consumers

No implementation should begin before these contracts are reviewed.
