# Use Contracts for Communication Between Contexts

- Deciders: Good, Bad and Ugly
- Date: 2025-10-23
- Tags: architecture, integration, bounded-contexts

## Status
Accepted

## Context and Problem Statement

Our modular architecture has multiple bounded contexts (SSO, Booking, Payment, etc.) that occasionally need to interact. Without clear boundaries, direct imports between contexts lead to tight coupling, circular dependencies, and make individual contexts hard to test and evolve.

We needed a lightweight mechanism to make inter-context dependencies explicit and prevent access to internal implementation details.

## Decision Outcome

**Chosen option:** Contract interfaces defined in `src/Contract/`.

### How It Works

1. **Define** — the owning context declares a contract interface in `src/Contract/`
```php
// src/Contract/Offer.php
interface OfferClient {
    public function getById(string $id): ?Offer;
}
```

2. **Implement** — the owning context implements it in its `Interface/Adapter/` layer
```php
// src/Offer/Interface/Adapter/OfferClientAdapter.php
class OfferClientAdapter implements \App\Contract\Offer\OfferClient { ... }
```

3. **Consume** — other contexts depend only on the contract, never on internals
```php
// src/Booking/Core/Service/BookingService.php
class BookingService {
    public function __construct(private readonly \App\Contract\Offer\OfferClient $offerClient) {}
}
```

### Positive Consequences
- All inter-context dependencies are visible in one place (`src/Contract/`)
- Contexts cannot access each other's internals
- Compile-time type safety via PHP interfaces
- Contexts can be tested in isolation with mocked contracts
- No event bus or complex infrastructure needed

### Negative Consequences
- Synchronous runtime coupling between contexts
- Contract changes require coordination with all consumers
- Risk of exposing anemic CRUD-style operations instead of domain operations

## Pros and Cons of the Options

| Option | Good | Bad |
|---|---|---|
| Direct service calls | Simple, no abstractions | Tight coupling, hard to test |
| Domain events / event bus | Async decoupling, eventual consistency | Heavy infrastructure, overkill for our scale |
| **Contract interfaces (chosen)** | Explicit, type-safe, easy to mock | Synchronous coupling, coordination on changes |

## Implementation Guidelines

- All contracts live in `src/Contract/` at the root level
- Expose domain operations, not CRUD (`authenticateUser()` not `getUser()`)
- Treat contracts as public APIs — prefer backward-compatible changes
- Contexts depend on contracts, **never** on other context implementations
- `Common` module is exempt — it provides shared utilities, not a bounded context

## Links
- [Bounded Context — Martin Fowler](https://martinfowler.com/bliki/BoundedContext.html)
- [Enterprise Integration Patterns](https://www.enterpriseintegrationpatterns.com/patterns/messaging/)
