# PesanMakan Express - Sistem Pemesanan Restoran (LSP Serkom)

Aplikasi web pemesanan makanan berbasis Laravel 11 yang dirancang khusus untuk memenuhi seluruh indikator kisi-kisi Sertifikasi Kompetensi (LSP Serkom) Rekayasa Perangkat Lunak.

---

## Spesifikasi & Fitur Utama

1. **Sisi Customer (Publik):**
   - Katalog menu terorganisir per kategori (Makanan, Minuman, Cemilan).
   - Quantity stepper interaktif (+/-) dan kalkulasi total estimasi belanja real-time.
   - Modal pop-up konfirmasi ringkasan pesanan sebelum checkout.
   - Eksekusi transaksi atomik via `DB::transaction` untuk integritas multi-tabel.

2. **Sisi Admin (Terproteksi Autentikasi Laravel Breeze):**
   - Dashboard monitoring pesanan masuk real-time dengan Eager Loading (`Order::with('orderDetails.food')`) anti N+1 query.
   - Perubahan status pesanan instan via method PATCH (Pending, Diproses, Selesai, Batal).
   - CRUD Master Makanan lengkap (Upload gambar, validasi format, auto-cleanup foto lama saat update/delete).

---

## Panduan Instalasi Langkah demi Langkah (Zero to Running)

### Langkah 1: Nyalakan Web Server & Database
1. Buka aplikasi **XAMPP Control Panel**.
2. Klik tombol **Start** pada modul **Apache** dan **MySQL**.

### Langkah 2: Setup Database & Environment
1. Salin `.env.example` ke `.env`:
   ```bash
   cp .env.example .env
   ```
2. Pastikan konfigurasi database di `.env`:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=pesanmakan
   DB_USERNAME=root
   DB_PASSWORD=
   ```

### Langkah 3: Eksekusi Migrasi & Seeding
```bash
php artisan key:generate
php artisan migrate:fresh --seed
php artisan storage:link
```

### Langkah 4: Jalankan Server Aplikasi
```bash
php artisan serve
```
Akses di browser:
- Halaman Customer: `http://127.0.0.1:8000/`
- Halaman Login Admin: `http://127.0.0.1:8000/login`
  - **Email:** `admin@gmail.com`
  - **Password:** `password123`
