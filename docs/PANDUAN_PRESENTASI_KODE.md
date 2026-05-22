# Panduan Presentasi Kode KosKu

Gunakan urutan ini agar penjelasan kode singkat, rapi, dan mudah dipahami.

## 1. Route
File: `routes/web.php`

Jelaskan bahwa route dibagi menjadi halaman publik, autentikasi, dashboard, kos, booking, pembayaran, dan review.

## 2. Controller utama
Folder: `app/Http/Controllers`

Fokus pada:
- `KosController` untuk daftar kos, detail kos, tambah/edit kos, upload foto, dan verifikasi.
- `KamarController` untuk unit kamar milik kos.
- `BookingController` untuk pengajuan sewa dan perubahan status booking.
- `PaymentController` untuk upload bukti dan validasi pembayaran.
- `DashboardController` untuk ringkasan admin, pemilik, dan user.

## 3. Model dan relasi
Folder: `app/Models`

Relasi utama:
- `User` memiliki banyak `Kos`, `Booking`, `Payment`, dan `Review`.
- `Kos` memiliki banyak `Kamar`, `Booking`, `Review`, dan `KosFoto`.
- `Booking` terhubung ke `User`, `Kos`, `Kamar`, dan `Payment`.
- `Payment` terhubung ke `Booking`, `User`, dan `Kos`.

## 4. Database
Folder: `database/migrations`

Jelaskan tabel inti:
`users`, `profile_pemiliks`, `kos`, `kamars`, `fasilitas`, `fasilitas_kos`, `kos_fotos`, `bookings`, `payments`, dan `reviews`.

## 5. Seeder
File: `database/seeders/DatabaseSeeder.php`

Seeder membuat akun uji, data kos realistis, fasilitas, kamar, booking, pembayaran, dan review.

## 6. View Blade
Folder: `resources/views`

Fokus pada:
- `home.blade.php` untuk landing page.
- `kos/index.blade.php` untuk pencarian dan filter kos.
- `kos/show.blade.php` untuk detail kos, maps, kamar, booking, dan review.
- `dashboard/index.blade.php` untuk dashboard sesuai role.
- `dashboard/admin-page.blade.php` untuk halaman manajemen admin/pemilik.

## Alur presentasi singkat
Pemilik tambah kos dan kamar → user mencari kos → user booking → pemilik/admin menerima booking → user upload bukti pembayaran → pembayaran divalidasi → user memberi review.
