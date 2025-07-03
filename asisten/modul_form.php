<?php
require_once '../config.php';

$pageTitle = 'Form Modul';
$activePage = 'modul';

$editMode = false;
$modul = [
    'id' => '',
    'praktikum_id' => '',
    'judul' => '',
    'deskripsi' => '',
    'file_materi' => ''
];

// Ambil data modul jika mode edit
if (isset($_GET['id'])) {
    $editMode = true;
    $id = intval($_GET['id']);
    $stmt = $conn->prepare("SELECT * FROM modul WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $modul = $result->fetch_assoc();
    $stmt->close();
}

// Ambil semua data praktikum untuk dropdown
$praktikumList = [];
$result = $conn->query("SELECT * FROM mata_praktikum ORDER BY nama ASC");
while ($row = $result->fetch_assoc()) {
    $praktikumList[] = $row;
}

// Proses submit form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $judul = trim($_POST['judul']);
    $deskripsi = trim($_POST['deskripsi']);
    $praktikum_id = intval($_POST['praktikum_id']);

    $file_materi = $modul['file_materi'];
    if (isset($_FILES['file_materi']) && $_FILES['file_materi']['error'] === UPLOAD_ERR_OK) {
        $tmpName = $_FILES['file_materi']['tmp_name'];
        $fileName = basename($_FILES['file_materi']['name']);
        $targetPath = '../uploads/' . $fileName;
        move_uploaded_file($tmpName, $targetPath);
        $file_materi = $fileName;
    }

    if ($editMode) {
        $stmt = $conn->prepare("UPDATE modul SET praktikum_id = ?, judul = ?, deskripsi = ?, file_materi = ? WHERE id = ?");
        $stmt->bind_param("isssi", $praktikum_id, $judul, $deskripsi, $file_materi, $modul['id']);
    } else {
        $stmt = $conn->prepare("INSERT INTO modul (praktikum_id, judul, deskripsi, file_materi) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("isss", $praktikum_id, $judul, $deskripsi, $file_materi);
    }

    if ($stmt->execute()) {
        header("Location: modul.php");
        exit();
    }

    $stmt->close();
}

require_once '../asisten/templates/header.php';
?>

<!-- FORM MODUL -->
<div class="bg-white shadow-md rounded-lg p-6 max-w-2xl mx-auto">
    <h2 class="text-2xl font-bold text-gray-800 mb-6">
        <?php echo $editMode ? 'Edit Modul' : 'Tambah Modul'; ?>
    </h2>
    <form action="" method="post" enctype="multipart/form-data" class="space-y-4">
        
        <div>
            <label for="praktikum_id" class="block font-medium text-gray-700 mb-1">Pilih Praktikum</label>
            <select name="praktikum_id" id="praktikum_id" required class="w-full border border-gray-300 rounded px-3 py-2">
                <option value="">-- Pilih --</option>
                <?php foreach ($praktikumList as $praktikum): ?>
                    <option value="<?php echo $praktikum['id']; ?>" <?php echo ($praktikum['id'] == $modul['praktikum_id']) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($praktikum['nama']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div>
            <label for="judul" class="block font-medium text-gray-700 mb-1">Judul Modul</label>
            <input type="text" name="judul" id="judul" value="<?php echo htmlspecialchars($modul['judul']); ?>" required class="w-full border border-gray-300 rounded px-3 py-2">
        </div>

        <div>
            <label for="deskripsi" class="block font-medium text-gray-700 mb-1">Deskripsi</label>
            <textarea name="deskripsi" id="deskripsi" rows="4" class="w-full border border-gray-300 rounded px-3 py-2"><?php echo htmlspecialchars($modul['deskripsi']); ?></textarea>
        </div>

        <div>
            <label for="file_materi" class="block font-medium text-gray-700 mb-1">File Materi (PDF)</label>
            <input type="file" name="file_materi" id="file_materi" class="block w-full text-sm text-gray-600">
            <?php if ($editMode && $modul['file_materi']): ?>
                <p class="text-sm mt-1">Saat ini: 
                    <a href="../uploads/<?php echo $modul['file_materi']; ?>" class="text-blue-600 underline" target="_blank">
                        <?php echo $modul['file_materi']; ?>
                    </a>
                </p>
            <?php endif; ?>
        </div>

        <div class="flex justify-end">
            <a href="modul.php" class="bg-gray-300 text-gray-800 px-4 py-2 rounded mr-2">Batal</a>
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                <?php echo $editMode ? 'Simpan Perubahan' : 'Tambah Modul'; ?>
            </button>
        </div>
    </form>
</div>

<?php require_once '../asisten/templates/footer.php'; ?>
