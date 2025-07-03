<?php
require_once '../config.php';
session_start();

// Pastikan user yang login adalah asisten
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'asisten') {
    header("Location: ../login.php");
    exit;
}

if (isset($_GET['id'])) {
    $id = (int) $_GET['id'];

    // Cegah penghapusan user yang sedang login
    if ($_SESSION['user_id'] == $id) {
        $_SESSION['message'] = "Tidak bisa menghapus akun sendiri.";
        header("Location: manajemen_user.php");
        exit;
    }

    // Eksekusi query hapus
    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        $_SESSION['message'] = "Akun berhasil dihapus.";
    } else {
        $_SESSION['message'] = "Gagal menghapus akun.";
    }

    $stmt->close();
}

header("Location: manajemen_user.php");
exit;
