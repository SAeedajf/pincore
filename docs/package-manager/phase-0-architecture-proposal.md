# Advanced Pinx Package Dependency Manager

## Phase 0 - Architecture Audit & Design Proposal

## Objective

This document defines the architectural direction for evolving Pinx package management into a production-grade package orchestration system.

The goal is not only dependency checking. The target is a complete package lifecycle system with dependency resolution, planning, safe installation, rollback, repository abstraction and security verification.

---

# Current Problem

An application can require multiple applications:

```
com_shop
 ├── com_payment
 ├── com_inventory
 └── com_user
```

Current expected experience:

```
pinx install com_shop
```

The system should automatically:

- detect missing dependencies
- detect incompatible versions
- resolve compatible versions
- install dependencies first
- install the requested package last

Example:

```
Dependency Check:

com_payment
Not installed

com_inventory
Installed: 1.2
Required: >=1.5

Installing dependencies...

✓ com_payment
✓ com_inventory

Installing com_shop...
```

---

# Design Principles

## 1. Backward Compatibility

Existing Pinx applications must continue working.

No immediate replacement of current manifest or lifecycle system.

## 2. Core Responsibility

Dependency management belongs inside Pincore, not Manager UI.

UI, CLI and API should consume the same core services.

## 3. Extensible Architecture

Package sources must not be coupled to one marketplace.

Supported future sources:

- Pinoox Market
- Local repository
- Git repository
- Private enterprise repository
- Custom package providers

---

# Target Architecture

```
Component/PackageManager/

Domain/
 ├── Package
 ├── PackageVersion
 ├── Dependency
 ├── Constraint
 └── Capability

Registry/
 ├── InstalledPackageRegistry
 ├── MetadataStore
 └── Cache

Dependency/
 ├── Resolver
 ├── GraphBuilder
 ├── ConstraintSolver
 ├── ConflictDetector
 └── CycleDetector

Repository/
 ├── RepositoryInterface
 ├── MarketRepository
 ├── LocalRepository
 ├── GitRepository
 └── PrivateRepository

Planner/
 ├── InstallPlanner
 ├── UpdatePlanner
 └── RemovePlanner

Transaction/
 ├── TransactionManager
 ├── SnapshotManager
 └── RollbackManager

Security/
 ├── SignatureVerifier
 ├── IntegrityChecker
 └── TrustManager
```

---

# Development Phases

## Phase 0 - Audit & Architecture

Tasks:

- inspect current Pinx lifecycle
- inspect dependency handling
- identify extension points
- define migration strategy
- define compatibility rules

Deliverables:

- architecture document
- migration plan
- testing strategy

---

## Phase 1 - Package Domain Layer

Introduce isolated domain objects:

- Package
- Version
- Dependency
- Constraint
- Capability

No breaking installer changes.

---

## Phase 2 - Manifest Evolution

Introduce enhanced metadata while preserving existing applications.

Future manifest example:

```json
{
 "name":"com_shop",
 "version":{
   "name":"3.2.1",
   "code":30201
 },
 "dependencies":{
   "com_payment":"^2.0"
 }
}
```

---

## Phase 3 - Dependency Graph Engine

Capabilities:

- recursive dependency discovery
- dependency ordering
- cycle detection
- reverse dependency analysis

---

## Phase 4 - Version Constraint Solver

Support:

```
^2.0
~2.4
>=2 <3
2.*
```

Detect conflicts before installation.

---

## Phase 5 - Repository Layer

Separate package discovery from package installation.

---

## Phase 6 - Installation Planner

Support dry-run:

```
pinx install com_shop --plan
```

Show:

- install operations
- update operations
- conflicts
- final dependency state

---

## Phase 7 - Transaction Engine

Installation pipeline:

```
Download
Verify
Extract
Register
Migration
Enable
```

Failure must support rollback.

---

## Phase 8 - Lock File

Introduce reproducible installation state:

```
pinx.lock
```

Use cases:

- production deployment
- server cloning
- CI/CD

---

## Phase 9 - Security

Verify:

- package signature
- checksum
- publisher identity
- package integrity

---

# Implementation Rules

- Do not rewrite existing Pinx system immediately.
- Introduce new services behind interfaces.
- Keep existing applications installable.
- Add automated tests for every phase.
- Avoid coupling dependency resolution with UI or marketplace.

---

# Next Action

Complete Phase 0 analysis before implementation.

Required outputs:

1. Current architecture map
2. Extension points
3. Migration plan
4. Interface design
5. Test matrix
