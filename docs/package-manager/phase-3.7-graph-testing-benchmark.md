# PHASE 3.7 — Graph Testing & Benchmark

## Objective

Finalize Dependency Graph Engine validation before entering Constraint Solver.

## Test Categories

### Graph correctness

- Empty graph
- Single node
- Linear dependency chain
- Multiple dependency branches
- Deep dependency trees

### Cycle detection

Examples:

```
A -> B -> C -> A
```

Expected result:

- Detect cycle
- Return complete cycle path
- Block resolution

### Ordering validation

Topological ordering must be:

- deterministic
- repeatable
- dependency-safe

Example:

```
Database
   |
Payment
   |
Shop
```

Expected:

```
Database
Payment
Shop
```

## Reverse Dependency Testing

Before removing a package:

```
com_shop
   |
com_payment
```

The system must identify dependent packages and prevent unsafe removal.

## Performance Targets

The graph engine should be evaluated against:

- 100 packages
- 1000 packages
- 10000 packages

Metrics:

- execution time
- memory usage
- traversal count

## Exit Criteria

Graph Engine is ready for PHASE 4 when:

- all graph algorithms have automated tests
- cycle paths are explainable
- ordering is deterministic
- large graphs remain performant
