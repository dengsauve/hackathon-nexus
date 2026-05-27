# Production Operations

## Deployment

- Serve the Laravel `public/` directory as the web root.
- Keep `.env` outside version control.
- Run `composer install --no-dev --optimize-autoloader`.
- Run `php artisan migrate --force`.
- Run `php artisan config:cache`, `php artisan route:cache`, and `php artisan view:cache`.
- Build assets with `npm run build` and deploy `public/build`.

## Backups

- Back up the production database daily.
- Back up `storage/app` if local file storage is used.
- Test restore into a staging database before major events.

## Monitoring

- Use `/up` for platform health checks.
- Monitor application logs in `storage/logs`.
- Alert on repeated HTTP 500 responses and failed jobs.

## Log Rotation

- Rotate `storage/logs/*.log` daily on shared hosting.
- Retain at least 14 days of logs for incident review.

## Scheduled Jobs

- Configure cron to run `php artisan schedule:run` every minute when supported.
- If cron is unavailable, keep `QUEUE_CONNECTION=sync` and run operational reminders manually.
