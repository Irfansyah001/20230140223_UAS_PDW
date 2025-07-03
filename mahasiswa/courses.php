<?php
require_once '../config.php';
session_start();

// Validasi login mahasiswa
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'mahasiswa') {
    header("Location: ../login.php");
    exit;
}

$pageTitle = 'Cari Praktikum';
$activePage = 'courses';

require_once '../mahasiswa/templates/header_mahasiswa.php';

// Ambil semua mata praktikum
$sql = "SELECT * FROM mata_praktikum ORDER BY created_at DESC";
$result = $conn->query($sql);

// Ambil daftar praktikum yang sudah diikuti user ini
$userId = $_SESSION['user_id'];
$daftar = [];
$res = $conn->query("SELECT praktikum_id FROM praktikum_mahasiswa WHERE user_id = $userId");
while ($row = $res->fetch_assoc()) {
    $daftar[] = $row['praktikum_id'];
}
?>

<div class="bg-white shadow-md rounded-lg p-6">
    <h2 class="text-2xl font-bold text-gray-800 mb-4">Daftar Mata Praktikum Tersedia</h2>

    <?php
    if (isset($_GET['status'])) {
        if ($_GET['status'] == 'success') {
            echo '<p class="bg-green-100 text-green-700 p-2 rounded mb-4">Berhasil mendaftar ke praktikum!</p>';
        } elseif ($_GET['status'] == 'already') {
            echo '<p class="bg-yellow-100 text-yellow-700 p-2 rounded mb-4">Anda sudah terdaftar pada praktikum ini.</p>';
        } elseif ($_GET['status'] == 'error') {
            echo '<p class="bg-red-100 text-red-700 p-2 rounded mb-4">Terjadi kesalahan saat mendaftar. Silakan coba lagi.</p>';
        }
    }
    ?>

    <?php if ($result && $result->num_rows > 0): ?>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="border rounded-lg p-4 shadow">
                    <h3 class="text-xl font-semibold text-blue-700 mb-1"><?php echo htmlspecialchars($row['nama']); ?></h3>
                    <p class="text-gray-600 mb-2">
                        <strong>Semester:</strong> <?php echo htmlspecialchars($row['semester']); ?> |
                        <strong>TA:</strong> <?php echo htmlspecialchars($row['tahun_ajaran']); ?>
                    </p>
                    <p class="text-gray-700 mb-3"><?php echo nl2br(htmlspecialchars($row['deskripsi'])); ?></p>

                    <?php if (in_array($row['id'], $daftar)): ?>
                        <button class="bg-green-100 text-green-600 font-semibold px-4 py-2 rounded" disabled>
                            ✅ Sudah Terdaftar
                        </button>
                    <?php else: ?>
                        <form action="daftar_praktikum.php" method="post" onsubmit="return confirm('Yakin ingin mendaftar ke praktikum ini?')">
                            <input type="hidden" name="praktikum_id" value="<?php echo $row['id']; ?>">
                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
                                Daftar Praktikum
                            </button>
                        </form>
                    <?php endif; ?>
                </div>
            <?php endwhile; ?>
        </div>
    <?php else: ?>
        <p class="text-gray-500">Belum ada data praktikum.</p>
    <?php endif; ?>
</div>

<?php require_once '../mahasiswa/templates/footer_mahasiswa.php'; ?>
