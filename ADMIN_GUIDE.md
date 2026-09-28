# 📘 RET NEWS - Admin User Guide

**Version:** 1.0  
**Last Updated:** July 15, 2026

---

## Table of Contents

1. [Getting Started](#getting-started)
2. [Dashboard Overview](#dashboard-overview)
3. [Article Management](#article-management)
4. [Category Management](#category-management)
5. [Tag Management](#tag-management)
6. [Import/Export Articles](#importexport-articles)
7. [User Management](#user-management)
8. [FAQs & Troubleshooting](#faqs--troubleshooting)

---

## Getting Started

### Login to Admin Panel

1. Navigate to: `https://retnews.com/login`
2. Enter your email and password
3. Click "Sign In"
4. You'll be redirected to the dashboard

**Lost Password?**
- Click "Forgot Password" on login page
- Enter your email
- Check email for reset link
- Create new password

---

## Dashboard Overview

The admin dashboard provides quick access to all management features.

### Key Sections

| Section | Description |
|---------|-------------|
| **Statistics** | View article count, user statistics, etc |
| **Quick Actions** | Create article, manage categories, export data |
| **Recent Activity** | View recently created/edited articles |
| **Navigation Menu** | Access all admin functions (sidebar) |

### Sidebar Menu

```
Dashboard
├── Categories
├── Tags
├── Articles
├── Users
├── Ads
├── Affiliates
├── Keywords
├── Analytics
├── Import/Export
└── Settings
```

---

## Article Management

### Create New Article

**Steps:**
1. Go to **Articles** → **Create Article**
2. Fill in the form:
   - **Title:** Article headline (required)
   - **Slug:** URL-friendly name (auto-generated, can edit)
   - **Content:** Article body (supports HTML)
   - **Excerpt:** Short summary (optional)
   - **Category:** Select from dropdown
   - **Featured Image:** Upload article header image
   - **Meta Title:** SEO title (60-70 chars recommended)
   - **Meta Description:** SEO description (155-160 chars)
   - **Status:** Published / Draft / Scheduled
   - **Publish Date:** When to publish

3. Click **"Save"** or **"Publish"**

**Tips:**
- Use clear, descriptive titles for better SEO
- Add relevant featured image (1200x630px recommended)
- Fill SEO fields to improve search ranking
- Use Draft status to work on articles before publishing

### Edit Article

**Steps:**
1. Go to **Articles**
2. Find article in table
3. Click **"Edit"** button
4. Make changes
5. Click **"Update"**

### Delete Article

**Steps:**
1. Go to **Articles**
2. Find article
3. Click **"Delete"** button
4. Confirm deletion

**Note:** Deleted articles can be restored from trash (soft delete)

### Article Status

| Status | Visibility | Description |
|--------|-----------|-------------|
| **Published** | ✅ Public | Visible on website |
| **Draft** | ❌ Private | Only visible to admin |
| **Scheduled** | ⏰ Pending | Published at scheduled date |

### Bulk Actions

**Coming Soon:** Bulk edit, bulk delete, bulk status change

---

## Category Management

### Create Category

**Steps:**
1. Go to **Categories** → **Create**
2. Enter **Name** (e.g., "Technology", "Sports")
3. Slug auto-generates (can edit)
4. Add description (optional)
5. Click **"Save"**

### Edit Category

1. Go to **Categories**
2. Click **"Edit"** on category
3. Update fields
4. Click **"Update"**

### Delete Category

1. Go to **Categories**
2. Click **"Delete"**
3. Note: Articles in this category will become uncategorized

### Popular Categories

- News
- Technology
- Sports
- Entertainment/Lifestyle
- Business

---

## Tag Management

### Create Tag

**Steps:**
1. Go to **Tags** → **Create**
2. Enter **Name** (e.g., "AI", "Climate Change")
3. Click **"Save"**

### Add Tags to Articles

When creating/editing articles, search and select tags from the tag field.

### Best Practices

- Keep tags simple (1-2 words)
- Reuse existing tags when possible
- Avoid creating too many similar tags
- Use tags for searchability and categorization

---

## Import/Export Articles

### Export Articles

Export all articles to CSV format.

**Steps:**
1. Go to **Import/Export**
2. Click **"Export CSV"**
3. File downloads automatically
4. Open in Excel or spreadsheet app

**Exported columns:**
- ID, Title, Slug, Content, Excerpt
- Category, Author, Featured Image
- Published At, Status

### Import Articles

**Steps:**
1. Go to **Import/Export**
2. Click **"Choose File"** and select CSV
3. Check **"Skip Duplicates"** if needed
4. Click **"Import CSV"**
5. Review results

**CSV Format:**
```
Title,Slug,Content,Excerpt,Category,Author,Featured Image,Published At,Status
My Article,my-article,Article content here,Short summary,News,Admin User,https://example.com/image.jpg,2026-07-15 10:00:00,published
```

**Rules:**
- **Title, Slug, Content** are required
- **Slug** must be unique and lowercase with hyphens only
- **Featured Image** must be valid URL
- **Published At** format: `YYYY-MM-DD HH:mm:ss`
- **Status** must be: published / draft / scheduled

**Download Template:**
- Click **"Download Template"** to see example CSV format

### Common Import Errors

| Error | Solution |
|-------|----------|
| "Slug hanya boleh berisi huruf, angka, dan tanda hubung" | Use lowercase, no spaces, only alphanumeric + hyphen |
| "Featured Image URL tidak valid" | Ensure URL starts with `http://` or `https://` |
| "Format Published At harus YYYY-MM-DD HH:mm:ss" | Use exact format with date and time |
| "Kolom yang diperlukan tidak ditemukan" | Ensure CSV has Title, Slug, Content columns |

---

## User Management

### View Users

**Go to:** **Users**

See list of all admin users and their roles.

### Create User

**Steps:**
1. **Users** → **Create User**
2. Fill in:
   - **Name:** Full name
   - **Email:** Unique email
   - **Password:** Strong password (min 8 chars)
   - **Role:** Admin / Editor / Viewer
3. Click **"Create"**

### Edit User

1. Go to **Users**
2. Click **"Edit"** on user
3. Update name, email, role
4. Click **"Update"**

### Delete User

1. Go to **Users**
2. Click **"Delete"**
3. Articles by deleted user remain (assigned to admin)

### User Roles

| Role | Permissions |
|------|------------|
| **Admin** | Full access (create, edit, delete) |
| **Editor** | Create & edit articles, view all |
| **Viewer** | Read-only access |

---

## FAQs & Troubleshooting

### Q: How do I recover a deleted article?

**A:** Articles use soft delete (not permanently removed).

1. Go to **Articles**
2. Look for "View Trash" or similar option
3. Find deleted article
4. Click **"Restore"**

### Q: My article isn't showing on website

**Possible causes:**
- [ ] Status is "Draft" (not Published)
- [ ] Published date is in the future
- [ ] Category is hidden
- [ ] Cache needs clearing

**Solution:**
1. Edit article
2. Set Status to "Published"
3. Set Published Date to current date
4. Click Update

### Q: How do I add images to article content?

**In the WYSIWYG editor:**
1. Click **"Insert Image"** button
2. Upload or paste image URL
3. Click **"Insert"**

**Note:** Images should be optimized (< 500KB)

### Q: Can I schedule an article?

**Yes!**
1. Create/Edit article
2. Set Status to "Scheduled"
3. Set "Publish Date" to future date
4. Click **"Publish"**

The article will publish automatically on that date.

### Q: How do I bulk edit articles?

**Coming Soon!** Feature in development

### Q: What are the SEO fields for?

- **Meta Title:** Shows in Google search results (60-70 chars)
- **Meta Description:** Summary in search results (155-160 chars)

**Impact:** Better SEO = higher search ranking

### Q: Can I import from WordPress?

**Yes!** Export from WordPress as CSV and import here.

Steps:
1. In WordPress: **Tools** → **Export** → **Select "Posts"**
2. Download XML file
3. Convert XML to CSV (use online tool)
4. Import to RET NEWS

### Q: How do I change admin password?

1. Click profile icon (top right)
2. Go to **"Settings"** or **"Profile"**
3. Click **"Change Password"**
4. Enter current password
5. Enter new password (twice)
6. Click **"Update"**

### Q: The admin panel is slow

**Solutions:**
1. Clear browser cache (Ctrl+Shift+Delete)
2. Clear application cache:
   ```bash
   php artisan cache:clear
   ```
3. Check internet connection
4. Try different browser

### Q: I forgot my password

1. Click **"Forgot Password"** on login page
2. Enter email address
3. Check email for reset link
4. Click link and create new password
5. Login with new password

**Note:** Password reset email expires after 1 hour

### Q: Can I export data?

**Yes!** Go to **Import/Export** → **Export CSV**

You can download all articles, then re-import to another instance.

---

## Support

**Need Help?**

- Check this guide
- Contact support: support@retnews.com
- Email admin: admin@retnews.com

---

**Last Updated:** July 15, 2026  
**Version:** 1.0
