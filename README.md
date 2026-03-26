# 🍳 Dapurku Dashboard

Sistem manajemen keuangan dan investor berbasis web yang dibangun dengan Laravel 12 dan Filament v5 untuk efisiensi operasional maksimal.

## 🚀 Tech Stack

- **Framework**: [Laravel ^12.0 (Dev)](https://laravel.com)
- **Admin Panel**: [Filament v5.3.5](https://filamentphp.com)
- **Programming Language**: PHP 8.2+
- **Database**: MySQL / MariaDB (Mendukung SQLite)
- **UI Components**: Blade, Livewire, Tailwind CSS, Heroicons
- **Custom Middleware**: LogRequestPerformance (Query & Request Timing)

## ✨ Fitur Utama

- **Panel Admin Terintegrasi**: Manajemen data cepat menggunakan Filament v5.
- **Manajemen Investor (User)**: Implementasi **Soft Deletes** untuk menjaga integritas data riwayat transaksi.
- **Laporan Bulanan Dinamis**: Penarikan data laporan berbasis **SQL View** (`monthly_reports_view`) untuk performa tinggi.
- **Autentikasi Kustom**: Login kustom dengan **Modal Notifikasi** interaktif untuk penanganan error.
- **Pemantauan Performa**: Logging otomatis untuk melacak query lambat (>500ms) dan request lama secara real-time.
- **Pengaturan Profil**: Update data akun secara terpisah melalui **Modal Actions** (Username, Email, Password).

## 🛠️ Persiapan Lingkungan (Prerequisites)

Pastikan Anda memiliki:

- PHP >= 8.2
- Composer
- Database (MySQL/SQLite)

## 🏁 Panduan Instalasi (Getting Started)

Ikuti langkah-langkah berikut untuk menjalankan project di lokal:

1. **Clone Repository**

    ```bash
    git clone [url-repo-anda]
    cd dashboard-dapur
    ```

2. **Instal Dependensi PHP**

    ```bash
    composer install
    ```

3. **Konfigurasi Environment**

    ```bash
    cp .env.example .env
    php artisan key:generate
    ```

    _Sesuaikan konfigurasi database (`DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`) di file `.env`._

4. **Migrasi & Seed Database**

    ```bash
    php artisan migrate --seed
    ```

5. **Jalankan Aplikasi**
    ```bash
    php artisan serve
    ```

### 🔑 Akun Default (Seeder)

- **Admin**: `admin` / `admin@admin.com` / `password`
- **User**: `shifyannn` / `asifyan@gmail.com` / `password`

## 📁 Struktur Proyek (Filament v5 Pattern)

Project ini mengikuti arsitektur modular Filament v5:

- `app/Filament/Admin/Resources/`: Setiap resource memiliki folder sendiri dengan sub-folder `Schemas/` (Form) dan `Tables/` (Action/Filter).
- `app/Http/Middleware/LogRequestPerformance.php`: Middleware untuk tracking performa aplikasi.
- `app/Providers/Filament/AdminPanelProvider.php`: Pusat konfigurasi navigasi, tema warna (`Amber`), dan brand ("Dapurku").

_Dokumentasi ini dibuat untuk memudahkan proses onboarding dan pemeliharaan tim pengembang Dapurku. Project ini sepenuhnya berbasis PHP (tanpa build step NPM)._
