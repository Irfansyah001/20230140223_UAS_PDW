<?php
require_once '../config.php';
session_start();

// Cek login dan role asisten
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'asisten') {
    header("Location: ../login.php");
    exit;
}

$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 10;
$offset = ($page - 1) * $limit;

// Hitung total user
$countSql = "SELECT COUNT(*) FROM users WHERE role IN ('mahasiswa', 'asisten')";
if ($search !== '') {
    $countSql .= " AND (nama LIKE ? OR email LIKE ?)";
    $stmt = $conn->prepare($countSql);
    $searchTerm = "%$search%";
    $stmt->bind_param("ss", $searchTerm, $searchTerm);
} else {
    $stmt = $conn->prepare($countSql);
}
$stmt->execute();
$stmt->bind_result($totalUsers);
$stmt->fetch();
$stmt->close();

$totalPages = ceil($totalUsers / $limit);

// Ambil data user
$sql = "SELECT * FROM users WHERE role IN ('mahasiswa', 'asisten')";
if ($search !== '') {
    $sql .= " AND (nama LIKE ? OR email LIKE ?)";
}
$sql .= " ORDER BY created_at DESC LIMIT ? OFFSET ?";

if ($search !== '') {
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssii", $searchTerm, $searchTerm, $limit, $offset);
} else {
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $limit, $offset);
}
$stmt->execute();
$result = $stmt->get_result();

// Template
$pageTitle = 'Manajemen Pengguna';
$activePage = 'user';
require_once 'templates/header.php';
?>

<div class="flex justify-between items-center mb-6">
    <a href="user_form.php" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">+ Tambah User</a>
</div>

<form method="GET" class="mb-4">
    <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" placeholder="Cari nama atau email..." class="border border-gray-300 px-3 py-2 rounded w-1/3" />
    <button type="submit" class="ml-2 bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded">Cari</button>
</form>

<?php if (isset($_SESSION['message'])): ?>
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-2 rounded mb-4">
        <?= $_SESSION['message'];
        unset($_SESSION['message']); ?>
    </div>
<?php endif; ?>

<div class="bg-white shadow-md rounded-lg overflow-x-auto">
    <table class="min-w-full table-auto">
        <thead class="bg-gray-200 text-gray-700">
            <tr>
                <th class="px-4 py-2 text-left">#</th>
                <th class="px-4 py-2 text-left">Nama</th>
                <th class="px-4 py-2 text-left">Email</th>
                <th class="px-4 py-2 text-left">Role</th>
                <th class="px-4 py-2 text-left">Dibuat</th>
                <th class="px-4 py-2 text-center">Aksi</th>
            </tr>
        </thead>
        <tbody class="text-gray-800">
            <?php
            $no = $offset + 1;
            while ($row = $result->fetch_assoc()): ?>
                <tr class="border-b">
                    <td class="px-4 py-2"><?= $no++; ?></td>
                    <td class="px-4 py-2"><?= htmlspecialchars($row['nama']) ?></td>
                    <td class="px-4 py-2"><?= htmlspecialchars($row['email']) ?></td>
                    <td class="px-4 py-2 capitalize"><?= $row['role'] ?></td>
                    <td class="px-4 py-2"><?= date('d M Y', strtotime($row['created_at'])) ?></td>
                    <td class="px-4 py-2 text-center">
                        <a href="user_form.php?id=<?= $row['id'] ?>" class="text-yellow-500 hover:text-yellow-700 font-semibold mr-2">Edit</a>
                        <a href="user_hapus.php?id=<?= $row['id'] ?>" onclick="return confirm('Yakin hapus user ini?')" class="text-red-500 hover:text-red-700 font-semibold">Hapus</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<!-- PAGINATION -->
<div class="mt-4 flex justify-center items-center space-x-2">
    <?php if ($page > 1): ?>
        <a href="?search=<?= urlencode($search) ?>&page=<?= $page - 1 ?>" class="px-3 py-1 bg-gray-300 rounded hover:bg-gray-400">Prev</a>
    <?php endif; ?>

    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
        <a href="?search=<?= urlencode($search) ?>&page=<?= $i ?>" class="px-3 py-1 <?= $i === $page ? 'bg-blue-600 text-white' : 'bg-gray-300 hover:bg-gray-400' ?> rounded"><?= $i ?></a>
    <?php endfor; ?>

    <?php if ($page < $totalPages): ?>
        <a href="?search=<?= urlencode($search) ?>&page=<?= $page + 1 ?>" class="px-3 py-1 bg-gray-300 rounded hover:bg-gray-400">Next</a>
    <?php endif; ?>
</div>

<?php require_once 'templates/footer.php'; ?>