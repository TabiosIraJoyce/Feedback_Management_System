# Smoke Test Form

Use this form after a deployment, major code change, or before a demo. The goal is to quickly identify:

- broken core workflows
- permission or access issues
- missing or incomplete module behavior
- UI/UX gaps that need improvement

Fill out one copy of this form per test run.

---

## Test Run Info

| Field | Value |
| --- | --- |
| Test date |  |
| Tester name |  |
| Environment | `local` / `staging` / `production` |
| Build / commit |  |
| Browser / device |  |
| Overall result | `PASS` / `PASS WITH ISSUES` / `FAIL` |

---

## Status Legend

| Status | Meaning |
| --- | --- |
| `PASS` | Module works as expected |
| `FAIL` | Module is broken or blocked |
| `PARTIAL` | Module works but has issues |
| `N/A` | Not tested in this run |

Severity:

- `Critical` = blocks business use
- `High` = major workflow issue
- `Medium` = usable but needs correction
- `Low` = minor bug or polish item

---

## Role Accounts To Test

Prepare these accounts before running the checklist:

| Role | Test account | Notes |
| --- | --- | --- |
| Super Admin |  | Full access |
| Admin |  | Admin access |
| Department Head / scoped reviewer |  | Review scope check |
| Employee |  | Submitter / limited access |
| Public user | No login | QR and tracking flows |

---

## Smoke Test Matrix

| Module | Core test | Expected result | Status | Severity if failed | Notes / defects |
| --- | --- | --- | --- | --- | --- |
| Login | Login with valid admin account | Redirects to dashboard |  |  |  |
| Login | Login with invalid password | Validation shown, no login |  |  |  |
| Dashboard | Open dashboard | Page loads without error |  |  |  |
| Dashboard | Load dashboard metrics | Cards/charts refresh correctly |  |  |  |
| User Management | Open Admin > Users | Users page loads |  |  |  |
| User Management | Create new user | New account is saved successfully |  |  |  |
| User Management | Update existing user | Changes are saved successfully |  |  |  |
| Role Permissions | Open Admin > Roles | Role matrix loads |  |  |  |
| Role Permissions | Update a role permission | Changes save correctly |  |  |  |
| Business Types | Create or edit business type | Record saves correctly |  |  |  |
| Branches | Create or edit branch | Record saves correctly |  |  |  |
| Branches | Toggle branch status | Status changes correctly |  |  |  |
| Departments | Create or edit department | Record saves correctly |  |  |  |
| Departments | Toggle department status | Status changes correctly |  |  |  |
| Products | Create or edit product | Record saves correctly |  |  |  |
| Products | Assign product to branch | Assignment saves correctly |  |  |  |
| Products | Toggle assigned branch status | Assignment status updates |  |  |  |
| Public Feedback | Open public feedback landing page | Page loads without login |  |  |  |
| Public Feedback | Open branch feedback form via URL/QR | Correct target form opens |  |  |  |
| Public Feedback | Submit public feedback | Submission is saved and success message appears |  |  |  |
| Form Builder | Open Admin > Forms | Form list loads |  |  |  |
| Form Builder | Create or edit public form | Form saves correctly |  |  |  |
| QR Management | Open Admin > QR Codes | QR list loads |  |  |  |
| QR Management | Download QR code | File downloads correctly |  |  |  |
| QR Management | Print QR code | Print page loads correctly |  |  |  |
| QR Management | Toggle QR status | Status updates correctly |  |  |  |
| Complaints | Open complaints list | List loads without error |  |  |  |
| Complaints | Create new complaint | Complaint ticket is created |  |  |  |
| Complaints | Assign complaint | Assignee and status update correctly |  |  |  |
| Complaints | Close complaint | Complaint closes correctly |  |  |  |
| Complaints | Reopen complaint | Complaint reopens correctly |  |  |  |
| Public Complaint Tracking | Search by tracking number | Matching record is found |  |  |  |
| Public Complaint Tracking | View complaint timeline | Timeline and status data appear |  |  |  |
| Public Complaint Tracking | Submit survey | Survey is saved correctly |  |  |  |
| Public Complaint Tracking | Reopen via public portal | Reopen works only when allowed |  |  |  |
| Feedback Center | Open feedback list | List loads correctly |  |  |  |
| Feedback Center | Submit internal/customer feedback | Record is created |  |  |  |
| Feedback Center | Edit feedback | Changes save correctly |  |  |  |
| Employee Feedback | Open employee feedback list | List and filters load |  |  |  |
| Employee Feedback | Submit employee feedback | Record is created |  |  |  |
| Employee Feedback | Update status | Workflow status changes correctly |  |  |  |
| Employee Feedback | Update internal notes | Notes save correctly |  |  |  |
| Notifications | Open notifications | Items load correctly |  |  |  |
| Notifications | Mark one as read | Item updates correctly |  |  |  |
| Notifications | Mark all as read | All items update correctly |  |  |  |
| Search | Use global search | Relevant results appear |  |  |  |
| Reports | Open reports page | Report page loads |  |  |  |
| Reports | Filter report data | Filters work correctly |  |  |  |
| Reports | Export CSV/PDF | File exports correctly |  |  |  |
| Reports | Create scheduled report | Schedule is saved correctly |  |  |  |
| SLA Settings | Open Admin > SLA | SLA config page loads |  |  |  |
| SLA Settings | Update SLA settings | Changes save correctly |  |  |  |
| System Settings | Open Admin > Settings | Settings page loads |  |  |  |
| System Settings | Update settings | Changes save correctly |  |  |  |
| Profile | Open profile page | Profile page loads |  |  |  |
| Profile | Update profile | Changes save correctly |  |  |  |
| Attachments | Upload allowed file | File uploads successfully |  |  |  |
| Attachments | Preview/download attachment | File is accessible correctly |  |  |  |
| Access Control | Employee access restrictions | Restricted modules are blocked |  |  |  |
| Access Control | Department/branch scoped review access | Reviewer only sees allowed records |  |  |  |
| Responsiveness | Test on mobile/tablet | Layout remains usable |  |  |  |

---

## Improvement / Missing Feature Log

Use this section for items that are not exactly broken, but still need work.

| Module | Type | Description | Suggested improvement | Priority |
| --- | --- | --- | --- | --- |
| Example: User Management | UX | Success message is unclear after save | Add visible success banner | Medium |
|  | `Bug` / `Missing Feature` / `UX` / `Performance` / `Security` |  |  |  |
|  |  |  |  |  |
|  |  |  |  |  |

---

## Top Issues Summary

List the most important findings from this smoke test run.

1. 
2. 
3. 
4. 
5. 

---

## Release Recommendation

Select one:

- [ ] Ready for demo
- [ ] Ready for UAT
- [ ] Ready for production
- [ ] Not ready, fix critical issues first

Reason:

`Add short summary here.`

---

## Suggested Follow-Up

After the smoke test, create tickets for:

- every `FAIL`
- every `PARTIAL` with Medium or higher impact
- every repeated UX issue seen in multiple modules
- every missing permission or role-scoping issue

Recommended labels:

- `smoke-test`
- `critical`
- `ui-ux`
- `permission`
- `reporting`
- `public-flow`
