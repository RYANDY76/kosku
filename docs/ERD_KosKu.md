# ERD KosKu

Relasi utama sistem KosKu:

```mermaid
erDiagram
    users ||--o{ kos : memiliki
    users ||--o{ bookings : membuat
    users ||--o{ payments : membayar
    users ||--o{ reviews : memberi
    users ||--o| profile_pemiliks : punya

    kos ||--o{ kamars : memiliki
    kos ||--o{ kos_fotos : punya
    kos ||--o{ bookings : menerima
    kos ||--o{ reviews : mendapat
    kos ||--o{ payments : terkait
    kos }o--o{ fasilitas : fasilitas_kos

    kamars ||--o{ bookings : dipilih
    bookings ||--o| payments : menghasilkan

    users {
        bigint id PK
        string name
        string email
        string password
        enum role "admin, pemilik, user"
    }

    profile_pemiliks {
        bigint id PK
        bigint user_id FK
        string no_wa
        text alamat
    }

    kos {
        bigint id PK
        bigint user_id FK
        string nama_kos
        string tipe_kos
        text alamat
        integer harga
        enum status "tersedia, penuh"
        enum verification_status "pending, approved, rejected"
        boolean premium
    }

    kamars {
        bigint id PK
        bigint kos_id FK
        string tipe_kamar
        string kode_kamar
        integer harga
        integer jumlah_kamar
        enum status "tersedia, penuh"
    }

    fasilitas {
        bigint id PK
        string nama_fasilitas
    }

    fasilitas_kos {
        bigint kos_id FK
        bigint fasilitas_id FK
    }

    bookings {
        bigint id PK
        bigint user_id FK
        bigint kos_id FK
        bigint kamar_id FK
        string nama_pemesan
        string no_wa
        date tanggal_masuk
        enum status "pending, approved, rejected, cancelled"
    }

    payments {
        bigint id PK
        bigint booking_id FK
        bigint user_id FK
        bigint kos_id FK
        integer nominal
        string metode
        enum status "unpaid, pending, valid, rejected"
        string bukti
    }

    reviews {
        bigint id PK
        bigint user_id FK
        bigint kos_id FK
        integer rating
        text komentar
    }
```

## Catatan relasi

- `users.role` membedakan admin, pemilik kos, dan pencari kos.
- Pemilik kos memiliki banyak data `kos`, sedangkan user pencari kos dapat membuat `bookings`, `reviews`, dan `payments`.
- `kos` memiliki banyak `kamars`, foto tambahan, review, dan booking.
- Relasi many-to-many antara `kos` dan `fasilitas` disimpan pada tabel pivot `fasilitas_kos`.
- `payments` dibuat dari `bookings` yang sudah diterima.
