# Frontend UI/UX Standards — SecondShiftBD LMS

> **Mandatory reference for all frontend work.** Every UI change, new page, component, or Livewire/Volt feature must follow this document. Treat it as the single source of truth for production-level UI/UX.

---

## 1. Purpose

This guide defines how we build **scalable, responsive, reactive, accessible, and maintainable** frontend experiences for the LMS. It reflects standards used by senior frontend engineers who also own UX/UI quality — not just visual polish, but usability, performance, and consistency at scale.

**Applies to:**

- `resources/views/**` (Blade, Livewire Volt, Flux)
- `resources/css/**`, `resources/js/**`
- `tailwind.config.js`
- Any student-facing or authenticated UI (not Filament admin unless explicitly requested)

---

## 2. Tech Stack (Project Context)

| Layer | Tool | Role |
|-------|------|------|
| Templates | Blade + Livewire Volt | Server-rendered UI + reactive islands |
| Components | Blade components (`x-*`), Flux | Reusable UI primitives |
| Styling | Tailwind CSS v3/v4, `@tailwindcss/forms` | Utility-first, design tokens |
| Interactivity | Alpine.js, Livewire wire:* | Local UI state + server sync |
| Build | Vite | CSS/JS bundling |
| Icons | Inline SVG (preferred), Font Awesome (legacy) | Visual affordances |
| Locale | Bengali-first (`lang/bn/`) | Copy, numerals, RTL-safe layout |

**Rule:** Do not introduce a second CSS framework, jQuery, or inline `<style>` blocks unless there is a documented exception.

---

## 3. Design System

### 3.1 Brand Tokens (use these — never hardcode hex in templates)

Defined in `tailwind.config.js`:

| Token | Usage |
|-------|--------|
| `brand-navy` / `brand-navy-light` | Primary brand, headers, CTAs |
| `brand-blue` / `brand-blue-light` | Accents, links, active states |
| `brand-gold` | Highlights, badges, promotions |
| `ostad-*` | Legacy alias — prefer `brand-*` in new code |

**Elevation:** Use `shadow-elevation-1` … `shadow-elevation-5` for depth (Material Design 3).

**Radius:** Prefer `rounded-md-sm` (8px), `rounded-md-md` (12px), `rounded-md-lg` (16px), `rounded-2xl` for cards.

**Motion:** Use `transition-colors`, `transition-all`, timing `ease-md-standard` / `ease-md-emphasized`. Keep animations **≤ 300ms** for UI feedback; decorative animations ≤ 600ms.

**Interaction:** Apply `md-ripple` class to primary clickable surfaces (buttons, list items). Ripple is handled globally in `resources/js/app.js`.

### 3.2 Typography

- **Font:** Figtree (loaded in layouts) — `font-sans`
- **Scale (mobile → desktop):**
  - Page title: `text-2xl sm:text-3xl md:text-4xl font-extrabold`
  - Section title: `text-xl sm:text-2xl font-bold`
  - Body: `text-sm sm:text-base`
  - Caption/meta: `text-xs sm:text-sm text-gray-500`
- **Line height:** Headings `leading-tight`; body `leading-relaxed`
- **Bengali copy:** Use natural Bengali for user-facing strings; keep English only for proper nouns/brands where appropriate
- **Numerals:** Use `$this->bn()` helper in Volt for marketing stats; keep formatting consistent within a page

### 3.3 Spacing & Layout

- **Page container:** `max-w-7xl mx-auto px-4 sm:px-6 lg:px-8`
- **Narrow content (forms, articles):** `max-w-2xl` or `max-w-3xl mx-auto`
- **Section vertical rhythm:** `py-12 md:py-16 lg:py-20`
- **Component gaps:** `gap-4` (default), `gap-6` (cards/grids), `gap-8` (sections)
- **Grid:** `grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4` — always start at 1 column

### 3.4 Color & Contrast

- Body text on white: `text-gray-700` or `text-gray-900`
- Muted/secondary: `text-gray-500`
- Links: `text-brand-blue hover:text-brand-navy` with underline on hover for inline links
- **Minimum contrast:** WCAG AA (4.5:1 body, 3:1 large text)
- Never rely on color alone for status — pair with icon + text

---

## 4. Responsive Design (Mobile-First)

### 4.1 Breakpoint Strategy

Design **mobile first**, then enhance:

```
default (<640px) → sm (640px) → md (768px) → lg (1024px) → xl (1280px)
```

**Required patterns:**

```html
<!-- Stack on mobile, row on desktop -->
<div class="flex flex-col md:flex-row gap-4">

<!-- Hide/show by breakpoint -->
<div class="hidden sm:block">Desktop nav</div>
<div class="sm:hidden">Mobile menu</div>

<!-- Fluid typography -->
<h1 class="text-3xl sm:text-4xl md:text-5xl">

<!-- Touch-friendly targets -->
<button class="min-h-[44px] min-w-[44px] px-4 py-3">
```

### 4.2 Touch & Pointer

- Minimum tap target: **44×44px** (WCAG 2.5.5)
- Adequate spacing between adjacent tap targets (`gap-2` minimum)
- Hover states must have `:focus-visible` equivalents for keyboard users
- Avoid hover-only disclosure of critical actions on mobile

### 4.3 Images & Media

- Always set `width`/`height` or aspect ratio (`aspect-video`, `aspect-square`) to prevent layout shift
- Use `loading="lazy"` for below-fold images
- Course thumbnails: consistent aspect ratio across cards (e.g. `aspect-video object-cover`)
- Video player: responsive wrapper `relative w-full aspect-video`

---

## 5. Component Architecture (Scalable)

### 5.1 Hierarchy

```
Layout (app.blade.php, guest.blade.php)
  └── Page (Volt or Blade view)
        └── Section (semantic <section>)
              └── Component (x-button, x-card, Flux)
                    └── Primitive (button, input, label)
```

### 5.2 When to Create a Component

Create or extend a Blade/Livewire component when:

- The UI pattern appears **2+ times**
- It has **variants** (primary/secondary, size, disabled)
- It encapsulates **accessibility** behavior (modal focus trap, dropdown keyboard)

**Do not** copy-paste 20+ lines of markup across pages.

### 5.3 Component API Rules

- Use `@props([...])` with sensible defaults
- Merge classes via `$attributes->merge(['class' => '...'])`
- Support `disabled`, `aria-*`, and `wire:*` passthrough via `$attributes`
- Name by **purpose** not appearance: `x-course-card` not `x-blue-box`

### 5.4 File Locations

| Type | Path |
|------|------|
| Global Blade components | `resources/views/components/` |
| Public course UI (Volt) | `resources/views/livewire/frontend/` |
| Auth pages | `resources/views/pages/auth/` |
| Settings/Teams (Volt) | `resources/views/pages/settings/`, `pages/teams/` |
| Layouts | `resources/views/layouts/` |
| Shared partials | `resources/views/partials/` |

### 5.5 Existing Components — Reuse First

Before creating new UI, check:

- `x-primary-button`, `x-secondary-button`, `x-danger-button`
- `x-text-input`, `x-input-label`, `x-input-error`
- `x-modal`, `x-dropdown`, `x-nav-link`, `x-responsive-nav-link`
- `x-auth-drawer`, Flux icons/components

Extend these rather than duplicating button/input styles inline.

---

## 6. UX Principles

### 6.1 Clarity Over Cleverness

- One primary action per section/card
- Labels must describe outcomes: **"কোর্সে ভর্তি হন"** not vague **"Submit"**
- Progressive disclosure: show essentials first; advanced options in expandable areas

### 6.2 Feedback & System Status

Every user action needs visible feedback within **100ms** (optimistic UI or loading state):

| State | Pattern |
|-------|---------|
| Loading | `wire:loading` spinner/disabled button; skeleton for lists |
| Success | Toast/banner + updated UI (don't rely on silent success) |
| Error | Inline field errors + summary for form-level failures |
| Empty | Illustration/icon + helpful message + CTA |
| Disabled | Reduced opacity + `cursor-not-allowed` + tooltip/reason if non-obvious |

### 6.3 Error Prevention & Recovery

- Destructive actions: confirmation modal with clear consequence text
- Forms: validate on blur/submit; preserve user input on server errors
- Payment/checkout: never lose cart context; show clear retry path
- Device limit, enrollment expiry: explain **why** blocked and **what to do next**

### 6.4 Consistency

- Same action = same label, icon, and placement across pages
- Navigation: active route styling (`request()->routeIs()`)
- Card layout: consistent padding (`p-4 sm:p-6`), image ratio, price display
- Modals: title → body → actions (primary right or full-width on mobile)

### 6.5 Trust & Education Context

- Show instructor name, course stats, reviews where relevant
- Certificate verification, payment history: professional, calm UI — avoid flashy patterns
- Support tickets: thread layout, timestamps, clear status badges

---

## 7. Reactivity (Livewire + Alpine)

### 7.1 Livewire / Volt Rules

- Keep Volt components focused: **one page/feature per file**
- Public properties: only what the view needs; avoid leaking large Eloquent graphs — use DTOs/arrays or `@computed`
- Use `wire:model.live.debounce.300ms` for search; avoid live on every keystroke for heavy queries
- Always handle empty collections in the view (`@forelse`)
- Use `wire:key` on repeated elements in loops
- Prefer `$this->dispatch()` for cross-component events over tight coupling

**Loading states (required):**

```html
<button wire:click="enroll" wire:loading.attr="disabled" class="md-ripple ...">
    <span wire:loading.remove wire:target="enroll">ভর্তি হন</span>
    <span wire:loading wire:target="enroll">অপেক্ষা করুন...</span>
</button>
```

### 7.2 Alpine.js Rules

- Use for **local UI only** (mobile nav toggle, tabs, accordions) — not business logic
- Keep `x-data` small; extract repeated behavior to Alpine data components if reused 3+ times
- Always pair `x-show` with transitions sparingly; ensure focus management in modals

### 7.3 Avoid

- Duplicating state in Alpine and Livewire for the same data
- Full page reloads when Livewire can partial-update
- Blocking the UI without a loading indicator during network calls

---

## 8. Accessibility (Non-Negotiable)

### 8.1 Semantic HTML

- Use `<nav>`, `<main>`, `<section>`, `<article>`, `<header>`, `<footer>`, `<button>`, `<a>` correctly
- **Never** use `<div onclick>` for navigation — use `<a href>` or `<button type="button">`
- One `<h1>` per page; don't skip heading levels

### 8.2 Keyboard & Focus

- All interactive elements reachable via Tab
- Visible focus: `focus:outline-none focus-visible:ring-2 focus-visible:ring-brand-blue focus-visible:ring-offset-2`
- Modals: trap focus, restore on close, close on Escape
- Dropdowns: arrow key navigation where applicable

### 8.3 ARIA & Labels

- Icon-only buttons: `aria-label="..."`
- Form inputs: associated `<label for="">` or `aria-labelledby`
- Live regions for dynamic updates: `aria-live="polite"` for toasts/alerts
- Images: meaningful `alt` text; decorative images `alt=""`

### 8.4 Motion

- Respect `prefers-reduced-motion: reduce` for non-essential animations
- No autoplay video with sound; provide controls

---

## 9. Forms UX

- Group related fields visually; one column on mobile
- Mark required fields consistently (asterisk + `aria-required="true"`)
- Show validation next to the field (`x-input-error`)
- Submit button stays visible — sticky footer on long mobile forms if needed
- Phone OTP: show countdown, resend cooldown, clear error for wrong code
- Password fields: show/hide toggle with accessible label

**Input styling baseline:**

```html
<input class="w-full rounded-md-md border-gray-300 focus:border-brand-blue focus:ring-brand-blue text-sm" />
```

Use `@tailwindcss/forms` defaults; extend via components, not one-off classes.

---

## 10. Performance

- **CSS:** Tailwind utilities only; no large custom CSS blocks — extend in `app.css` sparingly (e.g. `md-ripple`)
- **JS:** Keep `app.js` lean; page-specific JS only when Livewire/Alpine insufficient
- **Images:** Use appropriate sizes; prefer WebP where generated; always dimensions/aspect ratio
- **Fonts:** `preconnect` to font CDN (already in layout); limit weights (400, 500, 600, 700)
- **Lists:** Paginate or infinite scroll — never render 500+ DOM nodes
- **Livewire:** Lazy-load heavy Volt components when possible (`@livewire(..., lazy)`)
- **Third-party CSS:** Avoid new CDN dependencies; prefer SVG icons over icon font where feasible

---

## 11. Internationalization & Locale

- User-facing strings: Bengali primary; use `__('key')` or `lang/bn/*` for shared copy
- Numbers in marketing: Bengali digits via `bn()` helper for consistency
- Layout must tolerate **longer Bengali strings** — avoid fixed widths on labels/buttons
- Dates/currency: format for Bangladesh (BDT `৳`, locale-aware dates)
- Don't break words awkwardly — use `break-words` on narrow containers

---

## 12. Page Templates

### 12.1 Marketing / Course Catalog

- Hero → value prop → search/filter → grid → social proof → CTA
- Course cards: thumbnail, title, instructor, price, rating, badge (e.g. "নতুন")
- Filters: collapsible on mobile; sticky filter bar optional on desktop

### 12.2 Course Detail

- Clear enrollment CTA above fold on mobile (sticky bottom bar acceptable)
- Curriculum accordion: module → lessons with type icons (video/quiz/pdf)
- Preview lesson clearly marked; locked content visually distinct

### 12.3 Lesson Player

- Video area dominant; sidebar curriculum collapses on mobile
- Progress indicator; next/previous navigation
- Respect `device.limit` messaging — friendly, not punitive

### 12.4 Dashboard

- At-a-glance: continue learning, enrollments, progress
- Card-based; empty state guides to course catalog

### 12.5 Auth

- Minimal friction; social login + OTP visible
- Errors friendly and specific; loading on submit
- Use existing auth layouts (`layouts/auth/*`, `x-auth-drawer`)

---

## 13. Code Quality Checklist (Before Every PR / Change)

### Visual & Responsive
- [ ] Works at 320px, 375px, 768px, 1024px, 1440px widths
- [ ] No horizontal scroll on mobile
- [ ] Touch targets ≥ 44px
- [ ] Uses brand tokens (not random hex/gray)

### UX
- [ ] Loading, empty, error, and success states implemented
- [ ] One clear primary action per view section
- [ ] Destructive actions confirmed
- [ ] Copy is Bengali-appropriate and consistent

### Technical
- [ ] Reused existing components where possible
- [ ] No inline `<style>` or `<script>` blocks
- [ ] Semantic HTML + labels/ARIA on interactive elements
- [ ] `wire:loading` / disabled states on async actions
- [ ] Images have alt text and aspect ratio
- [ ] `npm run build` succeeds

### Accessibility
- [ ] Keyboard navigable
- [ ] Focus visible
- [ ] Color contrast AA
- [ ] Icon buttons have `aria-label`

---

## 14. Anti-Patterns (Do Not Do)

| ❌ Bad | ✅ Good |
|--------|---------|
| Inline styles `style="color: #3498DB"` | `text-brand-blue` |
| Copy-paste button classes everywhere | `<x-primary-button>` or shared class via merge |
| `wire:model.live` on every keystroke for DB search | `.debounce.300ms` or form submit |
| Silent form failure (page reload, no message) | Inline errors + `@error` / session flash |
| Fixed pixel widths on text containers | `max-w-*`, `min-w-0`, `flex-1` |
| Hidden critical actions behind hover only | Visible or in overflow menu with label |
| Generic "Error occurred" | Specific, actionable Bengali message |
| New jQuery/React/Vue for one widget | Livewire/Alpine/Blade component |
| 12 different card styles | One `x-course-card` variant system |
| Font Awesome for every icon | Inline SVG for new work |

---

## 15. Example: Production-Grade Course Card

```html
<article class="group flex flex-col bg-white rounded-md-lg shadow-elevation-1 hover:shadow-elevation-3 transition-shadow overflow-hidden">
    <a href="{{ route('courses.show', $course->slug) }}" class="block focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-brand-blue focus-visible:ring-inset">
        <div class="aspect-video bg-gray-100 overflow-hidden">
            <img
                src="{{ Storage::url($course->thumbnail) }}"
                alt="{{ $course->title }}"
                loading="lazy"
                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
            />
        </div>
    </a>
    <div class="flex flex-col flex-1 p-4 sm:p-5 gap-2">
        <h3 class="font-bold text-gray-900 line-clamp-2">
            <a href="{{ route('courses.show', $course->slug) }}" class="hover:text-brand-navy focus-visible:underline">
                {{ $course->title }}
            </a>
        </h3>
        <p class="text-sm text-gray-500">{{ $course->instructor?->name }}</p>
        <div class="mt-auto flex items-center justify-between gap-2 pt-2">
            <span class="text-lg font-extrabold text-brand-navy">৳{{ number_format($course->effective_price) }}</span>
            <a href="{{ route('courses.show', $course->slug) }}"
               class="md-ripple inline-flex items-center min-h-[44px] px-4 py-2 rounded-md-md bg-brand-navy text-white text-sm font-bold hover:bg-brand-navy-light transition-colors focus-visible:ring-2 focus-visible:ring-brand-blue focus-visible:ring-offset-2">
                বিস্তারিত
            </a>
        </div>
    </div>
</article>
```

---

## 16. AI Agent Instructions (Every Prompt)

When implementing or modifying **any** frontend in this project:

1. **Read this file first** (`docs/FRONTEND-UI-UX-STANDARDS.md`).
2. **Do not change backend code** — see `.cursor/rules/no-backend-changes.mdc`. UI work is views/CSS/JS only unless the user explicitly requests backend changes.
3. **Inspect existing patterns** in the nearest similar view/component before writing new markup.
4. **Mobile-first** — write base classes for mobile, then `sm:` / `md:` / `lg:` enhancements.
5. **Reuse** Blade components, brand tokens, elevation, and `md-ripple` — do not invent parallel styling systems.
6. **Implement all UI states** — loading, empty, error, success — not just the happy path.
7. **Bengali-first copy** with accessible, specific labels.
8. **Livewire:** prefer markup-only changes; if touching Volt PHP, keep queries and behavior identical unless explicitly asked.
9. **Accessibility:** semantic HTML, focus rings, ARIA on icon buttons, form labels.
10. **Keep diffs focused** — don't refactor unrelated pages or backend in the same change.
11. **Verify** against Section 13 checklist before considering the task complete.

---

## 17. Related Files

| File | Purpose |
|------|---------|
| `tailwind.config.js` | Brand colors, elevation, radius, motion tokens |
| `resources/css/app.css` | Global utilities (ripple, base layers) |
| `resources/js/app.js` | Alpine bootstrap, ripple handler |
| `resources/views/layouts/app.blade.php` | Main authenticated layout |
| `resources/views/layouts/guest.blade.php` | Public/guest layout |
| `lang/bn/` | Bengali translations |

---

*Last updated: project frontend standards v1.0 — SecondShiftBD LMS*
