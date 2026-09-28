# 🧪 Manual Testing Guide - Retnews Frontend Integration

**Status**: ✅ Database ready (124 articles, 18 keywords, 3 users)
**Dev Server**: Running on http://localhost:8000
**Test Credentials**:
- Admin: `admin@newsmedia.test` / `password`
- Editor: `editor@newsmedia.test` / `password`  
- Writer: `writer@newsmedia.test` / `password`

---

## Phase 1: Homepage (Retnews Design)

### Section 1: Navigation & Header
**What to check:**
- [ ] Top navigation bar visible at top
- [ ] Logo/site name visible
- [ ] Main menu items visible (Home, Blog, Categories, etc.)
- [ ] Responsive on mobile view (F12 → mobile device mode)

**If issues:**
- Check: `resources/views/layouts/app.blade.php`
- Check: `resources/views/partials/topbar.blade.php`

---

### Section 2: Breaking News Strip
**What to check:**
- [ ] Ticker section visible below header
- [ ] Shows latest/breaking news
- [ ] Scrolls or updates (if implemented)

**If issues:**
- Check: `resources/views/partials/breaking-strip.blade.php`

---

### Section 3: Hero Carousel (Featured Articles)
**What to check:**
- [ ] Large carousel showing 5 featured articles
- [ ] Each slide shows: article image, title, author, date
- [ ] Can click article → goes to article detail page
- [ ] Navigation arrows work (click left/right)
- [ ] Images load correctly
- [ ] Text readable over images

**Expected data**: 86 published articles total (top 5 by date in carousel)

**If issues:**
- [ ] Missing images? Check article `featured_image` field
- [ ] Carousel not working? Check Swiper library is loaded (resources/views/layouts/app.blade.php)
- [ ] Articles not showing? Check: `HomeController::index()` returns `$heroSlides` variable
- Check: `resources/views/partials/hero.blade.php`

---

### Section 4: Category Strip (Showcase)
**What to check:**
- [ ] Horizontal carousel showing latest articles from different categories
- [ ] 8 articles total in strip
- [ ] Each shows: image, category label, title, author, date
- [ ] Clickable (article link works)
- [ ] Carousel navigation works

**Expected data**: Latest 8 articles across all categories

**If issues:**
- Check: `resources/views/partials/category-strip.blade.php`
- Verify: `HomeController` has `$categoryStrip` variable

---

### Section 5: Recent & Popular (Dual Column)
**What to check:**

**Left Column (Recent):**
- [ ] Shows 4 most recent articles
- [ ] Displays as cards with image + title + author + date
- [ ] Clickable to article detail

**Right Column (Popular):**
- [ ] Shows 5 most viewed articles
- [ ] Displays as cards with image + title + author + date
- [ ] Clickable to article detail

**Expected data**: 
- Recent: Latest 4 published articles
- Popular: Top 5 by views_count (may be low initially)

**If issues:**
- Check: `resources/views/partials/recent-and-popular.blade.php`
- Verify: HomeController has `$recentFeatured` and `$popularPosts` variables

---

### Section 6: Sports Section
**What to check:**
- [ ] Carousel showing 4 sports-related articles
- [ ] Only shows articles from "Sports" category
- [ ] Images + titles visible
- [ ] Can click to article detail

**Expected data**: 20 sports articles seeded (carousel shows latest 4)

**If issues:**
- Check: `resources/views/partials/sports.blade.php`
- Verify: `HomeController` correctly queries `whereHas('category', fn($q) => $q->where('slug', 'sports'))`

---

### Section 7: Lifestyle Section
**What to check:**
- [ ] Carousel showing 4 lifestyle/entertainment articles
- [ ] Only shows articles from "Entertainment" category
- [ ] Images + titles visible
- [ ] Can click to article detail

**Expected data**: 18 entertainment articles seeded

**If issues:**
- Check: `resources/views/partials/lifestyle.blade.php`
- Verify: Category slug is 'entertainment' (not 'lifestyle')

---

### Section 8: Technology Section
**What to check:**
- [ ] Carousel showing 4 tech articles
- [ ] Only shows articles from "Technology" category
- [ ] Images + titles visible
- [ ] Can click to article detail

**Expected data**: 15 technology articles seeded

**If issues:**
- Check: `resources/views/partials/technology.blade.php`

---

### Section 9: Sidebar
**What to check:**

**Latest Articles Box:**
- [ ] Shows 5 most recent articles
- [ ] Article list with small images + titles
- [ ] Clickable to detail

**Popular Categories:**
- [ ] Shows all 7 categories
- [ ] Each shows article count (e.g., "Politics (21 articles)")
- [ ] Clickable to category page

**Popular Tags:**
- [ ] Shows top 10 tags
- [ ] Each shows usage count
- [ ] Clickable to tag filter page

**Newsletter Signup:**
- [ ] Email input field visible
- [ ] Subscribe button
- [ ] Can submit email

**If issues:**
- Check: `resources/views/partials/sidebar.blade.php`

---

### Section 10: Pagination
**What to check:**
- [ ] At bottom of page
- [ ] Shows page numbers
- [ ] "Previous" and "Next" buttons visible
- [ ] Can click to next page (shows next 12 articles)
- [ ] Shows correct article count per page

**Expected**: 12 articles per page × ~10 pages total

**If issues:**
- Check: `resources/views/partials/pagination.blade.php`
- Verify: `HomeController` calls `Article::published()->paginate(12)`

---

### Section 11: Footer
**What to check:**
- [ ] Footer visible at bottom
- [ ] Contains site info, links, copyright
- [ ] Social media icons (if configured)
- [ ] Links work

**If issues:**
- Check: `resources/views/partials/footer.blade.php`

---

## Phase 2: Article Detail Pages

### Test: Click any article → view detail page

**What to check:**
- [ ] Article URL format: `/blog/{slug}` (e.g., `/blog/artikel-terbaru-politik`)
- [ ] Article title visible and matches homepage
- [ ] Featured image displays large at top
- [ ] Full article content displays (HTML formatted)
- [ ] Author name + date visible
- [ ] "Read time" estimate shown (e.g., "5 min read")
- [ ] Category tag clickable
- [ ] Tags displayed and clickable

**Article Metadata (SEO section):**
- [ ] Meta title/description in browser title & meta tags
- [ ] Keywords section (if keywords assigned to article)
- [ ] FAQ section (if FAQs exist for article)

**Related Content:**
- [ ] Related articles from same category shown (if implemented)
- [ ] Previous/Next article links (if implemented)

**Sidebar on article page:**
- [ ] Latest articles list
- [ ] Categories + tags
- [ ] Newsletter signup

**If issues:**
- Check: `resources/views/blog/show.blade.php` or `resources/views/pages/article-detail.blade.php`
- Check: Article model has relationships `meta()`, `keywords()`, `faqs()`
- Verify: `Frontend\ArticleController::show()` loads these relationships with `->with('meta', 'keywords', 'faqs')`

---

## Phase 3: Blog Listing

### Test: Visit `/blog`

**What to check:**
- [ ] Lists all published articles
- [ ] 12 articles per page
- [ ] Article cards show: image, title, excerpt, author, date
- [ ] Can click card to view article
- [ ] Pagination works (next/previous)

**Filters:**
- [ ] Category filter: `/blog/kategori/{category-slug}` works
- [ ] Tag filter: `/blog/tag/{tag-slug}` works
- [ ] Search: `/blog/search?q=keyword` works (if implemented)

**If issues:**
- Check: `resources/views/blog/index.blade.php`
- Check: `Frontend\ArticleController::index()`

---

## Phase 4: Admin Panel

### Access: http://localhost:8000/admin
**Login**: `admin@newsmedia.test` / `password`

### Admin Dashboard
**What to check:**
- [ ] Dashboard loads without errors
- [ ] Shows stats: total articles, keywords, users, views
- [ ] Charts or graphs (if implemented)
- [ ] Quick action buttons

**If issues:**
- Check: `resources/views/admin/dashboard/index.blade.php`
- Check: `AdminController` or `DashboardController`

---

### Admin: Articles Management

**List View** (`/admin/articles`):
- [ ] Shows all articles in table
- [ ] Columns: Title, Author, Category, Status, Date, Actions
- [ ] Can edit each article
- [ ] Can delete article
- [ ] Status filter (Draft, Published, etc.)
- [ ] Search by title

**Create Article** (`/admin/articles/create`):
- [ ] Form fields present:
  - [ ] Title (required)
  - [ ] Excerpt
  - [ ] Content (rich editor, if present)
  - [ ] Featured image (upload)
  - [ ] Category (dropdown)
  - [ ] Tags (multi-select, if implemented)
  - [ ] Status (Draft/Published/Scheduled/Archived)
  - [ ] Published date/time
  - [ ] **Scheduled date** (for scheduling)
  - [ ] Meta title/description (SEO section)
  - [ ] Focus keyword
  - [ ] Keywords (multi-select)
  - [ ] FAQs (add multiple Q&A)
  - [ ] Is Featured checkbox

- [ ] Can upload featured image
- [ ] Can select multiple keywords
- [ ] Can add/remove FAQs
- [ ] Form validation works (try submitting empty title)
- [ ] Save as Draft works
- [ ] Publish works (published_at set to now)
- [ ] Schedule for future works (saves scheduled_at)

**Edit Article** (`/admin/articles/{id}/edit`):
- [ ] All fields pre-populated
- [ ] Can modify any field
- [ ] Save updates
- [ ] See article meta (seo_score if calculated)
- [ ] See linked keywords
- [ ] See FAQs

**After Creating/Editing Article:**
- [ ] Article appears on homepage (if published)
- [ ] Article detail page accessible at `/blog/{slug}`
- [ ] Keywords reflected on article page
- [ ] FAQs visible on article page

**If issues:**
- Check: `resources/views/admin/articles/create.blade.php` and `edit.blade.php`
- Check: `Admin\ArticleController::store()` and `update()`
- Check: Keywords saved via `$article->keywords()->sync($request->keywords)`
- Check: FAQs saved via ArticleFaq model

---

### Admin: Keywords Management

**List View** (`/admin/keywords`):
- [ ] Shows all keywords in table
- [ ] Columns: Keyword, Category, Intent, Status, Actions
- [ ] Status shows: Pending, Processing, Done, Failed
- [ ] Can edit keyword
- [ ] Can delete keyword
- [ ] Filter by status (pending, processing, done, failed)

**Create Keyword** (`/admin/keywords/create`):
- [ ] Form fields:
  - [ ] Keyword (required)
  - [ ] Category (dropdown)
  - [ ] Description (optional)
  - [ ] Intent (Informational/Navigational/Transactional/Commercial)
  - [ ] Tone (Professional/Casual/etc.)
  - [ ] Target word count
  - [ ] Use humanizer (checkbox)

- [ ] Can save keyword
- [ ] Status defaults to "Pending"

**Generate Article from Keyword:**
- [ ] Find keyword in list
- [ ] Click "Generate" button (if present and ANTHROPIC_API_KEY configured)
- [ ] Shows generating... status
- [ ] Creates draft article after ~30 seconds
- [ ] New article appears in Articles list
- [ ] Article has: title, content, meta, FAQs, keywords linked
- [ ] Keyword status changes to "Done"

**If no API key configured:**
- [ ] Button may be disabled or show error message
- [ ] This is expected (can test later with API key)

**If issues:**
- Check: `.env` has `ANTHROPIC_API_KEY=sk_ant_...` (if testing generation)
- Check: `resources/views/admin/keywords/index.blade.php`
- Check: `Admin\KeywordController::generate()` calls `ArticleGeneratorService`

---

### Admin: Analytics Dashboard

**Access**: `/admin/analytics`

**What to check:**
- [ ] Shows top articles by views
- [ ] Shows recent analytics data
- [ ] Can click article → view detailed analytics

**Article Analytics** (`/admin/analytics/{article}`):
- [ ] Displays metrics:
  - [ ] Total views
  - [ ] Unique visitors
  - [ ] Average time on page
  - [ ] Scroll depth percentage
  - [ ] Date range (daily breakdown)

- [ ] Shows SEO score (0-100)
- [ ] Shows SEO checklist:
  - [ ] Title length (40-60 chars)
  - [ ] Meta description (120-160 chars)
  - [ ] Content length (300+ words)
  - [ ] Headings (H2/H3 present)
  - [ ] Image with alt text
  - [ ] Keyword in content
  - [ ] URL (slug format)
  - [ ] Internal links

- [ ] Shows SEO recommendations (actionable items to improve score)

**If issues:**
- Check: `resources/views/admin/analytics/index.blade.php`
- Check: `Admin\ArticleAnalyticsController::show()`
- Check: `SEOService` calculates scores and recommendations

---

### Admin: Settings

**Access**: `/admin/settings` (or similar)

**What to check:**
- [ ] Can view site settings in sections:
  - **General**: Site name, description, contact email
  - **SEO**: Default meta tags, focus keywords
  - **Social**: Facebook, Twitter, Instagram URLs
  - **AI**: Anthropic API key (if present)
  - **Homepage**: Default sections visibility

- [ ] Can edit each setting
- [ ] Can upload logo/favicon (if implemented)
- [ ] **IMPORTANT: Anthropic API key field** (if testing AI)
  - [ ] Input field for ANTHROPIC_API_KEY
  - [ ] Save persists key to `.env` or database
  - [ ] Can enable AI generation after key saved

**If issues:**
- Check: `resources/views/admin/settings/index.blade.php`
- Check: `Admin\SettingController`
- Settings stored in `settings` table or `.env` (verify which)

---

### Admin: Homepage Settings

**Access**: `/admin/home-page-settings` (or `/admin/settings`)

**What to check:**
- [ ] List of homepage sections:
  - Hero Carousel
  - Category Strip
  - Recent & Popular
  - Sports Section
  - Lifestyle Section
  - Technology Section
  - Sidebar
  - Pagination

- [ ] Each section has:
  - [ ] Toggle ON/OFF (checkbox)
  - [ ] Order (number field for reordering)
  - [ ] Items count (how many articles to show)

- [ ] Can enable/disable sections
- [ ] Can change order (reorder sections on homepage)
- [ ] Changes persist
- [ ] Homepage reflects changes after save (sections show/hide, reorder)

**If issues:**
- Check: `resources/views/admin/home-page-settings/index.blade.php`
- Check: `Admin\HomePageSettingController::update()`
- Check: `HomeController` uses `HomePageSetting::where('is_enabled', true)->ordered()` to render sections

---

## Phase 5: User Management & Permissions

### Admin: Users

**Access**: `/admin/users` or `/admin/staff`

**What to check:**
- [ ] List all users
- [ ] Can edit user (change name, email, role)
- [ ] Can delete user
- [ ] Assign roles: Admin, Editor, Writer

**Roles & Permissions:**
- [ ] **Admin**: Can access everything (articles, keywords, analytics, settings, users)
- [ ] **Editor**: Can manage articles, categories, comments; view analytics
- [ ] **Writer**: Can create/edit own articles; view dashboard

**Test Permission Restrictions:**
1. Log in as **Writer**
   - [ ] Can access dashboard
   - [ ] Can create article
   - [ ] Can edit own article only (not others')
   - [ ] Cannot access keywords
   - [ ] Cannot access analytics
   - [ ] Cannot access settings
   - [ ] Cannot access users

2. Log in as **Editor**
   - [ ] Can create/edit any article
   - [ ] Can manage categories & tags
   - [ ] Can view analytics
   - [ ] Cannot manage keywords
   - [ ] Cannot manage users
   - [ ] Cannot manage settings

3. Log in as **Admin**
   - [ ] Full access to everything

**If permission issues:**
- Check: `app/Policies/ArticlePolicy.php` (if using policies)
- Check: Spatie Permission middleware in routes (`middleware('permission:...')`)
- Run: `php artisan tinker` → `Auth::user()->can('create', \App\Models\Article::class)`

---

## Phase 6: Frontend Features

### Newsletter Signup
**Where**: Sidebar on homepage and article pages

**What to check:**
- [ ] Email input field visible
- [ ] Subscribe button clickable
- [ ] Can enter email
- [ ] Form validation (invalid email shows error)
- [ ] After submit, confirmation message
- [ ] Email saved to `subscribers` table

**If issues:**
- Check: `resources/views/components/newsletter-form.blade.php`
- Check: Newsletter controller (route `/subscribe` or similar)

---

### Affiliate Links
**Where**: Admin → Ads/Affiliates section (if implemented)

**What to check:**
- [ ] Can create affiliate link with destination URL
- [ ] Can view link: `/go/{slug}`
- [ ] Click on affiliate link → redirects to destination
- [ ] Click tracked in database

**If issues:**
- Check: `Admin\AffiliateLinkController`
- Check: `Frontend\AffiliateController` or redirect logic

---

### Ads
**Where**: Homepage (header, sidebar, footer) or in-article

**What to check:**
- [ ] Ad banners/scripts display
- [ ] Can manage ads in admin
- [ ] Can set start/end dates
- [ ] Can set placement (header, sidebar, in_article, footer)

**If issues:**
- Check: `Admin\AdController`
- Check: blade includes for ad placements

---

## Phase 7: Edge Cases & Responsiveness

### Mobile Responsiveness
- [ ] View homepage on mobile (F12 → toggle device toolbar)
- [ ] All sections stack vertically
- [ ] Navigation collapses into hamburger menu
- [ ] Images scale appropriately
- [ ] Text readable (not too small)
- [ ] Carousels work on touch

### Tablet View
- [ ] 2-column layout if applicable
- [ ] Sidebar wraps or stacks

### Dark Mode (if implemented)
- [ ] Toggle dark mode
- [ ] All text readable in dark mode
- [ ] Images visible

### Slow Network (if implemented)
- [ ] Lazy load images
- [ ] Pagination loads next page properly
- [ ] No console errors

---

## Phase 8: Database & Backend Verification

### Using Tinker
```bash
php artisan tinker
```

**Verify Article-Keyword Relationship:**
```php
$article = \App\Models\Article::with('keywords')->first();
echo $article->keywords->pluck('keyword'); // Should show array of keywords
```

**Verify Article Metadata:**
```php
$article = \App\Models\Article::with('meta')->first();
echo $article->meta->meta_title; // Should show SEO title
```

**Verify Article FAQs:**
```php
$article = \App\Models\Article::with('faqs')->first();
echo $article->faqs->count(); // Number of FAQs
```

**Verify Article Analytics:**
```php
$analytics = \App\Models\ArticleAnalytic::first();
echo $analytics; // Should show views, unique_visitors, etc.
```

**Check View Tracking:**
```php
$views = \App\Models\ArticleView::count();
echo "Total views tracked: $views";
```

---

## Common Issues & Solutions

| Issue | Solution |
|-------|----------|
| Homepage blank/errors | Run `php artisan migrate` & `php artisan db:seed` |
| Articles not showing | Check: `Article::published()->count()` in tinker |
| Keywords not generating | Check: .env has `ANTHROPIC_API_KEY` |
| Admin not accessible | Check: Login with `admin@newsmedia.test` / `password` |
| Images not loading | Check: featured_image path (should be relative or full URL) |
| Carousels not working | Check: Swiper.js library loaded in layout |
| Pagination missing | Check: Article model returns paginated results |
| Categories not filtering | Check: Category slugs match in URL |
| Permission denied errors | Check: User has correct role assigned |
| Analytics empty | Check: ArticleAnalytic records exist; views_count > 0 |
| SEO score 0 | Check: Article has proper title, content, meta fields |

---

## Testing Checklist Summary

- [ ] **Homepage**: All 8 sections load, images show, carousels work
- [ ] **Article Detail**: Full content displays, metadata shows, FAQs visible
- [ ] **Blog Listing**: Pagination works, filtering works
- [ ] **Admin Articles**: CRUD works, keywords/FAQs saved
- [ ] **Admin Keywords**: Create keyword, (optionally) generate article
- [ ] **Admin Analytics**: View stats and SEO scores
- [ ] **Admin Settings**: Can save settings, Anthropic key input visible
- [ ] **Homepage Settings**: Can toggle sections and reorder
- [ ] **Permissions**: Writer/Editor/Admin roles work correctly
- [ ] **Responsive**: Works on mobile/tablet/desktop
- [ ] **Database**: Articles, keywords, analytics, views tracked

---

## Next: Detailed Bug Reports

For any section that **fails**, provide:
1. **Expected**: What should happen
2. **Actual**: What actually happened
3. **Steps**: How to reproduce
4. **Error**: Any console errors (F12 → Console tab)
5. **File**: Which blade/controller file might be involved

---

**Ready to test! Open browser and go to: http://localhost:8000** 🚀
