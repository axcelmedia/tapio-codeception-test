# Goal
Create a modular, extensible Codeception acceptance-test scaffold for:
https://tapio.dev.rp003.webhelplogin.com/
Start with the Sign-in flow (`/auth/login`) but design the repository so new actions (admin checks, forms, checkout, CRUD operations) can be added quickly and safely. Use PhpBrowser for speed. Provide helpers, templates, and examples so non-core developers can add tests without rewriting boilerplate.

---

# High-level design & conventions
- **PhpBrowser** suite as default for fast HTTP-level tests. Include optional WebDriver suite skeleton for JS-heavy flows.
- **Data-driven users**: `tests/_data/users.php` returns account objects keyed by role (`admin`, `editor`, `user_test1`, ...).
- **Helpers & Page-Objects**: `tests/_support/Helper/Acceptance.php` provides reusable functions (e.g., `loginAs($I, $role)`, `seeInDashboard($I)`, `fetchResetLinkFromIMAP()`).
- **Cest templates**: Provide an action template (e.g., `TemplateActionCest.php`) with step-wise comments to copy for any new flow.
- **Selectors**: Prefer `data-qa` attributes. Include fallback selectors. Document where to change them.
- **Environment & secrets**: `.env` stores base URL and IMAP creds; CI uses secrets injected as environment variables (e.g., `ADMIN_EMAIL`, `ADMIN_PASS`). Never commit real secrets.
- **Test artifacts**: Save HTTP dumps to `tests/_output`; helper `_failed()` writes last response.
- **Naming & tags**: Use `tests/acceptance/<resource>/<Action>Cest.php` (e.g., `users/AdminLoginCest.php`). Use Codeception groups/tags for selective runs (`@tag admin`).

---

# Deliverables
1. Repo layout (recommended)
.
├─ composer.json
├─ codeception.yml
├─ .env.example
├─ tests/
│ ├─ acceptance.suite.yml
│ ├─ _data/
│ │ └─ users.php
│ ├─ _support/
│ │ ├─ _bootstrap.php
│ │ └─ Helper/
│ │ └─ Acceptance.php
│ ├─ acceptance/
│ │ ├─ TemplateActionCest.php
│ │ ├─ SignInCest.php
│ │ └─ Admin/AdminLoginCest.php
│ └─ _output/

2. `.env.example` keys for admin and multiple roles:
```dotenv
BASE_URL=https://tapio.dev.rp003.webhelplogin.com
TEST_USER_EMAIL=ken+test1@restobox.com
TEST_USER_PASS=testpassword

# Admin account (use CI secrets locally)
ADMIN_EMAIL=admin@example.com
ADMIN_PASS=adminpassword

# IMAP for password-reset checks
IMAP_HOST=
IMAP_PORT=993
IMAP_USER=
IMAP_PASS=
IMAP_FLAGS=/imap/ssl/novalidate-cert
