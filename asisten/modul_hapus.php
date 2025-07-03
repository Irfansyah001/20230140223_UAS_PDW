<?php
require_once '../config.php';
session_start();

// Cek login dan role
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'asisten') {
    header("Location: ../login.php");
    exit;
}

// Pastikan ID dikirim
if (!isset($_GET['id'])) {
    header("Location: modul.php");
    exit;
}

$id = intval($_GET['id']);

// Ambil nama file materi jika ada
$stmt = $conn->prepare("SELECT file_materi FROM modul WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$file = '';
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $file = $row['file_materi'];
}
$stmt->close();

// Hapus dari database
$stmtDel = $conn->prepare("DELETE FROM modul WHERE id = ?");
$stmtDel->bind_param("i", $id);
$stmtDel->execute();
$stmtDel->close();

// Hapus file PDF jika ada
if (!empty($file)) {
    $filePath = '../uploads/' . $file;
    if (file_exists($filePath)) {
        unlink($filePath);
    }
}

// Redirect kembali ke halaman modul
header("Location: modul.php");
exit;
?>
