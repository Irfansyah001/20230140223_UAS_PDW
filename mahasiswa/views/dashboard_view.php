<?php
require_once '../config.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Cek login
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'mahasiswa') {
    header("Location: ../login.php");
    exit;
}

$userId = $_SESSION['user_id'];
$namaUser = $_SESSION['nama'];

// Total praktikum
$stmt1 = $conn->prepare("SELECT COUNT(*) FROM praktikum_mahasiswa WHERE user_id = ?");
$stmt1->bind_param("i", $userId);
$stmt1->execute();
$stmt1->bind_result($totalPraktikum);
$stmt1->fetch();
$stmt1->close();

// Total laporan dikumpulkan (anggap = tugas selesai)
$stmt2 = $conn->prepare("SELECT COUNT(*) FROM laporan WHERE user_id = ?");
$stmt2->bind_param("i", $userId);
$stmt2->execute();
$stmt2->bind_result($tugasSelesai);
$stmt2->fetch();
$stmt2->close();

// Total modul dari praktikum yang diikuti
$sqlModul = "SELECT COUNT(*) FROM modul 
             WHERE praktikum_id IN (
                 SELECT praktikum_id FROM praktikum_mahasiswa WHERE user_id = ?
             )";
$stmt3 = $conn->prepare($sqlModul);
$stmt3->bind_param("i", $userId);
$stmt3->execute();
$stmt3->bind_result($totalModul);
$stmt3->fetch();
$stmt3->close();

// Tugas Menunggu = total modul - laporan yang sudah dikumpulkan
$tugasPending = $totalModul - $tugasSelesai;
if ($tugasPending < 0) $tugasPending = 0; // mencegah minus
?>

<!-- TAMPILAN -->
<div class="bg-gradient-to-r from-blue-500 to-cyan-400 text-white p-8 rounded-xl shadow-lg mb-8">
    <h1 class="text-3xl font-bold">Selamat Datang Kembali, <?php echo htmlspecialchars($namaUser); ?>!</h1>
    <p class="mt-2 opacity-90">Terus semangat dalam menyelesaikan semua modul praktikummu.</p>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
    <div class="bg-white p-6 rounded-xl shadow-md flex flex-col items-center justify-center">
        <div class="text-5xl font-extrabold text-blue-600"><?php echo $totalPraktikum; ?></div>
        <div class="mt-2 text-lg text-gray-600">Praktikum Diikuti</div>
    </div>

    <div class="bg-white p-6 rounded-xl shadow-md flex flex-col items-center justify-center">
        <div class="text-5xl font-extrabold text-green-500"><?php echo $tugasSelesai; ?></div>
        <div class="mt-2 text-lg text-gray-600">Tugas Selesai</div>
    </div>

    <div class="bg-white p-6 rounded-xl shadow-md flex flex-col items-center justify-center">
        <div class="text-5xl font-extrabold text-yellow-500"><?php echo $tugasPending; ?></div>
        <div class="mt-2 text-lg text-gray-600">Tugas Menunggu</div>
    </div>
</div>

<!-- NOTIFIKASI TERBARU -->
<div class="bg-white p-6 rounded-xl shadow-md">
    <h3 class="text-2xl font-bold text-gray-800 mb-4">Notifikasi Terbaru</h3>
    <ul class="space-y-4">
        <?php
        $sqlNotif = "SELECT icon, pesan FROM notifikasi_mahasiswa 
                     WHERE user_id = ? ORDER BY created_at DESC LIMIT 3";
        $stmtNotif = $conn->prepare($sqlNotif);
        $stmtNotif->bind_param("i", $userId);
        $stmtNotif->execute();
        $result = $stmtNotif->get_result();
        while ($row = $result->fetch_assoc()):
        ?>
            <li class="flex items-start p-3 border-b border-gray-100 last:border-b-0">
                <span class="text-xl mr-4"><?php echo htmlspecialchars($row['icon']); ?></span>
                <div><?php echo $row['pesan']; ?></div>
            </li>
        <?php endwhile; ?>
    </ul>
</div>
