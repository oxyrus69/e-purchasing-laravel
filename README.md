<div align="center">
  <!-- Ikon generik untuk E-Purchasing, Anda bisa menggantinya dengan URL logo Anda -->
  <img src="https://raw.githubusercontent.com/user-attachments/assets/e0f54546-d443-424a-9e32-21ea3a6a1f02" alt="Logo E-Purchasing" width="120">
  <h1><strong>E-Purchasing System</strong></h1>
  <p>
    Aplikasi web untuk mengelola dan melacak alur permintaan pembelian (Purchase Request & Purchase Order) secara efisien.
  </p>
  <p>
    <img src="https://img.shields.io/badge/Laravel-11-FF2D20?style=for-the-badge&logo=laravel" alt="Laravel 11">
    <img src="https://img.shields.io/badge/PHP-8.3-777BB4?style=for-the-badge&logo=php" alt="PHP 8.3">
    <img src="https://img.shields.io/badge/MySQL-4479A1?style=for-the-badge&logo=mysql&logoColor=white" alt="MySQL">
    <img src="https://img.shields.io/badge/Tailwind_CSS-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white" alt="Tailwind CSS">
  </p>
</div>

---

## 📖 Deskripsi Proyek

**E-Purchasing System** adalah solusi berbasis web yang dirancang untuk mendigitalkan dan menyederhanakan proses pengadaan barang di dalam sebuah perusahaan. Aplikasi ini memungkinkan departemen untuk membuat Permintaan Pembelian (Purchase Request), yang kemudian akan ditinjau dan disetujui oleh manajer sebelum diubah menjadi Pesanan Pembelian (Purchase Order) kepada supplier.

Tujuannya adalah untuk menciptakan alur kerja yang transparan, terdokumentasi, dan efisien, mengurangi penggunaan kertas dan mempercepat proses pengadaan.

---

## ✨ Fitur Utama

Aplikasi ini memiliki alur kerja yang jelas dengan peran pengguna yang berbeda.

| Peran | Fitur yang Dapat Diakses |
| :--- | :--- |
| 👤 **Admin** | <ul><li>**Dashboard Utama:** Melihat ringkasan semua transaksi (PR, PO, Supplier).</li><li>**Manajemen Data Master:** CRUD penuh untuk Produk, Supplier, dan Kategori.</li><li>**Manajemen Stok:** Menyesuaikan dan melacak stok barang.</li><li>**Kontrol Pengguna:** Mengelola semua akun pengguna dan peran mereka.</li><li>**Melihat Semua Transaksi:** Memiliki akses penuh untuk melihat semua PR dan PO dari semua departemen.</li></ul> |
| 👨‍💼 **Manajer** | <ul><li>**Persetujuan PR:** Menyetujui atau menolak Permintaan Pembelian (PR) yang diajukan oleh staf di departemennya.</li><li>**Pembuatan PO:** Mengonversi PR yang disetujui menjadi Pesanan Pembelian (PO).</li><li>**Pelacakan:** Memantau status semua PO di departemennya.</li></ul> |
| 🧑‍💻 **Staf**| <ul><li>**Permintaan Pembelian:** Membuat Permintaan Pembelian (PR) baru untuk barang yang dibutuhkan.</li><li>**Melihat Status:** Melacak status PR yang telah diajukan (Menunggu, Disetujui, Ditolak).</li></ul> |

---

## 🖼️ Galeri Tampilan

Berikut adalah beberapa tampilan dari aplikasi E-Purchasing:

**1. Halaman Login**
<img src="https://raw.githubusercontent.com/oxyrus69/e-purchasing-laravel/main/public/img/login.png" alt="Halaman Login" width="700">

**2. Dashboard Admin**
<img src="https://raw.githubusercontent.com/oxyrus69/e-purchasing-laravel/main/public/img/dashboard.png" alt="Dashboard Admin" width="700">

**3. Halaman Purchase Order**
<img src="https://raw.githubusercontent.com/oxyrus69/e-purchasing-laravel/main/public/img/po.png" alt="Purchase Order" width="700">

---

## 🛠️ Teknologi yang Digunakan

-   **Backend:** Laravel 11, PHP 8.3
-   **Frontend:** Tailwind CSS, Alpine.js
-   **Database:** MySQL

---

## 📄 Lisensi

Proyek ini berada di bawah Lisensi MIT. Lihat file `LICENSE.md` untuk detail lebih lanjut.
