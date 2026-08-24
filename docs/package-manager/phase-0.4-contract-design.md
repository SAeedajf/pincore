# PHASE 0.4 — Package Manager Contract Design

## Purpose

Define stable interfaces before implementation. The new Package Manager must be framework-agnostic and must not couple the core to a specific marketplace or distribution source.

The existing Pinoox package lifecycle remains the execution layer. The new system becomes the orchestration layer.

---

# Core Contracts

## PackageRepositoryInterface

Responsibility:

- Discover packages
- Retrieve metadata
- Resolve available versions
- Fetch package artifacts

Implementations:

- Market repository
- Local repository
- Git repository
- Private enterprise repository

The resolver must never know where packages are stored.

---

## PackageRegistryInterface

Responsibility:

Track installed package state.

Required information:

- Package identity
- Installed version
- Hash
- Publisher
- Installation state
- Dependencies
- Capabilities

Possible storage:

- Database registry
- Lock file
- Filesystem metadata

---

## DependencyResolverInterface

Responsibility:

Build complete dependency graph.

Required features:

- Direct dependencies
- Transitive dependencies
- Optional dependencies
- Capability dependencies
- Circular dependency detection
- Reverse dependency lookup

---

## ConstraintSolverInterface

Responsibility:

Select compatible versions.

Supported constraints:

- Semantic versions
- Version ranges
- Existing installed versions
- Platform requirements

Must detect conflicts before installation.

---

## InstallPlannerInterface

Responsibility:

Generate execution plan without changing the system.

Example output:

INSTALL
- com_payment 2.4.0

UPDATE
- com_inventory 1.2 -> 1.8

UNCHANGED
- com_user

---

## TransactionManagerInterface

Responsibility:

Provide atomic package operations.

Operations:

- Begin transaction
- Record changes
- Commit
- Rollback
- Restore previous state

---

## PackageInstallerInterface

Responsibility:

Execute approved plans.

Must integrate with existing:

- Pinx installer
- AppProvisioner
- Lifecycle hooks
- Migration system

---

## SecurityVerifierInterface

Responsibility:

Validate package trust.

Checks:

- Signature
- Hash integrity
- Publisher trust
- Permissions

---

# Design Rules

1. No direct dependency on Pinoox Market.
2. Existing AppDependency and AppProvisioner remain compatible.
3. New contracts must be usable by non-Pinoox PHP modular systems.
4. Interfaces are created before implementations.
5. Every implementation must be replaceable through dependency injection.

---

# Next Phase

PHASE 1 — Domain Layer Implementation

Create:

- Package entities
- Version value objects
- Dependency objects
- Constraint objects
- Capability objects

No installer behavior changes in Phase 1.
