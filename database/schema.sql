CREATE DATABASE IF NOT EXISTS db_portal_sdm CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE db_portal_sdm;

CREATE TABLE IF NOT EXISTS pegawai (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nip VARCHAR(25) NOT NULL UNIQUE,
    nama_lengkap VARCHAR(120) NOT NULL,
    jenis_kelamin ENUM('Laki-laki', 'Perempuan') NOT NULL,
    tanggal_lahir DATE NOT NULL,
    pendidikan ENUM('SMA/SMK', 'D3', 'S1', 'S2', 'S3') NOT NULL,
    jabatan VARCHAR(80) NOT NULL,
    divisi VARCHAR(80) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO pegawai (nip, nama_lengkap, jenis_kelamin, tanggal_lahir, pendidikan, jabatan, divisi) VALUES
('199503122022011001', 'Rangga Pratama', 'Laki-laki', '1995-03-12', 'S1', 'Backend Engineer', 'Teknologi Informasi'),
('199807242023022002', 'Nadia Kusuma', 'Perempuan', '1998-07-24', 'S1', 'UI/UX Designer', 'Pengembangan Produk'),
('198911052018011003', 'Hendra Wijaya', 'Laki-laki', '1989-11-05', 'S2', 'Engineering Manager', 'Teknologi Informasi'),
('200102182024012004', 'Aulia Rahma', 'Perempuan', '2001-02-18', 'D3', 'QA Analyst', 'Jaminan Mutu'),
('199309302020011005', 'Bagus Setiawan', 'Laki-laki', '1993-09-30', 'SMA/SMK', 'IT Support', 'Operasional'),
('198406152015022006', 'Ratna Sari', 'Perempuan', '1984-06-15', 'S2', 'HR Lead', 'Sumber Daya Manusia');