<?php
require_once '../config.php';
session_start();

// Cek apakah user login sebagai mahasiswa
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'mahasiswa') {
    header("Location: ../login.php");
    exit;
}

$userId = $_SESSION['user_id'];

// Validasi POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['praktikum_id'])) {
    $praktikumId = intval($_POST['praktikum_id']);

    // Cek apakah sudah terdaftar
    $checkSql = "SELECT id FROM praktikum_mahasiswa WHERE user_id = ? AND praktikum_id = ?";
    $stmt = $conn->prepare($checkSql);
    $stmt->bind_param("ii", $userId, $praktikumId);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        // Sudah pernah daftar
        $stmt->close();
        header("Location: courses.php?status=already");
        exit;
    }

    $stmt->close();

    // Insert ke tabel relasi
    $insertSql = "INSERT INTO praktikum_mahasiswa (user_id, praktikum_id) VALUES (?, ?)";
    $insertStmt = $conn->prepare($insertSql);
    $insertStmt->bind_param("ii", $userId, $praktikumId);

    if ($insertStmt->execute()) {
        $insertStmt->close();
        header("Location: courses.php?status=success");
        exit;
    } else {
        $insertStmt->close();
        header("Location: courses.php?status=error");
        exit;
    }
}

// Jika tidak valid
header("Location: courses.php");
exit;
?>
