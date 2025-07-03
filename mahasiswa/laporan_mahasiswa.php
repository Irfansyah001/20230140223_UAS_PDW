<?php
require_once '../config.php';
session_start();

// Validasi login mahasiswa
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'mahasiswa') {
    header("Location: ../login.php");
    exit;
}

$pageTitle = 'Laporan Saya';
$activePage = 'laporan';
require_once '..mahasiswa/templates/header_mahasiswa.php';

$user_id = $_SESSION['user_id'];

// Ambil laporan milik mahasiswa ini
$sql = "SELECT l.*, m.nama AS nama_modul 
        FROM laporan l 
        JOIN modul m ON l.modul_id = m.id 
        WHERE l.user_id = ?
        ORDER BY l.uploaded_at DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<div class="bg-white p-6 rounded shadow-md">
    <h2 class="text-2xl font-bold mb-4">Daftar Laporan yang Telah Diunggah</h2>

    <?php if ($result && $result->num_rows > 0): ?>
        <table class="w-full table-auto border border-collapse">
            <thead class="bg-gray-100">
                <tr>
                    <th class="border p-2">Modul</th>
                    <th class="border p-2">Tanggal Upload</th>
                    <th class="border p-2">File</th>
                    <th class="border p-2">Nilai</th>
                    <th class="border p-2">Komentar</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr class="hover:bg-gray-50">
                        <td class="border p-2"><?php echo htmlspecialchars($row['nama_modul']); ?></td>
                        <td class="border p-2"><?php echo htmlspecialchars($row['uploaded_at']); ?></td>
                        <td class="border p-2">
                            <a href="../uploads/<?php echo htmlspecialchars($row['file_path']); ?>" target="_blank" class="text-blue-600 hover:underline">Lihat File</a>
                        </td>
                        <td class="border p-2 text-center">
                            <?php echo is_null($row['nilai']) ? '<span class="text-yellow-600">Belum Dinilai</span>' : htmlspecialchars($row['nilai']); ?>
                        </td>
                        <td class="border p-2">
                            <?php echo htmlspecialchars($row['komentar'] ?? '-'); ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p class="text-gray-600">Belum ada laporan yang diunggah.</p>
    <?php endif; ?>
</div>

<?php require_once '../mahasiswa/templates/footer_mahasiswa.php'; ?>
