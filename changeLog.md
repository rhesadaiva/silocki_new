### **Changelog Aplikasi SILOCKI V 2.0**

---
#### 02 Maret 2020
-   **New:** Menambah Konfigurasi Tahun Berjalan untuk menampilkan data Kontrak Kinerja dan IKU berdasarkan tahun yang telah ditetapkan. Administrator bisa mengganti tahun dan status aktif pada menu konfigurasi.
-   **New:** Menambah *library* **Ignited DataTables** untuk keperluan ***server-side*** ***Datatables***.
-   **New:** Menambah menu log aktivitas untuk melihat aktivitas pada aplikasi SILOCKI dengan menggunakan ***server-side*** ***Datatables***.
-   **New:** Menambah filter pada controller Kontrak Kinerja dan IKU agar bisa menampilkan data berdasarkan Konfigurasi Tahun Berjalan.
-   **New:** Menambah *route* untuk mengambil log aktivitas, data konfigurasi, dan *update* data konfigurasi.

---

#### 03 Maret 2020
-   **Fix:** Mengubah struktur database pada tabel **user** dengan menambahkan kolom *is_active* dan mengubah isi kolom *seksi* menjadi id pada tabel **seksi_subseksi**.
-   **Fix:** Mengubah nama tabel **seksi/subseksi** menjadi **seksi_subseksi** dan mengubah kolom *id* menjadi *id_seksi_subseksi*.
-   **Fix:** Memperbaiki pemanggilan variabel pada halaman **index** untuk admin, pejabat, dan pelaksana
-   **Fix:** Memperbaiki query manual pada **Indikator_model**, **Kontrak_model**, **Admin_model**, **Global_model** dengan mengubah sesuai standar *Query Builder*.

---

#### 05 Maret 2020
- **Fix:** Menghapus metode **getSentLogbook** pada **Logbook_model**  
- **Fix:** Memperbaiki query manual pada **Logbook_model** dengan mengubah sesuai standar *Query Builder*.

#### 27 Mei 2020
- **Fix:** Memperbaiki bug double entry pada perekaman logbook  
- **New** Merubah render form via javascript