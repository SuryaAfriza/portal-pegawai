<!-- Komponen Tabel Daftar & Pencarian Pegawai -->
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