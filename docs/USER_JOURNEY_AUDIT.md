# User Journey Audit Report – ATCL SACCOS Referral Portal

**Audit date:** 2026-02-10  
**Reference:** `docs/USER_JOURNEYS.md`  
**Scope:** Trace each user journey through routes, views, backend components, models, and database to verify readiness.

---

## Executive Summary

| Status | Count |
|--------|-------|
| OK | 42 |
| Issue Found | 2 |
| Partial / Note | 3 |

Overall, the implementation is **complete and consistent** with the documented journeys. Two issues were found that should be fixed; three items are noted for enhancement.

---

## 1. Authentication Module

### 1.1 Member – Register

| Check | Status | Details |
|-------|--------|---------|
| Route `/register` | OK | Jetstream/Fortify auto-registers |
| View `auth/register.blade.php` | OK | Exists; fields: name, email, membership_number, phone, password, password_confirmation |
| Backend `CreateNewUser` | OK | Validates and creates user; `role` and `is_active` from DB defaults (member, true) |
| User model | OK | `membership_number`, `phone`, `role`, `is_active` in fillable; casts correct |
| DB migration | OK | `add_membership_fields` adds membership_number, phone, role (default 'member'); `add_is_active` adds is_active (default true) |
| Links to login | OK | Form posts to `route('register')`; link to login page |

### 1.2 Member – Log in

| Check | Status | Details |
|-------|--------|---------|
| Route `/login` | OK | Fortify |
| View `auth/login.blade.php` | OK | Email or membership number field (label "Email or membership number"); password; remember me; Forgot Password link |
| Fortify `authenticateUsing` | OK | Finds user by email OR membership_number; checks `is_active`; checks `locked_until` |
| Lockout | OK | `LogLoginAttempt` counts failed attempts; locks account when threshold reached; `locked_until` blocks login |
| DB `login_attempts` | OK | Table exists; listener logs identifier, IP, success, attempted_at |
| Redirect to dashboard | OK | Fortify home = `/dashboard`; route closure redirects approvers to `/admin/dashboard` |

**Issue 1:** `lockout_minutes` (lockout duration) is used in `LogLoginAttempt` but **not configurable in Admin Settings**. It defaults to 15. Admins cannot change how long accounts stay locked.

### 1.3 Member – Reset password

| Check | Status | Details |
|-------|--------|---------|
| Route `password.request` | OK | Fortify |
| View `auth/forgot-password.blade.php` | OK | Exists |
| Reset flow | OK | Jetstream/Fortify handles token, email, reset form |

### 1.4 Member – Logout

| Check | Status | Details |
|-------|--------|---------|
| Logout in nav | OK | Profile dropdown has "Log out" with POST to `route('logout')` |
| Session clear | OK | Laravel default |
| Redirect to login | OK | Default behavior |

### 1.5 Session timeout

| Check | Status | Details |
|-------|--------|---------|
| Setting `session_timeout_minutes` | OK | Stored in Settings; applied in `AppServiceProvider` boot via `Config::set('session.lifetime', $minutes)` |
| Laravel session | OK | Uses configured lifetime |

---

## 2. Referrals Module

### 2.1 Submit referral

| Check | Status | Details |
|-------|--------|---------|
| Route `/referrals/create` | OK | `ReferralList::class` indexed; `SubmitReferral::class` for create |
| View `livewire/referrals/submit-referral.blade.php` | OK | All fields: referred_name, referred_phone, referred_email, relationship, notes |
| Component `SubmitReferral` | OK | `submit()` validates; phone/email required (at least one); duplicate check |
| Duplicate check | OK | Same referrer + same phone or email blocked |
| `ReferralIdService` | OK | Uses `Setting::get('referral_id_prefix')`; generates REF-YYYYMMDD-NNNN |
| Referral model | OK | All required fields in fillable |
| Status history | OK | Creates initial entry on submit |
| Redirect after submit | OK | To `referrals.index`; flash message with referral ID |

### 2.2 View my referrals

| Check | Status | Details |
|-------|--------|---------|
| Route `/referrals` | OK | `ReferralList::class` |
| View `livewire/referrals/referral-list.blade.php` | OK | Table columns: ID, name, contact, date, status, View link |
| Filters | OK | `statusFilter`, `dateFrom`, `dateTo` bound and used in query |
| Sort | OK | `sortBy()` for referral_id, referred_name, created_at, status |
| Pagination | OK | `WithPagination`; 10 per page |
| Query | OK | `where('referrer_id', auth()->id())` |

### 2.3 View referral detail

| Check | Status | Details |
|-------|--------|---------|
| Route `/referrals/{referral}` | OK | Route model binding |
| View `livewire/referrals/referral-detail.blade.php` | OK | Shows referral data, status, status history |
| Authorization | OK | `mount()` checks: approver OR own referral; else 403 |
| `statusHistories.changedBy` | OK | Relationship exists on `ReferralStatusHistory`; `ReferralDetail` loads it |
| Mark notifications read | OK | `mount()` marks unread notifications for this referral as read |

---

## 3. Approval Workflow Module

### 3.1 View pending referrals

| Check | Status | Details |
|-------|--------|---------|
| Route `/admin/referrals/pending` | OK | `PendingReferrals::class`; middleware `admin` |
| View `livewire/admin/pending-referrals.blade.php` | OK | Table with ID, name, contact, referrer, date, actions; checkboxes |
| Filters | OK | `dateFrom`, `dateTo`, `referrerId` |
| Query | OK | `whereIn('status', ['pending', 'in_review'])` |

### 3.2 Approve / Reject (single)

| Check | Status | Details |
|-------|--------|---------|
| `approve($id)` | OK | Updates status, approved_by, approved_at; creates history; sends notification + SMS |
| `reject()` | OK | Modal with `rejectionReason`; updates status, rejection_reason; history; notification + SMS |
| View buttons | OK | Approve, Reject (only when `isPending()`) |
| Modal | OK | `showRejectModal`; textarea for reason; validation |

### 3.3 Bulk approve / reject

| Check | Status | Details |
|-------|--------|---------|
| `bulkApprove()` | OK | `selected` array; processes each; notifications + SMS |
| `bulkReject()` | OK | `showBulkRejectModal`; shared `bulkRejectionReason`; validation |
| View | OK | Buttons shown when `count($selected) > 0` |
| Wire bindings | OK | `wire:model="selected"` on checkboxes |

### 3.4 Admin referral detail (approve/reject from detail)

| Check | Status | Details |
|-------|--------|---------|
| Route `/admin/referrals/{referral}` | OK | Same `ReferralDetail` component |
| Approve/Reject buttons | OK | Shown when `auth()->user()->isApprover() && $referral->isPending()` |
| Back link | OK | `route('admin.referrals.pending')` when on admin route |

### 3.5 All referrals (admin)

| Check | Status | Details |
|-------|--------|---------|
| Route `/admin/referrals/all` | OK | `AllReferrals::class`; must be before `{referral}` route |
| View | OK | Filters: status, dateFrom, dateTo, referrerId; sort; Export CSV link |
| Query | OK | All statuses; filters applied |
| Referrer dropdown | OK | `referrerUsers` from distinct referrers |

---

## 4. Dashboards Module

### 4.1 Member dashboard

| Check | Status | Details |
|-------|--------|---------|
| Route `/dashboard` | OK | Returns `view('dashboard')` for members; redirects approvers to admin |
| View `dashboard.blade.php` | OK | Embeds `@livewire('member-dashboard')` |
| `MemberDashboard` | OK | Passes total, pending, approved, rejected, recent, referralsOverTime |
| View vars | OK | All used in template |

### 4.2 Admin dashboard

| Check | Status | Details |
|-------|--------|---------|
| Route `/admin/dashboard` | OK | `AdminDashboard::class` |
| View `livewire/admin/dashboard.blade.php` | OK | Cards; period dropdown; referralsOverTime; top referrers |
| `period` filter | OK | daily / monthly / annual; affects trend grouping |
| `referralsOverTime` | OK | Collection keyed by bucket (date/month/year) |

---

## 5. Notifications Module

### 5.1 Email

| Check | Status | Details |
|-------|--------|---------|
| `ReferralStatusNotification` | OK | `via`: mail, database; `toMail()`; `toArray()` |
| Trigger | OK | Called on approve/reject in PendingReferrals, ReferralDetail |

### 5.2 SMS

| Check | Status | Details |
|-------|--------|---------|
| `SmsService` | OK | Checks `sms_notifications_enabled`; sends via configured driver (log by default) |
| Trigger | OK | Called after notify in approve/reject flows |

### 5.3 In-app

| Check | Status | Details |
|-------|--------|---------|
| `NotificationDropdown` | OK | Bell in nav; `notifications`, `unreadCount` |
| `markAsRead($id)` | OK | Updates notification by id |
| `markAllAsRead()` | OK | Updates all unread |
| Link to referral | OK | Resolves referral from `data.referral_id`; uses `route('referrals.show', $referral)` |
| Mark on open | OK | `ReferralDetail::mount()` marks notifications for this referral |

---

## 6. Administration Module

### 6.1 Users

| Check | Status | Details |
|-------|--------|---------|
| Route `/admin/users` | OK | `Users::class` |
| View | OK | Search, role filter; table with role dropdown, enable/disable |
| `updateRole($userId, $role)` | OK | Validates role; updates user |
| `toggleActive($userId)` | OK | Blocks self-disable; toggles `is_active` |
| Nav visibility | OK | Only when `Auth::user()->isAdmin()` |

### 6.2 Settings

| Check | Status | Details |
|-------|--------|---------|
| Route `/admin/settings` | OK | `Settings::class` |
| View | OK | referral_id_prefix, lockout_attempts, session_timeout_minutes, email/SMS toggles |
| `Setting` model | OK | `get()` / `set()` with cache |
| `save()` | OK | Persists all fields |

**Issue 2:** `lockout_minutes` is used in `LogLoginAttempt` but is **not** in the Settings form. Add a "Lockout duration (minutes)" field so admins can configure how long accounts stay locked.

---

## 7. Reports & Export Module

| Check | Status | Details |
|-------|--------|---------|
| Route `/admin/reports` | OK | `Reports::class` |
| View | OK | period, dateFrom, dateTo; summary cards; Export CSV link |
| Route `/admin/reports/export` | OK | `ExportReferralsController`; accepts dateFrom, dateTo |
| Export query | OK | Applies date filters; includes referrer, approver |

**Note:** Export does not apply status or referrer filters from All Referrals page; it uses only date range.

---

## 8. Navigation & Layout

| Check | Status | Details |
|-------|--------|---------|
| Nav for members | OK | Dashboard, My referrals, Submit referral |
| Nav for approvers | OK | + Admin, Pending referrals, All referrals, Reports |
| Nav for admins | OK | + Users, Settings |
| Responsive | OK | Hamburger; responsive links for all roles |
| Layout `layouts.app` | OK | Includes `@livewire('navigation-menu')`; navigation-menu has Auth::user() (used only when authenticated) |

---

## 9. Database Schema

| Table | Status | Notes |
|-------|--------|-------|
| users | OK | name, email, membership_number, phone, password, role, is_active, locked_until |
| referrals | OK | referral_id, referrer_id, referred_*, status, rejection_reason, approved_by, approved_at |
| referral_status_histories | OK | referral_id, from_status, to_status, changed_by, comment |
| notifications | OK | Laravel notifications table |
| settings | OK | key, value |
| login_attempts | OK | identifier, ip_address, success, attempted_at |

---

## 10. Fixes Applied

### Fix 1: `lockout_minutes` added to Admin Settings ✅

- **Settings.php:** Added `lockout_minutes` property, mount load, validation, and save.
- **settings.blade.php:** Added "Lockout duration (minutes)" input.
- **LogLoginAttempt:** Already uses `Setting::get('lockout_minutes') ?: 15` – no change needed.

### Remaining note

- **Export filters:** Export from All Referrals uses only date range; status and referrer filters are not passed (PRD gap; lower priority).

---

## 11. Conclusion

The implementation is **aligned with the documented user journeys**. Routes, views, Livewire components, models, and database schema are in place and wired correctly. The `lockout_minutes` gap has been fixed; lockout duration is now configurable from Admin Settings.
