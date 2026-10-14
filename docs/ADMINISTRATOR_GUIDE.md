# System Administrator Guide

## Roles and Permissions

The system uses Spatie Permission for role-based access control.

- `Super Admin` has full access across the application.
- `Supervisor` can manage complaints within their branch.
- `Employee` can receive assigned complaints and manage feedback.
- `Customer` can submit and track complaints and feedback.

## Administration Tasks

- Manage users and roles under the Admin > Users area.
- Maintain system settings under Admin > Settings.
- Configure SLAs under Admin > SLA.
- Monitor scheduled reports and audit logs.

## Audit Logging

The application uses Spatie Activitylog to capture important actions such as:

- attachment uploads and downloads
- complaint updates
- notification reads
- public tracking activity

Review audit logs for abnormal access or suspicious workflows.

## Security Hardening

- Review login and session settings.
- Rotate admin passwords regularly.
- Confirm file upload policies and attachment restrictions.
- Ensure `SESSION_SECURE_COOKIE` and `SESSION_HTTP_ONLY` are enabled.
