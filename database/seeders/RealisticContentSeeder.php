<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Article;
use App\Models\Category;
use App\Models\Video;
use App\Models\Announcement;
use App\Models\Event;
use App\Models\User;

class RealisticContentSeeder extends Seeder
{
    public function run()
    {
        // Get or create user
        $user = User::firstOrCreate(['email' => 'admin@newsmedia.id'], [
            'name' => 'Admin News Media',
            'password' => bcrypt('password'),
        ]);

        // Article data - realistic for Depok
        $articles = [
            // Depok Today
            [
                'title' => 'Walikota Depok: Pembangunan Infrastruktur Transportasi Jadi Prioritas 2026',
                'slug' => 'walikota-depok-infrastruktur-2026',
                'excerpt' => 'Walikota Depok menegaskan fokus pada perbaikan jalan, pembangunan Terminal Terintegrasi Depok, dan persiapan jalur Transjakarta fase 3.',
                'content' => '<p>Depok – Dalam konferensi pers yang digelar di Kantor Walikota, Walikota Depok mengumumkan prioritas pembangunan infrastruktur untuk tahun 2026 yang mencakup perbaikan jalan di 45 kelurahan, pembangunan terminal transportasi terintegrasi di Pusat Kota, dan persiapan jalur Transjakarta fase 3.</p><p>"Kami berkomitmen untuk meningkatkan kualitas hidup warga Depok melalui infrastruktur yang lebih baik," ujar Walikota. Anggaran yang dialokasikan untuk proyek ini mencapai Rp 1,2 triliun dari APBD Depok 2026.</p><p>Pembangunan Terminal Terintegrasi Depok diproyeksikan akan menampung 50.000 penumpang per hari dan mengurangi kemacetan di pusat kota hingga 30%.</p>',
                'category_id' => 26,
                'status' => 'published',
                'featured_image' => '/images/placeholder-news-media.svg',
                'published_at' => now()->subDays(1),
            ],
            [
                'title' => 'Ratusan UMKM Depok Ikuti Program Pelatihan Digital Marketing Gratis dari Pemkot',
                'slug' => 'umkm-depok-digital-marketing-gratis',
                'excerpt' => 'Pemerintah Kota Depok menggelar program gratis pelatihan digital marketing untuk 500 UMKM dengan materi strategi penjualan online dan media sosial.',
                'content' => '<p>Depok – Sebanyak 500 UMKM dari berbagai sektor mengikuti program pelatihan digital marketing gratis yang diselenggarakan Dinas Perindustrian dan Perdagangan Depok di Gedung Serba Guna Kelurahan Pancoran Mas.</p><p>Program selama tiga hari ini mencakup materi strategi penjualan online, optimasi media sosial, dan penggunaan platform e-commerce. "UMKM adalah tulang punggung ekonomi Depok, mereka perlu didukung dengan keterampilan digital," kata Kepala Dinas Perindustrian.</p><p>Peserta mendapatkan sertifikat dan kesempatan untuk mempresentasikan produk mereka kepada investor lokal.</p>',
                'category_id' => 25,
                'status' => 'published',
                'featured_image' => '/images/placeholder-news-media.svg',
                'published_at' => now()->subDays(2),
            ],
            [
                'title' => 'Jadwal Ulang PPDB SMA/SMK Depok Dimulai 15 Maret, Daftar Sekarang',
                'slug' => 'ppdb-sma-smk-depok-maret-2026',
                'excerpt' => 'Dinas Pendidikan Kota Depok mengumumkan penjadwalan ulang PPDB untuk 45 sekolah menengah dengan kuota 12.000 siswa baru.',
                'content' => '<p>Depok – Dinas Pendidikan Kota Depok mengumumkan jadwal ulang Penerimaan Peserta Didik Baru (PPDB) untuk SMA dan SMK se-Depok. Pendaftaran dibuka mulai 15 Maret 2026 hingga 22 Maret 2026.</p><p>Sebanyak 45 sekolah menengah di Depok membuka kesempatan untuk 12.000 siswa baru. Pendaftaran dilakukan secara online melalui portal ppdb.depok.go.id.</p><p>Persyaratan meliputi:</p><ul><li>Ijazah SMP/Sederajat</li><li>Kartu Keluarga</li><li>Akta Kelahiran</li><li>Raport semester terakhir</li></ul><p>Hasil pengumuman akan diumumkan pada 2 April 2026.</p>',
                'category_id' => 28,
                'status' => 'published',
                'featured_image' => '/images/placeholder-news-media.svg',
                'published_at' => now()->subDays(3),
            ],
            [
                'title' => 'Puskesmas Depok Buka Layanan Vaksinasi COVID-19 Booster Gratis Setiap Hari',
                'slug' => 'puskesmas-depok-vaksin-booster-gratis',
                'excerpt' => 'Layanan vaksinasi COVID-19 booster kini tersedia di 25 Puskesmas di Depok tanpa biaya setiap hari Senin-Jumat pukul 08:00-16:00.',
                'content' => '<p>Depok – Dinas Kesehatan Kota Depok mengumumkan layanan vaksinasi COVID-19 booster gratis tersedia di 25 Puskesmas di seluruh wilayah Depok.</p><p>Layanan vaksinasi dibuka Senin-Jumat pukul 08:00-16:00, sementara pada akhir pekan tersedia di 5 lokasi khusus di mall dan pusat perbelanjaan.</p><p>Persyaratan peserta:</p><ul><li>Sudah menerima vaksin dosis lengkap minimal 3 bulan lalu</li><li>Membawa KTP/KK</li><li>Dalam kondisi sehat</li></ul><p>Kepala Dinas Kesehatan mengajak warga untuk segera melakukan booster untuk meningkatkan kekebalan tubuh terhadap varian terbaru COVID-19.</p>',
                'category_id' => 25,
                'status' => 'published',
                'featured_image' => '/images/placeholder-news-media.svg',
                'published_at' => now()->subDays(4),
            ],
            [
                'title' => 'Festival Kuliner Depok 2026: 150 Pedagang Siap Manjakan Lidah Pengunjung',
                'slug' => 'festival-kuliner-depok-2026-150-pedagang',
                'excerpt' => 'Festival Kuliner Depok akan diselenggarakan 20-22 Maret 2026 di Alun-Alun Depok dengan menghadirkan 150 pedagang kuliner lokal dan internasional.',
                'content' => '<p>Depok – Panitia Festival Kuliner Depok 2026 telah menyelesaikan persiapan untuk menghadirkan pengalaman kuliner terbaik bagi warga dan pengunjung dari luar Depok.</p><p>Festival ini akan berlangsung 20-22 Maret 2026 di Alun-Alun Depok dengan menghadirkan 150 pedagang dari berbagai kategori: makanan tradisional, modern fusion, dan internasional.</p><p>Beberapa highlight festival:</p><ul><li>Kontes Masakan Ternikmat dengan hadiah Rp 50 juta</li><li>Live cooking demo dari chef terkenal</li><li>Zona Food Truck Premium</li><li>Musik live setiap malam</li><li>Zona Anak dengan mainan gratis</li></ul><p>Harga tiket masuk Rp 20.000 untuk dewasa dan Rp 10.000 untuk anak-anak.</p>',
                'category_id' => 25,
                'status' => 'published',
                'featured_image' => '/images/placeholder-news-media.svg',
                'published_at' => now()->subDays(5),
            ],
            [
                'title' => 'Pasar Modern Depok Town Center Buka, Integrated Shopping Experience Terlengkap',
                'slug' => 'pasar-modern-depok-town-center-buka',
                'excerpt' => 'Pusat perbelanjaan terbesar se-Depok dibuka dengan fasilitas lengkap: 300 toko, food court internasional, bioskop 8 layar, dan area bermain anak.',
                'content' => '<p>Depok – Depok Town Center, pusat perbelanjaan terbesar se-Kota Depok, resmi dibuka hari Sabtu (8 Maret 2026) dengan menghadirkan pengalaman berbelanja terintegrasi yang belum pernah ada sebelumnya.</p><p>Dengan luas 120.000 meter persegi, Depok Town Center menghadirkan:</p><ul><li>300+ toko retail dari brand lokal dan internasional</li><li>Food court dengan 50+ restoran</li><li>Bioskop 8 layar dengan teknologi IMAX</li><li>Zona bermain anak dengan area indoor play terbesar</li><li>Fasilitas kesehatan (klinik dan farmasi)</li><li>Parkir 3.000 mobil</li></ul><p>Pembukaan dirayakan dengan diskon pembukaran hingga 70% dan doorprize menarik.</p>',
                'category_id' => 25,
                'status' => 'published',
                'featured_image' => '/images/placeholder-news-media.svg',
                'published_at' => now()->subDays(6),
            ],
            [
                'title' => 'Tips Memilih Sekolah Terbaik untuk Anak: Panduan Orang Tua di Depok',
                'slug' => 'tips-memilih-sekolah-terbaik-depok',
                'excerpt' => 'Panduan lengkap untuk orang tua dalam memilih sekolah yang tepat bagi anak, dari aspek akademik, fasilitas, hingga lingkungan sekolah.',
                'content' => '<p>Memilih sekolah untuk anak adalah keputusan penting yang mempengaruhi masa depan pendidikan mereka. Berikut tips memilih sekolah terbaik di Depok:</p><h3>1. Akreditasi dan Reputasi</h3><p>Pilih sekolah yang terakreditasi A dan memiliki reputasi baik di masyarakat. Cek website sekolah dan baca review orang tua lain.</p><h3>2. Fasilitas dan Sarana</h3><p>Kunjungi langsung sekolah. Perhatikan:</p><ul><li>Ruang kelas yang nyaman</li><li>Laboratorium yang lengkap</li><li>Perpustakaan modern</li><li>Lapangan olahraga</li><li>Kantin yang bersih</li></ul><h3>3. Kurikulum dan Program</h3><p>Pastikan kurikulum sesuai dengan kebutuhan anak. Tanyakan tentang program ekstrakurikuler yang tersedia.</p><h3>4. Biaya Pendidikan</h3><p>Bandingkan biaya dengan kualitas yang diberikan. Jangan memilih hanya berdasarkan biaya termurah.</p><h3>5. Jarak dari Rumah</h3><p>Pertimbangkan waktu tempuh untuk kenyamanan anak dalam perjalanan ke sekolah.</p>',
                'category_id' => 28,
                'status' => 'published',
                'featured_image' => '/images/placeholder-news-media.svg',
                'published_at' => now()->subDays(7),
            ],
            [
                'title' => 'Cara Mudah Mendapatkan Sertifikat Halal untuk UMKM Makanan Depok',
                'slug' => 'sertifikat-halal-umkm-makanan-depok',
                'excerpt' => 'Panduan lengkap dan biaya untuk mendapatkan sertifikat halal dari LPPOM MUI, penting untuk UMKM makanan di Depok.',
                'content' => '<p>Bagi UMKM di bidang makanan dan minuman, sertifikat halal bukan hanya sebuah kebutuhan tetapi juga keharusan untuk meningkatkan kepercayaan konsumen.</p><h3>Langkah-langkah Mendapatkan Sertifikat Halal</h3><p><strong>1. Persiapan Dokumen</strong></p><ul><li>Form permohonan sertifikat halal</li><li>Surat pernyataan halal dari pemilik usaha</li><li>Salinan izin usaha</li><li>Daftar produk dan bahan baku</li><li>Sertifikat bahan baku dari supplier</li></ul><p><strong>2. Audit LPPOM MUI</strong></p><p>Tim audit akan mengecek proses produksi, bahan baku, dan kebersihan fasilitas produksi.</p><p><strong>3. Biaya Sertifikasi</strong></p><ul><li>Biaya audit: Rp 1.500.000 - Rp 5.000.000</li><li>Biaya sertifikasi: Rp 500.000 - Rp 1.000.000</li><li>Biaya tahunan: Rp 1.000.000</li></ul><p><strong>4. Waktu Proses</strong></p><p>Proses sertifikasi membutuhkan waktu 2-3 bulan dari permohonan hingga penetapan.</p><p>Hubungi LPPOM MUI Cabang Depok untuk informasi lebih lanjut: (021) XXXX-XXXX</p>',
                'category_id' => 25,
                'status' => 'published',
                'featured_image' => '/images/placeholder-news-media.svg',
                'published_at' => now()->subDays(8),
            ],
        ];

        // Create articles
        foreach ($articles as $article) {
            Article::create([
                'user_id' => $user->id,
                'category_id' => $article['category_id'],
                'title' => $article['title'],
                'slug' => $article['slug'],
                'excerpt' => $article['excerpt'],
                'content' => $article['content'],
                'featured_image' => $article['featured_image'],
                'status' => $article['status'],
                'published_at' => $article['published_at'],
                'views_count' => rand(100, 5000),
                'read_time' => rand(3, 15),
                'is_featured' => false,
            ]);
        }

        // Note: Announcements and Events tables will be created in future migrations
        // For now, we'll focus on articles and videos which are working properly

        // Videos
        $videos = [
            [
                'title' => 'Wawancara Walikota Depok: Visi Depok 2026',
                'description' => 'Walikota Depok berbagi visi dan misi pemerintah Depok untuk tahun 2026 dalam pembangunan infrastruktur, ekonomi, dan pendidikan.',
                'youtube_url' => 'https://www.youtube.com/embed/dQw4w9WgXcQ',
                'youtube_id' => 'dQw4w9WgXcQ',
                'thumbnail_url' => 'https://img.youtube.com/vi/dQw4w9WgXcQ/maxresdefault.jpg',
                'category_id' => 26,
                'status' => 'published',
                'published_at' => now()->subDays(5),
            ],
            [
                'title' => 'Profil UMKM Lokal: Batik Depok yang Terkenal Internasional',
                'description' => 'Kisah sukses pengusaha muda yang mengubah batik tradisional Depok menjadi produk eksport dengan omset Rp 5 miliar per tahun.',
                'youtube_url' => 'https://www.youtube.com/embed/jNQXAC9IVRw',
                'youtube_id' => 'jNQXAC9IVRw',
                'thumbnail_url' => 'https://img.youtube.com/vi/jNQXAC9IVRw/maxresdefault.jpg',
                'category_id' => 25,
                'status' => 'published',
                'published_at' => now()->subDays(3),
            ],
            [
                'title' => 'Tips Hidup Sehat: Sesi Yoga di Taman Depok',
                'description' => 'Program yoga gratis setiap Minggu pagi di Taman Depok dengan instruktur bersertifikat. Kesehatan fisik dan mental dimulai dari gerakan yang tepat.',
                'youtube_url' => 'https://www.youtube.com/embed/9bZkp7q19f0',
                'youtube_id' => '9bZkp7q19f0',
                'thumbnail_url' => 'https://img.youtube.com/vi/9bZkp7q19f0/maxresdefault.jpg',
                'category_id' => 25,
                'status' => 'published',
                'published_at' => now()->subDays(7),
            ],
            [
                'title' => 'Highlight Event: Peluncuran Depok Town Center',
                'description' => 'Rekaman momen peluncuran pusat perbelanjaan terbesar se-Depok dengan dihadiri ribuan pengunjung dan berbagai penampilan hiburan.',
                'youtube_url' => 'https://www.youtube.com/embed/ZbZSe6N_BXs',
                'youtube_id' => 'ZbZSe6N_BXs',
                'thumbnail_url' => 'https://img.youtube.com/vi/ZbZSe6N_BXs/maxresdefault.jpg',
                'category_id' => 25,
                'status' => 'published',
                'published_at' => now()->subDays(2),
            ],
        ];

        foreach ($videos as $video) {
            Video::create([
                'user_id' => $user->id,
                'title' => $video['title'],
                'description' => $video['description'],
                'youtube_url' => $video['youtube_url'],
                'youtube_id' => $video['youtube_id'],
                'thumbnail_url' => $video['thumbnail_url'],
                'category_id' => $video['category_id'],
                'status' => $video['status'],
                'published_at' => $video['published_at'],
                'views_count' => rand(500, 10000),
            ]);
        }

        $this->command->info('Realistic content seeder completed successfully!');
    }
}
