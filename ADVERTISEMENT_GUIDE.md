# 📢 Sistem Manajemen Iklan - Panduan Lengkap

## 📋 Daftar Isi
1. [Gambaran Umum](#gambaran-umum)
2. [Ukuran Iklan Standar](#ukuran-iklan-standar)
3. [Cara Mengelola Iklan](#cara-mengelola-iklan)
4. [Mengintegrasikan Iklan di Frontend](#mengintegrasikan-iklan-di-frontend)
5. [Tracking & Analitik](#tracking--analitik)

---

## 🎯 Gambaran Umum

Sistem manajemen iklan terintegrasi memungkinkan Anda untuk:
- ✅ Upload dan kelola gambar iklan
- ✅ Mengatur posisi iklan di berbagai tempat
- ✅ Menjadwalkan iklan (tanggal mulai & berakhir)
- ✅ Melacak view dan klik iklan
- ✅ Menampilkan iklan secara dinamis dari database

---

## 📐 Ukuran Iklan Standar

### 1. **Header Banner** (1200x128px)
- **Lokasi:** Di atas halaman (header)
- **Penggunaan:** Banner promosi utama
- **Rasio:** 9.4:1 (landscape)
- **Format File:** JPG, PNG
- **Max Size:** 5MB

```
┌─────────────────────────────────────────────────┐
│                 IKLAN HEADER                    │
│              1200px × 128px                     │
└─────────────────────────────────────────────────┘
```

### 2. **Medium Rectangle** (300x250px)
- **Lokasi:** Sidebar atas / Side kolom
- **Penggunaan:** Iklan standar samping
- **Rasio:** 1.2:1
- **Format File:** JPG, PNG
- **Max Size:** 5MB

```
┌──────────────┐
│  IKLAN MED   │
│  RECTANGLE   │
│  300×250     │
└──────────────┘
```

### 3. **Half Page** (300x600px)
- **Lokasi:** Sidebar bawah
- **Penggunaan:** Iklan skyscraper panjang
- **Rasio:** 1:2
- **Format File:** JPG, PNG
- **Max Size:** 5MB

```
┌──────────────┐
│  IKLAN HALF  │
│   PAGE       │
│  300×600     │
│              │
│              │
│              │
└──────────────┘
```

### 4. **Content Middle** (300x400px)
- **Lokasi:** Tengah konten artikel
- **Penggunaan:** Iklan di antara paragraf
- **Rasio:** 3:4
- **Format File:** JPG, PNG
- **Max Size:** 5MB

```
┌──────────────┐
│IKLAN TENGAH  │
│   KONTEN     │
│  300×400     │
│              │
└──────────────┘
```

### ✨ Ukuran Alternatif
- **Leaderboard:** 728x90px (horizontal banner)
- **Large Leaderboard:** 970x90px (wide banner)
- **Custom:** Bisa disesuaikan dengan kebutuhan

---

## 🛠️ Cara Mengelola Iklan

### Akses Admin Panel
```
URL: http://localhost:8000/admin/advertisements
```

### Menambah Iklan Baru
1. Klik tombol **"Tambah Iklan"** (warna merah)
2. Isi form dengan data iklan:
   - **Nama Iklan:** Identitas unik untuk iklan Anda
   - **Posisi:** Pilih lokasi tampilnya
   - **Gambar:** Upload file JPG/PNG (max 5MB)
   - **URL Tujuan:** Link klik iklan (opsional)
   - **Deskripsi:** Catatan tentang iklan
   - **Ukuran:** Pilih dari preset atau custom
   - **Tanggal:** Jadwal tayang (opsional)
   - **Status:** Aktifkan/Nonaktifkan

### Mengedit Iklan
1. Klik ikon ✏️ pada baris iklan
2. Ubah data yang diperlukan
3. Klik **"Perbarui Iklan"**

### Menghapus Iklan
1. Klik ikon 🗑️ pada baris iklan
2. Konfirmasi penghapusan
3. Iklan masuk ke trash (soft delete)

### Memulihkan Iklan Terhapus
1. Iklan yang dihapus masih terlihat di list dengan status "Dihapus"
2. Klik ikon ↶ untuk memulihkan

### Viewing Analytics
- **Views:** Jumlah kali iklan ditampilkan
- **Klik:** Jumlah kali iklan diklik
- Statistik update real-time

---

## 🎨 Mengintegrasikan Iklan di Frontend

### Menampilkan Iklan Menggunakan Component
```blade
<!-- Header Banner -->
<x-advertisement placement="header_banner" />

<!-- Sidebar Atas -->
<x-advertisement placement="sidebar_top" />

<!-- Sidebar Bawah -->
<x-advertisement placement="sidebar_bottom" />

<!-- Konten Tengah -->
<x-advertisement placement="content_middle" />
```

### Contoh Implementasi di Homepage
```blade
<!-- Di app-modern.blade.php (header) -->
<x-advertisement placement="header_banner" />

<!-- Di home-modern.blade.php (sidebar) -->
<x-advertisement placement="sidebar_top" />
<!-- ... konten sidebar ... -->
<x-advertisement placement="sidebar_bottom" />

<!-- Di tengah artikel -->
<x-advertisement placement="content_middle" />
```

### Styling Responsive
Component otomatis responsive dengan `max-width: 100%`:
- Desktop: Ukuran penuh
- Tablet/Mobile: Otomatis scale down

---

## 📊 Tracking & Analitik

### Pencatatan Otomatis
- **View:** Dicatat saat halaman dimuat
- **Klik:** Dicatat saat iklan diklik

### API Endpoints
```
POST /api/advertisements/{id}/view
POST /api/advertisements/{id}/click
```

### Data yang Dilacak
- View Count (total tampilan)
- Click Count (total klik)
- CTR (Click-Through Rate) = Klik / View

---

## 🚀 Tips Optimasi

### Ukuran File Gambar
- Compress gambar sebelum upload
- Gunakan format WebP untuk performa lebih baik
- Target: 50-200KB per gambar

### Penempatan Optimal
- **Header:** Untuk brand awareness
- **Sidebar Atas:** Untuk produk/promo
- **Sidebar Bawah:** Untuk iklan sekunder
- **Konten Tengah:** Untuk engagement tinggi

### Best Practices
✅ Update iklan secara berkala
✅ Monitor CTR dan view ratio
✅ Gunakan jadwal untuk kampanye musiman
✅ Optimize ukuran gambar
✅ Test berbagai posisi iklan
✅ A/B test dengan gambar berbeda

---

## 📝 Referensi Database

### Tabel: advertisements
```sql
- id (int)
- name (string) - Nama iklan
- placement (string) - header_banner, sidebar_top, sidebar_bottom, content_middle
- image (string) - Path gambar
- url (string) - URL tujuan
- description (text) - Deskripsi
- size (string) - 1200x128, 300x250, dll
- width (int) - Lebar pixel
- height (int) - Tinggi pixel
- is_active (boolean) - Status aktif
- start_date (datetime) - Mulai tayang
- end_date (datetime) - Akhir tayang
- click_count (int) - Total klik
- view_count (int) - Total views
- created_at, updated_at, deleted_at
```

---

## ❓ FAQ

**Q: Berapa ukuran file maksimal iklan?**
A: 5MB per file

**Q: Bisa menggunakan iklan berukuran custom?**
A: Ya, bisa. Pilih "Custom" dan masukkan lebar & tinggi pixel.

**Q: Apakah iklan langsung tampil saat disave?**
A: Ya, jika status "Aktif" dan tanggal valid.

**Q: Bisa menjadwalkan iklan untuk tanggal tertentu?**
A: Ya, isi kolom "Tanggal Mulai" dan "Tanggal Berakhir".

**Q: Bagaimana jika tidak ada iklan untuk posisi tertentu?**
A: Posisi tersebut akan kosong (tidak menampilkan placeholder).

---

## 📞 Support

Untuk pertanyaan atau masalah, hubungi administrator sistem.

---

**Last Updated:** September 2026
**Version:** 1.0
