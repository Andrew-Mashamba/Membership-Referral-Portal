# ATCL SACCOS Membership Referral Portal – Implementation Summary

This document summarizes what was implemented from **docs/ATCLSACCOS_PRD.json**.

---

## Design (ATCL SACCOS)

- **Tailwind:** Brand tokens in `tailwind.config.js` (`brandBlue`, `brandGray`, `primaryText`, `secondaryText`, `rounded-lgx`, `shadow-soft`, `text-title`, `text-subtitle`, `text-body`).
- **Component:** `resources/views/components/atcl-button.blade.php` (default + primary variant).
- **Guest layout (optional):** `resources/views/layouts/guest-atcl.blade.php` (40vh header, ATCL brand).
- **Design reference:** `docs/DESIGN_GUIDELINES.md`.

---

## Database

- **Users:** `membership_number` (unique), `phone`, `role` (`member` | `approver` | `administrator`).
- **referrals:** `referral_id`, `referrer_id`, `referred_name`, `referred_phone`, `referred_email`, `relationship`, `notes`, `status`, `rejection_reason`, `approved_by`, `approved_at`.
- **referral_status_histories:** `referral_id`, `from_status`, `to_status`, `changed_by`, `comment`.
- **notifications:** Laravel database notifications (for in-app notification list).
- **settings:** `key`, `value` (admin-configurable: referral ID prefix, lockout, session timeout, email/SMS toggles).
- **login_attempts:** `identifier`, `ip_address`, `success`, `attempted_at` (audit log for all login attempts).
- **users:** `is_active` (boolean) for enabling/disabling portal access.

---

## Authentication (EPIC-001)

- **Registration:** Jetstream register + `membership_number`, `phone` (see `CreateNewUser`, `auth/register.blade.php`).
- **Login:** Email **or** membership number (Fortify `authenticateUsing` in `FortifyServiceProvider`).
- **Password reset / Logout:** Jetstream (Forgot password, logout in nav).

---

## Referrals (EPIC-002)

- **Submit:** `GET /referrals/create` → Livewire `Referrals\SubmitReferral` (form + `ReferralIdService`). Duplicate check: same referrer cannot submit again for the same phone or email.
- **Unique ID:** Configurable prefix from settings (default `REF`) + `YYYYMMDD-0001` in `App\Services\ReferralIdService`.
- **My referrals:** `GET /referrals` → Livewire `Referrals\ReferralList` (filter by status, date range, sort, paginate).
- **Detail + tracking:** `GET /referrals/{referral}` → Livewire `Referrals\ReferralDetail` (status history).

---

## Approval workflow (EPIC-003)

- **Pending list:** `GET /admin/referrals/pending` → Livewire `Admin\PendingReferrals` (approve, reject, bulk approve, **bulk reject with shared reason**; filters: date range, referrer).
- **All referrals:** `GET /admin/referrals/all` → Livewire `Admin\AllReferrals` (all statuses, filters: status, date, referrer; sort; export CSV link).
- **Approve / Reject:** From pending list or from referral detail (admin); `ReferralStatusNotification` (mail + database) sent to referrer.

---

## Dashboard (EPIC-004)

- **Member:** `GET /dashboard` → `MemberDashboard` (counts, **referrals over time last 6 months**, recent referrals, link to submit).
- **Admin:** `GET /admin/dashboard` → `Admin\Dashboard` (counts, **referrals over time last 6 months**, top referrers, link to pending).  
  Approvers are redirected to `admin/dashboard` after login.

---

## Notifications (EPIC-005)

- **Email:** On approve/reject via `ReferralStatusNotification` (mailable + queue).
- **In-app:** Notification bell in nav → `NotificationDropdown` (unread list, link to referral; **mark as read** when opening a referral; **Mark all as read**).  
  **SMS:** Toggle in admin settings only; gateway not implemented (PRD: "where applicable").

---

## Administration (EPIC-006)

- **Middleware:** `admin` → `EnsureUserIsAdmin` (allows `approver` or `administrator`).
- **Routes:** All under `GET /admin/*` (dashboard, pending referrals, all referrals, referral detail, reports, export, **settings**, **users**).
- **User/role:** `GET /admin/users` → Livewire `Admin\Users` (list users, assign role: member/approver/administrator, **enable/disable** portal access via `is_active`).  
  To set role from CLI: `php artisan user:admin` (email or membership number).
- **Settings:** `GET /admin/settings` → Livewire `Admin\Settings` (referral ID prefix, lockout attempts per minute, session timeout minutes, email/SMS notification toggles). Stored in `settings` table; lockout and prefix used at runtime.
- **Login audit:** All login attempts (success and failure) logged to `login_attempts` (identifier, IP, success, timestamp). Inactive users cannot log in.

---

## Reports (EPIC-007)

- **Period report:** `GET /admin/reports` → Livewire `Admin\Reports` (date range, counts).
- **Export CSV:** `GET /admin/reports/export?dateFrom=...&dateTo=...` → `ExportReferralsController`.

---

## Routes overview

| Route | Purpose |
|-------|--------|
| `/` | Welcome |
| `/login`, `/register`, `/forgot-password`, `/reset-password` | Auth (Jetstream) |
| `/dashboard` | Member dashboard (or redirect to admin) |
| `/referrals` | My referrals list |
| `/referrals/create` | Submit referral |
| `/referrals/{referral}` | Referral detail |
| `/admin/dashboard` | Admin dashboard |
| `/admin/referrals/pending` | Pending referrals (approve/reject/bulk approve/bulk reject; filters) |
| `/admin/referrals/all` | All referrals (filters, export CSV) |
| `/admin/referrals/{referral}` | Referral detail (with approve/reject for admin) |
| `/admin/reports` | Reports (period + export link) |
| `/admin/reports/export` | CSV export |
| `/admin/settings` | Admin settings (ID prefix, lockout, session timeout, email/SMS) |
| `/admin/users` | User management (role, enable/disable) |

---

## Make first user an administrator

```bash
php artisan user:admin
# Enter email or membership number when prompted
```

---

## Optional next steps (from PRD)

- **STORY-016:** SMS gateway integration (settings toggle exists; wire to provider when needed).
- **STORY-021:** Reports: optional PDF/Excel export; optional breakdown by referrer.
- **STORY-023:** Responsive: card layout on mobile for tables; touch targets (partially applied).
- **STORY-025:** Backup/recovery script or doc (or link from admin).
- **STORY-027:** User manual and administrator guide (step-by-step).
- **STORY-028:** Integration with existing SACCOS systems (member validation API, etc.).
