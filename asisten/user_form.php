<?php
require_once '../config.php';
session_start();

// Cek login & role
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'asisten') {
    header("Location: ../login.php");
    exit;
}

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$isEdit = $id > 0;
$error = '';
$nama = $email = $password = $role = '';

// Handle saat form disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = trim($_POST['nama']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $role = $_POST['role'];

    if (empty($nama) || empty($email) || ($isEdit ? false : empty($password)) || empty($role)) {
        $error = "Semua field wajib diisi.";
    } else {
        if ($isEdit) {
            // Update user (tanpa update password jika kosong)
            if (!empty($password)) {
                $stmt = $conn->prepare("UPDATE users SET nama=?, email=?, password=?, role=? WHERE id=?");
                $stmt->bind_param("ssssi", $nama, $email, $password, $role, $id);
            } else {
                $stmt = $conn->prepare("UPDATE users SET nama=?, email=?, role=? WHERE id=?");
                $stmt->bind_param("sssi", $nama, $email, $role, $id);
            }
        } else {
            // Insert user baru
            $stmt = $conn->prepare("INSERT INTO users (nama, email, password, role) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $nama, $email, $password, $role);
        }

        if ($stmt->execute()) {
            header("Location: manajemen_user.php");
            exit;
        } else {
            $error = "Terjadi kesalahan: " . $stmt->error;
        }
    }
}

// Jika edit, ambil data user
if ($isEdit && $_SERVER['REQUEST_METHOD'] === 'GET') {
    $stmt = $conn->prepare("SELECT * FROM users WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        $nama = $row['nama'];
        $email = $row['email'];
        $role = $row['role'];
    } else {
        $error = "User tidak ditemukan.";
    }
}

$pageTitle = $isEdit ? "Edit User" : "Tambah User";
$activePage = 'user';
require_once 'templates/header.php';
?>

<h2 class="text-2xl font-bold mb-4"><?= $isEdit ? "Edit Akun Pengguna" : "Tambah Akun Pengguna" ?></h2>

<?php if (!empty($error)): ?>
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
        <?= $error ?>
    </div>
<?php endif; ?>

<form method="POST" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4 w-full md:w-2/3 lg:w-1/2">
    <div class="mb-4">
        <label class="block text-gray-700 font-bold mb-2">Nama</label>
        <input type="text" name="nama" value="<?= htmlspecialchars($nama) ?>" class="shadow border rounded w-full py-2 px-3 text-gray-700" required>
    </div>

    <div class="mb-4">
        <label class="block text-gray-700 font-bold mb-2">Email</label>
        <input type="email" name="email" value="<?= htmlspecialchars($email) ?>" class="shadow border rounded w-full py-2 px-3 text-gray-700" required>
    </div>

    <div class="mb-4">
        <label class="block text-gray-700 font-bold mb-2">
            Password <?= $isEdit ? '(kosongkan jika tidak diubah)' : '' ?>
        </label>
        <input type="password" name="password" class="shadow border rounded w-full py-2 px-3 text-gray-700" <?= $isEdit ? '' : 'required' ?>>
    </div>

    <div class="mb-6">
        <label class="block text-gray-700 font-bold mb-2">Role</label>
        <select name="role" class="shadow border rounded w-full py-2 px-3 text-gray-700" required>
            <option value="">-- Pilih Role --</option>
            <option value="mahasiswa" <?= $role === 'mahasiswa' ? 'selected' : '' ?>>Mahasiswa</option>
            <option value="asisten" <?= $role === 'asisten' ? 'selected' : '' ?>>Asisten</option>
        </select>
    </div>

    <div class="flex items-center justify-between">
        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
            Simpan
        </button>
        <a href="manajemen_user.php" class="text-blue-500 hover:underline">Kembali</a>
    </div>
</form>

<?php require_once 'templates/footer.php'; ?>
