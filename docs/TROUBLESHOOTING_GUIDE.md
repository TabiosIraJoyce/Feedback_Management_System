# Troubleshooting Guide

## Common Issues

### 1. Application shows a blank page or exception

- Confirm `APP_DEBUG=false` in production.
- Check `storage/logs/laravel.log` for errors.
- Run `php artisan config:clear` and `php artisan route:cache`.

### 2. Dashboard metrics do not refresh

- Ensure the `/dashboard/metrics` route is accessible.
- Verify JavaScript is loading from `resources/js/app.js`.
- Check browser console for network or CORS issues.

### 3. Uploads fail

- Confirm `storage/app/public` is writable.
- Check allowed file types and size limits in `AttachmentValidationService`.
- Use supported MIME types: JPG, JPEG, PNG, WEBP, PDF, DOC, DOCX.

### 4. Notifications not marking as read

- Ensure notifications are loaded with `notificationItems`.
- Confirm `notifications.read` and `notifications.read-all` routes exist.

### 5. Session issues or logout loops

- Verify `SESSION_DRIVER` and `SESSION_SECURE_COOKIE` settings.
- Ensure the app uses HTTPS with secure cookies in production.
- Check `storage/framework/sessions` file permissions.
