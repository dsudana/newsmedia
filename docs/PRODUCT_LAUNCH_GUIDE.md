# News Portal Template - Product Launch Guide

## 🎯 What's Included

A complete, production-ready Laravel 11 news portal template with:

### Core Features
✅ **Site Configuration Panel** - Branding, colors, contact info, social media links  
✅ **Newsletter System** - Subscriber management, email templates, broadcast  
✅ **Ad Management** - Multiple ad slots (header, sidebar, content, footer)  
✅ **Legal Pages** - Privacy Policy, Terms of Service, Cookie Policy  
✅ **Article Management** - Full CRUD with series support, featured images, SEO  
✅ **Homepage Builder** - Drag-drop section management  
✅ **Category Management** - Organize articles by topics  
✅ **Analytics Dashboard** - Track article views and engagement  

---

## 📋 Quick Setup (5 minutes)

### 1. Environment Setup
```bash
# Copy environment file
cp .env.example .env

# Generate app key
php artisan key:generate

# Run migrations (creates database tables)
php artisan migrate

# Seed default data (creates legal pages)
php artisan db:seed --class=LegalPageSeeder
```

### 2. Admin Panel Login
- Navigate to `/admin`
- Create admin user (or use included credentials)
- Go to **Admin > Site Settings** to customize branding

---

## 🔧 Configuration Guide

### Site Branding (/admin/site-settings)
- **Logo & Favicon** - Upload your images
- **Primary/Secondary Colors** - Set brand colors (hex format)
- **Site Info** - Name, tagline, description
- **Contact Info** - Email, phone, address
- **Social Media** - Facebook, Twitter, Instagram, YouTube links
- **System Settings** - Timezone (WIB/WITA/WIT), articles per page

### Newsletter Setup (/admin/newsletter/)
1. **Subscribers Tab** - View all newsletter subscribers
   - Filter by active/inactive
   - Export emails for bulk campaigns
   
2. **Templates Tab** - Create email templates
   - Support for manual, daily, weekly sends
   - HTML templates with variable substitution
   - Test templates before broadcasting

### Ad Management (/admin/ads/)
1. **Ad Slots** - Manage advertising locations
   - Create slots for header, sidebar, content, footer
   - Support for Google AdSense, manual HTML, affiliate codes
   - Toggle slots on/off without deleting

2. **Settings** - Global ad configuration
   - Paste Google AdSense publisher ID
   - Enable/disable all ads globally

### Legal Pages (/admin/legal-pages/)
- Manage Privacy Policy, Terms of Service, Cookie Policy
- URLs: `/privacy-policy`, `/terms-of-service`, `/cookie-policy`
- Edit HTML content directly
- Pre-populated with default templates

---

## 🚀 Deployment Checklist

Before going live:

- [ ] Configure `.env` with production database credentials
- [ ] Set `APP_DEBUG=false` in `.env`
- [ ] Run `php artisan config:cache`
- [ ] Set strong `APP_KEY` in `.env`
- [ ] Configure mail driver for newsletters (SMTP settings)
- [ ] Set up storage link: `php artisan storage:link`
- [ ] Enable HTTPS (use SSL certificate)
- [ ] Configure file permissions for storage/logs directories
- [ ] Set up cron job for scheduled tasks: `* * * * * cd /path/to/app && php artisan schedule:run >> /dev/null 2>&1`
- [ ] Test newsletter sending with test subscriber
- [ ] Verify ads display correctly on frontend

---

## 📦 Key File Locations

```
app/Http/Controllers/Admin/
├── SiteSettingController.php      # Site configuration
├── NewsletterController.php        # Newsletter management
├── AdManagementController.php      # Ad management
└── LegalPageController.php         # Legal pages

resources/views/admin/
├── settings/                       # Site settings views
├── newsletter/                     # Newsletter views
├── ads/                           # Ad management views
└── legal-pages/                   # Legal page views

app/Models/
├── SiteSetting.php                # Site configuration storage
├── NewsletterSubscriber.php       # Email subscribers
├── NewsletterTemplate.php         # Email templates
├── AdSlot.php                     # Ad placement configuration
└── LegalPage.php                  # Legal page content
```

---

## 💻 Frontend Integration

### Newsletter Widget
Add to any view:
```blade
<x-newsletter-subscription />
```

### Legal Links in Footer
```blade
<a href="{{ route('legal.show', 'privacy-policy') }}">Privacy Policy</a>
<a href="{{ route('legal.show', 'terms-of-service') }}">Terms of Service</a>
<a href="{{ route('legal.show', 'cookie-policy') }}">Cookie Policy</a>
```

### Rendering Ads
In your templates:
```blade
<!-- Header ads -->
@php $ads = \App\Models\AdSlot::active()->byLocation('header')->get(); @endphp
@foreach($ads as $ad)
    <div class="ad-slot">
        @if($ad->ad_type === 'manual')
            {!! $ad->manual_html !!}
        @else
            {!! $ad->ad_code !!}
        @endif
    </div>
@endforeach
```

---

## 🔐 Security Reminders

- Change default admin password immediately
- Use strong, unique passwords for all admin accounts
- Enable two-factor authentication (if available)
- Regularly backup your database
- Keep Laravel dependencies updated: `composer update`
- Monitor storage for large files taking disk space
- Review newsletter subscriber list periodically
- Test ad code from trusted sources only

---

## 📊 Admin Routes

| Page | URL | Purpose |
|------|-----|---------|
| Site Settings | `/admin/site-settings` | Brand configuration |
| Newsletter Subscribers | `/admin/newsletter/subscribers` | Manage email list |
| Newsletter Templates | `/admin/newsletter/templates` | Create email templates |
| Ad Management | `/admin/ads` | Setup advertising slots |
| Ad Settings | `/admin/ads/settings` | Global ad configuration |
| Legal Pages | `/admin/legal-pages` | Manage policies/terms |

---

## 🆘 Troubleshooting

**Newsletter not sending?**
- Check SMTP credentials in `.env`
- Verify database connection
- Check `storage/logs/laravel.log` for errors

**Ads not displaying?**
- Verify AdSense code is valid
- Check browser console for JavaScript errors
- Ensure ad slot is marked as active
- Clear browser cache (hard refresh)

**Images not showing?**
- Run `php artisan storage:link`
- Check file permissions on `storage/app/public`
- Verify files exist in storage directory

---

## 📚 Next Steps

1. **Customize Homepage** - Go to `/admin/homepage-builder` to arrange sections
2. **Add Articles** - Create content via `/admin/articles`
3. **Configure Categories** - Organize content via `/admin/categories`
4. **Setup Newsletter Templates** - Create first email template
5. **Add Legal Pages** - Update privacy policy and terms
6. **Test Frontend** - Visit `/` and verify everything displays correctly

---

## 📞 Support

For issues or questions:
1. Check the troubleshooting section above
2. Review Laravel documentation: https://laravel.com/docs
3. Check error logs in `storage/logs/`
4. Verify database migrations completed successfully

---

**Version:** 1.0  
**Last Updated:** July 2026  
**Laravel Version:** 11.x  
**PHP Version:** 8.3+
