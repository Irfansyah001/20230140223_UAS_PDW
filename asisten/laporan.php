<?php
require_once '../config.php';
session_start();

// Validasi login asisten
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'asisten') {
    header("Location: ../login.php");
    exit;
}

$pageTitle = 'Laporan Masuk';
$activePage = 'laporan';
require_once '../asisten/templates/header.php';

// Ambil pilihan filter
$filterModul = $_GET['modul'] ?? '';
$filterMahasiswa = $_GET['mahasiswa'] ?? '';
$filterStatus = $_GET['status'] ?? '';

// Query dropdown
$modulList = $conn->query("SELECT id, judul FROM modul");
$mahasiswaList = $conn->query("SELECT id, nama FROM users WHERE role='mahasiswa'");

// Query laporan
$where = "WHERE 1=1";
$params = [];
$types = '';

if ($filterModul !== '') {
    $where .= " AND l.modul_id = ?";
    $params[] = $filterModul;
    $types .= 'i';
}

if ($filterMahasiswa !== '') {
    $where .= " AND l.user_id = ?";
    $params[] = $filterMahasiswa;
    $types .= 'i';
}

if ($filterStatus === 'belum') {
    $where .= " AND l.nilai IS NULL";
} elseif ($filterStatus === 'sudah') {
    $where .= " AND l.nilai IS NOT NULL";
}

$sql = "SELECT l.*, u.nama AS nama_mahasiswa, m.judul AS nama_modul 
        FROM laporan l
        JOIN users u ON l.user_id = u.id
        JOIN modul m ON l.modul_id = m.id
        $where ORDER BY l.uploaded_at DESC";

$stmt = $conn->prepare($sql);
if ($params) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();
?>

<div class="bg-white p-6 rounded-xl shadow-md">
    <h2 class="text-2xl font-bold mb-6">Laporan Masuk</h2>

    <form method="get" class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div>
            <label class="block font-semibold mb-1">Filter Modul:</label>
            <select name="modul" class="w-full border p-2 rounded">
                <option value="">Semua Modul</option>
                <?php while ($modul = $modulList->fetch_assoc()): ?>
                    <option value="<?php echo $modul['id']; ?>" <?php if ($modul['id'] == $filterModul) echo 'selected'; ?>>
                        <?php echo htmlspecialchars($modul['judul']); ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>
        <div>
            <label class="block font-semibold mb-1">Filter Mahasiswa:</label>
            <select name="mahasiswa" class="w-full border p-2 rounded">
                <option value="">Semua Mahasiswa</option>
                <?php while ($mhs = $mahasiswaList->fetch_assoc()): ?>
                    <option value="<?php echo $mhs['id']; ?>" <?php if ($mhs['id'] == $filterMahasiswa) echo 'selected'; ?>>
                        <?php echo htmlspecialchars($mhs['nama']); ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>
        <div>
            <label class="block font-semibold mb-1">Filter Status:</label>
            <select name="status" class="w-full border p-2 rounded">
                <option value="">Semua Status</option>
                <option value="belum" <?php if ($filterStatus === 'belum') echo 'selected'; ?>>Belum Dinilai</option>
                <option value="sudah" <?php if ($filterStatus === 'sudah') echo 'selected'; ?>>Sudah Dinilai</option>
            </select>
        </div>
        <div class="md:col-span-3 text-right mt-2">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">Terapkan Filter</button>
        </div>
    </form>

    <?php if (isset($_SESSION['flash'])): ?>
        <div class="mb-4 p-3 rounded 
        <?php echo $_SESSION['flash']['type'] === 'success' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'; ?>">
            <?php echo htmlspecialchars($_SESSION['flash']['message']); ?>
        </div>
        <?php unset($_SESSION['flash']); ?>
    <?php endif; ?>

    <?php if ($result->num_rows > 0): ?>
        <table class="w-full border border-collapse">
            <thead class="bg-gray-100">
                <tr>
                    <th class="border p-2">Mahasiswa</th>
                    <th class="border p-2">Modul</th>
                    <th class="border p-2">Tanggal Upload</th>
                    <th class="border p-2">File</th>
                    <th class="border p-2">Nilai</th>
                    <th class="border p-2">Komentar</th>
                    <th class="border p-2">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr class="hover:bg-gray-50">
                        <form action="nilai_laporan.php" method="post">
                            <input type="hidden" name="laporan_id" value="<?php echo $row['id']; ?>">
                            <td class="border p-2"><?php echo htmlspecialchars($row['nama_mahasiswa']); ?></td>
                            <td class="border p-2"><?php echo htmlspecialchars($row['nama_modul']); ?></td>
                            <td class="border p-2"><?php echo htmlspecialchars($row['uploaded_at']); ?></td>
                            <td class="border p-2">
                                <a href="../uploads/<?php echo htmlspecialchars($row['file_path']); ?>" target="_blank" class="text-blue-600 hover:underline">Lihat File</a>
                            </td>
                            <td class="border p-2">
                                <input type="number" name="nilai" value="<?php echo htmlspecialchars($row['nilai']); ?>" min="0" max="100" class="w-16 border rounded p-1">
                            </td>
                            <td class="border p-2">
                                <textarea name="komentar" rows="2" class="w-full border rounded p-1"><?php echo htmlspecialchars($row['komentar']); ?></textarea>
                            </td>
                            <td class="border p-2 text-center">
                                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded text-sm">Simpan</button>
                            </td>
                        </form>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p class="text-gray-600">Tidak ditemukan laporan dengan filter saat ini.</p>
    <?php endif; ?>
</div>

<?php require_once '../asisten/templates/footer.php'; ?>
