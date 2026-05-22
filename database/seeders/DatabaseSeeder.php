<?php

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\Fasilitas;
use App\Models\Kamar;
use App\Models\Payment;
use App\Models\Kos;
use App\Models\ProfilePemilik;
use App\Models\Review;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::create(['name' => 'Admin KosKu', 'email' => 'admin@kosku.test', 'password' => Hash::make('password'), 'role' => 'admin']);
        $pemilik = User::create(['name' => 'Pemilik Kos Palu', 'email' => 'pemilik@kosku.test', 'password' => Hash::make('password'), 'role' => 'pemilik']);
        $pemilik2 = User::create(['name' => 'Ibu Melati', 'email' => 'melati@kosku.test', 'password' => Hash::make('password'), 'role' => 'pemilik']);
        $pemilik3 = User::create(['name' => 'Pak Budi Santoso', 'email' => 'budi@kosku.test', 'password' => Hash::make('password'), 'role' => 'pemilik']);
        $user = User::create(['name' => 'User Pencari Kos', 'email' => 'user@kosku.test', 'password' => Hash::make('password'), 'role' => 'user']);
        $user2 = User::create(['name' => 'Rina Mahasiswa', 'email' => 'rina@kosku.test', 'password' => Hash::make('password'), 'role' => 'user']);

        ProfilePemilik::create(['user_id' => $pemilik->id, 'no_wa' => '6281234567890', 'alamat' => 'Tondo, Palu']);
        ProfilePemilik::create(['user_id' => $pemilik2->id, 'no_wa' => '6281234567888', 'alamat' => 'Palu Selatan']);
        ProfilePemilik::create(['user_id' => $pemilik3->id, 'no_wa' => '6281234567877', 'alamat' => 'Palu Timur']);

        $namaFasilitas = ['WiFi', 'AC', 'Kipas Angin', 'Tempat Tidur', 'Lemari', 'Kamar Mandi Dalam', 'Dapur Bersama', 'Parkiran', 'Free Air', 'Free Listrik', 'CCTV', 'Laundry Terdekat', 'Khusus Putri', 'Dekat Kampus', 'Ruang Tamu', 'Balkon', 'Cleaning Service', 'Akses 24 Jam'];
        foreach ($namaFasilitas as $nama) {
            Fasilitas::firstOrCreate(['nama_fasilitas' => $nama]);
        }
        $facilityIds = Fasilitas::pluck('id', 'nama_fasilitas');

        $frontPhotos = [
            'images/front-1.jpg','images/front-2.jpg','images/front-3.jpg','images/front-4.jpg',
            'images/front-5.jpg','images/front-6.jpg','images/front-7.jpg','images/front-8.jpg',
            'images/front-9.jpg','images/front-10.jpg','images/front-11.jpg','images/front-12.jpg',
        ];
        $roomPhotos = [
            'images/gallery/room-1.jpg','images/gallery/room-2.jpg','images/gallery/room-3.jpg','images/gallery/room-4.jpg',
            'images/gallery/room-5.jpg','images/gallery/room-6.jpg','images/gallery/room-7.jpg','images/gallery/room-8.jpg',
            'images/gallery/room-9.jpg','images/gallery/room-10.jpg','images/gallery/room-11.jpg','images/gallery/room-12.jpg',
        ];
        $kitchenPhotos = [
            'images/gallery/kitchen-1.jpg','images/gallery/kitchen-2.jpg','images/gallery/kitchen-3.jpg','images/gallery/kitchen-4.jpg',
            'images/gallery/kitchen-5.jpg','images/gallery/kitchen-6.jpg','images/gallery/kitchen-7.jpg','images/gallery/kitchen-8.jpg',
            'images/gallery/kitchen-9.jpg','images/gallery/kitchen-10.jpg','images/gallery/kitchen-11.jpg','images/gallery/kitchen-12.jpg',
        ];
        $parkingPhotos = [
            'images/gallery/parking-1.jpg','images/gallery/parking-2.jpg','images/gallery/parking-3.jpg','images/gallery/parking-4.jpg',
            'images/gallery/parking-5.jpg','images/gallery/parking-6.jpg','images/gallery/parking-7.jpg','images/gallery/parking-8.jpg',
            'images/gallery/parking-9.jpg','images/gallery/parking-10.jpg','images/gallery/parking-11.jpg','images/gallery/parking-12.jpg',
        ];

        // Data utama dibuat realistis dan ringkas agar alur website mudah dijelaskan saat UAS.
        // Setiap kos memakai foto cover yang berbeda, lalu memiliki foto kamar, dapur, dan parkiran tersendiri.
        $dataKos = [
            ['Kos Mawar Modern', 'campur', 'Tondo', 'Jl. Soekarno Hatta, Tondo, Palu', 'Kos dua lantai dengan parkiran motor luas, akses dekat kampus, WiFi stabil, dan lingkungan tenang untuk mahasiswa maupun pekerja.', 950000, '6281234567890', $frontPhotos[0], 'tersedia', true, 'approved', 1.2, ['WiFi', 'Kipas Angin', 'Tempat Tidur', 'Lemari', 'Free Air', 'Dekat Kampus', 'Parkiran'], [['Standar Kipas', 950000, 4, 'tersedia', 'Kamar standar dengan ventilasi baik.']]],
            ['Kos Melati Putri Residence', 'putri', 'Palu Timur', 'Jl. Tombolotutu, Palu Timur', 'Kos khusus putri dengan CCTV, gerbang aman, kamar rapi, dan suasana nyaman untuk mahasiswi serta pekerja perempuan.', 1000000, '6281234567891', $frontPhotos[1], 'tersedia', true, 'approved', 1.8, ['WiFi', 'Kamar Mandi Dalam', 'Lemari', 'Tempat Tidur', 'Khusus Putri', 'CCTV', 'Akses 24 Jam'], [['Khusus Putri', 1000000, 3, 'tersedia', 'Kamar putri dengan fasilitas lengkap.']]],
            ['Kos Anggrek Hemat', 'campur', 'Palu Selatan', 'Jl. Zebra, Palu Selatan', 'Kos ekonomis dengan akses jalan mudah, cocok untuk mahasiswa yang mencari kamar nyaman dengan harga bersahabat.', 650000, '6281234567892', $frontPhotos[2], 'tersedia', false, 'approved', 2.5, ['Kipas Angin', 'Tempat Tidur', 'Lemari', 'Laundry Terdekat', 'Free Air'], [['Ekonomis', 650000, 5, 'tersedia', 'Kamar sederhana, bersih, dan terjangkau.']]],
            ['Kos Cendana Premium', 'campur', 'Pusat Kota', 'Jl. Veteran, Palu', 'Kos premium dengan fasad modern, area parkir aman, kamar AC, kamar mandi dalam, dan akses dekat pusat kota.', 1350000, '6281234567893', $frontPhotos[3], 'tersedia', true, 'approved', 3.1, ['WiFi', 'AC', 'Kamar Mandi Dalam', 'Parkiran', 'CCTV', 'Cleaning Service', 'Akses 24 Jam'], [['AC Deluxe', 1350000, 2, 'tersedia', 'Kamar luas dengan AC dan kamar mandi dalam.']]],
            ['Kos Sakura Putri', 'putri', 'Palu Barat', 'Jl. Diponegoro, Palu Barat', 'Kos putri yang rapi, tenang, memiliki area parkir motor tertutup, dan dapur bersama untuk penghuni.', 900000, '6281234567894', $frontPhotos[4], 'penuh', false, 'approved', 2.2, ['WiFi', 'Kipas Angin', 'Parkiran', 'Dapur Bersama', 'Khusus Putri', 'Ruang Tamu'], [['Putri Hemat', 900000, 0, 'penuh', 'Kamar sedang penuh, waiting list tersedia.']]],
            ['Kos Kampus Tadulako', 'putra', 'Kampus Untad', 'Jl. Cendana, sekitar Kampus Tadulako, Palu', 'Kos putra dekat kampus dengan akses cepat ke fotokopi, warung makan, dan transportasi umum.', 750000, '6281234567895', $frontPhotos[5], 'tersedia', false, 'approved', 0.7, ['WiFi', 'Kipas Angin', 'Tempat Tidur', 'Dapur Bersama', 'Dekat Kampus', 'Parkiran'], [['Standar Dekat Kampus', 750000, 6, 'tersedia', 'Pilihan hemat dekat kampus.']]],
            ['Kos Joglo Harmoni', 'campur', 'Besusu', 'Jl. Setia Budi, Besusu, Palu', 'Kos dengan halaman nyaman, area santai, dan suasana hangat untuk penghuni yang ingin tinggal di area kota.', 850000, '6281234567896', $frontPhotos[6], 'tersedia', false, 'approved', 2.9, ['WiFi', 'Kipas Angin', 'Ruang Tamu', 'Dapur Bersama', 'Parkiran'], [['Joglo Standar', 850000, 3, 'tersedia', 'Kamar dengan area bersama yang nyaman.']]],
            ['Kos Green Yard', 'putra', 'Palu Utara', 'Jl. Trans Sulawesi, Palu Utara', 'Kos dengan halaman luas, parkiran mobil dan motor, cocok untuk pekerja yang mencari tempat tinggal tenang.', 1100000, '6281234567897', $frontPhotos[7], 'tersedia', true, 'approved', 4.0, ['WiFi', 'AC', 'Parkiran', 'Dapur Bersama', 'CCTV', 'Free Air'], [['Green AC', 1100000, 2, 'tersedia', 'Kamar AC dengan halaman luas.']]],
        ];

        foreach ($dataKos as $i => [$nama, $tipe, $area, $alamat, $deskripsi, $harga, $wa, $foto, $status, $premium, $verifikasi, $jarak, $fasilitas, $kamars]) {
            $owner = [$pemilik, $pemilik2, $pemilik3][$i % 3];
            $kos = Kos::create([
                'user_id' => $owner->id,
                'nama_kos' => $nama,
                'tipe_kos' => $tipe,
                'lokasi_area' => $area,
                'alamat' => $alamat,
                'deskripsi' => $deskripsi,
                'harga' => $harga,
                'no_wa' => $wa,
                'foto' => $foto,
                'status' => $status,
                'premium' => $premium,
                'verification_status' => $verifikasi,
                'jarak_kampus' => $jarak,
            ]);

            $kos->fasilitas()->sync(collect($fasilitas)->map(fn ($f) => $facilityIds[$f])->toArray());

            foreach ($kamars as [$tipeKamar, $hargaKamar, $jumlah, $statusKamar, $catatan]) {
                Kamar::create([
                    'kos_id' => $kos->id,
                    'tipe_kamar' => $tipeKamar,
                    'kode_kamar' => 'K-' . str_pad((string) ($i + 1), 2, '0', STR_PAD_LEFT),
                    'lantai' => ($i % 3) + 1,
                    'luas_kamar' => 12 + ($i % 5),
                    'harga' => $hargaKamar,
                    'harga_harian' => max(50000, (int) round($hargaKamar / 20)),
                    'harga_tahunan' => $hargaKamar * 11,
                    'jumlah_kamar' => $jumlah,
                    'status' => $statusKamar,
                    'catatan' => $catatan,
                    'foto' => $roomPhotos[$i % count($roomPhotos)],
                ]);
            }

            $kos->fotos()->create(['path' => $roomPhotos[$i % count($roomPhotos)], 'jenis' => 'kamar', 'caption' => 'Foto Kamar', 'urutan' => 2]);
            $kos->fotos()->create(['path' => $kitchenPhotos[$i % count($kitchenPhotos)], 'jenis' => 'dapur', 'caption' => 'Foto Dapur', 'urutan' => 3]);
            $kos->fotos()->create(['path' => $parkingPhotos[$i % count($parkingPhotos)], 'jenis' => 'parkiran', 'caption' => 'Foto Parkiran', 'urutan' => 4]);

            if ($verifikasi === 'approved') {
                Review::create(['user_id' => $user->id, 'kos_id' => $kos->id, 'rating' => rand(4, 5), 'komentar' => 'Lokasinya strategis dan informasi kosnya cukup jelas.']);
                if ($i % 2 === 0) {
                    Review::create(['user_id' => $user2->id, 'kos_id' => $kos->id, 'rating' => rand(3, 5), 'komentar' => 'Foto dan fasilitas sesuai kebutuhan, pemilik juga mudah dihubungi.']);
                }
            }
        }

        $firstKos = Kos::where('verification_status', 'approved')->first();
        $secondKos = Kos::where('verification_status', 'approved')->skip(1)->first();
        $bookingPending = Booking::create(['user_id' => $user->id, 'kos_id' => $firstKos->id, 'kamar_id' => $firstKos->kamar()->first()->id, 'nama_pemesan' => $user->name, 'no_wa' => '6281111111111', 'tanggal_masuk' => now()->addDays(10), 'catatan' => 'Ingin survei kamar akhir pekan.', 'status' => 'pending']);
        $bookingApproved = Booking::create(['user_id' => $user2->id, 'kos_id' => $secondKos->id, 'kamar_id' => $secondKos->kamar()->first()->id, 'nama_pemesan' => $user2->name, 'no_wa' => '6282222222222', 'tanggal_masuk' => now()->addDays(20), 'catatan' => 'Butuh kamar dekat kampus.', 'status' => 'approved']);

        Payment::create(['booking_id' => $bookingApproved->id, 'user_id' => $bookingApproved->user_id, 'kos_id' => $bookingApproved->kos_id, 'nominal' => $bookingApproved->kamar->harga, 'metode' => 'Transfer Bank', 'status' => 'valid', 'jatuh_tempo' => now()->addDays(7), 'paid_at' => now()->subDay(), 'catatan' => 'Pembayaran telah dikonfirmasi.']);
        Payment::create(['booking_id' => $bookingPending->id, 'user_id' => $bookingPending->user_id, 'kos_id' => $bookingPending->kos_id, 'nominal' => $bookingPending->kamar->harga, 'metode' => 'Transfer Bank', 'status' => 'unpaid', 'jatuh_tempo' => now()->addDays(7), 'catatan' => 'Menunggu persetujuan booking.']);
    }
}
