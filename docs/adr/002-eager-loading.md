# ADR 002: Eager Loading Strategy

## Context
Avoid N+1 queries on dashboard.

## Decision
Order::with('orderDetails.food').