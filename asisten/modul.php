<?php
require_once '../config.php';

$sql = "SELECT modul.*, mata_praktikum.nama AS nama_praktikum
        FROM modul
        JOIN mata_praktikum ON modul.praktikum_id = mata_praktikum.id
        ORDER BY modul.urutan ASC";
$result = $conn->query($sql);

// Inisialisasi template
$pageTitle = 'Manajemen Modul';
$activePage = 'modul';
require_once '../asisten/templates/header.php';
?>

<!-- Konten -->
<div class="mb-6">
    <a href="modul_form.php" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition duration-200">
        + Tambah Modul
    </a>
</div>

<div class="bg-white shadow-md rounded-lg overflow-hidden">
    <table class="min-w-full table-auto">
        <thead class="bg-gray-200 text-gray-700">
            <tr>
                <th class="px-4 py-2 text-left">#</th>
                <th class="px-4 py-2 text-left">Judul Modul</th>
                <th class="px-4 py-2 text-left">Praktikum</th>
                <th class="px-4 py-2 text-left">File</th>
                <th class="px-4 py-2 text-left">Tanggal Upload</th>
                <th class="px-4 py-2 text-center">Aksi</th>
            </tr>
        </thead>
        <tbody class="text-gray-800">
            <?php if ($result && $result->num_rows > 0): ?>
                <?php $no = 1; while ($row = $result->fetch_assoc()): ?>
                    <tr class="border-b">
                        <td class="px-4 py-2"><?php echo $no++; ?></td>
                        <td class="px-4 py-2"><?php echo htmlspecialchars($row['judul']); ?></td>
                        <td class="px-4 py-2"><?php echo htmlspecialchars($row['nama_praktikum']); ?></td>
                        <td class="px-4 py-2">
                            <?php if (!empty($row['file_materi'])): ?>
                                <a href="../uploads/<?php echo $row['file_materi']; ?>" class="text-blue-600 underline" target="_blank">Unduh</a>
                            <?php else: ?>
                                <span class="text-gray-400 italic">Tidak ada</span>
                            <?php endif; ?>
                        </td>
                        <td class="px-4 py-2"><?php echo date('d M Y, H:i', strtotime($row['created_at'])); ?></td>
                        <td class="px-4 py-2 text-center">
                            <a href="modul_form.php?id=<?php echo $row['id']; ?>" class="text-yellow-500 hover:text-yellow-700 font-semibold mr-3">Edit</a>
                            <a href="modul_hapus.php?id=<?php echo $row['id']; ?>" onclick="return confirm('Yakin ingin menghapus modul ini?')" class="text-red-500 hover:text-red-700 font-semibold">Hapus</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6" class="px-4 py-4 text-center text-gray-500">Belum ada modul ditambahkan.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php require_once '../asisten/templates/footer.php'; ?>
