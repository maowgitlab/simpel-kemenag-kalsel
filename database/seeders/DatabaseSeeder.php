<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // $categories = ['Nasional', 'Daerah', 'Satuan Kerja', 'Siaran Pers', 'Opini'];
        // foreach ($categories as $category) {
        //     \App\Models\Category::create([
        //         'nama_kategori' => $slug = $category,
        //         'slug' => str()->slug($slug),
        //         'jumlah_dibaca' => fake()->numberBetween(0, 1000),
        //     ]);
        // }

        $listServices = [
            [
                'judul' => 'Permohonan Data Keagamaan', 
                'services' => ['Pelayanan Permohonan Informasi Keagamaan']
            ],
            [
                'judul' => 'Penyelenggaraan Haji & Umrah', 
                'services' => [
                    'Ijin Pendirian Kantor Cabang Penyelenggara Perjalanan Ibadah Umrah (PPIU) Penyelenggara Ibadah Haji Khusus (PIHK)',
                    'Rekomendasi Ijin Pendirian Penyelenggaraan Perjalanan Ibadah Umrah (PPIU) Dan Penyelenggaraan Ibadah Haji Khusus (PIHK)'
                ]
            ],
            [
                'judul' => 'Pendidikan', 
                'services' => [
                    'Legalisir Ijasah Madrasah',
                    'Pelayanan Izin Penelitian Madrasah',
                    'Pelayanan Permohonan Ijin Pendirian Operasional RA Madrasah',
                    'Pelayanan Permohonan Keterangan Pengganti Ijazah Karena Kesalahan Penulisan',
                    'Pelayanan Permohonan Surat Rekomendasi Mutasi Siswa Madrasah',
                    'Permohonan Blanko Ijasah Madrasah',
                    'Permohonan Izin Operasional Pondok Pesantren',
                    'Permohonan Izin Pendirian Madrasah',
                    'Permohonan Izin Penutupan Madrasah',
                    'Permohonan Menyelenggarakan Satuan Pendidikan Kesetaraaan Tingkat Ulya',
                    'Permohonan Perpanjangan Izin Operasional Madrasah',
                    'Permohonan Perpanjangan Izin Operasional Pondok Pesantren',
                    'Permohonan Rekomendasi Melanjutkan Studi Ke Luar Negeri',
                    'Permohonan Rekomendasi Melanjutkan Studi Ke Luar Negeri Dari Madrasah',
                    'Permohonan Rekomendasi Penegerian Madrasah',
                    'Permohonan SK Pengganti Izin Pendirian Madrasah Karena Hilang',
                ]
            ],
            [
                'judul' => 'Bimbingan Masyarakat Islam', 
                'services' => [
                    'Pelayanan Legalisasi Lembaga Amil Zakat',
                    'Pelayanan Permohonan Audiensi',
                    'Pelayanan Permohonan Konsultasi',
                    'Pelayanan Ruislagh (Tukar Guling) Tanah Wakaf ',
                    'Permohonan Kitab Suci Al-Qur\'\an',
                    'Permohonan Pelayanan Rohaniawan Dan Pembaca Doa',
                    'Permohonan Rekomendasi Jadwal Sholat Imsakiyah',
                    'Permohonan Rekomendasi Sarana dan Prasarana Rumah Ibadah',
                    'Verifikasi Arah Kiblat',
                ]
            ],
            [
                'judul' => 'Bimbingan Masyarakat Kristen', 
                'services' => [
                    'Permohonan Pelayanan Rohaniawan Dan Pembaca Doa',
                ]
            ],
            [
                'judul' => 'Bimbingan Masyarakat Katolik', 
                'services' => [
                    'Permohonan Pelayanan Rohaniawan Dan Pembaca Doa',
                ]
            ],
            [
                'judul' => 'Bimbingan Masyarakat Hindu', 
                'services' => [
                    'Permohonan Pelayanan Rohaniawan Dan Pembaca Doa',
                ]
            ],
            [
                'judul' => 'Bimbingan Masyarakat Buddha', 
                'services' => [
                    'Permohonan Pelayanan Rohaniawan Dan Pembaca Doa',
                ]
            ],
            [
                'judul' => 'Kepegawaian', 
                'services' => [
                    'Pelayanan Izin Magang/Praktik Kerja Lapangan (PKL)',
                ]
            ],
            [
                'judul' => 'Hubungan Masyarakat', 
                'services' => [
                    'Permohonan Pelayanan Rohaniawan dan Pembaca Doa',
                ]
            ],
        ];

        foreach ($listServices as $listService) {
            // Membuat entri ListService
            $listServiceEntry = \App\Models\ListService::create([
                'judul' => $listService['judul'],
                'jumlah_pengajuan' => fake()->numberBetween(0, 100),
            ]);

            // Membuat entri Service yang berelasi dengan ListService
            foreach ($listService['services'] as $service) {
                \App\Models\Service::create([
                    'judul' => $service,
                    'list_id' => $listServiceEntry->id,
                ]);
            }
        }
        User::factory(1)->create();
        // \App\Models\Media::factory(2)->create();
    }
}
