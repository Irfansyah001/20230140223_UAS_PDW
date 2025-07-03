# SIMPRAK: Sistem Manajemen Praktikum Onlin

## Deskripsi Proyek

SIMPRAK (Sistem Informasi Manajemen Praktikum) adalah sebuah platform berbasis web yang dirancang untuk streamline pengelolaan kegiatan praktikum di lingkungan pendidikan. Aplikasi ini memfasilitasi seluruh alur kerja praktikum, mulai dari distribusi materi, pengumpulan laporan, hingga proses penilaian tugas. SIMPRAK melayani dua peran utama: Mahasiswa dan Asisten, masing-masing dengan antarmuka dan fungsionalitas yang disesuaikan.

## Fitur Utama

### Untuk Mahasiswa

  * **Dasbor Interaktif**: Tampilan ringkasan praktikum yang diikuti, tugas yang telah diselesaikan, dan tugas yang masih menunggu.
  * **Praktikum Saya**: Melihat daftar praktikum yang sedang diikuti.
  * **Cari Praktikum**: Menjelajahi dan mendaftar pada praktikum yang tersedia.
  * **Detail Praktikum**: Mengakses informasi rinci mengenai modul, tugas, dan nilai untuk setiap praktikum.

### Untuk Asisten

  * **Dasbor Administratif**: Ikhtisar total modul yang diajarkan, laporan yang masuk, dan laporan yang belum dinilai.
  * **Manajemen Praktikum**: Menambah, mengedit, atau menghapus data praktikum.
  * **Manajemen Modul**: Mengelola modul-modul yang terkait dengan praktikum.
  * **Laporan Masuk**: Memantau dan menilai laporan-laporan yang telah dikumpulkan oleh mahasiswa.
  * **Manajemen Pengguna**: Mengelola akun pengguna (mahasiswa dan asisten).

## Teknologi yang Digunakan

  * **Backend**: PHP
  * **Database**: MySQL
  * **Frontend**: HTML, CSS (dengan TailwindCSS), JavaScript (opsional)

## Alur Autentikasi

  * **Login**: Pengguna dapat masuk menggunakan email dan password mereka. Sistem akan mengarahkan mereka ke dasbor yang sesuai berdasarkan peran (`mahasiswa` atau `asisten`).
  * **Registrasi**: Pengguna baru dapat mendaftar dengan menyediakan nama, email, password, dan memilih peran (`mahasiswa` atau `asisten`). Password akan di-hash sebelum disimpan.
  * **Logout**: Fungsi logout akan menghapus sesi pengguna dan mengarahkan kembali ke halaman login.

## Tampilan Interface

### Beranda
![image](https://github.com/user-attachments/assets/13b68495-746a-416b-92df-2fa231f657f1)

### Halaman Login
![image](https://github.com/user-attachments/assets/d1278499-f0ce-4b50-9253-d8b28637b90a)

### Halaman Registrasi
![image](https://github.com/user-attachments/assets/a347f07f-cdf4-4c6a-bef4-ee0022b50dad)

### Dasbor Mahasiswa
![image](https://github.com/user-attachments/assets/948fceb8-6c2d-4b8c-b31c-2e9463f2b5a6)

### Praktikum Saya (Mahasiswa)
![image](https://github.com/user-attachments/assets/dafdea3d-777d-4bc5-81d8-a7d3038aa065)

### Cari Praktikum (Mahasiswa)
![image](https://github.com/user-attachments/assets/068b33a6-d51b-42cd-803e-0b8e57136f84)

### Detail Praktikum (Mahasiswa)
![image](https://github.com/user-attachments/assets/dc5ca432-2c84-4a11-b7b8-df2fa60c8ee0)

### Rekap Nilai
![image](https://github.com/user-attachments/assets/10730799-dee9-4bf8-90ed-c29e800004c5)

### Dasbor Asisten
![image](https://github.com/user-attachments/assets/5350fab8-d3a0-4210-bf52-f39915417585)

### Manajemen Praktikum (Asisten)
![image](https://github.com/user-attachments/assets/4b4353a5-bedb-4636-bd50-6a70f92dc3a7)

### Form Tambah Praktikum (Asisten)
![image](https://github.com/user-attachments/assets/effad43f-044e-4521-84b4-e1545e991da2)

### Manajemen Modul (Asisten)
![image](https://github.com/user-attachments/assets/fa28c1d7-ca44-4c04-99bc-5389de043f7b)

### Form Tambah Modul (Asisten)
![image](https://github.com/user-attachments/assets/5240832d-d83b-4b67-9821-43091b239adf)

### Laporan Masuk (Asisten)
![image](https://github.com/user-attachments/assets/d8cbc9c7-bf95-43f3-b07b-3ed93a168509)

### Manajemen Pengguna (Asisten)
![image](https://github.com/user-attachments/assets/9a6137d5-5a43-4f7d-b2bf-28fa8eeb63ef)

### Tambah Pengguna Baru (Asisten)
![image](https://github.com/user-attachments/assets/26b88f37-499c-49a7-9c84-a67626d8834f)
