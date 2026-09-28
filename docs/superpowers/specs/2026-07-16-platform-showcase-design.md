# Platform Showcase Dashboard Design Spec

**Date:** 2026-07-16  
**Status:** Design Approved  
**Effort Estimate:** 2-3 hours  
**Audience:** Admin users showcasing platform capabilities to stakeholders

---

## 1. Overview

The **Platform Showcase Dashboard** is an admin-only page that provides a real-time snapshot of the newsmedia platform's capabilities and content ecosystem. It combines key metrics (Articles, Users, Categories, etc.) with an overview of 8 major platform features, allowing admins to quickly demonstrate platform health and functionality to stakeholders.

**Objective:** Give admins a single, professional dashboard to showcase platform capabilities and current content performance without requiring them to navigate multiple admin sections.

**Success Criteria:**

- ✅ Page loads in < 2 seconds with fresh data
- ✅ Displays all 6 key metrics with month-over-month trends
- ✅ Shows 8 feature cards with links to respective admin sections
- ✅ Mobile-responsive (1-column on mobile, multi-column on desktop)
- ✅ Accessible only to admin users
- ✅ Matches existing admin design system (Tailwind, admin layout)

---

## 2. Architecture

### 2.1 Route & Access

**Route:** `GET /admin/showcase` or `GET /admin/platform-showcase`  
**Middleware:** `auth`, `verified`, admin role check (or high-privilege user)  
**View:** `resources/views/admin/showcase/index.blade.php`  
**Controller:** `app/Http/Controllers/Admin/ShowcaseController.php`

### 2.2 Layout

Uses existing `<x-x-admin-layout-modern>` component to maintain consistency with other admin pages (homepage builder, dashboard, etc.)

**Structure:**

```
Admin Layout (sidebar + header)
  ├── Page Title: "Platform Showcase" / "Platform Overview"
  ├── Page Subtitle: "Real-time snapshot of your content ecosystem"
  │
  ├── Hero Banner Section (full-width)
  │   ├── Gradient background (purple to blue or brand color)
  │   ├── Main heading
  │   ├── Subheading
  │   └── Optional illustration/icon
  │
  ├── Main Content Grid (2-column on desktop, 1-column on mobile)
  │   ├── Left Column (75% width)
  │   │   └── Feature Cards Grid (3-4 per row, responsive)
  │   │       ├── 8 feature cards (Blog, Builder, Search, Analytics, Users, Newsletter, WP Import, Ads)
  │   │
  │   └── Right Column (25% width)
  │       └── Statistics Sidebar (sticky on desktop)
  │           ├── 6 metric cards (stacked)
  │           └── Refresh button
```

---

## 3. Page Sections

### 3.1 Hero Banner

**Purpose:** Visually distinguish the showcase page and communicate its purpose

**Design:**

- **Background:** Gradient (e.g., purple-500 to blue-600, or brand color gradient)
- **Height:** 200-250px
- **Content Layout:** Centered, vertical stack
    - **Main Heading:** "Platform Overview" (font-size: 2xl-3xl, font-bold, white)
    - **Subheading:** "Real-time snapshot of your content ecosystem and platform health" (font-size: lg, white/gray-100)
    - **Optional Icon:** Dashboard or newspaper icon (40-60px, top-right or left corner, opacity 0.2)

**Responsive:**

- Desktop: Full-width banner above feature cards
- Mobile: Slightly reduced height (150-180px), centered text

---

### 3.2 Feature Cards Section

**Grid Layout:**

- **Desktop:** 4 columns (4 cards per row, wrapping to 2 rows)
- **Tablet:** 3 columns (6 cards fit in 2 rows, 2 cards in row 3)
- **Mobile:** 1 column (8 cards stacked vertically)
- **Gap:** 24px (Tailwind `gap-6`)
- **Width:** 100% of container minus sidebar width on desktop

**Each Feature Card:**

```
┌────────────────────────────┐
│ [Icon] (64px, colored bg)  │
│                            │
│ Feature Title              │
│ (font-bold, lg)            │
│                            │
│ 2-3 line description       │
│ Brief feature explanation  │
│                            │
│ [Link/CTA Button]          │
│ e.g., "View Dashboard"     │
│                            │
│ [Badge if applicable]      │
│ e.g., "Active" or "New"    │
└────────────────────────────┘
```

**Card Styling:**

- Background: White (`bg-white`)
- Border: Light gray (`border border-gray-200`)
- Shadow: Subtle (`shadow-md`)
- Padding: 24px (`p-6`)
- Rounded: `rounded-lg`
- Hover: Lift effect (`hover:shadow-lg`, `hover:-translate-y-1`, transition)

**8 Feature Cards:**

| #   | Title              | Icon | Description                                                                          | Link               | Badge  |
| --- | ------------------ | ---- | ------------------------------------------------------------------------------------ | ------------------ | ------ |
| 1   | Blog Management    | 📰   | Create, edit, and organize articles with categories and tags                         | View Dashboard     | Active |
| 2   | Homepage Builder   | 🎨   | Drag-and-drop page builder with 8 section types (carousel, banner, newsletter, etc.) | Configure          | Active |
| 3   | Search & Discovery | 🔍   | Full-text search with category and tag filters for readers                           | View               | Active |
| 4   | Content Analytics  | 📊   | Track article views, engagement, and performance metrics                             | View Analytics     | Active |
| 5   | User Management    | 👥   | Manage registered users, roles, and permissions                                      | View Users         | Active |
| 6   | Newsletter System  | 📧   | Subscriber management and email campaign sending                                     | Manage Subscribers | Active |
| 7   | WordPress Import   | 📥   | Bulk import articles and images from WordPress XML exports                           | Import             | Active |
| 8   | Ad Management      | 📢   | Create, schedule, and track advertisement placements                                 | Manage Ads         | Active |

**CTA Links:**
Each card's link should navigate to the related admin section:

- Blog Management → `/admin/articles`
- Homepage Builder → `/admin/homepage-builder/homepage`
- Search & Discovery → `/blog/search` (public)
- Content Analytics → `/admin/analytics`
- User Management → `/admin/users`
- Newsletter System → `/admin/subscribers` (or existing subscriber route)
- WordPress Import → `/admin/wp-import`
- Ad Management → `/admin/ads`

---

### 3.3 Statistics Sidebar

**Position:** Right column, sticky on desktop (stays visible while scrolling feature cards)  
**Width:** ~300px on desktop, full-width below cards on mobile  
**Background:** White with subtle gray border (`border-l border-gray-200`)  
**Padding:** 24px (`p-6`)

**6 Metric Cards (Stacked Vertically):**

Each metric card shows:

```
┌─────────────────────┐
│ [Icon] Label        │
│ VALUE               │
│ [Trend] indicator   │
│ (e.g., +42 ↑)       │
└─────────────────────┘
```

**Metric Details:**

| Metric            | Value Source                                               | Trend Calculation                           | Icon | Format                       |
| ----------------- | ---------------------------------------------------------- | ------------------------------------------- | ---- | ---------------------------- |
| Articles          | `Article::where('status', 'published')->count()`           | Articles added this month vs last month     | 📰   | "2,847<br>+42 this month ✓"  |
| Users             | `User::where('role', '!=', 'admin')->count()` or all users | New registrations this month                | 👥   | "156<br>+8 this month ✓"     |
| Categories        | `Category::whereNull('parent_id')->count()`                | Categories added this month                 | 🏷️   | "18<br>+2 this month ✓"      |
| Subscribers       | `Subscriber::where('subscribed', true)->count()`           | New subscribers this month                  | 📧   | "1,234<br>+156 this month ✓" |
| Homepage Sections | `HomepageSection::where('status', true)->count()`          | Count of active builder sections            | 🎨   | "12 active"                  |
| Avg Engagement    | `Article::avg('views')` rounded                            | Average views per article (no trend needed) | 📊   | "1,847 views/post<br>+18% ↑" |

**Trend Indicator Styling:**

- Positive change: Green text, ✓ checkmark or ↑ arrow
- No change: Gray text, — dash
- Negative change: Orange/red text, ↓ arrow
- Example: "+42 this month ✓" or "+8% ↑" or "No change —"

**Refresh Button:**
At bottom of sidebar:

- Text: "Refresh Data" or "↻ Refresh"
- Action: Reload page or AJAX refresh metrics
- Styling: Secondary button (`bg-gray-100 hover:bg-gray-200`)

---

## 4. Data Flow

### 4.1 Controller Logic

**ShowcaseController.php:**

```php
public function index()
{
    $metrics = [
        'articles' => [
            'total' => Article::published()->count(),
            'this_month' => Article::published()->thisMonth()->count(),
            'last_month' => Article::published()->lastMonth()->count(),
        ],
        'users' => [
            'total' => User::count(),
            'this_month' => User::thisMonth()->count(),
            'last_month' => User::lastMonth()->count(),
        ],
        'categories' => [
            'total' => Category::whereNull('parent_id')->count(),
            'this_month' => Category::whereNull('parent_id')->thisMonth()->count(),
        ],
        'subscribers' => [
            'total' => Subscriber::subscribed()->count(),
            'this_month' => Subscriber::subscribed()->thisMonth()->count(),
        ],
        'homepage_sections' => HomepageSection::active()->count(),
        'avg_engagement' => Article::published()->avg('views'),
    ];

    return view('admin.showcase.index', compact('metrics'));
}
```

**Query Optimization:**

- Use database aggregation (COUNT, AVG) not PHP loops
- Cache metrics for 1 hour if needed (optional optimization)
- Eager-load relationships if needed (likely not for this page)

### 4.2 Trend Calculation (Blade Template)

In the view, calculate trend:

```blade
@php
$trend = $metrics['articles']['this_month'] - $metrics['articles']['last_month'];
$trend_text = $trend > 0 ? "+{$trend} ✓" : ($trend < 0 ? "{$trend}" : "No change");
$trend_color = $trend > 0 ? 'text-green-600' : ($trend < 0 ? 'text-red-600' : 'text-gray-500');
@endphp
```

---

## 5. Design System & Styling

**Framework:** Tailwind CSS (existing admin design system)

**Color Palette:**

- Primary: Blue-600 (links, CTAs)
- Accent: Purple-500 to Blue-600 (hero banner gradient)
- Cards: White (`bg-white`)
- Text: Gray-900 (headings), Gray-600 (body)
- Borders: Gray-200
- Success: Green-600 (positive trends)
- Caution: Orange-500 (negative trends)

**Typography:**

- Page Title: text-3xl, font-bold, text-gray-900
- Section Heading: text-xl, font-bold
- Card Title: text-lg, font-semibold
- Card Description: text-sm, text-gray-600
- Metrics: text-2xl (value), font-bold; text-sm (trend)

**Spacing:**

- Container: `container mx-auto px-4 py-8`
- Section gap: `gap-6` or `gap-8`
- Card padding: `p-6`
- Sidebar padding: `p-6`

**Responsive Breakpoints:**

- Mobile (< 768px): 1-column feature cards, full-width sidebar
- Tablet (768px-1024px): 2-3 column feature cards, sidebar remains right
- Desktop (> 1024px): 4-column feature cards, sticky sidebar right (25% width)

---

## 6. Access Control & Security

**Access Level:** Admin users only (or configurable high-privilege role)

**Authorization Check:**

```php
$this->authorize('viewShowcase', User::class); // or gate: can('view-showcase')
```

**No sensitive data exposed:** Only aggregate counts, no personal user data or payment info.

---

## 7. Performance Requirements

**Page Load:**

- Target: < 2 seconds on average connection
- Database queries: ≤ 10 queries (use eager-loading, aggregation)
- No N+1 queries
- Cache metrics if needed (Redis, 1-hour TTL)

**Metrics Update Strategy:**

- Load fresh on page view
- Option: Manual "Refresh" button for admins
- No auto-refresh (avoid constant database hits)

---

## 8. Responsive Design

**Desktop (1024px+):**

- Hero banner full-width (250px height)
- 2-column layout: Feature cards (75%) + Sidebar (25%)
- Feature cards: 4 per row
- Sidebar: Sticky position
- All elements visible without scrolling (if content fits)

**Tablet (768px-1024px):**

- Hero banner full-width (200px height)
- Feature cards: 3 per row
- Sidebar: Remains right but not sticky (mobile space)
- Sidebar width: Adjusted to fit

**Mobile (< 768px):**

- Hero banner full-width (150px height, smaller text)
- Single-column layout: Feature cards stack vertically
- Sidebar moves below all feature cards (full-width)
- Cards: Full width with padding
- Touch-friendly: Larger tap targets for buttons

---

## 9. Future Enhancements

- [ ] Export dashboard as PDF report
- [ ] Customize which metrics display
- [ ] Chart library integration (Chart.js) for monthly trend graphs
- [ ] Real-time metrics updates via WebSocket
- [ ] Filter by date range (this month, quarter, year)
- [ ] Email dashboard snapshot to stakeholders
- [ ] Role-based metric visibility (e.g., hide ad revenue from some users)

---

## 10. Files to Create/Modify

**New Files:**

- `app/Http/Controllers/Admin/ShowcaseController.php`
- `resources/views/admin/showcase/index.blade.php`

**Modified Files:**

- `routes/web.php` (add showcase route)
- `resources/views/components/admin-layout.blade.php` (add sidebar menu link)

---

## 11. Success Criteria (Testing Checklist)

✅ Page loads at `/admin/showcase` with proper auth  
✅ All 6 metrics display with correct data  
✅ Trends calculate correctly (this month vs last month)  
✅ Feature cards show all 8 features with links to admin sections  
✅ Hero banner displays with proper gradient and text  
✅ Responsive on mobile (single column, sidebar below)  
✅ Responsive on tablet (3-column cards)  
✅ Responsive on desktop (4-column cards, sticky sidebar)  
✅ Sidebar stats update on page refresh  
✅ No database query errors or N+1 issues  
✅ Page loads in < 2 seconds  
✅ Accessible only to admin users

---

**Status:** Design Approved & Ready for Implementation Plan
