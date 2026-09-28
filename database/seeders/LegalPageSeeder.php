<?php

namespace Database\Seeders;

use App\Models\LegalPage;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LegalPageSeeder extends Seeder
{
    public function run(): void
    {
        LegalPage::firstOrCreate(
            ['slug' => 'privacy-policy'],
            [
                'title' => 'Kebijakan Privasi',
                'content' => '<h1>Kebijakan Privasi</h1><p>Kami menghormati privasi Anda. Platform ini mengumpulkan data pengguna untuk memberikan layanan terbaik. Data Anda dilindungi dengan enkripsi dan tidak akan dibagikan kepada pihak ketiga tanpa izin Anda.</p>',
            ]
        );

        LegalPage::firstOrCreate(
            ['slug' => 'terms-of-service'],
            [
                'title' => 'Syarat dan Ketentuan',
                'content' => '<h1>Syarat dan Ketentuan Layanan</h1><p>Dengan menggunakan platform ini, Anda menyetujui syarat dan ketentuan yang berlaku. Platform ini menyediakan konten berita dan informasi publik. Pengguna bertanggung jawab atas segala konten yang mereka posting.</p>',
            ]
        );

        LegalPage::firstOrCreate(
            ['slug' => 'cookie-policy'],
            [
                'title' => 'Kebijakan Cookie',
                'content' => '<h1>Kebijakan Cookie</h1><p>Platform ini menggunakan cookie untuk meningkatkan pengalaman pengguna. Cookie digunakan untuk mengingat preferensi dan melacak kunjungan. Anda dapat mengontrol pengaturan cookie melalui pengaturan browser Anda.</p>',
            ]
        );
    }
}
