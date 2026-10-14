# Maintenance Guide

## Daily Checks

- Confirm application uptime.
- Review failed queue jobs and restart workers.
- Inspect recent audit logs for unexpected changes.
- Verify backup jobs completed successfully.

## Weekly Tasks

- Run `php artisan schedule:run` via cron.
- Confirm scheduled reports are sending correctly.
- Review performance metrics and load times.
- Clear stale cache if needed:
  ```bash
  php artisan cache:clear
  php artisan route:cache
  php artisan config:cache
  php artisan view:cache
  ```

## Monthly Tasks

- Update composer and npm dependencies in a staging environment.
- Validate SSL certificate and renewal schedule.
- Test disaster recovery and data restore procedures.
- Review user roles and permissions for stale access.

## Backup Best Practices

- Use a dedicated backup service for the database.
- Archive uploaded attachments separately if using public storage.
- Encrypt backups and store them offsite.
