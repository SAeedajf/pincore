# PHASE 0.1 — Repository Audit Plan

## Objective

Prepare the codebase for a universal modular package management system while preserving compatibility with the existing Pinoox package lifecycle.

The target is not a Pinoox-specific installer. The target architecture should support a reusable PHP modular package ecosystem.

## Scope

This phase does not introduce implementation code. It identifies integration points, boundaries, and safe extension locations.

## Audit Areas

### 1. Current Package Lifecycle

Review:

- Package discovery
- Manifest loading
- Package validation
- Installation flow
- Provisioning
- Activation
- Migration execution
- Lifecycle events
- Removal process

Expected output:

```
Package Source
      |
      v
Manifest
      |
      v
Validation
      |
      v
Install
      |
      v
Provision
      |
      v
Lifecycle
      |
      v
Runtime
```

## 2. Dependency System Analysis

Review current dependency capabilities:

- Dependency declaration
- Version validation
- Missing dependency handling
- Reverse dependency checks
- Dependency ordering
- Circular dependency detection

Identify what should remain and what should move into the new PackageManager domain.

## 3. Target Architecture Direction

The new architecture should support:

- Multiple repositories
- Semantic version constraints
- Dependency graph resolution
- Conflict solving
- Install planning
- Transactional installation
- Rollback
- Package locking
- Package signing
- Enterprise/private repositories

## 4. Compatibility Rules

Mandatory rules:

- Existing applications must continue working.
- Existing Pinx packages must remain installable.
- Existing lifecycle hooks must not break.
- New PackageManager must be introduced through adapters/interfaces.
- No direct dependency on a specific marketplace implementation.

## 5. Proposed Future Modules

```
Component/
└── PackageManager

    Domain
    Registry
    Resolver
    Solver
    Repository
    Planner
    Installer
    Transaction
    Security
    Runtime
```

## 6. Next Phase Requirements

Before PHASE 1 implementation:

- Complete current architecture map.
- Identify extension points.
- Define interfaces/contracts.
- Define test scenarios.
- Confirm migration strategy.

## Decision

The project direction is set to:

Universal PHP Modular Package System

Pinoox compatibility is a requirement, not the architectural limitation.
