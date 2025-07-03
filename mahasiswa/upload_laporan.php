<?php
require_once '../config.php';
session_start();

// Validasi login mahasiswa
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'mahasiswa') {
    header("Location: ../login.php");
    exit;
}

// Validasi input modul
if (!isset($_GET['modul_id']) || !is_numeric($_GET['modul_id'])) {
    header("Location: my_courses.php");
    exit;
}

$modul_id = (int) $_GET['modul_id'];
$user_id = $_SESSION['user_id'];
$message = '';

// Proses jika form disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_FILES['laporan']) && $_FILES['laporan']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['laporan']['tmp_name'];
        $fileName = basename($_FILES['laporan']['name']);
        $fileSize = $_FILES['laporan']['size'];
        $fileType = $_FILES['laporan']['type'];

        $ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        $allowed = ['pdf', 'docx', 'doc'];

        if ($fileSize > 5 * 1024 * 1024) {
            $message = "Ukuran file terlalu besar. Maksimum 5MB.";
        } elseif (!in_array($ext, $allowed)) {
            $message = "Format file tidak diperbolehkan. Gunakan PDF, DOCX, atau DOC.";
        } else {
            $uploadDir = '../uploads/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            $newFileName = "laporan_{$user_id}_modul{$modul_id}_" . time() . "." . $ext;
            $destPath = $uploadDir . $newFileName;

            if (move_uploaded_file($fileTmpPath, $destPath)) {
                // Cek apakah sudah ada laporan sebelumnya
                $check = $conn->prepare("SELECT id FROM laporan WHERE user_id = ? AND modul_id = ?");
                $check->bind_param("ii", $user_id, $modul_id);
                $check->execute();
                $check->store_result();

                if ($check->num_rows > 0) {
                    // Update
                    $update = $conn->prepare("UPDATE laporan SET file_path = ?, uploaded_at = NOW() WHERE user_id = ? AND modul_id = ?");
                    $update->bind_param("sii", $newFileName, $user_id, $modul_id);
                    $update->execute();
                } else {
                    // Insert baru
                    $insert = $conn->prepare("INSERT INTO laporan (user_id, modul_id, file_path, uploaded_at) VALUES (?, ?, ?, NOW())");
                    $insert->bind_param("iis", $user_id, $modul_id, $newFileName);
                    $insert->execute();
                }

                // Ambil nama modul untuk notifikasi
                $stmtModul = $conn->prepare("SELECT judul FROM modul WHERE id = ?");
                $stmtModul->bind_param("i", $modul_id);
                $stmtModul->execute();
                $resultModul = $stmtModul->get_result();
                $modul = $resultModul->fetch_assoc();

                if ($modul) {
                    $icon = "📤";
                    $pesan = "Laporan untuk <strong>{$modul['judul']}</strong> berhasil diunggah.";

                    $notif = $conn->prepare("INSERT INTO notifikasi_mahasiswa (user_id, icon, pesan) VALUES (?, ?, ?)");
                    $notif->bind_param("iss", $user_id, $icon, $pesan);
                    $notif->execute();
                }

                $message = "Laporan berhasil diunggah.";
            } else {
                $message = "Gagal mengunggah file.";
            }
        }
    } else {
        $message = "Tidak ada file yang diunggah atau terjadi kesalahan.";
    }
}
?>

<?php
$pageTitle = 'Upload Laporan';
$activePage = '';
require_once '../mahasiswa/templates/header_mahasiswa.php';
?>

<div class="bg-white p-6 rounded shadow-md max-w-lg mx-auto">
    <h2 class="text-2xl font-bold mb-4">Upload Laporan</h2>

    <?php if (!empty($message)): ?>
        <p class="mb-4 p-3 rounded text-white 
            <?php echo (strpos($message, 'berhasil') !== false) ? 'bg-green-500' : 'bg-red-500'; ?>">
            <?php echo $message; ?>
        </p>
    <?php endif; ?>

    <form action="?modul_id=<?php echo $modul_id; ?>" method="post" enctype="multipart/form-data">
        <div class="mb-4">
            <label for="laporan" class="block font-semibold mb-2">Pilih File Laporan:</label>
            <input type="file" name="laporan" id="laporan" required class="border p-2 w-full">
            <p class="text-sm text-gray-500 mt-1">Hanya PDF, DOC, atau DOCX. Max 5MB.</p>
        </div>
        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">Upload</button>
    </form>
</div>

<?php require_once '../mahasiswa/templates/footer_mahasiswa.php'; ?>
