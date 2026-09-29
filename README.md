# Sistem Informasi Pelayanan Administrasi Desa Rawa

Sistem pelayanan administrasi desa berbasis web (Laravel 12 + Bootstrap 5 + MySQL) dengan 3 peran
pengguna (Warga, Kepala Dusun, Admin/Perangkat Desa), pengajuan surat online, pengaduan masyarakat,
dan notifikasi WhatsApp otomatis melalui Fonnte — dibangun sesuai Proposal KP Desa Rawa.

---

## ✅ Fitur Utama

- **Login terpisah & aman**: Admin pakai Email+Password (`/admin/login`), Warga & Kepala Dusun pakai NIK (`/login`) — rate-limited anti brute-force
- **Kepala Dusun hanya bisa dibuat oleh Admin** (tidak ada registrasi mandiri), role terkunci sejak akun dibuat, password sementara digenerate otomatis
- **NIK & No. KK dienkripsi at-rest** (AES) + blind-index HMAC untuk pencarian exact-match tanpa membocorkan data mentah
- **Audit log** menyeluruh — siapa mengubah apa, kapan, dari IP mana
- **Warga**: ajukan surat untuk diri sendiri/anggota keluarga (1 KK), pantau status, unduh PDF, buat pengaduan + foto
- **Kepala Dusun**: kelola data warga (tambah/ubah/import CSV massal), tandai warga meninggal, buatkan surat & laporkan keluhan atas nama warga
- **Admin**: proses & tandatangani surat (generate PDF kop desa otomatis), tolak dengan alasan, tanggapi pengaduan
- **Halaman data kependudukan terpisah per Dusun** (kartu ringkas → detail per dusun) — rapi, tidak tercampur
- **Arsip & Laporan Bulanan** ala mutasi rekening: pilih bulan/tahun (+ filter dusun), lihat rekap di halaman, atau unduh sebagai **Excel (multi-sheet)** maupun **PDF**
- **Notifikasi WhatsApp otomatis** (via Fonnte) di setiap perubahan status permohonan surat
- **Desain khas Ciamis**: palet hijau-emas, logo lambang (placeholder, siap diganti logo resmi), layout modern & responsif
- Security headers, Laravel Policy authorization, Form Request validation

---

## 🧰 Kebutuhan Sistem

- [Laragon](https://laragon.org/) (sudah termasuk Apache/Nginx + MySQL + PHP)
- **PHP 8.2 atau lebih baru** (Laravel 12 mewajibkan PHP 8.2+) — pastikan versi PHP di Laragon sudah sesuai, cek dengan mengetik `php -v` di terminal Laragon
- **Composer** ([https://getcomposer.org](https://getcomposer.org))
- **VS Code**
- Akun [Fonnte](https://fonnte.com) (gratis untuk trial) jika ingin notifikasi WhatsApp benar-benar aktif

---

## 🚀 Langkah Instalasi di Laragon + VS Code

### 1. Extract Proyek
Extract file ZIP ini ke folder `www` milik Laragon, contoh:
```
C:\laragon\www\sistem-desa-rawa
```

### 2. Buka Terminal di VS Code
Buka folder proyek di VS Code, lalu buka Terminal (`Ctrl + ~`).

### 3. Salin File Environment (WAJIB SEBELUM composer install)
```bash
copy .env.example .env
```
(Di macOS/Linux gunakan `cp .env.example .env`)

> ⚠️ **Urutan ini penting!** Jika `.env` belum ada saat `composer install` jalan, proses `package:discover` bisa gagal dengan error `Target class [files] does not exist` atau serupa, karena Laravel butuh `.env` untuk bisa boot dengan benar.

### 4. Install Dependency PHP (Composer)
```bash
composer install
```
Tunggu sampai folder `vendor/` selesai dibuat (proses ini mengunduh Laravel Framework, DomPDF, Laravel Excel, dsb — cukup memakan waktu).

### 5. Generate Application Key
```bash
php artisan key:generate
```

### 6. Buat Database di Laragon
- Buka **Laragon** → klik kanan → **MySQL** → **phpMyAdmin** (atau HeidiSQL)
- Buat database baru bernama: `db_desa_rawa`
- Pastikan `.env` sudah sesuai (default Laragon biasanya sudah cocok):
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=db_desa_rawa
DB_USERNAME=root
DB_PASSWORD=
```

### 7. Jalankan Migration + Seeder
Perintah ini akan membuat semua tabel **sekaligus mengisi data awal** (dusun, jenis surat, akun admin & kepala dusun, contoh data warga):
```bash
php artisan migrate --seed
```

### 8. Buat Symbolic Link Storage
Wajib agar foto pengaduan & PDF surat bisa diakses lewat browser:
```bash
php artisan storage:link
```

### 9. Jalankan Server
**Opsi A — pakai Laragon (disarankan):**
Klik "Start All" di Laragon, lalu ubah nama folder di Laragon settings agar otomatis menjadi virtual host `sistem-desa-rawa.test`, atau langsung akses lewat:
```
http://localhost/sistem-desa-rawa/public
```

**Opsi B — pakai artisan serve:**
```bash
php artisan serve
```
Lalu buka `http://127.0.0.1:8000`

---

## 🔑 Akun Default (hasil seeder)

| Peran | Login | Kredensial |
|---|---|---|
| Admin | Email: `admin@desarawa.go.id` | Password: `AdminDesaRawa#2026` |
| Kepala Dusun I-IV | NIK: `8888800001` s/d `8888800004` | Password: `KadusRawa#2026` (wajib ganti saat login pertama) |

Admin login di **`/admin/login`** (terpisah). Kepala Dusun & Warga login di **`/login`** (pakai NIK).

**Contoh registrasi Warga mandiri** (data sudah ada di seeder, coba daftar via halaman Register):
- NIK: `3207010101990001` (Budi Santoso)
- No. KK: `3207010101990000`
- Password awal saat login pertama = tanggal lahir format DDMMYYYY → `01011999`

> ⚠️ **Ganti semua password default** ini sebelum digunakan di lingkungan produksi/asli. Di produksi, buat akun Admin baru lewat `php artisan tinker` lalu nonaktifkan/hapus akun seeder ini.

---

## 📱 Mengaktifkan Notifikasi WhatsApp (Fonnte)

Selama token belum diisi, sistem tetap berjalan normal dalam **mode simulasi** (notifikasi hanya dicatat
ke tabel `whatsapp_logs`, tidak benar-benar terkirim) — jadi kamu tetap bisa demo/uji aplikasi tanpa akun Fonnte.

Untuk mengaktifkan WA sungguhan:
1. Daftar di [fonnte.com](https://fonnte.com), scan QR dengan WhatsApp yang akan jadi pengirim notifikasi
2. Salin **Token Device**
3. Buka file `.env`, isi:
```env
FONNTE_TOKEN=token_kamu_disini
FONNTE_ENABLED=true
```
4. Jalankan ulang `php artisan config:clear` lalu refresh aplikasi

---

## 📄 Import Data Warga Massal (CSV)

Kepala Dusun dapat mengimpor banyak data warga sekaligus lewat menu **Data Warga → Import CSV**.
Contoh file format CSV yang benar tersedia di:
```
/contoh-data/contoh-import-warga.csv
```
Format kolom (baris pertama = header, dilewati otomatis):
```
nik, no_kk, nama, jenis_kelamin(L/P), tempat_lahir, tanggal_lahir(YYYY-MM-DD), alamat, no_hp, status_ekonomi, status_pernikahan, status_pendidikan, pekerjaan
```

---

## 🗂️ Struktur Alur Permohonan Surat

```
Warga/Kadus ajukan  →  Diajukan  →  Admin klik "Proses"  →  Diproses
   →  Admin klik "Tandatangani & Buat PDF" (PDF otomatis dibuat)  →  Ditandatangani
   →  Admin klik "Tandai Selesai"  →  Selesai (warga bisa unduh PDF)

Di setiap perpindahan status → notifikasi WhatsApp otomatis terkirim ke nomor HP pemohon.
Admin juga bisa menolak permohonan kapan saja (kecuali sudah Selesai) dengan alasan wajib diisi.
```

---

## ⚙️ Kustomisasi Identitas Desa

Ubah nama desa, kecamatan, kabupaten, alamat kantor, dan nama Kepala Desa (dipakai di kop surat PDF
dan cetak laporan) melalui file `.env`:
```env
DESA_NAMA="Desa Rawa"
DESA_KECAMATAN="Lumbung"
DESA_KABUPATEN="Ciamis"
DESA_PROVINSI="Jawa Barat"
DESA_ALAMAT_KANTOR="Jl. Raya Desa Rawa No. 1, Kec. Lumbung, Kab. Ciamis"
DESA_KEPALA_DESA="Nama Kepala Desa"
```

---

## 🧱 Struktur Database Singkat

| Tabel | Keterangan |
|---|---|
| `dusuns` | Master data dusun |
| `warga` | Data kependudukan (diunggah Kepala Dusun, syarat registrasi akun) |
| `users` | Akun login (admin/kadus/warga), login pakai kolom `nik` |
| `surat_types` | Master jenis-jenis surat (11 jenis sesuai proposal) |
| `surat_requests` | Permohonan surat + status workflow |
| `surat_request_logs` | Riwayat status & log notifikasi WA per permohonan |
| `pengaduans` / `pengaduan_tanggapans` | Pengaduan warga & tanggapan admin |
| `whatsapp_logs` | Riwayat semua pesan WA yang dikirim/disimulasikan |

---

---

## 🎨 Mengganti Logo Resmi Ciamis

File `public/img/logo-ciamis.svg` saat ini adalah **placeholder** bergaya lambang daerah (bukan logo resmi Kabupaten Ciamis yang sebenarnya, karena berkas resminya tidak tersedia untuk disertakan otomatis di sini). Untuk pengajuan ke tingkat kabupaten, ganti dengan logo resmi:

1. Minta file logo resmi Kabupaten Ciamis & Desa Rawa ke kantor kecamatan/kabupaten (biasanya format PNG/SVG resolusi tinggi)
2. Simpan sebagai `public/img/logo-ciamis.png` (atau `.svg`)
3. Cari-ganti semua referensi `logo-ciamis.svg` di file berikut menjadi nama file logo resmi Anda:
   - `resources/views/layouts/app.blade.php` (sidebar)
   - `resources/views/layouts/guest.blade.php` (halaman login/register)
   - `resources/views/welcome.blade.php` (landing page)

---

## 🔐 Ringkasan Arsitektur Keamanan

| Lapisan | Implementasi |
|---|---|
| Enkripsi data pribadi | NIK & No. KK dienkripsi AES di database (`App\Traits\EncryptsSensitiveFields`), dicari lewat blind-index HMAC, bukan plain text |
| Autentikasi | Login Admin (email) & Warga/Kadus (NIK) terpisah total; rate-limit 5-10x percobaan/menit |
| Otorisasi | Laravel Policy (`SuratRequestPolicy`, `PengaduanPolicy`, `WargaPolicy`) - bukan pengecekan manual tersebar |
| Audit trail | Tabel `audit_logs` mencatat causer, aksi, subjek, IP, user-agent untuk setiap aksi penting |
| Validasi input | Form Request class per aksi, termasuk `UniqueEncrypted` rule khusus kolom terenkripsi |
| Header keamanan | `SecurityHeaders` middleware (X-Frame-Options, nosniff, Referrer-Policy, dst) |
| Kontrol akun | Admin dapat menonaktifkan/reset password akun Kepala Dusun kapan saja tanpa menghapus data historis |

**Rekomendasi tambahan sebelum go-live ke kabupaten:**
- Aktifkan HTTPS (SSL) — jangan jalankan di HTTP polos untuk data warga asli
- Pindahkan `.env` (terutama `APP_KEY`, `FONNTE_TOKEN`) ke luar version control, backup `APP_KEY` dengan aman (jika hilang, seluruh data NIK/KK terenkripsi tidak bisa didekripsi!)
- Aktifkan backup database rutin (mis. `mysqldump` terjadwal)
- Pertimbangkan audit keamanan independen jika akan menyimpan data seluruh warga desa dalam skala penuh

---

## 📊 Arsip & Laporan Bulanan (Excel/PDF)

Menu **Arsip & Laporan Bulanan** (khusus Admin) memungkinkan:
- Memilih periode bulan/tahun (mirip mutasi rekening bank), dengan filter per dusun
- Melihat rekap langsung di halaman (tab: Surat, Warga Baru, Pengaduan, Kematian)
- Mengunduh **Excel** (`.xlsx`, multi-sheet: Ringkasan, Warga, Surat, Pengaduan, Kematian)
- Mengunduh **PDF** siap cetak dengan kop surat resmi

Data kependudukan juga bisa diunduh per-dusun secara terpisah lewat menu **Data Kependudukan → [pilih dusun] → Unduh Excel Dusun Ini**, atau seluruh desa sekaligus lewat tombol **Unduh Semua Data**.

---

## 🛠️ Troubleshooting Umum

| Masalah | Solusi |
|---|---|
| `SQLSTATE[HY000] [1045] Access denied` | Cek username/password MySQL di `.env`, default Laragon: user `root`, password kosong |
| Foto/PDF tidak muncul (404) | Jalankan `php artisan storage:link` |
| `Target class [files]` / `[blade.compiler]` dsb saat `composer install` | `.env` belum ada saat itu. Jalankan `copy .env.example .env`, lalu `php artisan package:discover`, baru `php artisan key:generate` |
| Error `APP_KEY not set` | Jalankan `php artisan key:generate` |
| Data NIK/KK tidak bisa dibaca (`DecryptException`) | `APP_KEY` di `.env` berubah/berbeda dari saat data pertama dibuat. Jangan pernah mengganti `APP_KEY` setelah ada data - backup nilainya dengan aman |
| Export Excel gagal / class not found | Pastikan extension PHP `ext-zip` & `ext-gd` aktif di `php.ini` (biasanya sudah aktif default di Laragon) |
| Halaman putih / error 500 | Jalankan `php artisan config:clear && php artisan cache:clear` lalu cek `storage/logs/laravel.log` |
| Composer lambat/gagal | Pastikan koneksi internet stabil; bisa juga jalankan `composer install --no-dev` jika hanya butuh production |

---

## 📌 Catatan Pengembangan Lanjutan 

- Export laporan langsung ke PDF (saat ini laporan berbentuk halaman cetak via browser `Ctrl+P`)
- Manajemen akun Kepala Dusun oleh Admin (saat ini dibuat via seeder/tinker)
- Halaman edit profil & ganti nomor WhatsApp oleh warga
- Notifikasi email sebagai kanal cadangan selain WhatsApp


