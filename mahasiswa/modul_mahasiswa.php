<?php
require_once '../config.php';
session_start();

// Validasi login mahasiswa
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'mahasiswa') {
    header("Location: ../login.php");
    exit;
}

// Validasi praktikum_id dari URL
if (!isset($_GET['praktikum_id']) || !is_numeric($_GET['praktikum_id'])) {
    header("Location: my_courses.php");
    exit;
}

$praktikumId = (int) $_GET['praktikum_id'];
$userId = $_SESSION['user_id'];

$pageTitle = 'Modul Saya';
$activePage = 'modul';

require_once '../mahasiswa/templates/header_mahasiswa.php';

// Ambil nama praktikum untuk ditampilkan
$sqlNamaPraktikum = "SELECT nama FROM mata_praktikum 
                     INNER JOIN praktikum_mahasiswa ON mata_praktikum.id = praktikum_mahasiswa.praktikum_id 
                     WHERE mata_praktikum.id = ? AND praktikum_mahasiswa.user_id = ?";
$stmtNama = $conn->prepare($sqlNamaPraktikum);
$stmtNama->bind_param("ii", $praktikumId, $userId);
$stmtNama->execute();
$resNama = $stmtNama->get_result();
$praktikum = $resNama->fetch_assoc();

if (!$praktikum) {
    echo "<p class='text-red-500'>Praktikum tidak ditemukan atau Anda tidak terdaftar di praktikum ini.</p>";
    require_once '../mahasiswa/templates/footer_mahasiswa.php';
    exit;
}
?>

<h2 class="text-2xl font-bold text-gray-800 mb-4">Modul Praktikum Saya</h2>

<div class="bg-white shadow-md rounded-lg p-6 mb-6">
    <h3 class="text-xl font-semibold text-blue-700 mb-2">
        <?php echo htmlspecialchars($praktikum['nama']); ?>
    </h3>

    <?php
    // Ambil semua modul untuk praktikum ini
    $sqlModul = "SELECT * FROM modul WHERE praktikum_id = ? ORDER BY urutan ASC";
    $stmtModul = $conn->prepare($sqlModul);
    $stmtModul->bind_param("i", $praktikumId);
    $stmtModul->execute();
    $modulResult = $stmtModul->get_result();
    ?>

    <?php if ($modulResult->num_rows > 0): ?>
        <ul class="space-y-4">
            <?php while ($modul = $modulResult->fetch_assoc()): ?>
                <li class="border p-4 rounded-lg shadow-sm bg-gray-50">
                    <div class="flex justify-between items-center">
                        <div>
                            <p class="text-lg font-semibold text-gray-800">
                                Modul <?php echo $modul['urutan']; ?>: <?php echo htmlspecialchars($modul['judul']); ?>
                            </p>
                            <p class="text-gray-600 text-sm"><?php echo htmlspecialchars($modul['deskripsi']); ?></p>
                        </div>
                        <div class="flex flex-col items-end space-y-2 sm:space-y-0 sm:flex-row sm:items-center sm:space-x-2">
                            <?php if (!empty($modul['file_materi'])): ?>
                                <a href="../uploads/<?php echo htmlspecialchars($modul['file_materi']); ?>"
                                   class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded text-sm"
                                   target="_blank">
                                   See The Modul
                                </a>
                            <?php endif; ?>
                            <a href="upload_laporan.php?modul_id=<?php echo $modul['id']; ?>"
                               class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded text-sm">
                               Upload Laporan
                            </a>
                        </div>
                    </div>
                </li>
            <?php endwhile; ?>
        </ul>
    <?php else: ?>
        <p class="text-gray-500">Belum ada modul untuk praktikum ini.</p>
    <?php endif; ?>
</div>

<?php
require_once '../mahasiswa/templates/footer_mahasiswa.php';
?>
