# Deployment Guide

## Production Preparation

1. Set `APP_ENV=production` and `APP_DEBUG=false` in `.env`.
2. Configure `APP_URL` to the production URL.
3. Set `SESSION_SECURE_COOKIE=true` and `SESSION_HTTP_ONLY=true`.
4. Use a production-ready cache store such as Redis or database-backed cache.
5. Use a queue connection such as `database` or Redis for background jobs.
6. Configure mail settings for notifications:
   - `MAIL_MAILER=smtp`
   - `MAIL_HOST`
   - `MAIL_PORT`
   - `MAIL_USERNAME`
   - `MAIL_PASSWORD`
   - `MAIL_FROM_ADDRESS`
   - `MAIL_FROM_NAME`

## Hostinger Deployment

1. Upload application files to Hostinger public folder.
2. Configure PHP version to 8.2 or later.
3. Set environment variables in Hostinger dashboard using `.env` values.
4. Ensure `storage/` and `bootstrap/cache/` are writable.
5. Set the document root to the Laravel public folder.
6. Create `storage` symlink for public attachments:
   ```bash
   php artisan storage:link
   ```
7. Install dependencies on Hostinger or deploy from CI/CD package.

## Deployment Commands

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize
php artisan migrate --force
npm run build
```

## Scheduled Tasks and Queues

- `php artisan queue:work --sleep=3 --tries=3`
- `php artisan schedule:run`

Hostinger may require a cron job to run `php artisan schedule:run` every minute.

## Backup Procedures

- Backup database regularly.
- Backup `storage/app/public` if using file uploads.
- Export `.env` configuration from secure vault.

## HTTPS and Security

- Enforce HTTPS in production.
- Use SSL/TLS certificates from Hostinger or LetEncrypt.
- Confirm that `APP_URL` starts with `https://`.
