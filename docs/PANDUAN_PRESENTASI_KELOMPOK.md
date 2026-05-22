# Panduan Presentasi Kode KosKu - Kelompok 2

**Tema:** KosKu - Sistem Informasi Kos  
**Tujuan:** membantu pencari kos melihat informasi kos, maps, ketersediaan kamar, booking, pembayaran, dan review. Pemilik kos mengelola kos/kamar, sedangkan admin memantau seluruh data.

## Pembagian Role

| Anggota | Role | Bagian yang Dijelaskan | File Utama |
|---|---|---|---|
| Syifa | Front-end | Halaman publik, home, cari kos, detail kos, gallery, maps, navbar | `resources/views/home.blade.php`, `resources/views/kos/index.blade.php`, `resources/views/kos/show.blade.php`, `resources/views/components/navbar.blade.php` |
| Kethlien | Front-end | Dashboard, form, tabel, badge status, tampilan booking/pembayaran | `resources/views/dashboard/index.blade.php`, `resources/views/dashboard/admin-page.blade.php`, `resources/views/kos/_form.blade.php`, `resources/views/kamar/_form.blade.php` |
| Ryandi | Back-end | Route, controller kos/kamar, booking, validasi, pembatasan akses pemilik | `routes/web.php`, `app/Http/Controllers/KosController.php`, `app/Http/Controllers/KamarController.php`, `app/Http/Controllers/BookingController.php` |
| Dawai | Back-end | Login/register, middleware role, pembayaran, review, profile | `app/Http/Controllers/AuthController.php`, `app/Http/Middleware/RoleMiddleware.php`, `app/Http/Controllers/PaymentController.php`, `app/Http/Controllers/ReviewController.php`, `app/Http/Controllers/ProfileController.php` |
| Aris | Database | ERD, migration, seeder, model, relasi antar tabel | `database/migrations`, `database/seeders/DatabaseSeeder.php`, `app/Models` |

## Alur Sistem yang Dijelaskan

1. **Pemilik kos login** lalu menambahkan data kos dan kamar.
2. **User mencari kos** melalui halaman Cari Kos.
3. **User membuka detail kos** untuk melihat foto, fasilitas, harga, maps, dan kamar tersedia.
4. **User melakukan booking** kamar.
5. **Pemilik/Admin menerima atau menolak booking.**
6. Jika booking diterima, **user mengunggah bukti pembayaran.**
7. **Pemilik/Admin memvalidasi pembayaran.**
8. Setelah transaksi selesai, **user dapat memberi review.**

## Urutan Menjelaskan Kode

### 1. Route
Buka `routes/web.php`.

Jelaskan bahwa route dibagi menjadi:
- route publik: home, cari kos, detail kos, kontak;
- route guest: login dan register;
- route auth: profile, booking saya, pembayaran saya;
- route dashboard: booking, pembayaran, review, kelola kos dan kamar;
- route admin dan pemilik: panel sesuai role.

### 2. Controller
Buka controller sesuai alur:
- `KosController` untuk data kos, filter, detail kos, tambah/edit/hapus kos;
- `KamarController` untuk kamar pada kos tertentu;
- `BookingController` untuk pengajuan sewa;
- `PaymentController` untuk upload dan validasi bukti pembayaran;
- `AuthController` untuk login/register.

### 3. Model dan Relasi
Buka `app/Models`.

Relasi utama:
- `User` memiliki banyak booking dan bisa menjadi pemilik banyak kos;
- `Kos` memiliki banyak kamar, fasilitas, booking, foto, dan review;
- `Kamar` milik satu kos;
- `Booking` terhubung ke user, kos, kamar, dan payment;
- `Payment` milik booking;
- `Review` milik user dan kos.

### 4. Migration dan Seeder
Buka `database/migrations` dan `database/seeders/DatabaseSeeder.php`.

Jelaskan bahwa migration membentuk struktur tabel, sedangkan seeder mengisi data demo realistis:
- akun admin, pemilik, dan user;
- data kos dengan foto berbeda;
- kamar, fasilitas, booking, pembayaran, dan review.

### 5. View / Blade
Buka `resources/views`.

Jelaskan bahwa Blade digunakan untuk memisahkan layout, komponen, dan halaman:
- `layouts/app.blade.php` untuk halaman publik;
- `layouts/dashboard.blade.php` untuk admin/pemilik;
- `components/kos-card.blade.php` untuk card kos yang dipakai ulang;
- halaman `kos/index` dan `kos/show` untuk daftar dan detail kos.

## Bagian yang Tidak Perlu Dijelaskan Panjang

Jangan menjelaskan folder ini secara detail:
- `vendor` karena isinya dependency Composer;
- `node_modules` karena isinya dependency frontend;
- `bootstrap/cache` karena cache Laravel;
- `storage` karena penyimpanan file upload/cache.

## Jawaban Singkat Jika Ditanya

**Kenapa pakai role?**  
Agar admin, pemilik kos, dan pencari kos memiliki hak akses yang berbeda.

**Kenapa pakai middleware?**  
Untuk membatasi halaman tertentu agar hanya bisa diakses role yang sesuai.

**Kenapa pakai relasi model?**  
Agar data user, kos, kamar, booking, pembayaran, dan review saling terhubung secara rapi melalui Eloquent ORM.

**Kenapa maps ada di detail kos?**  
Karena lokasi adalah informasi penting bagi pencari kos, sehingga maps ditempatkan di halaman detail, bukan di semua card agar tampilan tetap ringan.
