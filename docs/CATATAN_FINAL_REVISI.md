# Catatan Final Revisi KosKu

Perubahan utama pada versi ini:

- Menghapus fitur notifikasi agar alur dan kode lebih sederhana.
- Menghapus route export CSV yang tidak menjadi fokus demo.
- Membersihkan komentar yang terdengar seperti catatan revisi/demo.
- Menjaga fokus alur: cari kos -> detail kos -> booking -> pembayaran -> review.
- Memastikan folder `storage/framework/views`, `cache`, dan `sessions` tersedia agar `php artisan optimize:clear` tidak gagal.

Bagian kode yang disarankan untuk dibuka saat UAS:

1. `routes/web.php`
2. `app/Http/Controllers/KosController.php`
3. `app/Http/Controllers/BookingController.php`
4. `app/Http/Controllers/PaymentController.php`
5. `app/Http/Middleware/RoleMiddleware.php`
6. `app/Models/Kos.php`, `Booking.php`, `Payment.php`, `User.php`
7. `database/migrations`
8. `database/seeders/DatabaseSeeder.php`
9. `resources/views/kos/show.blade.php`
10. `resources/views/dashboard/user-payments.blade.php`
