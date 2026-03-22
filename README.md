# 🍳 Dapurku Dashboard

Sistem manajemen keuangan dan investor berbasis web yang dibangun dengan teknologi paling mutakhir.

## 🚀 Tech Stack

- **Framework**: [Laravel 12](https://laravel.com)
- **Admin Panel**: [Filament v5.x](https://filamentphp.com) (Bleeding Edge)
- **Database**: MySQL / MariaDB
- **UI Components**: Blade, Tailwind CSS, Heroicons

## 📁 Struktur Proyek (Filament v5)

Proyek ini mengikuti arsitektur modular Filament v5 untuk pemisahan logika yang bersih:

### 1. Konfigurasi Panel (`app/Providers/Filament/`)

- **`AdminPanelProvider.php`**: Pusat pengaturan navigasi, tema warna (`Amber`), brand name (`Dapurku`), dan registrasi otomatis resource.

### 2. Sumber Daya & Modul (`app/Filament/Admin/Resources/`)

Setiap modul (User, Category, Laporan) diatur dalam folder kustom:

- **`Schemas/`**: Tempat mendefinisikan struktur **Form** (Input data).
- **`Tables/`**: Tempat mendefinisikan struktur **Table** (List data, Filter, Actions).
- **`Pages/`**: Logika halaman khusus untuk resource tersebut.

### 3. Fitur Utama & Kustomisasi

#### 🔐 Autentikasi (`app/Filament/Auth/Login.php`)

- Menggunakan class `Login` kustom yang di-override untuk menampilkan **Modal Notifikasi** jika login gagal (Username/Password salah), lengkap dengan fitur _auto-close_.

#### 👥 Manajemen Investor (User)

- Implementasi **Soft Deletes**: User yang dihapus tidak hilang dari database (hanya ditandai `deleted_at`), sehingga riwayat transaksi tetap aman.
- Filter khusus untuk menampilkan data aktif atau data yang sudah dihapus.

#### 📊 Laporan Keuangan (MonthlyReport)

- Data ditarik dari **SQL View** agar perhitungan performa keuangan tetap cepat dan dinamis.
- Relasi `User` diperbarui menggunakan `withTrashed()` agar tetap menampilkan nama investor meskipun akunnya sudah dinonaktifkan.

#### ⚙️ Pengaturan Profil (`app/Filament/Admin/Pages/Setting.php`)

- Halaman mandiri untuk mengubah data akun.
- Menggunakan pola **Modal Actions**: Username, Email, dan Password diubah secara terpisah melalui pop-up modal untuk keamanan dan UI yang lebih bersih.
- Tampilan kustom di: `resources/views/filament/admin/pages/setting.blade.php`.

## 🛠️ Panduan Pengembangan

| Tujuan                     | Lokasi Modifikasi                               |
| :------------------------- | :---------------------------------------------- |
| **Menambah Field Form**    | `app/Filament/Admin/Resources/[Nama]/Schemas/`  |
| **Mengubah Kolom Tabel**   | `app/Filament/Admin/Resources/[Nama]/Tables/`   |
| **Mengubah Ikon/Warna**    | `app/Providers/Filament/AdminPanelProvider.php` |
| **Kustomisasi View Blade** | `resources/views/filament/`                     |
| **Logika Database**        | `app/Models/`                                   |

---

_Dokumentasi ini dibuat secara dinamis untuk membantu pemeliharaan proyek Dapurku._
