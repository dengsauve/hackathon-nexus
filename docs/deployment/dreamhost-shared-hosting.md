# DreamHost Shared Hosting Deployment

This app is structured for shared hosting where long-running queue workers may not be available.

## Environment

- Point the domain web root to `public/`.
- Keep `.env` outside version control and set `APP_ENV=production`, `APP_DEBUG=false`, and a generated `APP_KEY`.
- Use `QUEUE_CONNECTION=sync` unless a separate worker is available.
- Configure `MAIL_MAILER` for the selected transactional provider.
- Configure `FILESYSTEM_DISK` for local storage or an S3-compatible provider.

## Release Steps

1. Upload the application files, excluding ignored local artifacts.
2. Run `composer install --no-dev --optimize-autoloader`.
3. Run `php artisan migrate --force`.
4. Run `php artisan config:cache`, `php artisan route:cache`, and `php artisan view:cache`.
5. Build assets locally with `npm run build` and upload `public/build`, or run the build step on the host if Node is available.

## Writable Paths

Ensure the PHP user can write to:

- `storage/`
- `bootstrap/cache/`
- `database/database.sqlite` if SQLite is used

## Public Directory

DreamHost should serve the Laravel `public/` directory directly. Do not expose the repository root as the web root.
