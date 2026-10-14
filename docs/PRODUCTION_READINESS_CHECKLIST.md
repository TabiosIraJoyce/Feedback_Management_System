# Production Readiness Checklist

## Configuration

- [ ] `APP_ENV=production`
- [ ] `APP_DEBUG=false`
- [ ] `APP_URL` set to the production URL
- [ ] `SESSION_SECURE_COOKIE=true`
- [ ] `SESSION_HTTP_ONLY=true`
- [ ] `SESSION_SAME_SITE=lax`
- [ ] Mail server configured for notifications
- [ ] `CACHE_STORE` configured for production
- [ ] `QUEUE_CONNECTION` configured and workers running

## Deployment

- [ ] Run `composer install --optimize-autoloader`
- [ ] Run `php artisan config:cache`
- [ ] Run `php artisan route:cache`
- [ ] Run `php artisan view:cache`
- [ ] Run `php artisan migrate --force`
- [ ] Run `npm run build`
- [ ] Confirm `storage/` and `bootstrap/cache/` permissions

## Performance and Monitoring

- [ ] Confirm dashboard caching is active
- [ ] Confirm public tracking throttle limits are enforced
- [ ] Confirm database connections use indexes for complaint queries
- [ ] Review scheduled tasks and queue workers
- [ ] Confirm audit logging is capturing critical events

## Security

- [ ] Confirm middleware enforces authentication and role access
- [ ] Confirm SSL/HTTPS is enforced in production
- [ ] Confirm file upload validation is applied
- [ ] Confirm error handling does not leak sensitive data
- [ ] Confirm session cookie settings are secure
