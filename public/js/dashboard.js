/**
 * File: public/js/dashboard.js
 * Deskripsi: Inisialisasi grafik Chart.js dan pop-up konfirmasi hapus data pegawai.
 */

document.addEventListener('DOMContentLoaded', function () {
    const statData = window.statData || { gender: {}, pendidikan: {}, usia: {} };

    // 1. Grafik Batang Perbandingan Laki-laki vs Perempuan
    const elGender = document.getElementById('chartGender');
    if (elGender) {
        new Chart(elGender, {
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
    }

    // 2. Grafik Pendidikan Terakhir
    const elPendidikan = document.getElementById('chartPendidikan');
    if (elPendidikan) {
        new Chart(elPendidikan, {
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
    }

    // 3. Grafik Kelompok Usia Pegawai
    const elUsia = document.getElementById('chartUsia');
    if (elUsia) {
        new Chart(elUsia, {
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
    }

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
});