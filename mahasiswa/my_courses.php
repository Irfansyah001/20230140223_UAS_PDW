<?php
require_once '../config.php';
session_start();

// Validasi login mahasiswa
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'mahasiswa') {
    header("Location: ../login.php");
    exit;
}

$pageTitle = 'Praktikum Saya';
$activePage = 'my_courses';

require_once '../mahasiswa/templates/header_mahasiswa.php';

// Ambil daftar praktikum yang sudah diikuti mahasiswa ini
$userId = $_SESSION['user_id'];
$sql = "SELECT mp.id AS praktikum_id, mp.nama, mp.semester, mp.tahun_ajaran, mp.deskripsi 
        FROM mata_praktikum mp
        INNER JOIN praktikum_mahasiswa pm ON mp.id = pm.praktikum_id
        WHERE pm.user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
?>

<div class="bg-white shadow-md rounded-lg p-6">
    <h2 class="text-2xl font-bold text-gray-800 mb-4">Praktikum yang Kamu Ikuti</h2>

    <?php if ($result && $result->num_rows > 0): ?>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="border rounded-lg p-4 shadow">
                    <h3 class="text-xl font-semibold text-blue-700 mb-1">
                        <?php echo htmlspecialchars($row['nama']); ?>
                    </h3>
                    <p class="text-gray-600 mb-2">
                        <strong>Semester:</strong> <?php echo htmlspecialchars($row['semester']); ?> |
                        <strong>TA:</strong> <?php echo htmlspecialchars($row['tahun_ajaran']); ?>
                    </p>
                    <p class="text-gray-700 mb-3"><?php echo nl2br(htmlspecialchars($row['deskripsi'])); ?></p>
                    <div class="mt-2 flex gap-2">
                        <a href="modul_mahasiswa.php?praktikum_id=<?php echo $row['praktikum_id']; ?>" 
                           class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-1 rounded text-sm">
                           Lihat Modul
                        </a>
                        <a href="rekap_nilai.php?praktikum_id=<?php echo $row['praktikum_id']; ?>" 
                           class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-1 rounded text-sm">
                           Lihat Nilai
                        </a>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    <?php else: ?>
        <p class="text-gray-500">Kamu belum mendaftar ke praktikum manapun.</p>
    <?php endif; ?>
</div>

<?php
require_once '../mahasiswa/templates/footer_mahasiswa.php';
?>
