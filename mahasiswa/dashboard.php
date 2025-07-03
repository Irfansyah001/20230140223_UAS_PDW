<?php
session_start();

// Variabel untuk title dan active menu
$pageTitle = 'Dashboard';
$activePage = 'dashboard';

// Panggil header mahasiswa
require_once '../mahasiswa/templates/header_mahasiswa.php';

// Panggil tampilan view
require_once '../mahasiswa/views/dashboard_view.php';

// Panggil footer mahasiswa
require_once '../mahasiswa/templates/footer_mahasiswa.php';
?>
