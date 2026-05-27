# Hackathon Nexus

[![PHP Validation](https://github.com/dengsauve/hackathon-nexus/actions/workflows/php.yml/badge.svg)](https://github.com/dengsauve/hackathon-nexus/actions/workflows/php.yml)
[![Frontend Validation](https://github.com/dengsauve/hackathon-nexus/actions/workflows/frontend.yml/badge.svg)](https://github.com/dengsauve/hackathon-nexus/actions/workflows/frontend.yml)
[![Docker Build](https://github.com/dengsauve/hackathon-nexus/actions/workflows/docker.yml/badge.svg)](https://github.com/dengsauve/hackathon-nexus/actions/workflows/docker.yml)

Hackathon Nexus is a Laravel-powered operating room for hackathons: public event discovery, participant portals, team management, project submissions, judging, notifications, and organizer controls in one focused app.

## What It Does

- Public homepage and event discovery for visitors.
- Email/password auth plus OAuth-ready Google and GitHub hooks.
- Authenticated portal with joined events, managed teams, and recommended activity.
- Team creation, invitations, membership management, and event registration.
- Organizer event management with publishing, status controls, QR codes, and assistance request monitoring.
- Project entries with repo/demo links, assets, submission validation, and confirmations.
- Judging workflows with rubrics, assignments, scoring, and event finalization.
- Admin moderation, role/permission scaffolding, audit logs, and notification preferences.

## Stack

- Laravel 13, PHP 8.3+
- SQLite by default for local development
- Blade, Vite, TypeScript, Tailwind CSS 4
- Laravel Socialite for OAuth providers
- Docker Compose for containerized validation and runtime checks
- GitHub Actions for PHP, frontend, and Docker build gates

## Local Setup

```bash
composer install
npm install
cp .env.example .env
php artisan key:generate
touch database/database.sqlite
php artisan migrate --seed
npm run build
```

Run the app:

```bash
composer run dev
```

Or run the Laravel server and Vite separately:

```bash
php artisan serve
npm run dev
```

## Docker

Build and validate the Compose setup:

```bash
docker compose config
docker compose build
docker compose up
```

## Quality Gates

These are the same broad checks represented in CI:

```bash
composer validate --strict
./vendor/bin/pint --test
php artisan migrate:fresh --seed --force
php artisan test
npm run typecheck
npm run build
docker compose build
```

## OAuth Configuration

The app expects OAuth credentials to stay in `.env`, never in source control.

```dotenv
GOOGLE_CLIENT_ID=
GOOGLE_CLIENT_SECRET=
GOOGLE_REDIRECT_URI="${APP_URL}/auth/google/callback"

GITHUB_CLIENT_ID=
GITHUB_CLIENT_SECRET=
GITHUB_REDIRECT_URI="${APP_URL}/auth/github/callback"
```

Email verification is currently disabled by product choice, but the verification views and controllers remain in place for future reactivation.

## Project Notes

- Product objectives live in [docs/product/codex-objectives-v1.md](docs/product/codex-objectives-v1.md).
- User flow canvas lives in [docs/flows/User Flows.canvas](docs/flows/User%20Flows.canvas).
- Deployment notes live in [docs/deployment/dreamhost-shared-hosting.md](docs/deployment/dreamhost-shared-hosting.md).
- Operations notes live in [docs/operations/production-operations.md](docs/operations/production-operations.md).

## Security Hygiene

Do not commit `.env`, local databases, storage artifacts, secrets, OAuth credentials, generated logs, or dependency directories. The repository is configured to keep those out of version control; keep production secrets in the deployment environment.
