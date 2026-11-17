# 🏢 Portal SDM — Workforce Management & People Analytics

![PHP](https://img.shields.io/badge/PHP-8.x-777BB4?style=flat-square&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-PDO-4479A1?style=flat-square&logo=mysql&logoColor=white)
![Bootstrap](https://img.shields.io/badge/Bootstrap-5.3-7952B3?style=flat-square&logo=bootstrap&logoColor=white)
![Chart.js](https://img.shields.io/badge/Chart.js-4.4-FF6384?style=flat-square&logo=chartdotjs&logoColor=white)

**Portal SDM** adalah aplikasi web internal untuk pengelolaan data personel dan visualisasi demografi karyawan (*People Analytics*) secara *real-time*. Sistem ini dirancang dengan arsitektur modular yang memisahkan lapisan *data access*, logika bisnis, fungsi utilitas, dan komponen antarmuka.

---

## ✨ Fitur Utama

* **📊 Real-Time People Analytics Dashboard**
  Menyajikan 3 visualisasi data interaktif menggunakan **Chart.js** yang diperbarui otomatis setiap kali terjadi perubahan data:
  * **Komposisi Gender (Bar Chart):** Perbandingan jumlah pegawai Laki-laki dan Perempuan.
  * **Kualifikasi Pendidikan (Doughnut Chart):** Distribusi jenjang pendidikan terakhir (`SMA/SMK`, `D3`, `S1`, `S2`, `S3`).
  * **Demografi Usia (Bar Chart):** Pengelompokan rentang usia produktif yang dihitung secara dinamis dari tanggal lahir.
* **🗂️ Manajemen Direktori Pegawai (CRUD & Pencarian Cepat)**
  Mendukung penambahan pegawai baru, pembaruan profil dan jabatan, penghapusan data, serta pencarian instan berdasarkan NIP, nama lengkap, jabatan, maupun divisi.
* **🛡️ Proteksi Konfirmasi Hapus (Safe-Delete Modal)**
  Menggunakan **SweetAlert2** untuk menampilkan *pop-up* konfirmasi interaktif sebelum data dihapus secara permanen dari basis data.

---

## 🏗️ Arsitektur & Praktik Rekayasa Perangkat Lunak

1. **Object-Oriented Programming (OOP) & Enkapsulasi**
   * `Database` (`config/database.php`): Mengelola koneksi basis data tunggal berbasis **PDO** dengan atribut `private`.
   * `Pegawai` (`src/Models/Pegawai.php`): Entitas objek yang membungkus properti pegawai secara *encapsulated* (`private`), menyediakan *getter*, serta menghitung usia otomatis melalui metode `getUsia()`.
   * `PegawaiService` (`src/Services/PegawaiService.php`): Lapisan *service/repository* untuk mengeksekusi transaksi CRUD dan agregasi statistik.
2. **Pemrograman Terstruktur & Modular**
   * `includes/helpers.php`: Menyediakan fungsi terstruktur untuk validasi input (`validasiInputPegawai`), sanitasi karakter XSS (`e`), dan klasifikasi kelompok usia (`kategorikanUsia`).
   * Pemisahan tampilan (*partial views*) ke dalam direktori `views/` serta aset statis ke dalam `public/`.
3. **Pemanfaatan Struktur Data**
   * Menggunakan koleksi objek (`Pegawai[]`) dan *associative array* multidimensi pada metode `hitungStatistikDashboard()` untuk memetakan distribusi pegawai dalam satu kali iterasi $O(n)$ sebelum dikonversi ke format JSON.
4. **Keamanan Aplikasi (Security Best Practices)**
   * Mencegah *SQL Injection* sepenuhnya menggunakan **PDO Prepared Statements** (`prepare` & `execute`).
   * Mencegah *Cross-Site Scripting (XSS)* melalui fungsi sanitasi `htmlspecialchars()` pada seluruh keluaran HTML.

---

## 📂 Struktur Direktori

```text
portal-sdm/
├── config/
│   └── database.php             # Konfigurasi koneksi PDO MySQL
├── database/
│   └── schema.sql               # Skema tabel & data awal (seeder)
├── includes/
│   └── helpers.php              # Fungsi validasi, sanitasi XSS, & utilitas usia
├── public/
│   ├── css/style.css            # Kustomisasi desain antarmuka dashboard
│   └── js/dashboard.js          # Konfigurasi Chart.js & modal SweetAlert2
├── src/
│   ├── Models/Pegawai.php       # Kelas entitas Pegawai (OOP)
│   └── Services/PegawaiService.php # Logika bisnis CRUD & agregasi grafik
├── views/
│   ├── dashboard_charts.php     # Komponen 3 kartu grafik analitik
│   ├── form_pegawai.php         # Komponen formulir tambah & edit pegawai
│   └── tabel_pegawai.php        # Komponen tabel direktori & filter pencarian
├── index.php                    # Front-controller utama aplikasi
└── README.md                    # Dokumentasi proyek & skenario pengujian QA
```

---

## 🚀 Panduan Instalasi Lokal (XAMPP)

1. **Siapkan Direktori Proyek**
   Pastikan folder proyek berada di dalam direktori `htdocs` pada instalasi XAMPP Anda:
   ```text
   C:\xampp\htdocs\portal-sdm
   ```
2. **Jalankan Layanan Server**
   Buka **XAMPP Control Panel**, lalu klik **Start** pada modul **Apache** dan **MySQL**.
3. **Impor Skema Basis Data**
   * Buka peramban dan akses `http://localhost/phpmyadmin`.
   * Pilih tab **SQL**, salin seluruh isi berkas `database/schema.sql`, lalu klik **Go**.
4. **Akses Aplikasi**
   Buka peramban dan kunjungi alamat berikut:
   ```text
   http://localhost/portal-sdm
   ```

---

## 🧪 Quality Assurance & Skenario Pengujian (Black-Box Testing)

Berikut adalah matriks pengujian fungsional dan non-fungsional untuk memastikan stabilitas sistem:

| Kode Uji | Modul / Fitur | Skenario & Input Pengujian | Ekspektasi Hasil | Status |
| :--- | :--- | :--- | :--- | :--- |
| **QA-01** | **Create Employee** | Mengisi seluruh kolom form dengan NIP unik dan data valid | Data tersimpan di MySQL, tampil di baris teratas tabel, dan grafik terbarui | ✅ **Pass** |
| **QA-02** | **Duplicate NIP Guard** | Memasukkan NIP yang sudah terdaftar pada pegawai lain | Sistem menangkap `PDOException` dan menampilkan *alert* peringatan tanpa *crash* | ✅ **Pass** |
| **QA-03** | **Read & Search Filter** | Mengetikkan kata kunci nama, NIP, atau divisi pada kolom pencarian | Tabel memfilter daftar pegawai secara akurat sesuai kata kunci | ✅ **Pass** |
| **QA-04** | **Update Employee** | Klik tombol **Edit**, ubah jenjang pendidikan/jabatan, lalu klik Simpan | Perubahan tersimpan dan proporsi grafik pendidikan langsung menyesuaikan | ✅ **Pass** |
| **QA-05** | **Delete Confirmation** | Klik tombol **Hapus** pada salah satu baris pegawai | Muncul *pop-up* konfirmasi SweetAlert2 sebelum penghapusan dieksekusi | ✅ **Pass** |
| **QA-06** | **Analytics Rendering** | Memuat halaman utama dashboard | Grafik batang gender, grafik pendidikan, dan grafik usia tampil akurat sesuai database | ✅ **Pass** |

---

## 🛠️ Tech Stack & Libraries

* **Backend:** PHP 8.x (Native OOP + Structured Procedural Helpers)
* **Database:** MySQL / MariaDB (PDO Driver)
* **UI Framework:** Bootstrap 5.3 & Bootstrap Icons 1.11
* **Data Visualization:** Chart.js 4.4
* **Interactive Dialogs:** SweetAlert2 11