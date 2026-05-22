# KosKu - Sistem Informasi Kos

KosKu adalah aplikasi web informasi kos berbasis Laravel untuk membantu pencari kos menemukan kos berdasarkan lokasi, tipe, budget, fasilitas, foto, maps, dan ketersediaan kamar. Sistem ini juga menyediakan alur booking, pembayaran, dan review. Pemilik kos dapat mengelola kos dan kamar, sedangkan admin memantau data utama sistem.

## Fitur Utama

- Autentikasi multi-role: **Admin**, **Pemilik Kos**, dan **Pencari Kos**.
- Halaman publik: Home, Cari Kos, Detail Kos, Maps, dan Kontak.
- Manajemen kos dan kamar oleh pemilik/admin.
- Galeri foto kos dengan cover, kamar, dapur, dan parkiran.
- Filter kos berdasarkan nama/lokasi, area, tipe, harga, status, dan fasilitas.
- Booking kamar oleh pencari kos.
- Validasi booking oleh pemilik/admin.
- Upload bukti pembayaran oleh user.
- Validasi pembayaran oleh pemilik/admin.
- Review dan rating kos.
- Dashboard admin dan pemilik yang fokus ke data utama.

## Teknologi

- Laravel
- Blade Template
- MySQL
- Eloquent ORM
- Middleware Role
- Vite
- Bootstrap Icons
- Custom CSS

## Akun Demo

| Role | Email | Password |
|---|---|---|
| Admin | `admin@kosku.test` | `password` |
| Pemilik Kos | `pemilik@kosku.test` | `password` |
| User / Pencari Kos | `user@kosku.test` | `password` |

## Cara Menjalankan

Pastikan XAMPP/Laragon sudah aktif dan database `kosku_db` sudah dibuat.

```bash
cd C:\xampp\htdocs\KosKu_FINAL_UAS_PRO_EXPLAINABLE
composer install
npm.cmd install
copy .env.example .env
php artisan key:generate
php artisan migrate:fresh --seed
php artisan storage:link
php artisan optimize:clear
```

Terminal 1:

```bash
php artisan serve
```

Terminal 2:

```bash
npm.cmd run dev
```

Buka aplikasi di:

```text
http://127.0.0.1:8000
```

## Alur Sistem

1. Pemilik kos menambahkan data kos dan kamar.
2. Pencari kos melihat daftar dan detail kos.
3. Pencari kos melihat foto, fasilitas, harga, maps, dan kamar tersedia.
4. Pencari kos mengajukan booking kamar.
5. Pemilik/admin menerima atau menolak booking.
6. Setelah booking diterima, pencari kos mengunggah bukti pembayaran.
7. Pemilik/admin memvalidasi pembayaran.
8. Pencari kos dapat memberi review.

## Pembagian Role Kelompok 2

| Anggota | Role |
|---|---|
| Syifa | Front-end |
| Kethlien | Front-end |
| Ryandi | Back-end |
| Dawai | Back-end |
| Aris | Database |

## Fokus Presentasi Kode

Jangan menjelaskan semua folder Laravel. Fokus pada:

- `routes/web.php`
- `app/Http/Controllers`
- `app/Http/Middleware/RoleMiddleware.php`
- `app/Models`
- `database/migrations`
- `database/seeders/DatabaseSeeder.php`
- `resources/views`

Dokumen pendukung:

- `docs/PANDUAN_PRESENTASI_KELOMPOK.md`
- `docs/CHECKLIST_FINAL_UAS.md`
- `docs/ERD_KosKu.md`
- `docs/PPT_OUTLINE_UAS.md`


## Catatan Final UAS

Versi ini difokuskan pada alur inti yang mudah dijelaskan saat presentasi:

1. Pemilik kos menambahkan data kos dan kamar.
2. User mencari kos, membuka detail, melihat foto, fasilitas, harga, dan maps.
3. User mengajukan sewa kamar.
4. Pemilik atau admin menerima/menolak booking.
5. Setelah diterima, user mengunggah bukti pembayaran.
6. Pemilik atau admin memvalidasi pembayaran.
7. User dapat memberikan review setelah booking diterima.

Fitur tambahan yang tidak menjadi alur utama, seperti notifikasi dan export laporan, sudah dihapus agar kode lebih bersih dan mudah dijelaskan.
