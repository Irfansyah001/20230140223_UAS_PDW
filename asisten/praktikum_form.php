<?php
require_once '../config.php';

$pageTitle = 'Form Praktikum';
$activePage = 'praktikum';

$editMode = false;
$praktikum = [
    'id' => '',
    'nama' => '',
    'deskripsi' => '',
    'semester' => '',
    'tahun_ajaran' => ''
];

// Jika ada ID → Edit Mode
if (isset($_GET['id'])) {
    $editMode = true;
    $id = intval($_GET['id']);
    $stmt = $conn->prepare("SELECT * FROM mata_praktikum WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $praktikum = $result->fetch_assoc();
    $stmt->close();
}

// Submit Form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = trim($_POST['nama']);
    $deskripsi = trim($_POST['deskripsi']);
    $semester = trim($_POST['semester']);
    $tahun_ajaran = trim($_POST['tahun_ajaran']);

    if ($editMode) {
        $stmt = $conn->prepare("UPDATE mata_praktikum SET nama=?, deskripsi=?, semester=?, tahun_ajaran=? WHERE id=?");
        $stmt->bind_param("ssssi", $nama, $deskripsi, $semester, $tahun_ajaran, $praktikum['id']);
    } else {
        $stmt = $conn->prepare("INSERT INTO mata_praktikum (nama, deskripsi, semester, tahun_ajaran) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $nama, $deskripsi, $semester, $tahun_ajaran);
    }

    if ($stmt->execute()) {
        header("Location: praktikum.php");
        exit();
    }
    $stmt->close();
}

require_once '../asisten/templates/header.php';
?>

<div class="bg-white shadow-md rounded-lg p-6 max-w-2xl mx-auto">
    <h2 class="text-2xl font-bold text-gray-800 mb-6">
        <?php echo $editMode ? 'Edit Praktikum' : 'Tambah Praktikum'; ?>
    </h2>
    <form action="" method="post" class="space-y-4">

        <div>
            <label for="nama" class="block font-medium text-gray-700 mb-1">Nama Praktikum</label>
            <input type="text" name="nama" id="nama" required value="<?php echo htmlspecialchars($praktikum['nama']); ?>" class="w-full border border-gray-300 rounded px-3 py-2">
        </div>

        <div>
            <label for="semester" class="block font-medium text-gray-700 mb-1">Semester</label>
            <select name="semester" id="semester" required class="w-full border border-gray-300 rounded px-3 py-2">
                <option value="">-- Pilih Semester --</option>
                <option value="Ganjil" <?php echo ($praktikum['semester'] === 'Ganjil') ? 'selected' : ''; ?>>Ganjil</option>
                <option value="Genap" <?php echo ($praktikum['semester'] === 'Genap') ? 'selected' : ''; ?>>Genap</option>
            </select>
        </div>

        <div>
            <label for="tahun_ajaran" class="block font-medium text-gray-700 mb-1">Tahun Ajaran</label>
            <input type="text" name="tahun_ajaran" id="tahun_ajaran" required placeholder="Contoh: 2024/2025" value="<?php echo htmlspecialchars($praktikum['tahun_ajaran']); ?>" class="w-full border border-gray-300 rounded px-3 py-2">
        </div>

        <div>
            <label for="deskripsi" class="block font-medium text-gray-700 mb-1">Deskripsi</label>
            <textarea name="deskripsi" id="deskripsi" rows="4" class="w-full border border-gray-300 rounded px-3 py-2"><?php echo htmlspecialchars($praktikum['deskripsi']); ?></textarea>
        </div>

        <div class="flex justify-end">
            <a href="praktikum.php" class="bg-gray-300 text-gray-800 px-4 py-2 rounded mr-2">Batal</a>
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                <?php echo $editMode ? 'Simpan Perubahan' : 'Tambah Praktikum'; ?>
            </button>
        </div>
    </form>
</div>

<?php require_once '../asisten/templates/footer.php'; ?>
