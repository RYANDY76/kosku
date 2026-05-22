# Outline PPT UAS KosKu

1. Judul Project
   - KosKu: Marketplace Kos Palu
   - Nama anggota dan role

2. Latar Belakang
   - Mahasiswa/pekerja sering kesulitan mencari kos sesuai lokasi, fasilitas, dan harga.
   - Pemilik kos butuh media untuk mengelola kamar, booking, dan pembayaran.

3. Tujuan Sistem
   - Membantu pencari kos menemukan kamar yang sesuai.
   - Membantu pemilik mengelola kos dan booking.
   - Membantu admin melakukan verifikasi data dan monitoring transaksi.

4. Role Pengguna
   - Admin: verifikasi kos, kelola user, fasilitas, booking, pembayaran.
   - Pemilik Kos: kelola kos/kamar, lihat booking, validasi pembayaran.
   - Pencari Kos: cari kos, favorit, booking, upload bukti, review.

5. Fitur Utama
   - Login/register multi-role
   - Dashboard sesuai role
   - CRUD kos dan kamar
   - Fasilitas dan galeri foto
   - Booking dan pembayaran
   - Review pengguna
   - Verifikasi kos oleh admin

6. Implementasi Laravel
   - Routing dan resource route
   - Controller CRUD
   - Blade layout/component
   - Migration, model, relasi Eloquent
   - Seeder data awal
   - Middleware role
   - Validasi form dan upload file

7. ERD dan Struktur Database
   - Screenshot ERD dari `docs/ERD_KosKu.md`

8. Pembagian Tugas Anggota
   - Frontend Developer: layout, halaman publik, login/register, dashboard UI.
   - Backend Developer: controller, route, auth, middleware, booking/payment.
   - Database Administrator: migration, model, seeder, ERD, relasi.

9. Alur Presentasi Sistem
   - Login admin -> verifikasi kos -> pantau pembayaran.
   - Login pemilik -> tambah kos/kamar -> cek booking.
   - Login user -> cari kos -> booking -> upload bukti -> review.

10. Penutup
   - Kesimpulan manfaat sistem.
   - Rencana pengembangan: chat pemilik, peta lokasi, reminder pembayaran.
