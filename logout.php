<?php
// Mulai session
session_start();

// Hapus semua data session
session_unset();
session_destroy();

// Arahkan ke halaman index.html setelah logout
header("Location: index.php");
exit;
?>