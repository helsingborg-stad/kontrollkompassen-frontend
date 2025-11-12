## Copilot / AI agent instructions for Kontrollkompassen frontend

Purpose: short, actionable guidance so an AI coding assistant can be immediately productive in this repo.

1. Big-picture architecture

- This repository contains a PHP Slim-based frontend application (not a separate SPA). The PHP app lives under `frontend/app/` and is the primary web entrypoint.
- Entry point: `frontend/app/index.php` — it loads `config_slim/bootstrap.php` which wires DI, middleware and routes.
- Dependency injection & bootstrapping: `frontend/app/config_slim/bootstrap.php`, `container.php`, `middleware.php`, `routes.php`.
- Code organization:
  - `frontend/app/source/php/Action/` — request handlers (named `*Action.php`) invoked from `routes.php`.
  - `frontend/app/source/php/Interfaces/` — abstract interfaces and contracts used across services.
  - `frontend/app/source/php/Helper/`, `Services/`, `Models/` — helpers, business logic and domain models.
  - Templates: `frontend/app/views/*.blade.php` (Blade style templates; theme files under `views/themes`).
  - Front-end assets: TypeScript at `frontend/app/source/ts/` and SASS at `frontend/app/source/sass/`. Built with Vite configured in `vite.config.mjs`.

2. Build / dev / test workflows (concrete commands)

- Install dependencies:
  - PHP: run `composer install` from `frontend/app/` to populate `vendor/` (tests use `./vendor/bin/phpunit`).
  - Node: run `npm install` (or `pnpm`/`yarn` if you prefer) in `frontend/app/` for Vite and tooling.
- Build assets (production):
  - cd `frontend/app/` && `npm run build` (uses `vite build` per `package.json`).
- Build assets (dev / faster):
  - `npm run build:dev` (vite with development mode) or `npm run watch` for continuous rebuilds.
  - Use `npm run tsc:watch` to watch TypeScript separately.
- Tests: from repo root or `frontend/app/` run `npm test` (this script runs `./vendor/bin/phpunit --testdox tests`). Ensure `composer install` has run first.

3. Project-specific conventions & patterns

- Naming: request handlers are `*Action.php` placed in `source/php/Action/` and wired in `routes.php`.
- Interfaces: many cross-cutting contracts live in `source/php/Interfaces/` (e.g., `AbstractCache`, `AbstractRequest`, etc.). Prefer coding to these interfaces for new services.
- Caching and sessions: see `Helper/MemoryCache.php`, `RedisCache.php`, `Session.php`. Use the existing cache abstractions rather than adding ad-hoc caches.
- Blade cache path is set in `index.php`: `BLADE_CACHE_PATH` defaults to `/tmp/cache/` — clear this when templates don't reflect changes.

4. Integration points & external dependencies to be aware of

- Composer dependencies (PHP) and `vendor/` — required for bootstrapping to work. Tests rely on vendor autoload and phpunit in `vendor/bin`.
- NPM packages: Vite, TypeScript, `@helsingborg-stad/styleguide` (UI tokens/components), Sass. Frontend build depends on these devDependencies in `frontend/app/package.json`.
- Redis may be used if `RedisCache.php` is enabled; configuration and environment-driven selection lives in `source/php/Helper/` and `config_slim/container.php`.

5. Helpful file references (examples you can open directly)

- Entry/boot: `frontend/app/index.php`
- DI / routes / middleware: `frontend/app/config_slim/bootstrap.php`, `frontend/app/config_slim/container.php`, `frontend/app/config_slim/routes.php`, `frontend/app/config_slim/middleware.php`
- Frontend build config: `frontend/app/package.json`, `frontend/app/vite.config.mjs`, `frontend/app/tsconfig.json`
- Main TypeScript & SASS: `frontend/app/source/ts/main.ts`, `frontend/app/source/sass/custom.scss`
- Views: `frontend/app/views/` (templates and theme files)

6. Quick examples the assistant may suggest

- Running tests locally (from repo root):
  - cd frontend/app && composer install && npm install && npm test
- Starting a dev asset watcher while serving PHP locally (dev notes):
  - cd frontend/app && npm run watch
  - In another terminal: cd frontend/app && php -S 0.0.0.0:8000 index.php (or run via Docker/FPM as project's infra requires)

7. Editing & PR guidance for AI edits

- Keep changes small and focused per PR: prefer one service/feature per PR with tests for new PHP behavior.
- When changing templates, remind to clear Blade cache (`/tmp/cache/`) or bump the cache key where appropriate.
- Prefer using existing interfaces in `source/php/Interfaces/` and register new services in `config_slim/container.php`.

If anything here is incomplete or wrong, point to the missing area (e.g., expected dev server command or Docker usage) and I will update the file. Thanks — ready to iterate.
