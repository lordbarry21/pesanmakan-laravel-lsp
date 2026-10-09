# ADR 001: Atomic Database Transactions

## Context
Order checkout touches multiple tables.

## Decision
Use DB::transaction() closure.