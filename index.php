<?php
/**
 * File: index.php
 * Deskripsi: Front-controller utama Portal Manajemen SDM dengan navigasi multi-halaman.
 */

session_start();

require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/src/Services/PegawaiService.php';

$database = new Database();
$service  = new PegawaiService($database->getConnection());

$errors      = [];
$editPegawai = null;
$page        = $_GET['page'] ?? 'dashboard';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $aksi = $_POST['aksi'] ?? '';

    if ($aksi === 'hapus') {
        $idHapus = (int) ($_POST['id'] ?? 0);
        if ($idHapus > 0 && $service->hapus($idHapus)) {
            $_SESSION['notif'] = ['tipe' => 'success', 'pesan' => 'Data pegawai berhasil dihapus dari sistem.'];
        } else {
            $_SESSION['notif'] = ['tipe' => 'danger', 'pesan' => 'Gagal menghapus data pegawai.'];
        }
        header('Location: index.php?page=pegawai');
        exit;
    }

    if ($aksi === 'simpan') {
        $errors = validasiInputPegawai($_POST);
        if (empty($errors)) {
            try {
                $pegawaiObj = new Pegawai($_POST);
                if (!empty($_POST['id'])) {
                    $service->perbarui($pegawaiObj);
                    $_SESSION['notif'] = ['tipe' => 'success', 'pesan' => 'Data pegawai berhasil diperbarui.'];
                } else {
                    $service->tambah($pegawaiObj);
                    $_SESSION['notif'] = ['tipe' => 'success', 'pesan' => 'Pegawai baru berhasil ditambahkan.'];
                }
                header('Location: index.php?page=pegawai');
                exit;
            } catch (PDOException $e) {
                $errors[] = 'Gagal menyimpan data: Pastikan NIP belum digunakan pegawai lain.';
                $page = 'form';
            }
        } else {
            $page = 'form';
        }
    }
}

if (isset($_GET['edit_id'])) {
    $editPegawai = $service->ambilBerdasarkanId((int) $_GET['edit_id']);
    $page = 'form';
}

$kataKunci     = trim($_GET['cari'] ?? '');
$daftarPegawai = $service->ambilSemua($kataKunci);
$semuaPegawai  = $kataKunci !== '' ? $service->ambilSemua() : $daftarPegawai;
$statistik     = $service->hitungStatistikDashboard($semuaPegawai);

$notif = $_SESSION['notif'] ?? null;
unset($_SESSION['notif']);

$judulHalaman = [
    'dashboard' => 'Dashboard Analitik Pegawai',
    'pegawai'   => 'Manajemen Data Pegawai',
    'form'      => $editPegawai ? 'Edit Data Pegawai' : 'Tambah Pegawai Baru',
][$page] ?? 'Dashboard Analitik Pegawai';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($judulHalaman) ?> — Portal SDM</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="public/css/style.css" rel="stylesheet">
</head>
<body>

<div class="admin-wrapper">
    <!-- Sidebar Navigasi Kiri -->
    <aside class="sidebar">
        <a href="index.php?page=dashboard" class="sidebar-brand d-flex align-items-center gap-2">
            <i class="bi bi-buildings-fill text-success fs-4"></i>
            <span>PORTAL SDM</span>
        </a>

        <nav class="sidebar-nav">
            <div class="text-uppercase text-secondary fw-bold px-3 mb-2" style="font-size: 0.7rem; letter-spacing: 0.8px;">
                Menu Utama
            </div>
            <a href="index.php?page=dashboard" class="nav-item-link <?= $page === 'dashboard' ? 'active' : '' ?>">
                <i class="bi bi-pie-chart-fill"></i>
                <span>Dashboard Grafik</span>
            </a>
            <a href="index.php?page=pegawai" class="nav-item-link <?= $page === 'pegawai' ? 'active' : '' ?>">
                <i class="bi bi-people-fill"></i>
                <span>Data Pegawai</span>
            </a>
            <a href="index.php?page=form" class="nav-item-link <?= $page === 'form' ? 'active' : '' ?>">
                <i class="bi bi-person-plus-fill"></i>
                <span><?= $editPegawai ? 'Edit Pegawai' : 'Tambah Pegawai' ?></span>
            </a>
        </nav>

        <div class="p-3 border-top border-secondary border-opacity-25 small text-secondary">
            <i class="bi bi-shield-check text-success me-1"></i> Sistem Aktif &bull; <?= count($semuaPegawai) ?> SDM
        </div>
    </aside>

    <!-- Area Konten Utama -->
    <div class="main-content">
        <header class="topbar d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-bold text-dark"><?= e($judulHalaman) ?></h5>
            <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-2">
                <i class="bi bi-database-check me-1"></i> Total: <?= count($semuaPegawai) ?> Pegawai
            </span>
        </header>

        <main class="p-4">
            <?php if ($notif): ?>
                <div class="alert alert-<?= e($notif['tipe']) ?> alert-dismissible fade show shadow-sm" role="alert">
                    <i class="bi bi-check-circle-fill me-1"></i> <?= e($notif['pesan']) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <?php if (!empty($errors)): ?>
                <div class="alert alert-danger shadow-sm">
                    <strong>Periksa kembali input Anda:</strong>
                    <ul class="mb-0 mt-1">
                        <?php foreach ($errors as $err): ?>
                            <li><?= e($err) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <?php
            if ($page === 'pegawai') {
                require __DIR__ . '/views/tabel_pegawai.php';
            } elseif ($page === 'form') {
                require __DIR__ . '/views/form_pegawai.php';
            } else {
                require __DIR__ . '/views/dashboard_charts.php';
            }
            ?>
        </main>
    </div>
</div>

<script>
    window.statData = <?= json_encode($statistik) ?>;
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="public/js/dashboard.js"></script>
</body>
</html>