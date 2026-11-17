<?php
/**
 * File: src/Services/PegawaiService.php
 * Deskripsi: Kelas layanan untuk operasi CRUD basis data dan agregasi statistik grafik.
 */

require_once __DIR__ . '/../Models/Pegawai.php';
require_once __DIR__ . '/../../includes/helpers.php';

class PegawaiService
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    /**
     * Mengambil semua data pegawai sebagai array objek Pegawai.
     * @param string $cari Kata kunci pencarian
     * @return Pegawai[]
     */
    public function ambilSemua(string $cari = ''): array
    {
        if ($cari !== '') {
            $stmt = $this->db->prepare(
                "SELECT * FROM pegawai 
                 WHERE nama_lengkap LIKE :q1 OR nip LIKE :q2 OR jabatan LIKE :q3 OR divisi LIKE :q4
                 ORDER BY id DESC"
            );
            $kw = "%{$cari}%";
            $stmt->execute(['q1' => $kw, 'q2' => $kw, 'q3' => $kw, 'q4' => $kw]);
        } else {
            $stmt = $this->db->query("SELECT * FROM pegawai ORDER BY id DESC");
        }

        $daftar = [];
        while ($row = $stmt->fetch()) {
            $daftar[] = new Pegawai($row);
        }
        return $daftar;
    }

    /**
     * Mengambil satu pegawai berdasarkan ID.
     */
    public function ambilBerdasarkanId(int $id): ?Pegawai
    {
        $stmt = $this->db->prepare("SELECT * FROM pegawai WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();

        return $row ? new Pegawai($row) : null;
    }

    /**
     * Menyimpan data pegawai baru.
     */
    public function tambah(Pegawai $p): bool
    {
        $stmt = $this->db->prepare(
            "INSERT INTO pegawai (nip, nama_lengkap, jenis_kelamin, tanggal_lahir, pendidikan, jabatan, divisi)
             VALUES (:nip, :nama, :jk, :tgl, :pendidikan, :jabatan, :divisi)"
        );
        return $stmt->execute([
            'nip'        => $p->getNip(),
            'nama'       => $p->getNamaLengkap(),
            'jk'         => $p->getJenisKelamin(),
            'tgl'        => $p->getTanggalLahir(),
            'pendidikan' => $p->getPendidikan(),
            'jabatan'    => $p->getJabatan(),
            'divisi'     => $p->getDivisi(),
        ]);
    }

    /**
     * Memperbarui data pegawai berdasarkan ID.
     */
    public function perbarui(Pegawai $p): bool
    {
        $stmt = $this->db->prepare(
            "UPDATE pegawai SET 
                nip = :nip,
                nama_lengkap = :nama,
                jenis_kelamin = :jk,
                tanggal_lahir = :tgl,
                pendidikan = :pendidikan,
                jabatan = :jabatan,
                divisi = :divisi
             WHERE id = :id"
        );
        return $stmt->execute([
            'id'         => $p->getId(),
            'nip'        => $p->getNip(),
            'nama'       => $p->getNamaLengkap(),
            'jk'         => $p->getJenisKelamin(),
            'tgl'        => $p->getTanggalLahir(),
            'pendidikan' => $p->getPendidikan(),
            'jabatan'    => $p->getJabatan(),
            'divisi'     => $p->getDivisi(),
        ]);
    }

    /**
     * Menghapus data pegawai berdasarkan ID.
     */
    public function hapus(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM pegawai WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }

    /**
     * Mengolah struktur data array asosiatif untuk 3 grafik dashboard:
     * 1. Perbandingan Gender (Laki-laki vs Perempuan)
     * 2. Pendidikan Terakhir
     * 3. Kelompok Usia Pegawai
     * @param Pegawai[] $daftarPegawai
     * @return array
     */
    public function hitungStatistikDashboard(array $daftarPegawai): array
    {
        $gender = [
            'Laki-laki' => 0,
            'Perempuan' => 0,
        ];

        $pendidikan = [
            'SMA/SMK' => 0,
            'D3'      => 0,
            'S1'      => 0,
            'S2'      => 0,
            'S3'      => 0,
        ];

        $kelompokUsia = [
            '< 25 Tahun'    => 0,
            '25 - 34 Tahun' => 0,
            '35 - 44 Tahun' => 0,
            '>= 45 Tahun'   => 0,
        ];

        foreach ($daftarPegawai as $p) {
            $jk = $p->getJenisKelamin();
            if (isset($gender[$jk])) {
                $gender[$jk]++;
            }

            $jenjang = $p->getPendidikan();
            if (isset($pendidikan[$jenjang])) {
                $pendidikan[$jenjang]++;
            }

            $kategori = kategorikanUsia($p->getUsia());
            $kelompokUsia[$kategori]++;
        }

        return [
            'gender'     => $gender,
            'pendidikan' => $pendidikan,
            'usia'       => $kelompokUsia,
        ];
    }
}