<?php
require_once '../config.php';
session_start();

// Validasi login asisten
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'asisten') {
    header("Location: ../login.php");
    exit;
}

// Validasi POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['laporan_id'])) {
    $laporan_id = (int) $_POST['laporan_id'];
    $nilai = isset($_POST['nilai']) ? (int) $_POST['nilai'] : null;
    $komentar = isset($_POST['komentar']) ? trim($_POST['komentar']) : null;

    // Validasi nilai antara 0 - 100
    if ($nilai < 0 || $nilai > 100) {
        $_SESSION['flash'] = ['type' => 'error', 'message' => 'Nilai harus antara 0 - 100.'];
    } else {
        // Update nilai dan komentar di laporan
        $stmt = $conn->prepare("UPDATE laporan SET nilai = ?, komentar = ?, dinilai_oleh = ?, dinilai_pada = NOW() WHERE id = ?");
        $stmt->bind_param("isii", $nilai, $komentar, $_SESSION['user_id'], $laporan_id);

        if ($stmt->execute()) {
            // Ambil user_id dan modul untuk notifikasi
            $queryInfo = $conn->prepare("SELECT user_id, modul_id FROM laporan WHERE id = ?");
            $queryInfo->bind_param("i", $laporan_id);
            $queryInfo->execute();
            $result = $queryInfo->get_result();
            $info = $result->fetch_assoc();

            $user_id = $info['user_id'];
            $modul_id = $info['modul_id'];

            // Ambil nama modul
            $queryModul = $conn->prepare("SELECT judul FROM modul WHERE id = ?");
            $queryModul->bind_param("i", $modul_id);
            $queryModul->execute();
            $resultModul = $queryModul->get_result();
            $modul = $resultModul->fetch_assoc();

            $pesan = "✅ Nilai untuk <strong>{$modul['judul']}</strong> sudah tersedia.";

            // Simpan notifikasi ke tabel notifikasi_mahasiswa
            $notif = $conn->prepare("INSERT INTO notifikasi_mahasiswa (user_id, icon, pesan) VALUES (?, ?, ?)");
            $icon = "✅";
            $notif->bind_param("iss", $user_id, $icon, $pesan);
            $notif->execute();

            $_SESSION['flash'] = ['type' => 'success', 'message' => 'Nilai dan komentar berhasil disimpan dan notifikasi dikirim.'];
        } else {
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'Gagal menyimpan data.'];
        }
    }
}

header("Location: laporan.php");
exit;
