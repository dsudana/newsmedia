# Branding & Styling Settings

Dokumentasi untuk pengaturan branding dan styling yang dapat dikustomisasi.

## Fitur Baru Ditambahkan

### 1. Upload Logo & Favicon
**Lokasi:** Admin Panel → Pengaturan Situs → Branding

- **Logo Situs**: Upload gambar logo (JPG, PNG, SVG) maksimal 2MB
  - Ditampilkan di header situs
  - Preview gambar saat ini ditampilkan sebelum upload
  
- **Favicon**: Upload icon situs (ICO, PNG) maksimal 1MB
  - Ditampilkan di browser tab
  - Preview ditampilkan sebelum upload

Upload otomatis disimpan ke: `storage/app/public/settings/`

### 2. Warna Branding (Color Customization)

**Warna Utama (Primary Color)**
- Default: `#3b82f6` (Biru)
- Digunakan untuk: Links, buttons, accents
- Real-time preview dengan color picker

**Warna Sekunder (Secondary Color)**
- Default: `#1f2937` (Gelap)
- Digunakan untuk: Text, headings, borders
- Real-time preview dengan color picker

Warna-warna ini otomatis diterapkan ke seluruh frontend melalui CSS variables.

### 3. Font Selection

**Font Isi (Body Font)**
Pilihan font untuk konten artikel dan paragraf:
- Segoe UI (default)
- Arial
- Helvetica
- Georgia
- Trebuchet MS
- Courier New
- Verdana
- Times New Roman

**Font Judul (Heading Font)**
Pilihan font untuk judul dan heading:
- Segoe UI (default)
- Arial
- Helvetica
- Georgia
- Trebuchet MS
- Impact
- Palatino
- Garamond

Font dipilih dari font system yang umum tersedia di semua browser.

## Implementasi Teknis

### CSS Variables
Pengaturan diterapkan via CSS custom properties (variables) di file `branding-styles.blade.php`:

```css
:root {
    --primary-color: #3b82f6;
    --secondary-color: #1f2937;
    --body-font: Segoe UI;
    --heading-font: Segoe UI;
}
```

### Aplikasi Global
Component `<x-branding-styles />` ditambahkan ke layout utama:
- `resources/views/layouts/main.blade.php`
- `resources/views/layouts/app.blade.php`
- `resources/views/layouts/admin.blade.php`
- `resources/views/components/layout.blade.php`

Memastikan branding diterapkan ke seluruh halaman frontend dan admin.

### Database Storage
Semua setting disimpan di tabel `site_settings` dengan key-value pattern:
- `logo_url` - Path file logo
- `favicon_url` - Path file favicon
- `primary_color` - Hex color untuk primary (#RRGGBB)
- `secondary_color` - Hex color untuk secondary (#RRGGBB)
- `body_font` - Nama font untuk body text
- `heading_font` - Nama font untuk heading

## Penggunaan di Views

### Mengakses warna di template:
```blade
<div style="color: var(--primary-color)">
    Text dengan primary color
</div>
```

### Mengakses font di CSS:
```css
h1 {
    font-family: var(--heading-font);
}

p {
    font-family: var(--body-font);
}
```

### Menampilkan logo:
```blade
@php
    $logoUrl = \App\Models\SiteSetting::get('logo_url');
@endphp
@if($logoUrl)
    <img src="{{ asset('storage/' . $logoUrl) }}" alt="Logo" class="h-12">
@endif
```

## Form Validation

Validasi di SiteSettingController:
- Logo: `nullable|image|max:2048` (max 2MB)
- Favicon: `nullable|image|max:1024` (max 1MB)
- Primary Color: `required|regex:/^#[0-9a-fA-F]{6}$/`
- Secondary Color: `required|regex:/^#[0-9a-fA-F]{6}$/`
- Body Font: `required|string`
- Heading Font: `required|string`

## Testing Checklist

- [ ] Upload logo dengan berbagai format (JPG, PNG, SVG)
- [ ] Upload favicon dengan format ICO/PNG
- [ ] Ubah primary color dan verifikasi di frontend
- [ ] Ubah secondary color dan verifikasi di frontend
- [ ] Ganti body font dan lihat perubahan di konten
- [ ] Ganti heading font dan lihat perubahan di judul
- [ ] Verifikasi logo tampil di header situs
- [ ] Verifikasi favicon tampil di browser tab
- [ ] Test di mobile view untuk responsiveness
- [ ] Clear browser cache untuk melihat perubahan color

## File yang Dimodifikasi

**Controllers:**
- `app/Http/Controllers/Admin/SiteSettingController.php` - Tambah body_font & heading_font handling

**Views:**
- `resources/views/admin/settings/index.blade.php` - Upload input, font dropdown, color picker
- `resources/views/layouts/main.blade.php` - Tambah branding-styles component
- `resources/views/layouts/app.blade.php` - Tambah branding-styles component
- `resources/views/layouts/admin.blade.php` - Tambah branding-styles component
- `resources/views/components/layout.blade.php` - Tambah branding-styles component

**Components:**
- `resources/views/components/branding-styles.blade.php` (NEW) - CSS variables & style injection

## Catatan Penting

1. **Storage Link**: Pastikan `php artisan storage:link` sudah dijalankan untuk akses public storage
2. **Caching**: Jika styles tidak berubah, clear cache browser (Ctrl+Shift+Delete)
3. **Font Fallback**: Semua font yang dipilih adalah system fonts, jadi tidak perlu external CDN
4. **Mobile**: Semua styling responsive dan mobile-friendly
5. **Performance**: CSS variables dibuat saat render, minimal impact ke performance

---

**Versi:** 1.0
**Tanggal:** Juli 2026
