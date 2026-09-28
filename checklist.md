1. Selalu Gunakan Versi Terbaru
   Gunakan Laravel, PHP, dan package yang masih didukung.
   Perbarui dependency secara rutin:
   composer update
   npm update
   Audit kerentanan:
   composer audit
   npm audit
   Hapus package yang tidak digunakan:
   composer remove nama-package
2. Amankan File .env

Jangan pernah:

Menyimpan .env di Git.
Membagikan API key.
Menaruh password database di source code.

Pastikan .gitignore berisi:

.env
.env.\*

Atur permission:

chmod 600 .env

Gunakan:

php artisan config:cache 3. Gunakan HTTPS di Semua Endpoint

Di server:

return 301 https://$host$request_uri;

Di Laravel:

// AppServiceProvider.php

use Illuminate\Support\Facades\URL;

public function boot()
{
if (app()->environment('production')) {
URL::forceScheme('https');
}
}

Tambahkan cookie aman:

SESSION_SECURE_COOKIE=true 4. Implementasi Authentication yang Kuat

Gunakan:

Laravel Breeze
Laravel Jetstream
Laravel Sanctum
Laravel Passport

Aktifkan:

Email verification.
Password reset.
Two-factor authentication (2FA).
Session timeout.

Contoh:

Route::middleware([
'auth',
'verified'
])->group(function () {

}); 5. Gunakan Authorization yang Ketat

Jangan hanya mengecek:

if ($user->isAdmin())

Gunakan:

Policy
Gate
Permission

Contoh:

public function update(User $user, Product $product)
{
return $user->id === $product->user_id;
}

Pakai:

$this->authorize('update', $product);

Untuk aplikasi besar, gunakan Spatie Permission:

composer require spatie/laravel-permission

Role:

Super Admin
Admin
Editor
User

Prinsip:

Least Privilege: pengguna hanya mendapat akses minimum yang dibutuhkan.

6. Lindungi dari SQL Injection

Jangan:

DB::select("SELECT \* FROM users WHERE email = '$email'");

Gunakan:

User::where('email', $email)->first();

Atau:

DB::select(
'SELECT \* FROM users WHERE email = ?',
[$email]
); 7. Cegah XSS (Cross Site Scripting)

Jangan:

{!! $userInput !!}

Gunakan:

{{ $userInput }}

Hanya gunakan:

{!! !!}

untuk HTML yang sudah disanitasi.

Tambahkan library:

composer require mews/purifier 8. Validasi Semua Input

Jangan pernah percaya input dari:

Form.
API.
URL.
File upload.

Gunakan:

$request->validate([
'name' => 'required|string|max:100',
'email' => 'required|email',
]);

Lebih baik menggunakan:

php artisan make:request StoreProductRequest 9. Amankan Upload File

Batasi:

'image' => [
'required',
'image',
'mimes:jpg,jpeg,png,webp',
'max:2048'
]

Simpan di:

storage/app/private

Jangan:

/public/uploads

Validasi:

MIME type.
Ukuran.
Ekstensi.
Scan antivirus jika perlu.

Blokir:

.php
.exe
.sh
.js 10. Aktifkan Rate Limiting

Batasi login:

RateLimiter::for('login', function ($request) {
    return Limit::perMinute(5)
        ->by($request->ip());
});

Batasi API:

Route::middleware('throttle:60,1');

Gunakan Cloudflare untuk perlindungan tambahan.

11. Lindungi dari CSRF

Laravel sudah memiliki:

@csrf

Pastikan semua form POST memiliki token:

<form method="POST">
    @csrf
</form>
12. Sembunyikan Informasi Sensitif

Production:

APP_DEBUG=false
APP_ENV=production

Jangan pernah:

APP_DEBUG=true

di server production.

13. Gunakan Header Keamanan

Tambahkan middleware:

$response->headers->set(
'X-Frame-Options',
'SAMEORIGIN'
);

$response->headers->set(
'X-Content-Type-Options',
'nosniff'
);

$response->headers->set(
'Referrer-Policy',
'strict-origin'
);

$response->headers->set(
'Content-Security-Policy',
"default-src 'self'"
);

Header penting:

Content-Security-Policy (CSP)
X-Frame-Options
Strict-Transport-Security
Referrer-Policy
Permissions-Policy 14. Amankan Database

Buat user database khusus:

Jangan:

root

Gunakan:

laravel_app

Berikan hak minimum:

GRANT SELECT, INSERT, UPDATE, DELETE
ON app_db.\*
TO 'laravel_app';

Backup otomatis:

Harian.
Mingguan.
Offsite backup. 15. Logging dan Monitoring

Pasang:

Laravel Telescope (development).
Sentry.
Grafana.
Prometheus.
Fail2ban.

Catat:

Login gagal.
Perubahan role.
Penghapusan data.
Error. 16. Hardening Server Linux

Update server:

apt update && apt upgrade

Nonaktifkan login root:

PermitRootLogin no

Gunakan:

SSH key.
Fail2ban.
UFW firewall.

Buka port seperlunya:

ufw allow 22
ufw allow 80
ufw allow 443
ufw enable

Tutup:

3306 (MySQL publik).
6379 (Redis publik).
9200 (Elasticsearch publik). 17. Pisahkan Environment

Buat:

Development.
Staging.
Production.

Jangan:

Menguji fitur langsung di production.
Menggunakan database production untuk development. 18. Jalankan Security Scan Berkala

Tools yang sering dipakai:

OWASP ZAP.
Nmap.
Nikto.
Trivy (Docker).
SonarQube.

Contoh:

docker run \
 -t owasp/zap2docker-stable \
 zap-baseline.py \
 -t https://domainanda.com 19. Keamanan API

Jika menggunakan API:

Gunakan Laravel Sanctum.
Gunakan token expiration.
Validasi origin.
Gunakan HTTPS.
Rate limit.
