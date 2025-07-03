<?php
require_once 'config.php'; // Koneksi DB

// Ambil semua mata praktikum dari database
$query = "SELECT * FROM mata_praktikum ORDER BY tahun_ajaran DESC, semester";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Katalog Mata Praktikum - SIMPRAK</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans">

    <nav class="bg-blue-600 shadow-md">
        <div class="max-w-7xl mx-auto px-4 py-4 flex justify-between items-center">
            <h1 class="text-white font-bold text-xl">SIMPRAK - Katalog Praktikum</h1>
            <div class="space-x-4">
                <a href="login.php" class="bg-white text-blue-600 font-semibold px-4 py-2 rounded hover:bg-gray-100">Login</a>
                <a href="register.php" class="bg-white text-blue-600 font-semibold px-4 py-2 rounded hover:bg-gray-100">Register</a>
            </div>
        </div>
    </nav>

    <main class="max-w-5xl mx-auto p-6">
        <h2 class="text-2xl font-bold mb-6 text-gray-800">Daftar Mata Praktikum Tersedia</h2>

        <?php if ($result && $result->num_rows > 0): ?>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <?php while ($row = $result->fetch_assoc()): ?>
                    <div class="bg-white rounded-lg shadow-md p-5 border border-gray-200">
                        <h3 class="text-xl font-semibold text-blue-700"><?php echo htmlspecialchars($row['nama']); ?></h3>
                        <p class="text-gray-600 mt-1"><?php echo htmlspecialchars($row['deskripsi']); ?></p>
                        <p class="mt-2 text-sm text-gray-500">Semester: <strong><?php echo $row['semester']; ?></strong></p>
                        <p class="text-sm text-gray-500">Tahun Ajaran: <strong><?php echo $row['tahun_ajaran']; ?></strong></p>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <p class="text-gray-600">Belum ada data mata praktikum yang tersedia.</p>
        <?php endif; ?>
    </main>

    <footer class="bg-gray-200 text-center text-sm py-4 mt-10">
        &copy; <?php echo date('Y'); ?> SIMPRAK - Sistem Informasi Praktikum
    </footer>

</body>
</html>
