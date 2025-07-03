<?php
// Pastikan session sudah dimulai
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Cek jika pengguna belum login atau bukan asisten
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'asisten') {
    header("Location: ../login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Panel Asisten - <?php echo $pageTitle ?? 'SIMPRAK'; ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans">

<div class="flex h-screen">
    <!-- Sidebar -->
    <aside class="w-64 bg-blue-800 text-white flex flex-col">
        <div class="p-6 border-b border-blue-700">
            <h2 class="text-xl font-bold text-center">Panel Asisten</h2>
            <p class="text-sm text-center text-blue-200 mt-1"><?php echo htmlspecialchars($_SESSION['nama']); ?></p>
        </div>
        <nav class="flex-grow px-4 py-6 space-y-2">
            <?php
                // Class Tailwind untuk aktif dan tidak aktif
                $activeClass = 'bg-blue-900 text-white';
                $inactiveClass = 'text-blue-200 hover:bg-blue-700 hover:text-white';
            ?>
            <a href="dashboard.php" class="block px-4 py-2 rounded-md transition <?php echo ($activePage == 'dashboard') ? $activeClass : $inactiveClass; ?>">
                Dashboard
            </a>
            <a href="modul.php" class="block px-4 py-2 rounded-md transition <?php echo ($activePage == 'modul') ? $activeClass : $inactiveClass; ?>">
                Manajemen Modul
            </a>
            <a href="laporan.php" class="block px-4 py-2 rounded-md transition <?php echo ($activePage == 'laporan') ? $activeClass : $inactiveClass; ?>">
                Laporan Masuk
            </a>
            <a href="praktikum.php" class="block px-4 py-2 rounded-md transition <?php echo ($activePage == 'praktikum') ? $activeClass : $inactiveClass; ?>">
                Manajemen Praktikum
            </a>
            <a href="manajemen_user.php" class="block px-4 py-2 rounded-md transition <?php echo ($activePage == 'user') ? $activeClass : $inactiveClass; ?>">
                Manajemen Pengguna
            </a>
        </nav>
        <div class="p-4 border-t border-blue-700">
            <a href="../logout.php" class="block w-full bg-red-500 text-center text-white py-2 rounded hover:bg-red-600">
                Logout
            </a>
        </div>
    </aside>

    <!-- Konten utama -->
    <main class="flex-1 p-6 overflow-y-auto">
        <h1 class="text-3xl font-bold text-gray-800 mb-6"><?php echo $pageTitle ?? 'Dashboard'; ?></h1>
