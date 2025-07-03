<?php
require_once '../config.php';

$pageTitle = 'Manajemen Praktikum';
$activePage = 'praktikum';

require_once '../asisten/templates/header.php';

// Ambil semua data praktikum
$sql = "SELECT * FROM mata_praktikum ORDER BY created_at DESC";
$result = $conn->query($sql);
?>

<div class="mb-6">
    <a href="praktikum_form.php" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition duration-200">
        + Tambah Praktikum
    </a>
</div>

<div class="bg-white shadow-md rounded-lg overflow-hidden">
    <table class="min-w-full table-auto">
        <thead class="bg-gray-200 text-gray-700">
            <tr>
                <th class="px-4 py-2 text-left">#</th>
                <th class="px-4 py-2 text-left">Nama Praktikum</th>
                <th class="px-4 py-2 text-left">Semester</th>
                <th class="px-4 py-2 text-left">Tahun Ajaran</th>
                <th class="px-4 py-2 text-left">Deskripsi</th>
                <th class="px-4 py-2 text-center">Aksi</th>
            </tr>
        </thead>
        <tbody class="text-gray-800">
            <?php if ($result && $result->num_rows > 0): ?>
                <?php $no = 1; while ($row = $result->fetch_assoc()): ?>
                    <tr class="border-b">
                        <td class="px-4 py-2"><?php echo $no++; ?></td>
                        <td class="px-4 py-2"><?php echo htmlspecialchars($row['nama']); ?></td>
                        <td class="px-4 py-2"><?php echo htmlspecialchars($row['semester']); ?></td>
                        <td class="px-4 py-2"><?php echo htmlspecialchars($row['tahun_ajaran']); ?></td>
                        <td class="px-4 py-2"><?php echo htmlspecialchars($row['deskripsi']); ?></td>
                        <td class="px-4 py-2 text-center">
                            <a href="praktikum_form.php?id=<?php echo $row['id']; ?>" class="text-yellow-500 hover:text-yellow-700 font-semibold mr-3">Edit</a>
                            <a href="praktikum_hapus.php?id=<?php echo $row['id']; ?>" onclick="return confirm('Yakin ingin menghapus praktikum ini?')" class="text-red-500 hover:text-red-700 font-semibold">Hapus</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6" class="px-4 py-4 text-center text-gray-500">Belum ada data praktikum.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php require_once '../asisten/templates/footer.php'; ?>
