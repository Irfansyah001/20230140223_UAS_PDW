<?php
$pageTitle = 'Halaman Tidak Ditemukan';
require_once 'templates/header_mahasiswa.php'; // atau header umum
?>

<div class="bg-white p-6 max-w-xl mx-auto rounded shadow mt-10 text-center">
    <h1 class="text-4xl font-bold text-red-600 mb-4">404</h1>
    <p class="text-lg text-gray-800 font-semibold mb-2">
        <?php
        // Tampilkan pesan custom jika ada
        if (isset($_GET['msg']) && !empty($_GET['msg'])) {
            echo htmlspecialchars($_GET['msg']);
        } else {
            echo "Halaman yang Anda cari tidak ditemukan.";
        }
        ?>
    </p>
    <a href="index.php" class="mt-4 inline-block bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
        Kembali ke Beranda
    </a>
</div>

<?php require_once 'templates/footer_mahasiswa.php'; ?>
