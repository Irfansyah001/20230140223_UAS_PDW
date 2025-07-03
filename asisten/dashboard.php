<?php
require_once '../config.php';
session_start();

// Validasi login asisten
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'asisten') {
    header("Location: ../login.php");
    exit;
}

$pageTitle = 'Dashboard';
$activePage = 'dashboard';
require_once '../asisten/templates/header.php';

// Query statistik
$totalMahasiswa = $conn->query("SELECT COUNT(*) as total FROM users WHERE role = 'mahasiswa'")->fetch_assoc()['total'];
$totalModul     = $conn->query("SELECT COUNT(*) as total FROM modul")->fetch_assoc()['total'];
$totalLaporan   = $conn->query("SELECT COUNT(*) as total FROM laporan")->fetch_assoc()['total'];
?>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    <div class="bg-white shadow-md rounded p-6">
        <h3 class="text-lg font-semibold text-gray-700 mb-2">Jumlah Mahasiswa</h3>
        <p class="text-3xl font-bold text-green-500"><?php echo $totalMahasiswa; ?></p>
    </div>
    <div class="bg-white shadow-md rounded p-6">
        <h3 class="text-lg font-semibold text-gray-700 mb-2">Jumlah Modul</h3>
        <p class="text-3xl font-bold text-blue-500"><?php echo $totalModul; ?></p>
    </div>
    <div class="bg-white shadow-md rounded p-6">
        <h3 class="text-lg font-semibold text-gray-700 mb-2">Jumlah Laporan</h3>
        <p class="text-3xl font-bold text-purple-500"><?php echo $totalLaporan; ?></p>
    </div>
</div>

<!-- Notifikasi Mahasiswa Mengunggah Laporan -->
<div class="mt-8 bg-white p-6 rounded-xl shadow-md">
    <h3 class="text-2xl font-bold text-gray-800 mb-4">Notifikasi Laporan Terbaru</h3>
    <ul class="space-y-4">
        <?php
        $notifQuery = "SELECT l.uploaded_at, u.nama AS mahasiswa, m.judul AS modul 
                       FROM laporan l 
                       JOIN users u ON l.user_id = u.id 
                       JOIN modul m ON l.modul_id = m.id 
                       ORDER BY l.uploaded_at DESC 
                       LIMIT 5";
        $notifResult = $conn->query($notifQuery);
        if ($notifResult && $notifResult->num_rows > 0):
            while ($notif = $notifResult->fetch_assoc()):
        ?>
            <li class="flex items-start p-3 border-b border-gray-100 last:border-b-0">
                <span class="text-xl mr-4">📥</span>
                <div>
                    <p class="font-semibold text-gray-700">
                        <?php echo htmlspecialchars($notif['mahasiswa']); ?> mengunggah laporan untuk <strong><?php echo htmlspecialchars($notif['modul']); ?></strong>
                    </p>
                    <p class="text-xs text-gray-500"><?php echo date('d M Y, H:i', strtotime($notif['uploaded_at'])); ?></p>
                </div>
            </li>
        <?php
            endwhile;
        else:
        ?>
            <li class="text-gray-500">Belum ada laporan terbaru.</li>
        <?php endif; ?>
    </ul>
</div>

<?php require_once '../asisten/templates/footer.php'; ?>
