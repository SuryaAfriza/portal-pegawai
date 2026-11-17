<?php
/**
 * File: index.php
 * Deskripsi: Controller utama dan antarmuka pengguna (UI) Portal Manajemen SDM.
 */

session_start();

require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/src/Services/PegawaiService.php';

$database = new Database();
$service  = new PegawaiService($database->getConnection());

$errors      = [];
$editPegawai = null;

// Tangani request POST (Tambah / Ubah / Hapus)
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

// Mode Edit jika ada parameter ?edit_id=
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
    <!-- Library Pre-existing: Bootstrap 5, Bootstrap Icons, Chart.js, SweetAlert2 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        body { background-color: #f1f5f9; font-family: 'Segoe UI', system-ui, sans-serif; }
        .navbar-brand { font-weight: 700; letter-spacing: 0.3px; }
        .stat-card { border: none; border-radius: 14px; transition: transform 0.15s ease; }
        .stat-card:hover { transform: translateY(-2px); }
        .chart-box { position: relative; height: 240px; }
        .card-panel { border: none; border-radius: 14px; }
    </style>
</head>
<body>

<!-- Topbar -->
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

    <!-- BAGIAN 1: DASHBOARD 3 GRAFIK (Kebutuhan Fungsional #5) -->
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card stat-card shadow-sm h-100">
                <div class="card-body">
                    <h6 class="fw-bold text-secondary mb-3">
                        <i class="bi bi-bar-chart-fill text-primary me-1"></i> Perbandingan Gender (Batang)
                    </h6>
                    <div class="chart-box">
                        <canvas id="chartGender"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card stat-card shadow-sm h-100">
                <div class="card-body">
                    <h6 class="fw-bold text-secondary mb-3">
                        <i class="bi bi-mortarboard-fill text-success me-1"></i> Grafik Pendidikan Terakhir
                    </h6>
                    <div class="chart-box">
                        <canvas id="chartPendidikan"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card stat-card shadow-sm h-100">
                <div class="card-body">
                    <h6 class="fw-bold text-secondary mb-3">
                        <i class="bi bi-people-fill text-warning me-1"></i> Distribusi Usia Pegawai
                    </h6>
                    <div class="chart-box">
                        <canvas id="chartUsia"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- BAGIAN 2: FORM INPUT & TABEL DATA PEGAWAI (Kebutuhan Fungsional #1 - #4) -->
    <div class="row g-4">
        <!-- Form Tambah / Ubah -->
        <div class="col-lg-4">
            <div class="card card-panel shadow-sm">
                <div class="card-header bg-white border-bottom py-3">
                    <h6 class="mb-0 fw-bold">
                        <?php if ($editPegawai): ?>
                            <i class="bi bi-pencil-square text-warning me-1"></i> Ubah Data Pegawai
                        <?php else: ?>
                            <i class="bi bi-person-plus-fill text-success me-1"></i> Tambah Pegawai Baru
                        <?php endif; ?>
                    </h6>
                </div>
                <div class="card-body">
                    <form method="POST" action="index.php">
                        <input type="hidden" name="aksi" value="simpan">
                        <?php if ($editPegawai): ?>
                            <input type="hidden" name="id" value="<?= $editPegawai->getId() ?>">
                        <?php endif; ?>

                        <div class="mb-2">
                            <label class="form-label small fw-semibold">NIP</label>
                            <input type="text" name="nip" class="form-control form-control-sm" required
                                   placeholder="Contoh: 199704112023011002"
                                   value="<?= e($_POST['nip'] ?? ($editPegawai ? $editPegawai->getNip() : '')) ?>">
                        </div>

                        <div class="mb-2">
                            <label class="form-label small fw-semibold">Nama Lengkap</label>
                            <input type="text" name="nama_lengkap" class="form-control form-control-sm" required
                                   placeholder="Masukkan nama lengkap"
                                   value="<?= e($_POST['nama_lengkap'] ?? ($editPegawai ? $editPegawai->getNamaLengkap() : '')) ?>">
                        </div>

                        <div class="row g-2 mb-2">
                            <div class="col-6">
                                <label class="form-label small fw-semibold">Jenis Kelamin</label>
                                <?php $jkVal = $_POST['jenis_kelamin'] ?? ($editPegawai ? $editPegawai->getJenisKelamin() : 'Laki-laki'); ?>
                                <select name="jenis_kelamin" class="form-select form-select-sm" required>
                                    <option value="Laki-laki" <?= $jkVal === 'Laki-laki' ? 'selected' : '' ?>>Laki-laki</option>
                                    <option value="Perempuan" <?= $jkVal === 'Perempuan' ? 'selected' : '' ?>>Perempuan</option>
                                </select>
                            </div>
                            <div class="col-6">
                                <label class="form-label small fw-semibold">Pendidikan</label>
                                <?php $pendVal = $_POST['pendidikan'] ?? ($editPegawai ? $editPegawai->getPendidikan() : 'S1'); ?>
                                <select name="pendidikan" class="form-select form-select-sm" required>
                                    <?php foreach (['SMA/SMK', 'D3', 'S1', 'S2', 'S3'] as $jenjang): ?>
                                        <option value="<?= $jenjang ?>" <?= $pendVal === $jenjang ? 'selected' : '' ?>><?= $jenjang ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div class="mb-2">
                            <label class="form-label small fw-semibold">Tanggal Lahir</label>
                            <input type="date" name="tanggal_lahir" class="form-control form-control-sm" required
                                   value="<?= e($_POST['tanggal_lahir'] ?? ($editPegawai ? $editPegawai->getTanggalLahir() : '')) ?>">
                        </div>

                        <div class="mb-2">
                            <label class="form-label small fw-semibold">Jabatan</label>
                            <input type="text" name="jabatan" class="form-control form-control-sm" required
                                   placeholder="Contoh: Software Engineer"
                                   value="<?= e($_POST['jabatan'] ?? ($editPegawai ? $editPegawai->getJabatan() : '')) ?>">
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-semibold">Divisi / Unit Kerja</label>
                            <input type="text" name="divisi" class="form-control form-control-sm" required
                                   placeholder="Contoh: Teknologi Informasi"
                                   value="<?= e($_POST['divisi'] ?? ($editPegawai ? $editPegawai->getDivisi() : '')) ?>">
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-<?= $editPegawai ? 'warning' : 'success' ?> btn-sm fw-semibold">
                                <i class="bi bi-save me-1"></i> <?= $editPegawai ? 'Simpan Perubahan' : 'Tambahkan Pegawai' ?>
                            </button>
                            <?php if ($editPegawai): ?>
                                <a href="index.php" class="btn btn-outline-secondary btn-sm">Batal Edit</a>
                            <?php endif; ?>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Tabel Daftar Pegawai -->
        <div class="col-lg-8">
            <div class="card card-panel shadow-sm">
                <div class="card-header bg-white border-bottom py-3 d-flex flex-wrap justify-content-between align-items-center gap-2">
                    <h6 class="mb-0 fw-bold"><i class="bi bi-table me-1 text-primary"></i> Daftar Pegawai Terdaftar</h6>
                    <form method="GET" action="index.php" class="d-flex gap-2">
                        <input type="text" name="cari" class="form-control form-control-sm"
                               placeholder="Cari NIP, nama, divisi..." value="<?= e($kataKunci) ?>">
                        <button type="submit" class="btn btn-dark btn-sm">Cari</button>
                        <?php if ($kataKunci !== ''): ?>
                            <a href="index.php" class="btn btn-outline-secondary btn-sm">Reset</a>
                        <?php endif; ?>
                    </form>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0 small">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-3">NIP & Nama</th>
                                    <th>Gender</th>
                                    <th>Pendidikan</th>
                                    <th>Usia</th>
                                    <th>Jabatan & Divisi</th>
                                    <th class="text-end pe-3">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($daftarPegawai)): ?>
                                    <tr>
                                        <td colspan="6" class="text-center py-4 text-muted">Belum ada data pegawai yang sesuai.</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($daftarPegawai as $p): ?>
                                        <tr>
                                            <td class="ps-3">
                                                <div class="fw-bold text-dark"><?= e($p->getNamaLengkap()) ?></div>
                                                <div class="text-muted" style="font-size: 0.78rem;"><?= e($p->getNip()) ?></div>
                                            </td>
                                            <td>
                                                <?php if ($p->getJenisKelamin() === 'Laki-laki'): ?>
                                                    <span class="badge bg-primary-subtle text-primary">Laki-laki</span>
                                                <?php else: ?>
                                                    <span class="badge bg-danger-subtle text-danger">Perempuan</span>
                                                <?php endif; ?>
                                            </td>
                                            <td><span class="badge bg-secondary"><?= e($p->getPendidikan()) ?></span></td>
                                            <td><?= $p->getUsia() ?> thn</td>
                                            <td>
                                                <div class="fw-semibold"><?= e($p->getJabatan()) ?></div>
                                                <div class="text-muted" style="font-size: 0.78rem;"><?= e($p->getDivisi()) ?></div>
                                            </td>
                                            <td class="text-end pe-3">
                                                <a href="index.php?edit_id=<?= $p->getId() ?>" class="btn btn-outline-primary btn-sm py-0 px-2">
                                                    <i class="bi bi-pencil"></i> Edit
                                                </a>
                                                <form method="POST" action="index.php" class="d-inline form-hapus">
                                                    <input type="hidden" name="aksi" value="hapus">
                                                    <input type="hidden" name="id" value="<?= $p->getId() ?>">
                                                    <button type="button" class="btn btn-outline-danger btn-sm py-0 px-2 btn-konfirmasi-hapus"
                                                            data-nama="<?= e($p->getNamaLengkap()) ?>">
                                                        <i class="bi bi-trash"></i> Hapus
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Script Libraries -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
// Data Statistik dari PHP ke JavaScript
const statData = <?= json_encode($statistik) ?>;

// 1. Grafik Batang Perbandingan Laki-laki vs Perempuan
new Chart(document.getElementById('chartGender'), {
    type: 'bar',
    data: {
        labels: Object.keys(statData.gender),
        datasets: [{
            label: 'Jumlah Pegawai',
            data: Object.values(statData.gender),
            backgroundColor: ['#3b82f6', '#ec4899'],
            borderRadius: 6
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { display: false } },
        scales: { y: { beginAtZero: true, ticks: { precision: 0 } } }
    }
});

// 2. Grafik Pendidikan Terakhir
new Chart(document.getElementById('chartPendidikan'), {
    type: 'doughnut',
    data: {
        labels: Object.keys(statData.pendidikan),
        datasets: [{
            data: Object.values(statData.pendidikan),
            backgroundColor: ['#94a3b8', '#06b6d4', '#10b981', '#6366f1', '#f59e0b']
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { position: 'bottom' } }
    }
});

// 3. Grafik Kelompok Usia Pegawai
new Chart(document.getElementById('chartUsia'), {
    type: 'bar',
    data: {
        labels: Object.keys(statData.usia),
        datasets: [{
            label: 'Pegawai',
            data: Object.values(statData.usia),
            backgroundColor: '#f59e0b',
            borderRadius: 6
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { display: false } },
        scales: { y: { beginAtZero: true, ticks: { precision: 0 } } }
    }
});

// Kebutuhan Non-Fungsional: Pop-up Konfirmasi Sebelum Hapus
document.querySelectorAll('.btn-konfirmasi-hapus').forEach((btn) => {
    btn.addEventListener('click', function () {
        const nama = this.getAttribute('data-nama');
        const form = this.closest('.form-hapus');

        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: 'Konfirmasi Hapus Data',
                text: `Apakah Anda yakin ingin menghapus data pegawai "${nama}"?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        } else if (confirm(`Apakah Anda yakin ingin menghapus data pegawai "${nama}"?`)) {
            form.submit();
        }
    });
});
</script>
</body>
</html>