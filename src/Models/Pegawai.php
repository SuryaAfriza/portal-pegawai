<?php
/**
 * File: src/Models/Pegawai.php
 * Deskripsi: Representasi objek entitas Pegawai dengan penerapan Enkapsulasi OOP.
 */

class Pegawai
{
    private ?int $id;
    private string $nip;
    private string $namaLengkap;
    private string $jenisKelamin;
    private string $tanggalLahir;
    private string $pendidikan;
    private string $jabatan;
    private string $divisi;

    public function __construct(array $data)
    {
        $this->id           = isset($data['id']) ? (int) $data['id'] : null;
        $this->nip          = trim($data['nip'] ?? '');
        $this->namaLengkap  = trim($data['nama_lengkap'] ?? '');
        $this->jenisKelamin = trim($data['jenis_kelamin'] ?? 'Laki-laki');
        $this->tanggalLahir = trim($data['tanggal_lahir'] ?? '');
        $this->pendidikan   = trim($data['pendidikan'] ?? 'S1');
        $this->jabatan      = trim($data['jabatan'] ?? '');
        $this->divisi       = trim($data['divisi'] ?? '');
    }

    public function getId(): ?int { return $this->id; }
    public function getNip(): string { return $this->nip; }
    public function getNamaLengkap(): string { return $this->namaLengkap; }
    public function getJenisKelamin(): string { return $this->jenisKelamin; }
    public function getTanggalLahir(): string { return $this->tanggalLahir; }
    public function getPendidikan(): string { return $this->pendidikan; }
    public function getJabatan(): string { return $this->jabatan; }
    public function getDivisi(): string { return $this->divisi; }

    /**
     * Menghitung usia pegawai secara dinamis berdasarkan tanggal lahir.
     * @return int Usia dalam tahun
     */
    public function getUsia(): int
    {
        if (empty($this->tanggalLahir)) {
            return 0;
        }
        $tglLahir = new DateTime($this->tanggalLahir);
        $hariIni  = new DateTime('today');
        return $tglLahir->diff($hariIni)->y;
    }
}