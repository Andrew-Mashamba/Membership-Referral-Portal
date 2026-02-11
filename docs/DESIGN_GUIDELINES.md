# ATCL SACCOS – Design Reference (Tailwind + Laravel)

All UI for the Membership Referral Portal MUST follow this design reference. PRD stories reference it via the **design** field.

---

## 1. Design Tokens (Tailwind Configuration)

**File:** `tailwind.config.js`

- **Colors (ATCL SACCOS Official):** `primaryBg` #FFFFFF, `primaryText` #1A1A1A, `secondaryText` / `accent` #797979, `brandBlue` #20538A (primary brand), `brandGray` #797979, `brandWhite` #FFFFFF, `iconBg` #20538A.
- **Shape:** `rounded-lgx` 16px, `rounded-mdx` 12px.
- **Shadow:** `shadow-soft` (0 2px 6px rgba(0,0,0,0.08)), `shadow-card` (0 4px 10px rgba(0,0,0,0.1)).
- **Typography:** `text-title` 15px, `text-subtitle` 11px, `text-body` 13px/1.5.
- **Spacing / touch:** `spacing.18` 72px, `spacing.20` 80px; buttons min-h-[72px] max-h-[80px]; min 48×48px touch targets.

---

## 2. Color Usage Rules (STRICT)

- **Default UI:** `bg-white`, `bg-primaryBg`, `text-primaryText`, `text-secondaryText`.
- **Brand:** `bg-brandBlue` (primary CTAs, headers), `text-brandBlue` (emphasis), `bg-brandGray` / `text-brandGray` (dividers, secondary).
- **Disallowed:** `bg-green-*`, `bg-orange-*`, `bg-red-*`, `bg-blue-*` (use only `bg-brandBlue`).  
- **Rule:** ATCL Blue = authority; use only for key actions and hierarchy.

---

## 3. Typography Rules

- Title: `text-title font-semibold text-primaryText`
- Subtitle: `text-subtitle text-secondaryText`
- Body: `text-body text-primaryText`
- Overflow: `truncate` (single line), `line-clamp-2` (two lines + ellipsis)

---

## 4. Spacing System

- Button gaps: `gap-3` (12px)
- Container padding: `p-4` (16px)
- Section margin: `mb-6` (24px)

---

## 5. Layout Template

- **Blade layout:** `resources/views/layouts/app.blade.php` — `body`: `bg-white text-primaryText antialiased`; `{{ $slot }}`.
- **Page structure:** `<x-app-layout>`, `min-h-screen px-6 flex flex-col`; header `h-[40vh]` with `bg-brandBlue`, logo in `bg-white shadow-soft rounded-lgx p-4`; main `flex-1 min-h-0 overflow-y-auto`; bottom spacing `h-6`.

---

## 6. Button Component

- **File:** `resources/views/components/atcl-button.blade.php`
- **Default:** full width, min-h-[72px] max-h-[80px], `bg-white rounded-lgx shadow-soft`, icon in `w-12 h-12 rounded-mdx bg-brandBlue`, title + subtitle with `truncate`.
- **Primary CTA:** `bg-brandBlue text-white` full width, same height and radius.
- **Usage:** `<x-atcl-button title="..." subtitle="..."><x-slot:icon>...</x-slot:icon></x-atcl-button>`

---

## 7. Accessibility

- Buttons: `aria-label`, `focus:outline-none focus:ring-2 focus:ring-brandBlue`.
- Contrast compliant; touch targets ≥48×48px; screen-reader friendly.

---

## 8. Animations

- **fadeInUp** – Cards and sections animate in from below (opacity 0→1, translateY 12px→0).
- **fadeIn** – Headers and simple content fade in (opacity 0→1).
- **Staggered delays** – Use 75ms, 150ms, 225ms, 300ms, 375ms for a cascading entrance (e.g. stat cards, sections).
- Keep motion subtle; no bounce, parallax, or chained effects.

---

## 9. UI/UX Guidelines

### Interactions

- **Hover lift** – Cards use `hover:-translate-y-1` and `hover:shadow-card` for a light lift.
- **Transitions** – Use `duration-300 ease-out` on state changes for smooth feedback.
- **Focus ring** – Use `focus:ring-2 focus:ring-brandBlue/30` (or `focus:ring-brandBlue`) for accessibility.

### Layout

- **Rounded corners** – Use `rounded-2xl` on cards for a modern, soft look.
- **Spacing** – Prefer generous padding and spacing between sections (e.g. `p-5 sm:p-6`, `mb-8`, `gap-4 sm:gap-5`).
- **Typography** – Use larger titles and clear hierarchy (e.g. `text-2xl` for page titles, `text-title` for section headings).

### Visual tweaks

- **Stat cards** – Larger numbers (`text-2xl` / `text-3xl`), clear labels with `text-subtitle text-secondaryText uppercase tracking-wider`.
- **Period badges** – Time-based data as pills with brand accents (`bg-brandBlue/5`, `border-brandBlue/10`, `text-brandBlue` for counts).
- **Lists (e.g. top referrers)** – Numbered badges, row hover (`hover:bg-brandGray/5`), clear counts and hierarchy.

### Brand colors (reference)

- **primaryText** – Headings and body text.
- **secondaryText** – Labels and placeholders.
- **brandBlue** – Accent links, counts, badges, primary actions.
- **brandGray** – Subtle borders and hover states (`border-brandGray/20`, `hover:bg-brandGray/5`).
- **white / primaryBg** – Backgrounds.

Keep the layout minimal while using subtle motion and clear hierarchy.

---

## 10. Performance (Laravel)

- Blade components; avoid Alpine unless required; `php artisan view:cache`; `@once` for scripts; `wire:key` in Livewire loops.

---

## 11. Admin Dashboard

The admin dashboard (`/admin/dashboard`) uses a consistent card layout, charts, and equal-height grids. Follow these rules when adding or changing dashboard sections.

### 11.1 Equal-height side-by-side cards

When two cards sit side by side (e.g. “Referrals over time” and “Status breakdown”):

- **Grid** – Added `items-stretch` so both columns share the same row height.
- **Column wrappers** – Each column is a flex container so the card inside can stretch to full height.
- **Cards** – Both cards use `flex flex-col w-full` (status card also has `h-full`) so they fill their column.
- **Content** – The padded content div in each card uses `flex flex-col flex-1 min-h-0` so the chart area can grow and the card height is driven by the grid.
- **Chart area** – Both chart containers use the same classes: `h-64 sm:h-72 flex-1 min-h-[240px]`, so the chart regions are the same size and the cards align.

Example structure:

```html
<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8 items-stretch">
  <div class="flex">
    <div class="... flex flex-col w-full">
      <div class="p-5 sm:p-6 flex flex-col flex-1 min-h-0">
        <h2>...</h2>
        <div class="h-64 sm:h-72 flex-1 min-h-[240px]" wire:ignore>
          <!-- chart canvas -->
        </div>
      </div>
    </div>
  </div>
  <div class="flex">
    <div class="... flex flex-col w-full h-full">
      <div class="p-5 sm:p-6 flex flex-col flex-1 min-h-0">
        <h2>...</h2>
        <div class="h-64 sm:h-72 flex-1 min-h-[240px] flex items-center justify-center" wire:ignore>
          <!-- chart canvas -->
        </div>
      </div>
    </div>
  </div>
</div>
```

### 11.2 Dashboard layout and spacing

- **Container** – `max-w-5xl mx-auto px-4 sm:px-0 py-6 sm:py-8`.
- **Stats grid** – `grid grid-cols-2 md:grid-cols-4 gap-4 sm:gap-5 mb-8`; stat cards use `p-5 sm:p-6`, `rounded-2xl`, `shadow-soft`, `border border-white/80`.
- **Section spacing** – Use `mb-8` between major sections (stats, charts row, top referrers).
- **Card styling** – White cards: `bg-white rounded-2xl shadow-soft border border-white/80`; hover: `hover:shadow-card` (and optional `hover:-translate-y-1`, `hover:border-brandBlue/10` for clickable cards).

### 11.3 Charts (Chart.js)

- **Colors** – Use ATCL brand only: `rgb(32, 83, 138)` (brandBlue), `rgba(32, 83, 138, 0.15)` (fill/light), `rgba(32, 83, 138, 0.5)` (mid), `rgb(121, 121, 121)` (brandGray). No green, orange, or red.
- **Chart containers** – Use `wire:ignore` on the wrapper div so Livewire does not replace the canvas on re-render; update chart data via Livewire methods (e.g. `$wire.getChartTimeData()`) and `$wire.$watch()` for reactive period changes.
- **Accessibility** – Give each canvas `role="img"` and `aria-label="…"` describing the chart.
- **Responsive** – Chart wrapper: `responsive: true`, `maintainAspectRatio: false`; container: fixed height (e.g. `h-64 sm:h-72` or `min-h-[240px]`).

### 11.4 Dashboard animations

- Apply `opacity-0 animate-fade-in-up` to card/section wrappers; use staggered `delay-75`, `delay-150`, `delay-225`, `delay-300`, `delay-375` for stats and subsequent sections so content enters in order.
- Reuse the same keyframes and delay classes defined in the dashboard view (or in a shared partial) for consistency.

### 11.5 Form controls in dashboard

- **Period / filters** – Selects: `rounded-xl border border-brandGray/20 px-4 py-2.5 text-body text-primaryText bg-primaryBg`, `focus:ring-2 focus:ring-brandBlue/30 focus:border-brandBlue`, `min-h-[44px]`. Use `wire:model.live` for instant updates.

---

**PRD field:** Each story’s **design** object references this document and specifies layout, components, and tokens for that screen.
