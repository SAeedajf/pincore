# Current Package Architecture Audit Map

## Scope

This document records the current pincore package architecture before implementing the Universal PHP Modular Package System.

No runtime behavior is changed in this phase.

## Current Package Layer

Current package responsibilities are distributed under:

```
Component/Package
```

Important existing components:

- App.php
- AppManifest.php
- AppDependency.php
- AppManager.php
- AppProvisioner.php
- AppProvider.php
- AppPackageContext.php

## Current Dependency Capability

Existing system already supports:

- Dependency declaration parsing
- Optional dependencies
- Minimum version code validation
- Dependency inspection
- Dependency satisfaction checks
- Dependency ordering helpers

Current limitation:

Dependency handling is validation-oriented, not a complete package resolution system.

Missing capabilities:

- Repository based package discovery
- Recursive package resolution
- Semantic version solving
- Conflict resolution
- Install planning
- Lock state management
- Transaction based installation

## Current Flow

```
App Manifest
     |
     v
Dependency Validation
     |
     v
Package Exists Check
     |
     v
Provision Existing Package
     |
     +--> Migration
     +--> Patch
     +--> Lifecycle
     +--> Cache
```

## Architecture Decision

The new Package Manager must be introduced as an independent domain layer.

Do not replace existing classes directly.

Target approach:

```
Existing Package System
          |
          v
Compatibility Adapter
          |
          v
Universal Package Manager Core
```

## Future Components

Planned additions:

```
Component/PackageManager

Domain
Registry
Resolver
Solver
Repository
Planner
Transaction
Security
Runtime
```

## Compatibility Rules

- Existing apps must continue working.
- Existing app.php manifests remain supported.
- New manifest capabilities are additive.
- Existing installer/provisioner lifecycle must remain reusable.
- No direct dependency on a specific marketplace.

## Audit Status

Phase 0.2 completed.

Next phase:

PHASE 1 - Domain Layer Design
