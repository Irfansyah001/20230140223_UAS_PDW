<?php
require_once '../config.php';
session_start();

// Validasi login dan role asisten
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'asisten') {
    header("Location: ../login.php");
    exit;
}

// Pastikan ada ID yang dikirim
if (!isset($_GET['id'])) {
    header("Location: praktikum.php");
    exit;
}

$id = intval($_GET['id']);

// Hapus data dari tabel
$stmt = $conn->prepare("DELETE FROM mata_praktikum WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->close();

// Kembali ke halaman utama
header("Location: praktikum.php");
exit;
?>
