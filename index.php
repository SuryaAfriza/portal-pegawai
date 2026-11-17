<?php
/**
 * File: index.php
 * Deskripsi: Front-controller utama Portal Manajemen SDM.
 */

session_start();

require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/src/Services/PegawaiService.php';

$database = new Database();
$service  = new PegawaiService($database->getConnection());

$errors      = [];
$editPegawai = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $aksi = $_POST['aksi'] ?? '';

    if ($aksi === 'hapus') {
        $idHapus = (int) ($_POST['id'] ?? 0);
        if ($idHapus > 0 && $service->hapus($idHapus)) {
            $_SESSION['notif'] = ['tipe' => 'success', 'pesan' => 'Data pegawai berhasil dihapus dari sistem.'];
        } else {
            $_SESSION['notif'] = ['tipe' => 'danger', 'pesan' => 'Gagal menghapus data pegawai.'];
        }
        header('Location: index.php');
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
                header('Location: index.php');
                exit;
            } catch (PDOException $e) {
                $errors[] = 'Gagal menyimpan data: Pastikan NIP belum digunakan pegawai lain.';
            }
        }
    }
}

if (isset($_GET['edit_id'])) {
    $editPegawai = $service->ambilBerdasarkanId((int) $_GET['edit_id']);
}

$kataKunci     = trim($_GET['cari'] ?? '');
$daftarPegawai = $service->ambilSemua($kataKunci);
$semuaPegawai  = $kataKunci !== '' ? $service->ambilSemua() : $daftarPegawai;
$statistik     = $service->hitungStatistikDashboard($semuaPegawai);

$notif = $_SESSION['notif'] ?? null;
unset($_SESSION['notif']);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal SDM — Sistem Manajemen Data Pegawai</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="public/css/style.css" rel="stylesheet">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm py-3 mb-4">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center gap-2" href="index.php">
            <i class="bi bi-buildings-fill text-success"></i>
            <span>PORTAL SDM <small class="fw-normal text-secondary fs-6">| Manajemen Pegawai</small></span>
        </a>
        <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-2">
            Total Aktif: <?= count($semuaPegawai) ?> Pegawai
        </span>
    </div>
</nav>

<div class="container pb-5">
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

    <?php require __DIR__ . '/views/dashboard_charts.php'; ?>

    <div class="row g-4">
        <div class="col-lg-4">
            <?php require __DIR__ . '/views/form_pegawai.php'; ?>
        </div>
        <div class="col-lg-8">
            <?php require __DIR__ . '/views/tabel_pegawai.php'; ?>
        </div>
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