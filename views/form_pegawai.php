<!-- Komponen Form Input Tambah / Ubah Pegawai -->
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