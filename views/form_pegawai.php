<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card card-panel shadow-sm">
            <div class="card-header bg-white border-bottom py-3 px-4 d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-bold">
                    <?php if ($editPegawai): ?>
                        <i class="bi bi-pencil-square text-warning me-1"></i> Formulir Perbarui Data Pegawai
                    <?php else: ?>
                        <i class="bi bi-person-plus-fill text-success me-1"></i> Formulir Tambah Pegawai Baru
                    <?php endif; ?>
                </h6>
                <a href="index.php?page=pegawai" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-arrow-left me-1"></i> Kembali ke Tabel
                </a>
            </div>

            <div class="card-body p-4">
                <form method="POST" action="index.php?page=form">
                    <input type="hidden" name="aksi" value="simpan">
                    <?php if ($editPegawai): ?>
                        <input type="hidden" name="id" value="<?= $editPegawai->getId() ?>">
                    <?php endif; ?>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Nomor Induk Pegawai (NIP)</label>
                            <input type="text" name="nip" class="form-control" required
                                   placeholder="Contoh: 199704112023011002"
                                   value="<?= e($_POST['nip'] ?? ($editPegawai ? $editPegawai->getNip() : '')) ?>">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Nama Lengkap</label>
                            <input type="text" name="nama_lengkap" class="form-control" required
                                   placeholder="Masukkan nama lengkap beserta gelar"
                                   value="<?= e($_POST['nama_lengkap'] ?? ($editPegawai ? $editPegawai->getNamaLengkap() : '')) ?>">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Jenis Kelamin</label>
                            <?php $jkVal = $_POST['jenis_kelamin'] ?? ($editPegawai ? $editPegawai->getJenisKelamin() : 'Laki-laki'); ?>
                            <select name="jenis_kelamin" class="form-select" required>
                                <option value="Laki-laki" <?= $jkVal === 'Laki-laki' ? 'selected' : '' ?>>Laki-laki</option>
                                <option value="Perempuan" <?= $jkVal === 'Perempuan' ? 'selected' : '' ?>>Perempuan</option>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Pendidikan Terakhir</label>
                            <?php $pendVal = $_POST['pendidikan'] ?? ($editPegawai ? $editPegawai->getPendidikan() : 'S1'); ?>
                            <select name="pendidikan" class="form-select" required>
                                <?php foreach (['SMA/SMK', 'D3', 'S1', 'S2', 'S3'] as $jenjang): ?>
                                    <option value="<?= $jenjang ?>" <?= $pendVal === $jenjang ? 'selected' : '' ?>><?= $jenjang ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Tanggal Lahir</label>
                            <input type="date" name="tanggal_lahir" class="form-control" required
                                   value="<?= e($_POST['tanggal_lahir'] ?? ($editPegawai ? $editPegawai->getTanggalLahir() : '')) ?>">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Jabatan</label>
                            <input type="text" name="jabatan" class="form-control" required
                                   placeholder="Contoh: Backend Developer"
                                   value="<?= e($_POST['jabatan'] ?? ($editPegawai ? $editPegawai->getJabatan() : '')) ?>">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Divisi / Unit Kerja</label>
                            <input type="text" name="divisi" class="form-control" required
                                   placeholder="Contoh: Teknologi Informasi"
                                   value="<?= e($_POST['divisi'] ?? ($editPegawai ? $editPegawai->getDivisi() : '')) ?>">
                        </div>
                    </div>

                    <hr class="my-4">

                    <div class="d-flex justify-content-end gap-2">
                        <a href="index.php?page=pegawai" class="btn btn-light border px-4">Batal</a>
                        <button type="submit" class="btn btn-<?= $editPegawai ? 'warning' : 'success' ?> fw-semibold px-4">
                            <i class="bi bi-check2-circle me-1"></i>
                            <?= $editPegawai ? 'Simpan Perubahan' : 'Simpan Data Pegawai' ?>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>