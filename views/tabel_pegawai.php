<div class="card card-panel shadow-sm">
    <div class="card-header bg-white border-bottom py-3 px-4 d-flex flex-wrap justify-content-between align-items-center gap-3">
        <div>
            <h6 class="mb-0 fw-bold text-dark">Direktori Data Pegawai</h6>
            <small class="text-muted">Menampilkan <?= count(\$daftarPegawai) ?> data pegawai terdaftar</small>
        </div>

        <div class="d-flex flex-wrap align-items-center gap-2">
            <form method="GET" action="index.php" class="d-flex gap-2">
                <input type="hidden" name="page" value="pegawai">
                <div class="input-group input-group-sm" style="width: 250px;">
                    <span class="input-group-text bg-light"><i class="bi bi-search"></i></span>
                    <input type="text" name="cari" class="form-control"
                           placeholder="Cari NIP, nama, divisi..." value="<?= e(\$kataKunci) ?>">
                </div>
                <button type="submit" class="btn btn-dark btn-sm px-3">Cari</button>
                <?php if (\$kataKunci !== ''): ?>
                    <a href="index.php?page=pegawai" class="btn btn-outline-secondary btn-sm">Reset</a>
                <?php endif; ?>
            </form>

            <a href="index.php?page=form" class="btn btn-success btn-sm fw-semibold px-3">
                <i class="bi bi-plus-lg me-1"></i> Tambah Pegawai
            </a>
        </div>
    </div>

    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4" style="width: 60px;">No</th>
                        <th>NIP</th>
                        <th>Nama Lengkap</th>
                        <th>Jenis Kelamin</th>
                        <th>Pendidikan</th>
                        <th>Usia</th>
                        <th>Jabatan & Divisi</th>
                        <th class="text-end pe-4">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty(\$daftarPegawai)): ?>
                        <tr>
                            <td colspan="8" class="text-center py-5 text-muted">
                                <i class="bi bi-inbox fs-3 d-block mb-2"></i>
                                Data pegawai tidak ditemukan.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php \$no = 1; foreach ($daftarPegawai as$p): ?>
                            <tr>
                                <td class="ps-4 text-muted"><?= \$no++ ?></td>
                                <td class="fw-semibold text-secondary"><?= e(\$p->getNip()) ?></td>
                                <td class="fw-bold text-dark"><?= e(\$p->getNamaLengkap()) ?></td>
                                <td>
                                    <?php if (\$p->getJenisKelamin() === 'Laki-laki'): ?>
                                        <span class="badge bg-primary-subtle text-primary px-2 py-1">Laki-laki</span>
                                    <?php else: ?>
                                        <span class="badge bg-danger-subtle text-danger px-2 py-1">Perempuan</span>
                                    <?php endif; ?>
                                </td>
                                <td><span class="badge bg-secondary"><?= e(\$p->getPendidikan()) ?></span></td>
                                <td>
                                    <span class="fw-semibold"><?= \$p->getUsia() ?> Tahun</span>
                                    <div class="text-muted" style="font-size: 0.75rem;"><?= e(\$p->getTanggalLahir()) ?></div>
                                </td>
                                <td>
                                    <div class="fw-semibold text-dark"><?= e(\$p->getJabatan()) ?></div>
                                    <div class="text-muted small"><?= e(\$p->getDivisi()) ?></div>
                                </td>
                                <td class="text-end pe-4">
                                    <a href="index.php?page=form&edit_id=<?= \$p->getId() ?>" class="btn btn-outline-primary btn-sm me-1">
                                        <i class="bi bi-pencil-square"></i> Edit
                                    </a>
                                    <form method="POST" action="index.php" class="d-inline form-hapus">
                                        <input type="hidden" name="aksi" value="hapus">
                                        <input type="hidden" name="id" value="<?= \$p->getId() ?>">
                                        <button type="button" class="btn btn-outline-danger btn-sm btn-konfirmasi-hapus"
                                                data-nama="<?= e(\$p->getNamaLengkap()) ?>">
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