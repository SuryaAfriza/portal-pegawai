<!-- Ringkasan KPI Atas -->
<div class="row g-3 mb-4">
    <div class="col-sm-6 col-xl-4">
        <div class="card stat-card shadow-sm">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="kpi-icon bg-success-subtle text-success">
                    <i class="bi bi-people-fill"></i>
                </div>
                <div>
                    <div class="text-muted small fw-medium">Total Seluruh Pegawai</div>
                    <h4 class="fw-bold mb-0"><?= count(\$semuaPegawai) ?> Orang</h4>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-4">
        <div class="card stat-card shadow-sm">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="kpi-icon bg-primary-subtle text-primary">
                    <i class="bi bi-gender-male"></i>
                </div>
                <div>
                    <div class="text-muted small fw-medium">Pegawai Laki-laki</div>
                    <h4 class="fw-bold mb-0"><?= \$statistik['gender']['Laki-laki'] ?? 0 ?> Orang</h4>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-4">
        <div class="card stat-card shadow-sm">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="kpi-icon bg-danger-subtle text-danger">
                    <i class="bi bi-gender-female"></i>
                </div>
                <div>
                    <div class="text-muted small fw-medium">Pegawai Perempuan</div>
                    <h4 class="fw-bold mb-0"><?= \$statistik['gender']['Perempuan'] ?? 0 ?> Orang</h4>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- 3 Grafik Analitik Utama -->
<div class="row g-4">
    <div class="col-lg-6">
        <div class="card stat-card shadow-sm h-100">
            <div class="card-header bg-white border-0 pt-4 px-4 pb-0">
                <h6 class="fw-bold text-dark mb-1">
                    <i class="bi bi-bar-chart-fill text-primary me-1"></i> Grafik Batang Perbandingan Gender
                </h6>
                <p class="text-muted small mb-0">Perbandingan jumlah pegawai laki-laki dan perempuan</p>
            </div>
            <div class="card-body px-4 pb-4">
                <div class="chart-box">
                    <canvas id="chartGender"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card stat-card shadow-sm h-100">
            <div class="card-header bg-white border-0 pt-4 px-4 pb-0">
                <h6 class="fw-bold text-dark mb-1">
                    <i class="bi bi-mortarboard-fill text-success me-1"></i> Grafik Pendidikan Terakhir
                </h6>
                <p class="text-muted small mb-0">Komposisi pegawai berdasarkan kualifikasi akademik</p>
            </div>
            <div class="card-body px-4 pb-4">
                <div class="chart-box">
                    <canvas id="chartPendidikan"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="card stat-card shadow-sm">
            <div class="card-header bg-white border-0 pt-4 px-4 pb-0">
                <h6 class="fw-bold text-dark mb-1">
                    <i class="bi bi-calendar2-range-fill text-warning me-1"></i> Grafik Distribusi Usia Pegawai
                </h6>
                <p class="text-muted small mb-0">Sebaran rentang usia produktif seluruh pegawai aktif</p>
            </div>
            <div class="card-body px-4 pb-4">
                <div class="chart-box" style="height: 260px;">
                    <canvas id="chartUsia"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>