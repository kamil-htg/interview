# Use Modular Layered Architecture for Domain-Oriented PHP Service

- Deciders: Good, Bad and Ugly
- Date: 2025-12-24
- Tags: architecture

## Status
Accepted

## Context and Problem Statement

We needed a consistent, scalable folder structure for a new PHP service that cleanly separates domain logic, infrastructure, and external interfaces.

Previous projects used flat Symfony-style structures that became tangled as they grew. Previous attempts at full DDD introduced too much complexity and boilerplate.

We needed a middle ground: clear separation of concerns, incremental growth, and easy onboarding.

## Decision Outcome

**Chosen option:** Modular layered structure with Core, Infrastructure, and Interface layers.

### Positive Consequences
- Domain code stays decoupled from Symfony and external systems
- New modules follow the same structure without coupling
- Core logic is unit-testable without loading the framework
- Consistent conventions simplify onboarding

### Negative Consequences
- Slight overhead for small/simple modules
- Requires team discipline to respect layer boundaries

## Pros and Cons of the Options

| Option | Good | Bad |
|---|---|---|
| Flat Symfony-style | Simple, fast setup | Tight coupling, poor scalability |
| Full DDD | Excellent domain separation | Too heavy, slow delivery |
| **Modular layered (chosen)** | Balanced structure, domain-centric | Initial setup overhead, needs team alignment |

## Example Structure

```
├── Common/
├── Contract/
├── Offer/
│   ├── Core/
│   ├── Infrastructure/
│   ├── Interface/Http/
│   ├── Resources/config/
│   └── Tests/
└── System/
    ├── Interface/Http/Healthcheck/
    ├── Resources/config/
    └── Tests/
```

## Links
- [DDD Strategic Design](https://medium.com/@lambrych/domain-driven-design-ddd-strategic-design-explained-55e10b7ecc0f)
- [Hexagonal Architecture](https://docs.aws.amazon.com/prescriptive-guidance/latest/cloud-design-patterns/hexagonal-architecture.html)
