<div align="center">
  <img src="public/images/logo-icon.png" alt="FutsalHub Logo Icon" width="100">
  <br>
  <img src="public/images/logo-text.png" alt="FutsalHub Logo Text" width="220">
  
  <p align="center">
    <strong>Platform Booking Lapangan Futsal Real-Time & Management System</strong>
  </p>

  <p align="center">
    <a href="#-fitur-utama">Fitur Utama</a> •
    <a href="#-tech-stack">Tech Stack</a> •
    <a href="#-instalasi--penggunaan">Instalasi</a> •
    <a href="#-struktur-peran-rbac">Sistem RBAC</a> •
    <a href="#-lisensi">Lisensi</a>
  </p>
</div>

---

## 📌 Tentang Proyek

**FutsalHub** adalah platform manajemen dan pemesanan lapangan futsal berbasis web yang dirancang untuk memberikan kemudahan reservasi lapangan secara *real-time* bagi pelanggan, sekaligus menyediakan sistem manajemen terpadu bagi pemilik dan pengelola Gelanggang Olahraga (GOR).

Sistem ini mendukung multi-GOR dengan kontrol akses berbasis peran (**Role-Based Access Control / RBAC**), validasi slot waktu yang ketat berdasarkan Zona Waktu Indonesia Barat (WIB / `Asia/Jakarta`), serta antarmuka yang responsif untuk perangkat desktop maupun mobile.

---

## ✨ Fitur Utama

### ⚽ 1. Modul Publik (Pelanggan / Anonymous)
- **Reservasi Lapangan Real-Time**: Memilih GOR, lapangan, tanggal, serta slot jam yang tersedia secara *real-time*.
- **Validasi Slot Waktu WIB**: Otomatis menonaktifkan slot jam yang sudah berlalu (*expired*) pada hari yang sama berdasarkan zona waktu `Asia/Jakarta`.
- **Dukungan Pembayaran**: Opsi pembayaran melalui Transfer Bank (dengan pengunggahan bukti pembayaran) atau Pembayaran di Tempat (*Pay at Venue*).
- **Cek Status Booking**: Pelanggan dapat mengecek status reservasi dan mengunggah ulang bukti transfer menggunakan **Kode Booking** unik (misal: `FTS-XXXXX`).

### 🛡️ 2. Modul Admin & Kasir (RBAC)
- **Quick Check-In Kasir**: Fitur verifikasi cepat di tempat menggunakan input/scanner Kode Booking untuk penyelesaian transaksi instan.
- **Dashboard Analitik**: Grafikal tren pendapatan 30 hari terakhir dan visualisasi jam sibuk pemesanan (*Peak Hours*) menggunakan **Chart.js**.
- **Manajemen Booking (CRUD & Approval)**: Fitur konfirmasi pembayaran transfer, verifikasi bukti TF, serta koreksi status pembayaran & pemesanan.
- **Manajemen Lapangan & GOR**: Pengaturan harga sewa per jam, tipe lantai (Vinyl, Rumput Sintetis, Interlock, dll.), serta status operasional lapangan/GOR.
- **Manajemen User Staff**: Penugasan akun staff (Super Admin, Admin GOR, Kasir) lengkap dengan relasi penempatan lokasi GOR.
- **Layout Responsif & Modular**: Tampilan admin dilengkapi *drawer sidebar* mobile dan pembungkus tabel terisolasi (*scroll horizontal*) yang stabil di semua ukuran layar.

---

## 🛠️ Tech Stack

- **Framework Back-End**: [Laravel 11](https://laravel.com/) (PHP 8.2+)
- **Front-End & Styling**: [Tailwind CSS](https://tailwindcss.com/)
- **Interaktivitas UI**: [Alpine.js](https://alpinejs.dev/)
- **Role & Access Control**: [Spatie Laravel-Permission](https://spatie.be/docs/laravel-permission)
- **Visualisasi Data**: [Chart.js](https://www.chartjs.org/)
- **Database**: MySQL / PostgreSQL
- **Build Tool**: [Vite](https://vitejs.dev/)

---

## 👥 Struktur Peran (RBAC)

Aplikasi ini mengimplementasikan 3 tingkat hak akses utama:

| Peran (Role) | Hak Akses & Cakupan Operasional |
| :--- | :--- |
| **Super Admin** | Akses penuh ke seluruh sistem: Kelola GOR (Global), Kelola Lapangan, Kelola Booking, Dashboard Analitik Global, dan Manajemen User/RBAC. |
| **Admin GOR** | Akses khusus GOR tertentu: Kelola Lapangan di GOR penugasan, Kelola & Approval Booking, Quick Check-In, dan Dashboard Analitik spesifik. |
| **Kasir** | Akses operasional lapangan: Kelola Booking harian dan Quick Check-In Kasir di lokasi GOR penugasan. |

---

## 🚀 Instalasi & Penggunaan

Ikuti langkah-langkah berikut untuk menjalankan proyek FutsalHub di lingkungan lokal Anda:

### 1. Clone Repositori
```bash
git clone https://github.com/username/futsalhub.git
cd futsalhub
```

### 2. Instal Dependensi Composer & NPM
```bash
composer install
npm install
```

### 3. Konfigurasi Environment (`.env`)
Salin file `.env.example` menjadi `.env`:
```bash
cp .env.example .env
```

Buka file `.env` dan atur konfigurasi database serta zona waktu:
```env
APP_NAME=FutsalHub
APP_TIMEZONE=Asia/Jakarta

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=futsalhub_db
DB_USERNAME=root
DB_PASSWORD=
```

### 4. Generate Key & Link Storage
```bash
php artisan key:generate
php artisan storage:link
```

### 5. Jalankan Migrasi & Seeder Database
Menjalankan migrasi database beserta data awal (Role Spatie, data GOR, Lapangan, dan Akun Demo):
```bash
php artisan migrate:fresh --seed
```

### 6. Jalankan Server Pengembang
Buka dua terminal dan jalankan perintah berikut:

**Terminal 1 (Laravel Server):**
```bash
php artisan serve
```

**Terminal 2 (Vite Assets Compiler):**
```bash
npm run dev
```

Aplikasi sekarang dapat diakses melalui browser di `http://127.0.0.1:8000`.

---

## 🔑 Akun Demo (Default Seeder)

Setelah menjalankan `php artisan db:seed`, Anda dapat menguji portal login staff (`http://127.0.0.1:8000/login`) menggunakan akun berikut:

- **Super Admin**: `admin@futsalhub.id` | Password: `password123`
- **Admin GOR**: `admin.cfc@futsalhub.id` | Password: `password123`
- **Kasir**: `kasir.cfc@futsalhub.id` | Password: `password123`

---

## 📂 Struktur Komponen Blade

```text
resources/views/
├── auth/
│   └── login.blade.php              # Halaman Login Staff
├── components/
│   ├── dashboard/
│   │   ├── charts-container.blade.php  # Komponen Chart Tren & Peak Hours
│   │   └── metric-cards.blade.php      # Komponen Kartu Ringkasan Metrik
│   ├── layouts/
│   │   ├── topbars/
│   │   │   └── admin-header.blade.php  # Komponen Topbar Admin
│   │   ├── admin.blade.php          # Layout Master Admin
│   │   └── app.blade.php            # Layout Master Publik
│   ├── logo.blade.php               # Komponen Modular Logo (Horizontal/Stacked)
│   ├── sidebar.blade.php            # Komponen Sidebar Navigasi & Profil
│   └── status-badge.blade.php       # Komponen Badge Status Transaksi
└── admin/
    ├── bookings/index.blade.php     # Manajemen & Verifikasi Booking
    ├── fields/index.blade.php       # Manajemen Lapangan
    ├── gors/index.blade.php         # Manajemen GOR
    ├── users/index.blade.php        # Manajemen Pengguna & RBAC
    └── dashboard.blade.php          # Dashboard Admin Utama
```

---

## 📝 Lisensi

Proyek ini dirilis di bawah [Lisensi MIT](LICENSE).