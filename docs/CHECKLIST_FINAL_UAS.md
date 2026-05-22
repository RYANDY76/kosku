# Checklist Final UAS KosKu

## Sebelum Demo

- [ ] XAMPP/Laragon aktif.
- [ ] Database `kosku_db` sudah dibuat.
- [ ] `php artisan migrate:fresh --seed` berhasil.
- [ ] `php artisan serve` berjalan.
- [ ] `npm.cmd run dev` berjalan.
- [ ] Browser membuka `http://127.0.0.1:8000`.
- [ ] Login admin, pemilik, dan user berhasil.

## Akun Demo

| Role | Email | Password |
|---|---|---|
| Admin | `admin@kosku.test` | `password` |
| Pemilik Kos | `pemilik@kosku.test` | `password` |
| User | `user@kosku.test` | `password` |

## Flow Demo Singkat

1. Tampilkan home dan halaman Cari Kos.
2. Buka detail kos, tunjukkan foto, fasilitas, harga, maps, dan tombol booking.
3. Login sebagai user dan lakukan booking.
4. Login sebagai pemilik kos, buka Booking Masuk, lalu terima booking.
5. Login sebagai user, buka Pembayaran Saya, lalu upload bukti.
6. Login sebagai pemilik/admin, validasi pembayaran.
7. Tunjukkan review dan dashboard.

## File Kode yang Dibuka Saat Ditanya

- `routes/web.php`
- `app/Http/Controllers/KosController.php`
- `app/Http/Controllers/BookingController.php`
- `app/Http/Controllers/PaymentController.php`
- `app/Http/Middleware/RoleMiddleware.php`
- `app/Models/Kos.php`
- `app/Models/Booking.php`
- `database/migrations`
- `database/seeders/DatabaseSeeder.php`
- `resources/views/components/kos-card.blade.php`
