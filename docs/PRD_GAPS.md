# PRD Implementation Gaps – ATCLSACCOS_PRD.json

Review date: 2026-02-10. This list covers **unimplemented or partially implemented** acceptance criteria from `docs/ATCLSACCOS_PRD.json`.

---

## Fully unimplemented

| Story | Title | Gap |
|-------|--------|-----|
| **STORY-004** | Secure Logout and Session | **Log logout event for audit** – Logout is not written to an audit log (login attempts are; logout is not). |
| **STORY-020** | System and Notification Settings | **Email/SMS template selection or edit** – Settings have on/off toggles only; no UI to edit email/SMS content or choose templates. |
| **STORY-021** | Report Generation | **Optional export: PDF, Excel** – Only CSV export exists. **Optional breakdown by referrer** – Reports show totals by status only; no “by referrer” breakdown table. |
| **STORY-022** | Export Referrals List | **Export with current list filters** – Export uses only `dateFrom`/`dateTo`. Status and referrer filters on All Referrals (and Pending) are not passed to export. **Format: CSV or Excel** – Excel not implemented. |
| **STORY-023** | Responsive and Mobile UI | **Tables → card layout on small screens** – Referral tables (My referrals, Pending, All referrals) stay as tables; no card layout on mobile. |
| **STORY-025** | Backup and Recovery | **Backup process and recovery procedure** – No backup script or documentation; no “Run backup” or backup section in admin. |
| **STORY-027** | Documentation and Handover | **User manual** (member: register, login, submit referral, view referrals, dashboard) and **Administrator guide** (approval workflow, reports, settings, user/role management) – Not created. |
| **STORY-028** | Integration | **Document integration points** and/or **member validation on registration** – No integration doc or API; no validation against SACCOS member data. |

---

## Partially implemented

| Story | Title | What’s done | What’s missing |
|-------|--------|-------------|----------------|
| **STORY-002** | Member Login | Rate limit (configurable) per identifier/IP; login attempt logging. | **“Lock account after 5 failed attempts”** – PRD can mean permanent account lock; current behavior is throttle per minute, not per-account lockout. |
| **STORY-004** | Secure Logout and Session | Logout clears session and redirects. | **Optional: auto-logout after inactivity** – `session_timeout_minutes` is stored in Settings but **not applied** to Laravel session lifetime; session expiry is still from `config/session.php` only. |
| **STORY-014** | Admin Dashboard | Summary counts, referrals over time (last 6 months), top referrers. | **Optional: referrals by period (daily/monthly/annual)** – No period filter on the dashboard itself (Reports page has period + date range). |
| **STORY-016** | SMS Notifications | Admin setting to enable/disable SMS. | **SMS gateway integration** and sending on approve/reject – Not implemented (PRD: “where applicable”). |
| **STORY-026** | Scalable Architecture | Laravel structure, .env, migrations, separation of areas. | **Documented deployment steps** – README may not include full deploy/manual steps. |

---

## Summary

- **Fully unimplemented:** 8 areas (logout audit, email/SMS template edit, PDF/Excel + by-referrer in reports, export with filters + Excel, mobile card layout, backup/recovery, user/admin docs, integration).
- **Partial:** 5 areas (account lockout vs throttle, session timeout from settings, admin dashboard period filter, SMS gateway, deployment docs).

Recommended next steps by impact:

1. **High:** Export with current filters (status, referrer) and optional Excel; apply session timeout from settings; mobile card layout for tables.
2. **Medium:** Logout audit; email template edit (or doc); reports breakdown by referrer; PDF/Excel export.
3. **Lower / doc-only:** Backup/recovery doc and optional admin backup; user manual and admin guide; integration doc or stub.
