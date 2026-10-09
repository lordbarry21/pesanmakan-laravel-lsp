# ANALISIS CRITICAL BUG, TRAP & PERBAIKAN HUMANISASI KODE (MODUL 1-4 LSP SERKOM)

Dokumen ini mencatat seluruh temuan error, jebakan teknis (traps), dan perbaikan humanisasi kode dari modul 1 sampai 4 agar sistem berjalan Zero-Error di hadapan Asesor LSP dan mudah dihafal secara end-to-end.

---

## 1. Analisis Modul 1: Fondasi Proyek, Database & Eloquent Model

### Temuan Error / Trap 1: Penamaan Tabel Migration Jamak (`foods` vs `food`)
- **Penyebab Masalah:**  
  Ketika siswa menjalankan `php artisan make:model Food -mcr`, Laravel secara otomatis membuat file migration `create_foods_table` (atau pada konfigurasi tertentu `create_food_table`). Jika di file migration tertulis `Schema::create('food', ...)`, maka saat tabel `order_details` dijalankan:
  ```php
  $table->foreignId('food_id')->constrained('foods');
  ```
  MySQL akan melempar fatal error:
  `General error: 1005 Can't create table ... foreign key constraint is incorrectly formed (errno: 150)`.
- **Perbaikan Humanis & Solusi:**  
  Pastikan nama tabel di `Schema::create` selalu eksplisit `'foods'` dan di model `Food.php` ditambahkan `protected $table = 'foods';`. Ini rumus pasti agar tidak pernah bentrok dengan pluralizer bahasa Inggris bawaan Laravel.

### Temuan Error / Trap 2: Mass Assignment Blocking pada Subtotal
- **Penyebab Masalah:**  
  Banyak siswa menggunakan array `$fillable = ['order_id', 'food_id', 'quantity'];` pada `OrderDetail.php` dan lupa memasukkan `'subtotal'`. Saat checkout dijalankan, Laravel melempar:
  `Illuminate\Database\Eloquent\MassAssignmentException: Add [subtotal] to fillable property to allow mass assignment on [App\Models\OrderDetail]`.
- **Perbaikan Humanis & Solusi:**  
  Gunakan **Konsep Induk**: `protected $guarded = ['id'];`. Hanya kolom `id` yang dijaga, sehingga semua kolom kalkulasi seperti `subtotal` bebas disimpan tanpa risiko mass assignment exception.

---

## 2. Analisis Modul 2: Autentikasi Breeze & CRUD Master Makanan

### Temuan Error / Trap 3: Storage Link Permission & Missing Physical File Cleanup
- **Penyebab Masalah:**  
  Di Windows, `php artisan storage:link` kadang gagal jika terminal tidak dijalankan dengan hak Administrator (Error 1314). Selain itu, pada method `destroy` dan `update`, jika file fisik gambar lama tidak dihapus dari `storage/app/public/foods`, disk server akan cepat penuh dengan file sampah (file orphan).
- **Perbaikan Humanis & Solusi:**  
  1. Tambahkan pengecekan eksistensi sebelum delete:
     ```php
     if ($food->image && Storage::disk('public')->exists($food->image)) {
         Storage::disk('public')->delete($food->image);
     }
     ```
  2. Gunakan `Storage::disk('public')` secara eksplisit, bukan `Storage::delete()` tanpa driver.

---

## 3. Analisis Modul 3: Sisi Customer & Transaksi Atomik

### Temuan Error / Trap 4: Mismatch Nilai Status Transaksi (Case-Sensitivity & Enum Mismatch)
- **Penyebab Masalah di Modul Asli:**  
  - Modul 1 menentukan enum migration: `enum('status', ['Pending', 'Diproses', 'Selesai'])` (Title Case).
  - Modul 3 Controller menginput: `'status' => 'pending'` (huruf kecil).
  - Modul 4 Blade memiliki opsi: `<option value="completed">` dan `<option value="cancelled">`.
  Akibatnya, saat admin memilih "completed" atau order dibuat dengan "pending", MySQL strict mode menolak data atau menyimpan string kosong `''`. Di hadapan asesor, pesanan tiba-tiba hilang statusnya!
- **Perbaikan Humanis & Solusi:**  
  1. Gunakan tipe kolom `string('status')->default('Pending')` pada migration `orders`.
  2. Di Controller, normalisasi status secara konsisten menggunakan `ucfirst(strtolower($request->status))` sehingga input apapun otomatis diubah menjadi standar ('Pending', 'Diproses', 'Selesai', 'Batal').

### Temuan Error / Trap 5: Transaksi Database Non-Atomik (Manual beginTransaction vs DB::transaction)
- **Penyebab Masalah:**  
  Modul asli menggunakan `DB::beginTransaction()` dengan blok `try-catch` manual. Jika ada exception yang tidak tertangkap dengan tepat atau jika koneksi putus sebelum commit, database bisa tersangkut dalam state lock yang tidak ter-rollback sempurna.
- **Perbaikan Humanis & Solusi:**  
  Gunakan closures `DB::transaction(function () use (...) { ... })`. Laravel secara otomatis melakukan commit jika closure berhasil, dan otomatis melakukan rollback jika ada error apapun yang terjadi. Jauh lebih ringkas, aman, dan mudah diingat saat ujian.

---

## 4. Analisis Modul 4: Sisi Admin & Eager Loading

### Temuan Error / Trap 6: N+1 Query Problem & Route Naming Ambiguity
- **Penyebab Masalah:**  
  Jika admin menampilkan 50 pesanan dan di view memanggil `$order->orderDetails`, lalu di dalamnya memanggil `$detail->food->name`, Laravel akan mengeksekusi 1 query untuk order + 50 query untuk order details + ratusan query untuk foods! Halaman dashboard menjadi sangat lambat.
- **Perbaikan Humanis & Solusi:**  
  Gunakan Eager Loading bertingkat:
  ```php
  Order::with('orderDetails.food')->latest()->get();
  ```
  Ini hanya mengeksekusi 3 query database seberapapun banyaknya transaksi yang ada.

---

## Ringkasan Konsep Induk (Universal First-Principles untuk Dihafal)
1. **Model, Migration, Controller (`-mcr`):**
   - `-m` : Buat file Migration (cetak biru tabel database di `database/migrations`)
   - `-c` : Buat file Controller (otak pengendali alur request-response di `app/Http/Controllers`)
   - `-r` : Resource controller dengan 7 method standar (`index`, `create`, `store`, `show`, `edit`, `update`, `destroy`)
2. **Kunci Sukses Foreign Key:**
   - Tabel master harus dimigrasikan duluan sebelum tabel relasi (urutan timestamp migration).
   - Selalu pasang `->constrained('nama_tabel_jamak')->onDelete('cascade')`.
3. **Kunci Transaksi Database:**
   - Multi-insert wajib dibungkus `DB::transaction()` agar jika salah satu gagal, seluruh transaksi batal (tidak ada data setengah jadi).
