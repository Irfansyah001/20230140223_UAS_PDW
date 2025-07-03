CREATE TABLE mata_praktikum (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    deskripsi TEXT,
    semester VARCHAR(20),
    tahun_ajaran VARCHAR(20),
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('mahasiswa', 'asisten') NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE modul (
    id INT AUTO_INCREMENT PRIMARY KEY,
    praktikum_id INT NOT NULL,                   -- FK ke tabel mata_praktikum
    judul VARCHAR(100) NOT NULL,                 -- Judul modul, misal "Modul 1: HTML"
    deskripsi TEXT,                              -- Penjelasan singkat isi modul
    urutan INT DEFAULT 1,
    file_materi VARCHAR(255),                    -- Nama file materi PDF yang diupload
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (praktikum_id) REFERENCES mata_praktikum(id)
        ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE praktikum_mahasiswa (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    praktikum_id INT NOT NULL,
    tanggal_daftar DATETIME DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY unique_pendaftaran (user_id, praktikum_id),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (praktikum_id) REFERENCES mata_praktikum(id) ON DELETE CASCADE
);

CREATE TABLE laporan (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,            -- FK ke users
    modul_id INT NOT NULL,           -- FK ke modul
    file_path VARCHAR(255) NOT NULL,
    uploaded_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    
    nilai INT DEFAULT NULL,
    komentar TEXT,
    dinilai_oleh INT DEFAULT NULL,   -- FK ke users (asisten)
    dinilai_pada DATETIME DEFAULT NULL,

    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (modul_id) REFERENCES modul(id) ON DELETE CASCADE,
    FOREIGN KEY (dinilai_oleh) REFERENCES users(id) ON DELETE SET NULL
);

CREATE TABLE notifikasi_mahasiswa (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    icon VARCHAR(10) DEFAULT '🔔',
    pesan TEXT NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);


INSERT INTO mata_praktikum (nama, deskripsi, semester, tahun_ajaran)
VALUES 
('Pemrograman Web', 'Belajar HTML, CSS, dan PHP.', 'Ganjil', '2024/2025'),
('Jaringan Komputer', 'Dasar jaringan dan topologi.', 'Genap', '2024/2025'),
('Algoritma dan Pemrograman', 'Struktur logika dan pemrograman dasar.', 'Ganjil', '2024/2025'),
('Basis Data', 'Perancangan dan manipulasi database dengan SQL.', 'Genap', '2024/2025'),
('Struktur Data', 'Pengenalan stack, queue, tree, dan graph.', 'Ganjil', '2024/2025'),
('Pemrograman Berorientasi Objek', 'Konsep OOP menggunakan Java atau C#.', 'Genap', '2024/2025'),
('Pemrograman Mobile', 'Membuat aplikasi Android dengan Kotlin/Java.', 'Ganjil', '2024/2025'),
('Keamanan Jaringan', 'Firewall, VPN, dan dasar keamanan jaringan.', 'Genap', '2024/2025'),
('Sistem Operasi', 'Manajemen proses, memori, dan file system.', 'Ganjil', '2024/2025'),
('Pemrograman Desktop', 'Membangun aplikasi desktop dengan .NET atau JavaFX.', 'Genap', '2024/2025'),
('Data Mining', 'Pengenalan klasifikasi dan clustering data.', 'Ganjil', '2024/2025'),
('Machine Learning', 'Konsep supervised dan unsupervised learning.', 'Genap', '2024/2025'),
('Artificial Intelligence', 'Pengenalan AI dan implementasi dasar.', 'Ganjil', '2024/2025'),
('Pemrograman Game', 'Membuat game 2D sederhana dengan Unity.', 'Genap', '2024/2025'),
('Pemrograman Web Lanjut', 'Framework Laravel atau React.', 'Ganjil', '2024/2025'),
('Internet of Things', 'Proyek IoT menggunakan sensor dan mikrokontroler.', 'Genap', '2024/2025'),
('Rekayasa Perangkat Lunak', 'Proses pengembangan perangkat lunak.', 'Ganjil', '2024/2025'),
('Manajemen Proyek TI', 'Perencanaan dan kontrol proyek TI.', 'Genap', '2024/2025'),
('Jaringan Lanjut', 'Routing, switching, dan konfigurasi router.', 'Ganjil', '2024/2025'),
('Cloud Computing', 'Mengenal layanan cloud dan deployment aplikasi.', 'Genap', '2024/2025');

INSERT INTO notifikasi_mahasiswa (user_id, icon, pesan)
VALUES 
(1, '🔔', 'Nilai untuk Modul 1: HTML & CSS telah diberikan.'),
(1, '⏳', 'Batas waktu pengumpulan Modul 2: PHP Native adalah besok!'),
(1, '✅', 'Anda berhasil mendaftar pada praktikum Jaringan Komputer.');
