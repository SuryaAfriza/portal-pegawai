<?php
/**
 * File: includes/helpers.php
 * Deskripsi: Kumpulan fungsi pemrograman terstruktur untuk validasi, sanitasi, dan pemformatan UI.
 */

/**
 * Sanitasi output string untuk mencegah celah XSS.
 */
function e(string $nilai): string
{
    return htmlspecialchars($nilai, ENT_QUOTES, 'UTF-8');
}

/**
 * Validasi input form data pegawai.
 * @param array $input Data dari $_POST
 * @return array Daftar pesan error (kosong jika valid)
 */
function validasiInputPegawai(array $input): array
{
    $errors = [];

    if (empty(trim($input['nip'] ?? '')) || strlen(trim($input['nip'])) < 5) {
        $errors[] = 'NIP wajib diisi minimal 5 karakter.';
    }
    if (empty(trim($input['nama_lengkap'] ?? ''))) {
        $errors[] = 'Nama lengkap pegawai wajib diisi.';
    }
    if (!in_array($input['jenis_kelamin'] ?? '', ['Laki-laki', 'Perempuan'], true)) {
        $errors[] = 'Jenis kelamin tidak valid.';
    }
    if (empty($input['tanggal_lahir'] ?? '')) {
        $errors[] = 'Tanggal lahir wajib diisi.';
    }
    if (!in_array($input['pendidikan'] ?? '', ['SMA/SMK', 'D3', 'S1', 'S2', 'S3'], true)) {
        $errors[] = 'Jenjang pendidikan tidak valid.';
    }
    if (empty(trim($input['jabatan'] ?? '')) || empty(trim($input['divisi'] ?? ''))) {
        $errors[] = 'Jabatan dan divisi wajib diisi.';
    }

    return $errors;
}

/**
 * Mengelompokkan usia ke dalam rentang kategori untuk grafik dashboard.
 */
function kategorikanUsia(int $usia): string
{
    if ($usia < 25) {
        return '< 25 Tahun';
    } elseif ($usia <= 34) {
        return '25 - 34 Tahun';
    } elseif ($usia <= 44) {
        return '35 - 44 Tahun';
    } else {
        return '>= 45 Tahun';
    }
}