# PHASE 2.2 — Registry & Repository Tests

## Goal

Verify the reference implementations before introducing database adapters or remote repositories.

## Registry scenarios

- Register package metadata
- Retrieve installed package
- Check package existence
- Remove package
- Return complete installed package collection

## Repository scenarios

- Add package artifact
- Find package by identifier
- Support multiple package versions
- Handle missing packages
- Keep repository independent from transport layer

## Scope

This phase validates contracts and reference implementations only.
No installer, resolver, downloader, or runtime lifecycle integration is introduced here.
