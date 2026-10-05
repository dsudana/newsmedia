# Comment Management Feature Documentation

## Overview

Complete comment management system for article discussions with admin moderation and public submission.

---

## Features

### Admin Features
✅ **View all comments** — Filter by status (pending/approved/rejected)
✅ **Approve/Reject comments** — Moderate before publication
✅ **Delete comments** — Remove inappropriate content
✅ **Bulk actions** — Approve, reject, or delete multiple at once
✅ **Comment details** — View full comment with article context
✅ **Search & filter** — By status, article, date

### Frontend Features
✅ **Public comment form** — Visitor comments with name/email
✅ **Display approved comments** — Show moderated comments on article
✅ **Anti-spam protection** — Honeypot field + validation
✅ **Comment count** — Show on article pages

---

## Files Created

### Controllers
- **`app/Http/Controllers/CommentController.php`** — Admin comment CRUD & moderation
- **`app/Http/Controllers/PublicCommentController.php`** — Public comment submission

### Models
- **`app/Models/Comment.php`** — Enhanced with scopes (approved, pending, rejected)
- **`app/Models/Article.php`** — Added `approvedComments()` relationship

### Views
- **`resources/views/admin/comments/index.blade.php`** — Admin comment list with filters
- **`resources/views/admin/comments/show.blade.php`** — Individual comment details
- **`resources/views/frontend/partials/comments.blade.php`** — Comment form & display for articles

### Routes
- **Admin routes**: `/admin/comments` (CRUD, approve, reject, bulk actions)
- **Public route**: `/articles/{article}/comments` (POST — submit comment)

### Sidebar
- Added **Comments** menu to admin sidebar (under Content section)

---

## Usage

### Admin Panel

#### View All Comments
```
GET /admin/comments
```
- Filter by status: Pending, Approved, Rejected, All
- Shows comment count per status
- Quick approve/reject buttons per comment
- Bulk actions with checkboxes

#### View Comment Details
```
GET /admin/comments/{id}
```
- Full comment content
- Author name & email
- Article linked
- Approve/Reject/Delete actions in sidebar

#### Approve Comment
```
POST /admin/comments/{id}/approve
```
- Changes status to "approved"
- Comment becomes visible on frontend

#### Reject Comment
```
POST /admin/comments/{id}/reject
```
- Changes status to "rejected"
- Comment remains in system but not displayed

#### Delete Comment
```
DELETE /admin/comments/{id}
```
- Permanently removes comment
- Cannot be recovered

### Bulk Actions
```
POST /admin/comments/bulk-approve
POST /admin/comments/bulk-reject
POST /admin/comments/bulk-delete
```
- Select multiple comments via checkboxes
- Apply action to all selected at once

### Frontend - Public Comments

#### Submit Comment
```
POST /articles/{article}/comments
OR
POST /blog/{article}/comments
```

**Request:**
```json
{
  "name": "John Doe",
  "email": "john@example.com",
  "content": "Great article! Very informative.",
  "honeypot": ""  // Anti-spam: must be empty
}
```

**Validation:**
- `name` — required, max 100 chars
- `email` — required, valid email
- `content` — required, 5-2000 chars

**Response:**
- Success: Redirect with message "Komentar Anda akan ditampilkan setelah disetujui oleh admin"
- Validation error: Back with error messages

---

## Integration with Article Pages

### Display Comments on Article Show Page

In `resources/views/frontend/articles/show.blade.php`, add:

```blade
@php
    $approvedComments = $article->approvedComments()->get();
@endphp

@include('frontend.partials.comments', ['article' => $article, 'approvedComments' => $approvedComments])
```

Or in the controller:

```php
public function show(Article $article)
{
    $article->load('approvedComments');
    return view('frontend.articles.show', compact('article'));
}
```

### Display Comment Count

```blade
<span class="text-sm text-gray-600">
    <i class="fas fa-comments mr-1"></i>
    {{ $article->approvedComments()->count() }} comments
</span>
```

---

## Database Schema

### comments table (already exists)

```
id            — Primary key
article_id    — FK to articles
user_id       — FK to users (nullable for anonymous)
name          — Comment author name
email         — Comment author email
content       — Comment text (max 2000 chars)
status        — ENUM: 'pending' | 'approved' | 'rejected'
created_at    — Timestamp
updated_at    — Timestamp

Indexes:
- article_id
- status
- created_at
```

---

## Permissions (Spatie)

### Suggested Permissions

```php
// In RoleSeeder or create separately:
Permission::create(['name' => 'moderate_comments']);
Permission::create(['name' => 'view_comments']);
Permission::create(['name' => 'delete_comments']);

// Assign to Admin role:
$admin->givePermissionTo(['moderate_comments', 'view_comments', 'delete_comments']);

// Optionally: Editors can moderate comments on their articles only
```

### Example - Protect Routes with Authorization

```php
// In CommentController
public function __construct()
{
    $this->middleware('permission:view_comments')->only('index', 'show');
    $this->middleware('permission:moderate_comments')->only('approve', 'reject');
    $this->middleware('permission:delete_comments')->only('destroy');
}
```

---

## Anti-Spam Features

### 1. Honeypot Field
```blade
<!-- Hidden field that should remain empty -->
<input type="text" name="honeypot" value="" class="hidden">
```

If filled, comment is silently accepted but not stored (bot trap).

### 2. Email Validation
- Must be valid email format
- Backend validates before saving

### 3. Content Validation
- Minimum 5 characters
- Maximum 2000 characters
- Prevents empty or spam-like submissions

### 4. Rate Limiting (Optional Enhancement)
```php
// In routes:
Route::post('/articles/{article}/comments', [PublicCommentController::class, 'store'])
    ->middleware('throttle:10,60');  // 10 comments per minute per IP
```

---

## Email Notifications (Optional Enhancement)

### Add Admin Notification

In `PublicCommentController@store`:

```php
use App\Notifications\NewCommentPendingNotification;

$comment = $article->comments()->create([...]);
$admins = User::role('admin')->get();
Notification::send($admins, new NewCommentPendingNotification($comment));
```

### Create Notification Class

```php
// app/Notifications/NewCommentPendingNotification.php
class NewCommentPendingNotification implements Notification
{
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('New comment pending approval')
            ->line('A new comment has been submitted on an article.')
            ->line('Author: ' . $this->comment->name)
            ->line('Comment: ' . Str::limit($this->comment->content, 100))
            ->action('Review in Admin Panel', route('admin.comments.index'))
            ->line('Thank you!');
    }
}
```

---

## Testing Workflow

### Admin Moderation
1. ✅ Go to `/admin/comments`
2. ✅ See "Pending" comments (if any)
3. ✅ Click "View" to see details
4. ✅ Click "Approve" — comment now visible on article
5. ✅ Click "Reject" — comment hidden from visitors

### Public Submission
1. ✅ Go to article page (e.g., `/articles/my-article`)
2. ✅ Scroll to comments section
3. ✅ Fill in Name, Email, Comment
4. ✅ Click "Post Comment"
5. ✅ See message: "Komentar Anda akan ditampilkan setelah disetujui oleh admin"
6. ✅ Go to admin panel → Comments → Pending
7. ✅ Approve the comment
8. ✅ Refresh article page — comment now visible!

---

## Customization

### Change Moderation Status
```php
// Pending only
$pendingComments = Comment::pending()->paginate(20);

// Approved only
$approvedComments = Comment::approved()->get();

// Rejected only
$rejectedComments = Comment::rejected()->get();
```

### Custom Messages

Edit `PublicCommentController.php`:

```php
'content.required' => 'Komentar tidak boleh kosong',
'content.min' => 'Komentar minimal 5 karakter',
'name.required' => 'Nama harus diisi',
```

### Customize Comment Display

Edit `resources/views/frontend/partials/comments.blade.php`:
- Change colors, spacing, icons
- Add reply functionality
- Add user avatars
- Add timestamps format

---

## Troubleshooting

### Comments Not Appearing
1. Check comment status in admin: must be "approved"
2. Verify `approvedComments()` is called in controller
3. Ensure `comments.blade.php` is included in article view

### Form Validation Errors
- Check `.env` for `APP_DEBUG=true` (shows error messages)
- Verify email format is correct
- Check content length (5-2000 chars)

### Admin Panel Not Showing
- Verify user is logged in
- Check user has `view_comments` permission (if added)
- Clear route cache: `php artisan route:clear`

---

## Next Steps

### Optional Enhancements
1. **Email notifications** — Notify admin of new pending comments
2. **Comment replies** — Allow nested comment threads
3. **Author replies** — Let article author respond to comments
4. **Comment ratings** — Upvote/downvote helpful comments
5. **Spam detection** — Integrate with Akismet or similar
6. **Comment avatars** — Use Gravatar or custom avatars
7. **Rich text** — Allow formatted comments (bold, links, etc.)

---

## File Summary

```
New Files (5):
✅ app/Http/Controllers/CommentController.php
✅ app/Http/Controllers/PublicCommentController.php
✅ resources/views/admin/comments/index.blade.php
✅ resources/views/admin/comments/show.blade.php
✅ resources/views/frontend/partials/comments.blade.php

Modified Files (3):
✅ app/Models/Comment.php — Added scopes
✅ app/Models/Article.php — Added approvedComments() relation
✅ resources/views/components/admin-layout-modern.blade.php — Added Comments menu

Routes Added (6):
✅ GET /admin/comments — List all comments
✅ GET /admin/comments/{id} — View comment details
✅ POST /admin/comments/{id}/approve — Approve comment
✅ POST /admin/comments/{id}/reject — Reject comment
✅ DELETE /admin/comments/{id} — Delete comment
✅ POST /articles/{article}/comments — Submit comment (public)
```

---

## Status

✅ **Complete & Ready** — Comment management system fully implemented!

Next: Integrate comments partial into article show pages.
