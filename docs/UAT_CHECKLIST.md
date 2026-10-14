# User Acceptance Testing (UAT) Checklist

## Access and Roles

- [ ] Verify Super Admin can access all administration modules.
- [ ] Verify Supervisor can view branch-specific complaints.
- [ ] Verify Employee can view assigned feedback and complaints.
- [ ] Verify Customer can submit complaints and feedback through the public portal.

## Complaint Workflow

- [ ] Submit a new complaint and verify the ticket is created.
- [ ] Assign a complaint to an employee and verify status changes.
- [ ] Close a complaint and verify SLA summary updates.
- [ ] Reopen a complaint from the public tracking portal when allowed.

## Feedback Workflow

- [ ] Submit internal feedback and verify status handling.
- [ ] Update feedback and verify attachments can be added.
- [ ] Archive and restore feedback workflows as permitted.

## Notifications and Tracking

- [ ] Confirm in-app notifications appear for complaint changes.
- [ ] Mark notifications as read individually and all at once.
- [ ] Search for a ticket in the tracking portal and view timeline data.
- [ ] Submit a public satisfaction survey for a resolved complaint.

## Reports and Analytics

- [ ] Verify dashboard statistics update after new complaints.
- [ ] Confirm analytics filters operate by branch, status, and date.
- [ ] Validate exported reports and scheduled report generation.
- [ ] Check that dashboard refresh uses live data and loading indicators.

## Security and Responsiveness

- [ ] Confirm unauthorized users cannot access protected routes.
- [ ] Verify file upload restrictions prevent unsupported file types.
- [ ] Test application responsiveness on desktop, tablet, and mobile.
- [ ] Confirm sessions and cookies are secure in production mode.
