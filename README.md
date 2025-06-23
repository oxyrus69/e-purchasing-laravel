**Sistem Informasi E-Purchasing**

Sistem Informasi Purchasing lengkap yang dibangun dari nol menggunakan Laravel 11. Proyek ini mencakup seluruh alur kerja pengadaan barang, mulai dari permintaan internal hingga pencatatan faktur, yang dilengkapi dengan sistem hak akses berbasis peran, notifikasi real-time, dan pelaporan dinamis. Proyek ini dibuat untuk menunjukkan pemahaman mendalam tentang pengembangan aplikasi web modern, arsitektur perangkat lunak, dan praktik terbaik dalam ekosistem Laravel.

**🚀 Fitur Utama & Fungsionalitas**

**🔐 Autentikasi & Hak Akses**

• Login, Registrasi, & Pengaturan Profil   
• Sistem Peran & Izin (Spatie/laravel-permission)   
• Menu Dinamis sesuai Hak Akses   
• UI Manajemen Pengguna untuk Admin

**🔄 Alur Kerja Purchasing**

• Purchase Request (PR): Pengajuan barang oleh staf.   
• Approval System: Persetujuan/penolakan PR oleh Manajer.   
• Purchase Order (PO): Pembuatan pesanan resmi ke supplier.   
• Goods Receipt Note (GRN): Pencatatan penerimaan barang.   
• Invoice Management: Pencatatan faktur dan status pembayaran.

**📦 Manajemen Inventaris**

• Stok Otomatis: Stok bertambah saat penerimaan barang (GRN).   
• Pengeluaran Stok: Modul permintaan internal untuk mengurangi stok.   
• Penyesuaian Stok: Fitur untuk stock opname.   
• Kartu Stok (Ledger): Riwayat lengkap pergerakan barang (IN, OUT, ADJ).

**✨ Fitur Lanjutan & UX**

• Dashboard Informatif: Kartu statistik untuk ringkasan data.   
• Notifikasi Real-time: Ikon lonceng untuk notifikasi PR, PO, & Stok Minimum.   
• Pencarian & Filter: Fungsi pencarian di semua modul utama.   
• Laporan Dinamis: Laporan berdasarkan periode dengan opsi Cetak Formal & Ekspor ke CSV/Excel.

**✅ Kualitas Kode**

• Unit & Feature Testing (PHPUnit): Tes otomatis untuk alur kerja krusial.   
• Service Class Pattern: Memisahkan logika bisnis yang kompleks dari controller.  
• Database Seeder: Otomatisasi pembuatan data awal (peran & pengguna).
