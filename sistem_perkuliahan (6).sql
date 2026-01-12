-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 08 Jan 2026 pada 07.24
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sistem_perkuliahan`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `bobot_nilai`
--

CREATE TABLE `bobot_nilai` (
  `id` int(11) NOT NULL,
  `mata_kuliah_id` int(11) NOT NULL,
  `tugas` decimal(5,2) DEFAULT 20.00 COMMENT 'Bobot tugas dalam persen',
  `quiz` decimal(5,2) DEFAULT 20.00 COMMENT 'Bobot quiz dalam persen',
  `uts` decimal(5,2) DEFAULT 30.00 COMMENT 'Bobot UTS dalam persen',
  `uas` decimal(5,2) DEFAULT 30.00 COMMENT 'Bobot UAS dalam persen',
  `kehadiran` decimal(5,2) DEFAULT 0.00 COMMENT 'Bobot kehadiran dalam persen',
  `praktikum` decimal(5,2) DEFAULT 0.00 COMMENT 'Bobot praktikum dalam persen',
  `total_bobot` decimal(5,2) GENERATED ALWAYS AS (`tugas` + `quiz` + `uts` + `uas` + `kehadiran` + `praktikum`) STORED,
  `semester_id` int(11) NOT NULL,
  `keterangan` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL
) ;

--
-- Dumping data untuk tabel `bobot_nilai`
--

INSERT INTO `bobot_nilai` (`id`, `mata_kuliah_id`, `tugas`, `quiz`, `uts`, `uas`, `kehadiran`, `praktikum`, `semester_id`, `keterangan`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 2, 15.00, 15.00, 25.00, 25.00, 10.00, 10.00, 1, 'Bobot untuk mata kuliah praktikum', '2026-01-07 06:49:59', '2026-01-07 06:49:59', NULL),
(2, 3, 25.00, 20.00, 25.00, 30.00, 0.00, 0.00, 1, 'Bobot untuk mata kuliah teori', '2026-01-07 06:49:59', '2026-01-07 06:49:59', NULL),
(3, 12, 20.00, 15.00, 30.00, 30.00, 5.00, 0.00, 1, 'Bobot standar teknik elektro', '2026-01-07 06:49:59', '2026-01-07 06:49:59', NULL),
(4, 18, 30.00, 20.00, 20.00, 30.00, 0.00, 0.00, 1, 'Bobot untuk MKU', '2026-01-07 06:49:59', '2026-01-07 06:49:59', NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `dosen`
--

CREATE TABLE `dosen` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `nidn` varchar(20) NOT NULL,
  `nip` varchar(30) DEFAULT NULL,
  `nama_lengkap` varchar(100) NOT NULL,
  `gelar_depan` varchar(20) DEFAULT NULL,
  `gelar_belakang` varchar(50) DEFAULT NULL,
  `jurusan_id` int(11) NOT NULL,
  `jenis_kelamin` enum('L','P') NOT NULL,
  `tempat_lahir` varchar(50) DEFAULT NULL,
  `tanggal_lahir` date DEFAULT NULL,
  `alamat` text DEFAULT NULL,
  `no_hp` varchar(20) DEFAULT NULL,
  `email_pribadi` varchar(100) DEFAULT NULL,
  `status_kepegawaian` enum('Tetap','Kontrak','Honorer') DEFAULT 'Tetap',
  `jabatan` enum('Asisten Ahli','Lektor','Lektor Kepala','Profesor') DEFAULT NULL,
  `status` enum('Aktif','Nonaktif','Pensiun','Cuti') DEFAULT 'Aktif',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `dosen`
--

INSERT INTO `dosen` (`id`, `user_id`, `nidn`, `nip`, `nama_lengkap`, `gelar_depan`, `gelar_belakang`, `jurusan_id`, `jenis_kelamin`, `tempat_lahir`, `tanggal_lahir`, `alamat`, `no_hp`, `email_pribadi`, `status_kepegawaian`, `jabatan`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 2, '0123456789', '197801012005011001', 'Andi Wijaya', 'Dr. Ir.', 'M.T.', 1, 'L', 'Jakarta', '1978-01-01', 'Jl. Mawar No. 123, Jakarta', '081234567890', 'andi.wijaya@gmail.com', 'Tetap', 'Lektor Kepala', 'Aktif', '2025-09-18 01:43:59', '2025-12-11 00:09:37', NULL),
(2, 3, '0123456790', '198202152006022002', 'Maya Sari', 'Dr.', 'S.Kom., M.T.', 2, 'P', 'Bandung', '1982-02-15', 'Jl. Melati No. 456, Bandung', '081234567891', 'maya.sari@gmail.com', 'Tetap', 'Lektor', 'Aktif', '2025-09-18 01:43:59', '2025-10-13 21:36:49', NULL),
(3, 4, '0123456791', '197512102003121003', 'Rudi Hartono', 'Prof. Dr. Ir.', 'M.T.', 3, 'L', 'Surabaya', '1975-12-10', 'Jl. Anggrek No. 789, Surabaya', '081234567892', 'rudi.hartono@gmail.com', 'Tetap', 'Profesor', 'Aktif', '2025-09-18 01:43:59', '2025-09-18 01:43:59', NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `fakultas`
--

CREATE TABLE `fakultas` (
  `id` int(11) NOT NULL,
  `kode_fakultas` varchar(10) NOT NULL,
  `nama_fakultas` varchar(100) NOT NULL,
  `dekan` varchar(100) DEFAULT NULL,
  `alamat` text DEFAULT NULL,
  `telepon` decimal(20,0) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `fakultas`
--

INSERT INTO `fakultas` (`id`, `kode_fakultas`, `nama_fakultas`, `dekan`, `alamat`, `telepon`, `email`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'FA', 'a', 'aa', 'null', 81294763785, 'a@a', '2025-09-18 01:43:59', '2025-12-24 01:58:12', NULL),
(2, 'FEKON', 'Fakultas Ekonomi dan Bisnis', 'Dr. Siti Nurhaliza, S.E., M.M.', 'Jl. Raya Universitas No. 2', 81294763785, 'fekon@univ.ac.id', '2025-09-18 01:43:59', '2025-12-23 18:59:12', NULL),
(3, 'FISIP', 'Fakultas Ilmu Sosial dan Politik', 'Dr. Ahmad Rahman, S.Sos., M.Si.', 'Jl. Raya Universitas No. 3', 21, 'fisip@univ.ac.id', '2025-09-18 01:43:59', '2025-09-18 01:43:59', NULL),
(4, 'test', 'test', 'test', 'test', 88888888, 'test@test.com', '2025-10-09 18:26:01', '2025-10-09 18:26:01', NULL),
(5, 'test2', 'test2', 'test2', 'test2', 0, 'test@test', '2025-11-12 20:02:23', '2025-11-12 20:02:30', NULL),
(8, 'test3', 'test3', 'test3', 'test3', 81294763785, 'test@test.com', '2025-12-11 18:33:01', '2025-12-11 18:33:01', NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `grade_system`
--

CREATE TABLE `grade_system` (
  `id` int(11) NOT NULL,
  `grade` varchar(3) NOT NULL COMMENT 'Nilai huruf: A, A-, B+, B, B-, C+, C, D, E',
  `min_score` decimal(5,2) NOT NULL COMMENT 'Nilai minimum untuk grade ini',
  `max_score` decimal(5,2) NOT NULL COMMENT 'Nilai maksimum untuk grade ini',
  `grade_point` decimal(3,2) NOT NULL COMMENT 'Bobot nilai (0.00 - 4.00)',
  `status_kelulusan` enum('Lulus','Tidak Lulus') DEFAULT 'Lulus',
  `keterangan` varchar(100) DEFAULT NULL,
  `Status` varchar(10) DEFAULT 'Aktif',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Tabel master sistem penilaian dengan rentang nilai dan bobot';

--
-- Dumping data untuk tabel `grade_system`
--

INSERT INTO `grade_system` (`id`, `grade`, `min_score`, `max_score`, `grade_point`, `status_kelulusan`, `keterangan`, `Status`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'A', 85.00, 100.00, 4.00, 'Lulus', 'Sangat Baik', '1', '2026-01-06 01:36:48', '2026-01-06 01:36:48', NULL),
(2, 'A-', 80.00, 84.99, 3.75, 'Lulus', 'Baik Sekali', '1', '2026-01-06 01:36:48', '2026-01-06 01:36:48', NULL),
(3, 'B+', 75.00, 79.99, 3.50, 'Lulus', 'Lebih dari Baik', '1', '2026-01-06 01:36:48', '2026-01-06 01:36:48', NULL),
(4, 'B', 70.00, 74.99, 3.00, 'Lulus', 'Baik', '1', '2026-01-06 01:36:48', '2026-01-06 01:36:48', NULL),
(5, 'B-', 65.00, 69.99, 2.75, 'Lulus', 'Cukup Baik', '1', '2026-01-06 01:36:48', '2026-01-06 01:36:48', NULL),
(6, 'C+', 60.00, 64.99, 2.50, 'Lulus', 'Lebih dari Cukup', '1', '2026-01-06 01:36:48', '2026-01-06 01:36:48', NULL),
(7, 'C', 55.00, 59.99, 2.00, 'Lulus', 'Cukup', '1', '2026-01-06 01:36:48', '2026-01-06 01:36:48', NULL),
(8, 'D', 50.00, 54.99, 1.00, 'Tidak Lulus', 'Kurang', '1', '2026-01-06 01:36:48', '2026-01-06 01:36:48', NULL),
(9, 'E', 0.00, 49.99, 0.00, 'Tidak Lulus', 'Sangat Kurang', '1', '2026-01-06 01:36:48', '2026-01-06 01:36:48', NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `jadwal_kuliah`
--

CREATE TABLE `jadwal_kuliah` (
  `id` int(11) NOT NULL,
  `kelas_id` int(11) NOT NULL,
  `ruangan_id` int(11) NOT NULL,
  `hari` enum('Senin','Selasa','Rabu','Kamis','Jumat','Sabtu') NOT NULL,
  `jam_mulai` time NOT NULL,
  `jam_selesai` time NOT NULL,
  `keterangan` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `jadwal_kuliah`
--

INSERT INTO `jadwal_kuliah` (`id`, `kelas_id`, `ruangan_id`, `hari`, `jam_mulai`, `jam_selesai`, `keterangan`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 10, 1, 'Senin', '16:00:00', '02:36:00', 'Kuliah reguler', '2025-09-18 01:43:59', '2025-10-15 19:47:52', NULL),
(2, 2, 4, 'Senin', '10:45:00', '13:15:00', 'Kuliah + Praktikum', '2025-09-18 01:43:59', '2025-09-18 01:43:59', NULL),
(3, 3, 2, 'Selasa', '08:00:00', '10:30:00', 'Kuliah reguler', '2025-09-18 01:43:59', '2025-09-18 01:43:59', NULL),
(4, 4, 3, 'Selasa', '10:45:00', '13:15:00', 'Kuliah reguler', '2025-09-18 01:43:59', '2025-09-18 01:43:59', NULL),
(5, 5, 5, 'Rabu', '08:00:00', '10:30:00', 'Praktikum Web', '2025-09-18 01:43:59', '2025-09-18 01:43:59', NULL),
(6, 6, 1, 'Rabu', '10:45:00', '13:15:00', 'Kuliah reguler', '2025-09-18 01:43:59', '2025-09-18 01:43:59', NULL),
(7, 7, 2, 'Kamis', '08:00:00', '09:30:00', 'Kuliah reguler', '2025-09-18 01:43:59', '2025-09-18 01:43:59', NULL),
(8, 8, 3, 'Kamis', '10:00:00', '11:30:00', 'Kuliah reguler', '2025-09-18 01:43:59', '2025-09-18 01:43:59', NULL),
(9, 11, 8, 'Sabtu', '09:55:00', '09:55:00', 'aw', '2025-10-15 19:55:18', '2025-10-15 19:55:18', NULL),
(10, 8, 7, 'Sabtu', '16:52:00', '02:52:00', 'adasdadad', '2025-12-05 00:52:36', '2025-12-05 00:52:36', NULL),
(11, 9, 3, 'Selasa', '08:27:00', '10:27:00', NULL, '2025-12-21 18:27:25', '2025-12-21 18:28:39', NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `jurusan`
--

CREATE TABLE `jurusan` (
  `id` int(11) NOT NULL,
  `kode_jurusan` varchar(10) NOT NULL,
  `nama_jurusan` varchar(100) NOT NULL,
  `fakultas_id` int(11) NOT NULL,
  `jenjang` enum('D3','S1','S2','S3') DEFAULT 'S1',
  `akreditasi` enum('A','B','C','Unggul','Baik Sekali','Baik') DEFAULT NULL,
  `kaprodi` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `jurusan`
--

INSERT INTO `jurusan` (`id`, `kode_jurusan`, `nama_jurusan`, `fakultas_id`, `jenjang`, `akreditasi`, `kaprodi`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'TI', 'Teknik Informatika', 4, 'S3', 'A', 'Dr. Ir. Andi Wijaya, M.T.', '2025-09-18 01:43:59', '2025-11-06 18:50:18', NULL),
(2, 'SI', 'Sistem Informasi', 1, 'S1', 'B', 'Dr. Maya Sari, S.Kom., M.T.', '2025-09-18 01:43:59', '2025-09-18 01:43:59', NULL),
(3, 'TE', 'Teknik Elektro', 1, 'S1', 'A', 'Prof. Dr. Ir. Rudi Hartono, M.T.', '2025-09-18 01:43:59', '2025-09-18 01:43:59', NULL),
(4, 'MN', 'Manajemen', 3, 'S1', 'A', 'Dr. Lina Marlina, S.E., M.M.', '2025-09-18 01:43:59', '2025-12-14 18:44:52', NULL),
(5, 'AK', 'Akuntansi', 2, 'S1', 'B', 'Dr. Bambang Suryono, S.E., M.Ak.', '2025-09-18 01:43:59', '2025-09-18 01:43:59', NULL),
(6, 'HI', 'Hubungan Internasional', 3, 'S1', 'B', 'Dr. Dewi Ratna, S.IP., M.Si.', '2025-09-18 01:43:59', '2025-09-18 01:43:59', NULL),
(7, 'test', 'test', 2, 'S1', 'A', 'test', '2025-10-09 20:31:44', '2025-10-09 20:31:44', NULL),
(8, 'test2', 'test2', 2, 'S2', 'Unggul', 'test2', '2025-10-09 20:37:03', '2025-11-06 19:14:15', NULL),
(11, 'test3', 'test3', 5, 'S2', 'B', 'test4', '2025-11-12 20:05:49', '2025-11-12 20:05:56', NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `kehadiran`
--

CREATE TABLE `kehadiran` (
  `id` int(11) NOT NULL,
  `pertemuan_id` int(11) NOT NULL,
  `mahasiswa_id` int(11) NOT NULL,
  `krs_detail_id` int(11) NOT NULL COMMENT 'Relasi ke KRS detail untuk validasi',
  `status_kehadiran` enum('Hadir','Izin','Sakit','Alpa') NOT NULL DEFAULT 'Alpa',
  `waktu_input` datetime DEFAULT current_timestamp() COMMENT 'Waktu dosen menginput kehadiran',
  `keterangan` text DEFAULT NULL,
  `diinput_oleh` int(11) DEFAULT NULL COMMENT 'ID dosen yang menginput',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Tabel detail kehadiran mahasiswa per pertemuan';

--
-- Dumping data untuk tabel `kehadiran`
--

--
-- Trigger `kehadiran`
--
DELIMITER $$
CREATE TRIGGER `after_kehadiran_insert` AFTER INSERT ON `kehadiran` FOR EACH ROW BEGIN
    DECLARE v_semester_id INT;
    DECLARE v_total_pertemuan INT;
    DECLARE v_total_hadir INT;
    DECLARE v_total_izin INT;
    DECLARE v_total_sakit INT;
    DECLARE v_total_alpa INT;
    DECLARE v_nilai_kehadiran DECIMAL(5,2);
    DECLARE v_persentase DECIMAL(5,2);
    DECLARE v_status_minimal VARCHAR(20);
    DECLARE v_kelas_id INT;
    
    -- Get kelas_id dan semester_id dari pertemuan
    SELECT p.kelas_id, k.semester_id
    INTO v_kelas_id, v_semester_id
    FROM pertemuan p
    JOIN kelas k ON p.kelas_id = k.id
    WHERE p.id = NEW.pertemuan_id;
    
    -- Hitung statistik kehadiran
    SELECT 
        COUNT(*) AS total_pertemuan,
        SUM(CASE WHEN status_kehadiran = 'Hadir' THEN 1 ELSE 0 END) AS total_hadir,
        SUM(CASE WHEN status_kehadiran = 'Izin' THEN 1 ELSE 0 END) AS total_izin,
        SUM(CASE WHEN status_kehadiran = 'Sakit' THEN 1 ELSE 0 END) AS total_sakit,
        SUM(CASE WHEN status_kehadiran = 'Alpa' THEN 1 ELSE 0 END) AS total_alpa
    INTO 
        v_total_pertemuan, v_total_hadir, v_total_izin, v_total_sakit, v_total_alpa
    FROM kehadiran kh
    JOIN pertemuan p ON kh.pertemuan_id = p.id
    WHERE kh.mahasiswa_id = NEW.mahasiswa_id
      AND p.kelas_id = v_kelas_id
      AND kh.deleted_at IS NULL;
    
    -- Hitung persentase
    IF v_total_pertemuan > 0 THEN
        SET v_persentase = ((v_total_hadir + v_total_izin + v_total_sakit) / v_total_pertemuan) * 100;
    ELSE
        SET v_persentase = 0;
    END IF;
    
    -- Hitung nilai kehadiran
    SET v_nilai_kehadiran = (v_total_hadir / v_total_pertemuan) * 100;
    
    -- Tentukan status minimal
    IF v_persentase >= 75 THEN
        SET v_status_minimal = 'Memenuhi';
    ELSE
        SET v_status_minimal = 'Tidak Memenuhi';
    END IF;
    
    -- Update atau Insert rekap_kehadiran
    INSERT INTO rekap_kehadiran (
        mahasiswa_id,
        kelas_id,
        semester_id,
        total_pertemuan,
        total_hadir,
        total_izin,
        total_sakit,
        total_alpa,
        nilai_kehadiran,
        status_minimal,
        keterangan
    ) VALUES (
        NEW.mahasiswa_id,
        v_kelas_id,
        v_semester_id,
        v_total_pertemuan,
        v_total_hadir,
        v_total_izin,
        v_total_sakit,
        v_total_alpa,
        v_nilai_kehadiran,
        v_status_minimal,
        CONCAT('Auto update - persentase: ', ROUND(v_persentase, 2), '%')
    )
    ON DUPLICATE KEY UPDATE
        total_pertemuan = v_total_pertemuan,
        total_hadir = v_total_hadir,
        total_izin = v_total_izin,
        total_sakit = v_total_sakit,
        total_alpa = v_total_alpa,
        nilai_kehadiran = v_nilai_kehadiran,
        status_minimal = v_status_minimal,
        keterangan = CONCAT('Auto update - persentase: ', ROUND(v_persentase, 2), '%'),
        updated_at = NOW();
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `after_kehadiran_update` AFTER UPDATE ON `kehadiran` FOR EACH ROW BEGIN
    DECLARE v_semester_id INT;
    DECLARE v_total_pertemuan INT;
    DECLARE v_total_hadir INT;
    DECLARE v_total_izin INT;
    DECLARE v_total_sakit INT;
    DECLARE v_total_alpa INT;
    DECLARE v_nilai_kehadiran DECIMAL(5,2);
    DECLARE v_persentase DECIMAL(5,2);
    DECLARE v_status_minimal VARCHAR(20);
    DECLARE v_kelas_id INT;
    
    -- Get kelas_id dan semester_id dari pertemuan
    SELECT p.kelas_id, k.semester_id
    INTO v_kelas_id, v_semester_id
    FROM pertemuan p
    JOIN kelas k ON p.kelas_id = k.id
    WHERE p.id = NEW.pertemuan_id;
    
    -- Hitung statistik kehadiran
    SELECT 
        COUNT(*) AS total_pertemuan,
        SUM(CASE WHEN status_kehadiran = 'Hadir' THEN 1 ELSE 0 END) AS total_hadir,
        SUM(CASE WHEN status_kehadiran = 'Izin' THEN 1 ELSE 0 END) AS total_izin,
        SUM(CASE WHEN status_kehadiran = 'Sakit' THEN 1 ELSE 0 END) AS total_sakit,
        SUM(CASE WHEN status_kehadiran = 'Alpa' THEN 1 ELSE 0 END) AS total_alpa
    INTO 
        v_total_pertemuan, v_total_hadir, v_total_izin, v_total_sakit, v_total_alpa
    FROM kehadiran kh
    JOIN pertemuan p ON kh.pertemuan_id = p.id
    WHERE kh.mahasiswa_id = NEW.mahasiswa_id
      AND p.kelas_id = v_kelas_id
      AND kh.deleted_at IS NULL;
    
    -- Hitung persentase (Hadir + Izin + Sakit dianggap hadir)
    IF v_total_pertemuan > 0 THEN
        SET v_persentase = ((v_total_hadir + v_total_izin + v_total_sakit) / v_total_pertemuan) * 100;
    ELSE
        SET v_persentase = 0;
    END IF;
    
    -- Hitung nilai kehadiran (0-100)
    SET v_nilai_kehadiran = (v_total_hadir / v_total_pertemuan) * 100;
    
    -- Tentukan status minimal
    IF v_persentase >= 75 THEN
        SET v_status_minimal = 'Memenuhi';
    ELSE
        SET v_status_minimal = 'Tidak Memenuhi';
    END IF;
    
    -- Update atau Insert rekap_kehadiran
    INSERT INTO rekap_kehadiran (
        mahasiswa_id,
        kelas_id,
        semester_id,
        total_pertemuan,
        total_hadir,
        total_izin,
        total_sakit,
        total_alpa,
        nilai_kehadiran,
        status_minimal,
        keterangan
    ) VALUES (
        NEW.mahasiswa_id,
        v_kelas_id,
        v_semester_id,
        v_total_pertemuan,
        v_total_hadir,
        v_total_izin,
        v_total_sakit,
        v_total_alpa,
        v_nilai_kehadiran,
        v_status_minimal,
        CONCAT('Auto update - persentase: ', ROUND(v_persentase, 2), '%')
    )
    ON DUPLICATE KEY UPDATE
        total_pertemuan = v_total_pertemuan,
        total_hadir = v_total_hadir,
        total_izin = v_total_izin,
        total_sakit = v_total_sakit,
        total_alpa = v_total_alpa,
        nilai_kehadiran = v_nilai_kehadiran,
        status_minimal = v_status_minimal,
        keterangan = CONCAT('Auto update - persentase: ', ROUND(v_persentase, 2), '%'),
        updated_at = NOW();
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Struktur dari tabel `kelas`
--

CREATE TABLE `kelas` (
  `id` int(11) NOT NULL,
  `kode_kelas` varchar(20) NOT NULL,
  `nama_kelas` varchar(50) NOT NULL,
  `mata_kuliah_id` int(11) NOT NULL,
  `dosen_id` int(11) NOT NULL,
  `semester_id` int(11) NOT NULL,
  `kapasitas` int(11) DEFAULT 40,
  `kuota_terisi` int(11) DEFAULT 0,
  `status` enum('Aktif','Nonaktif','Selesai') DEFAULT 'Aktif',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `kelas`
--

INSERT INTO `kelas` (`id`, `kode_kelas`, `nama_kelas`, `mata_kuliah_id`, `dosen_id`, `semester_id`, `kapasitas`, `kuota_terisi`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'TI101-A-2024', 'TI101 Kelas A', 1, 1, 1, 40, 0, 'Nonaktif', '2025-09-18 01:43:59', '2025-10-13 21:11:34', NULL),
(2, 'TI102-A-2024', 'TI102 Kelas A', 2, 3, 1, 35, 7, 'Aktif', '2025-09-18 01:43:59', '2026-01-06 23:21:36', NULL),
(3, 'TI103-A-2024', 'TI103 Kelas A', 3, 1, 1, 40, 3, 'Aktif', '2025-09-18 01:43:59', '2026-01-02 01:27:05', NULL),
(4, 'SI101-A-2024', 'SI101 Kelas A', 8, 2, 1, 30, 1, 'Aktif', '2025-09-18 01:43:59', '2026-01-01 18:47:06', NULL),
(5, 'SI102-A-2024', 'SI102 Kelas A', 9, 2, 1, 25, 1, 'Aktif', '2025-09-18 01:43:59', '2025-12-21 18:42:34', NULL),
(6, 'TE101-A-2024', 'TE101 Kelas A', 12, 3, 1, 35, 2, 'Aktif', '2025-09-18 01:43:59', '2026-01-06 23:21:25', NULL),
(7, 'MKU101-A-2024', 'Bahasa Indonesia A', 17, 1, 1, 50, 0, 'Aktif', '2025-09-18 01:43:59', '2025-09-18 01:43:59', NULL),
(8, 'MKU102-A-2024', 'Pancasila A', 18, 2, 1, 50, 2, 'Aktif', '2025-09-18 01:43:59', '2026-01-06 23:21:16', NULL),
(9, 'asdfgh', 'asdasd', 9, 1, 2, 14, 0, 'Aktif', '2025-10-13 21:35:26', '2025-10-13 21:35:26', NULL),
(10, 'asdadaq', 'qasd', 10, 1, 1, 15, 1, 'Aktif', '2025-10-13 21:35:56', '2025-12-21 18:41:49', NULL),
(11, 'blabla', 'blabla', 1, 2, 4, 5, 0, 'Selesai', '2025-10-13 21:37:25', '2025-10-13 21:37:40', NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `khs`
--

CREATE TABLE `khs` (
  `id` int(11) NOT NULL,
  `mahasiswa_id` int(11) NOT NULL,
  `semester_id` int(11) NOT NULL,
  `sks_semester` int(11) DEFAULT 0,
  `sks_kumulatif` int(11) DEFAULT 0,
  `ip_semester` decimal(3,2) DEFAULT 0.00,
  `ipk` decimal(3,2) DEFAULT 0.00,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `khs`
--

INSERT INTO `khs` (`id`, `mahasiswa_id`, `semester_id`, `sks_semester`, `sks_kumulatif`, `ip_semester`, `ipk`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 3, 3, 25, 54, 3.21, 4.00, '2025-09-18 01:43:59', '2025-10-15 21:32:01', NULL),
(2, 4, 3, 16, 48, 3.50, 3.45, '2025-09-18 01:43:59', '2025-09-18 01:43:59', NULL),
(3, 2, 4, 14, 1, 3.75, 3.75, '2025-10-15 21:27:25', '2026-01-07 06:50:00', NULL),
(4, 1, 2, 12, 12, 1.10, 3.20, '2025-12-09 20:23:44', '2025-12-09 20:23:44', NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `krs`
--

CREATE TABLE `krs` (
  `id` int(11) NOT NULL,
  `mahasiswa_id` int(11) NOT NULL,
  `semester_id` int(11) NOT NULL,
  `tanggal_pengisian` datetime NOT NULL DEFAULT current_timestamp(),
  `tanggal_persetujuan` datetime DEFAULT NULL,
  `status` enum('Draft','Diajukan','Disetujui','Ditolak') DEFAULT 'Draft',
  `total_sks` int(11) DEFAULT 0,
  `catatan` text DEFAULT NULL,
  `dosen_pa_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `krs`
--

INSERT INTO `krs` (`id`, `mahasiswa_id`, `semester_id`, `tanggal_pengisian`, `tanggal_persetujuan`, `status`, `total_sks`, `catatan`, `dosen_pa_id`, `created_at`, `updated_at`, `deleted_at`) VALUES
(18, 1, 1, '2025-12-10 00:00:00', '2025-12-10 00:00:00', 'Disetujui', 20, 'blaa', 1, '2025-12-09 23:06:12', '2026-01-02 01:14:43', NULL),
(19, 2, 4, '2025-12-11 00:00:00', '2025-12-18 00:00:00', 'Disetujui', 15, 'ce ce ce', 2, '2025-12-11 01:34:13', '2025-12-21 18:43:11', NULL),
(20, 3, 2, '2025-12-22 00:00:00', NULL, 'Ditolak', 22, 'sks kurang', 2, '2025-12-21 18:29:31', '2026-01-02 01:41:11', NULL),
(21, 6, 4, '2025-12-29 00:00:00', NULL, 'Draft', 10, 'a', 1, '2025-12-28 18:35:12', '2026-01-02 01:28:39', '2026-01-02 01:28:39');

-- --------------------------------------------------------

--
-- Struktur dari tabel `krs_detail`
--

CREATE TABLE `krs_detail` (
  `id` int(11) NOT NULL,
  `krs_id` int(11) NOT NULL,
  `kelas_id` int(11) NOT NULL,
  `mata_kuliah_id` int(11) NOT NULL,
  `sks` int(11) NOT NULL,
  `status_ambil` enum('Baru','Mengulang','Perbaikan') DEFAULT 'Baru',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `krs_detail`
--

INSERT INTO `krs_detail` (`id`, `krs_id`, `kelas_id`, `mata_kuliah_id`, `sks`, `status_ambil`, `created_at`, `updated_at`, `deleted_at`) VALUES
(42, 18, 2, 2, 9, 'Baru', '2026-01-02 01:26:44', '2026-01-02 01:26:44', NULL),
(43, 18, 3, 3, 3, 'Baru', '2026-01-02 01:27:05', '2026-01-02 01:27:05', NULL),
(44, 19, 8, 18, 2, 'Baru', '2026-01-06 23:21:14', '2026-01-06 23:21:14', NULL),
(45, 19, 6, 12, 3, 'Baru', '2026-01-06 23:21:24', '2026-01-06 23:21:24', NULL),
(46, 19, 2, 2, 9, 'Baru', '2026-01-06 23:21:35', '2026-01-06 23:21:35', NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `mahasiswa`
--

CREATE TABLE `mahasiswa` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `nim` varchar(20) NOT NULL,
  `nama_lengkap` varchar(100) NOT NULL,
  `jurusan_id` int(11) NOT NULL,
  `angkatan` year(4) NOT NULL,
  `jenis_kelamin` enum('L','P') NOT NULL,
  `tempat_lahir` varchar(50) DEFAULT NULL,
  `tanggal_lahir` date DEFAULT NULL,
  `alamat` text DEFAULT NULL,
  `no_hp` varchar(20) DEFAULT NULL,
  `email_pribadi` varchar(100) DEFAULT NULL,
  `nama_ayah` varchar(100) DEFAULT NULL,
  `nama_ibu` varchar(100) DEFAULT NULL,
  `no_hp_ortu` varchar(20) DEFAULT NULL,
  `status` enum('Aktif','Cuti','Lulus','DO','Mengundurkan Diri') DEFAULT 'Aktif',
  `semester_saat_ini` tinyint(4) DEFAULT 1,
  `ip_semester` decimal(3,2) DEFAULT 0.00,
  `ipk` decimal(3,2) DEFAULT 0.00,
  `total_sks` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `mahasiswa`
--

INSERT INTO `mahasiswa` (`id`, `user_id`, `nim`, `nama_lengkap`, `jurusan_id`, `angkatan`, `jenis_kelamin`, `tempat_lahir`, `tanggal_lahir`, `alamat`, `no_hp`, `email_pribadi`, `nama_ayah`, `nama_ibu`, `no_hp_ortu`, `status`, `semester_saat_ini`, `ip_semester`, `ipk`, `total_sks`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 5, '20240001', 'Ahmad Fauzi2', 2, '2024', 'L', 'Jakarta', '2005-03-15', 'Jl. Kenanga No. 123, Jakarta', '081234567893', 'ahmad.fauzi@gmail.com', 'Budi Fauzi', 'Siti Aisyah', '081234567894', 'Aktif', 1, 0.00, 1.00, 15, '2025-09-18 01:43:59', '2025-12-23 19:05:26', NULL),
(2, 6, '20240002', 'Sari Indah Putri', 2, '2024', 'P', 'Yogyakarta', '2005-07-22', 'Jl. Flamboyan No. 456, Yogyakarta', '081234567895', 'sari.indah@gmail.com', 'Joko Santoso', 'Dewi Sartika', '081234567896', 'Aktif', 1, 1.00, 1.00, 0, '2025-09-18 01:43:59', '2025-12-15 08:12:54', NULL),
(3, 7, '20230001', 'Budi Setiawan', 1, '2023', 'L', 'Medan', '2004-11-08', 'Jl. Cempaka No. 789, Medan', '081234567897', 'budi.setiawan@gmail.com', 'Andi Setiawan', 'Maya Lestari', '081234567898', 'Aktif', 3, 2.13, 2.00, 14, '2025-09-18 01:43:59', '2025-12-22 09:01:49', NULL),
(4, 8, '20230002', 'Rina Wati', 4, '2023', 'P', 'Surabaya', '2004-05-12', 'Jl. Dahlia No. 321, Surabaya', '081234567899', 'rina.wati@gmail.com', 'Hendra Wati', 'Susi Rahayu', '081234567900', 'Aktif', 3, 0.00, 0.00, 0, '2025-09-18 01:43:59', '2025-09-18 01:43:59', NULL),
(6, 4, '1234567', 'Shiro Neko', 4, '2025', 'L', NULL, NULL, 'jalan kenangan', '081294763785', 'farhansdwisatria10@gmail.com', NULL, NULL, NULL, 'Aktif', 5, 0.00, 0.00, 0, '2025-10-07 23:28:08', '2025-10-16 20:02:56', NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `mata_kuliah`
--

CREATE TABLE `mata_kuliah` (
  `id` int(11) NOT NULL,
  `kode_mk` varchar(15) NOT NULL,
  `nama_mk` varchar(100) NOT NULL,
  `jurusan_id` int(11) NOT NULL,
  `sks` tinyint(4) NOT NULL DEFAULT 3,
  `semester_rekomendasi` tinyint(4) DEFAULT NULL,
  `jenis` enum('Wajib','Pilihan') DEFAULT 'Wajib',
  `deskripsi` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `mata_kuliah`
--

INSERT INTO `mata_kuliah` (`id`, `kode_mk`, `nama_mk`, `jurusan_id`, `sks`, `semester_rekomendasi`, `jenis`, `deskripsi`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'TI101', 'Pengantar Teknologi Informasi', 3, 3, 5, 'Wajib', 'Mata kuliah pengantar mengenai dasar-dasar teknologi informasi', '2025-09-18 01:43:59', '2025-10-13 19:12:05', NULL),
(2, 'TI102', 'Algoritma dan Pemrograman', 1, 9, 1, 'Wajib', 'Pembelajaran algoritma dasar dan pemrograman komputer', '2025-09-18 01:43:59', '2026-01-02 01:25:51', NULL),
(3, 'TI103', 'Matematika Diskrit', 1, 3, 1, 'Wajib', 'Matematika untuk ilmu komputer', '2025-09-18 01:43:59', '2025-09-18 01:43:59', NULL),
(4, 'TI201', 'Struktur Data', 1, 3, 3, 'Wajib', 'Struktur data dan algoritma lanjutan', '2025-09-18 01:43:59', '2025-09-18 01:43:59', NULL),
(5, 'TI202', 'Basis Data', 1, 3, 3, 'Wajib', 'Desain dan implementasi basis data', '2025-09-18 01:43:59', '2025-09-18 01:43:59', NULL),
(6, 'TI301', 'Rekayasa Perangkat Lunak', 1, 3, 5, 'Wajib', 'Metodologi pengembangan perangkat lunak', '2025-09-18 01:43:59', '2025-09-18 01:43:59', NULL),
(7, 'TI302', 'Kecerdasan Buatan', 1, 3, 5, 'Pilihan', 'Pengantar artificial intelligence', '2025-09-18 01:43:59', '2025-09-18 01:43:59', NULL),
(8, 'SI101', 'Pengantar Sistem Informasi', 2, 3, 1, 'Wajib', 'Konsep dasar sistem informasi', '2025-09-18 01:43:59', '2025-09-18 01:43:59', NULL),
(9, 'SI102', 'Pemrograman Web', 2, 3, 2, 'Wajib', 'Pengembangan aplikasi berbasis web', '2025-09-18 01:43:59', '2025-09-18 01:43:59', NULL),
(10, 'SI201', 'Analisis dan Perancangan Sistem', 2, 3, 3, 'Wajib', 'Metodologi analisis sistem informasi', '2025-09-18 01:43:59', '2025-09-18 01:43:59', NULL),
(11, 'SI202', 'Manajemen Basis Data', 2, 3, 4, 'Wajib', 'Administrasi dan optimasi basis data', '2025-09-18 01:43:59', '2025-09-18 01:43:59', NULL),
(12, 'TE101', 'Rangkaian Listrik', 3, 3, 1, 'Wajib', 'Analisis rangkaian listrik dasar', '2025-09-18 01:43:59', '2025-09-18 01:43:59', NULL),
(13, 'TE102', 'Elektronika Dasar', 3, 4, 2, 'Wajib', 'Komponen dan rangkaian elektronika', '2025-09-18 01:43:59', '2025-09-18 01:43:59', NULL),
(14, 'TE201', 'Sistem Digital', 3, 3, 3, 'Wajib', 'Desain sistem digital', '2025-09-18 01:43:59', '2025-09-18 01:43:59', NULL),
(15, 'MN101', 'Pengantar Manajemen', 4, 3, 1, 'Wajib', 'Konsep dasar ilmu manajemen', '2025-09-18 01:43:59', '2025-09-18 01:43:59', NULL),
(16, 'MN102', 'Manajemen Sumber Daya Manusia', 4, 3, 3, 'Wajib', 'Pengelolaan SDM dalam organisasi', '2025-09-18 01:43:59', '2025-09-18 01:43:59', NULL),
(17, 'MN201', 'Manajemen Pemasaran', 4, 3, 4, 'Wajib', 'Strategi dan implementasi pemasaran', '2025-09-18 01:43:59', '2025-09-18 01:43:59', NULL),
(18, 'MKU101', 'Bahasa Indonesia', 1, 2, 1, 'Wajib', 'Kemampuan berbahasa Indonesia', '2025-09-18 01:43:59', '2025-09-18 01:43:59', NULL),
(19, 'MKU102', 'Pancasila', 1, 2, 1, 'Wajib', 'Ideologi dan nilai-nilai Pancasila', '2025-09-18 01:43:59', '2025-09-18 01:43:59', NULL),
(20, 'MKU103', 'Bahasa Inggris', 1, 2, 2, 'Wajib', 'Kemampuan berbahasa Inggris', '2025-09-18 01:43:59', '2025-09-18 01:43:59', NULL),
(21, 'a', 'aa', 5, 5, 5, 'Wajib', 'a', '2025-10-13 18:07:44', '2025-10-13 18:07:44', NULL),
(23, 'b', 'b', 3, 2, 1, 'Wajib', 'bla bla bla', '2025-10-13 18:13:10', '2025-10-13 18:13:10', NULL),
(24, 'c', 'c', 7, 12, 5, 'Wajib', 'bla bla bla', '2025-10-13 18:14:15', '2025-10-13 18:14:15', NULL),
(25, 'TA298', 'entahlah', 2, 12, 5, 'Pilihan', 'bla bla bla', '2025-10-13 18:16:50', '2025-10-13 18:16:50', NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000001_create_cache_table', 1),
(2, '0001_01_01_000002_create_jobs_table', 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `nilai`
--

CREATE TABLE `nilai` (
  `id` int(11) NOT NULL,
  `krs_detail_id` int(11) NOT NULL,
  `bobot_nilai_id` int(11) DEFAULT NULL,
  `tugas` decimal(5,2) DEFAULT NULL,
  `quiz` decimal(5,2) DEFAULT NULL,
  `uts` decimal(5,2) DEFAULT NULL,
  `uas` decimal(5,2) DEFAULT NULL,
  `kehadiran` decimal(5,2) DEFAULT NULL,
  `praktikum` decimal(5,2) DEFAULT NULL,
  `nilai_akhir` decimal(5,2) DEFAULT NULL,
  `nilai_huruf` enum('A','B','C','D','E') DEFAULT NULL,
  `nilai_mutu` decimal(3,2) DEFAULT NULL,
  `status` enum('Draft','Final') DEFAULT 'Draft',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL,
  `tanggal_input` datetime DEFAULT current_timestamp(),
  `input_by` int(11) DEFAULT NULL COMMENT 'ID dosen yang menginput nilai'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `nilai`
--

INSERT INTO `nilai` (`id`, `krs_detail_id`, `bobot_nilai_id`, `tugas`, `quiz`, `uts`, `uas`, `kehadiran`, `praktikum`, `nilai_akhir`, `nilai_huruf`, `nilai_mutu`, `status`, `created_at`, `updated_at`, `deleted_at`, `tanggal_input`, `input_by`) VALUES
(1, 42, 1, 85.00, 80.00, 78.00, 82.00, 95.00, 88.00, 83.50, 'A', 4.00, 'Final', '2026-01-07 06:49:59', '2026-01-07 06:49:59', NULL, '2024-12-20 10:00:00', 4),
(2, 43, 2, 88.00, 85.00, 80.00, 85.00, NULL, NULL, 84.75, 'A', 4.00, 'Final', '2026-01-07 06:49:59', '2026-01-07 06:49:59', NULL, '2024-12-20 10:30:00', 2),
(3, 44, 4, 90.00, 88.00, 85.00, 87.00, NULL, NULL, 87.50, 'A', 4.00, 'Final', '2026-01-07 06:49:59', '2026-01-07 06:49:59', NULL, '2024-12-20 11:00:00', 3),
(4, 45, 3, 82.00, 80.00, 78.00, 80.00, 90.00, NULL, 80.25, '', 3.75, 'Final', '2026-01-07 06:49:59', '2026-01-07 06:49:59', NULL, '2024-12-20 11:30:00', 4),
(5, 46, 1, 78.00, 75.00, 72.00, 76.00, 87.00, 80.00, 76.15, '', 3.50, 'Final', '2026-01-07 06:49:59', '2026-01-07 06:49:59', NULL, '2024-12-20 12:00:00', 4);

-- --------------------------------------------------------

--
-- Struktur dari tabel `pengaturan_kehadiran`
--

CREATE TABLE `pengaturan_kehadiran` (
  `id` int(11) NOT NULL,
  `kelas_id` int(11) NOT NULL,
  `minimal_kehadiran` decimal(5,2) DEFAULT 75.00 COMMENT 'Minimal kehadiran dalam persen untuk bisa ikut ujian',
  `auto_generate_pertemuan` tinyint(1) DEFAULT 1 COMMENT 'Auto generate pertemuan sesuai jadwal',
  `keterangan` text DEFAULT NULL,
  `aktif` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Tabel pengaturan aturan kehadiran per kelas';

--
-- Dumping data untuk tabel `pengaturan_kehadiran`
--

INSERT INTO `pengaturan_kehadiran` (`id`, `kelas_id`, `minimal_kehadiran`, `auto_generate_pertemuan`, `keterangan`, `aktif`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 1, 75.00, 1, 'Minimal kehadiran untuk ikut UAS', 1, '2026-01-07 06:50:00', '2026-01-07 06:50:00', NULL),
(2, 2, 75.00, 1, 'Minimal kehadiran untuk ikut UAS', 1, '2026-01-07 06:50:00', '2026-01-07 06:50:00', NULL),
(3, 3, 75.00, 1, 'Minimal kehadiran untuk ikut UAS', 1, '2026-01-07 06:50:00', '2026-01-07 06:50:00', NULL),
(4, 4, 75.00, 1, 'Minimal kehadiran untuk ikut UAS', 1, '2026-01-07 06:50:00', '2026-01-07 06:50:00', NULL),
(5, 5, 75.00, 1, 'Minimal kehadiran untuk ikut UAS', 1, '2026-01-07 06:50:00', '2026-01-07 06:50:00', NULL),
(6, 6, 75.00, 1, 'Minimal kehadiran untuk ikut UAS', 1, '2026-01-07 06:50:00', '2026-01-07 06:50:00', NULL),
(7, 7, 75.00, 1, 'Minimal kehadiran untuk ikut UAS', 1, '2026-01-07 06:50:00', '2026-01-07 06:50:00', NULL),
(8, 8, 75.00, 1, 'Minimal kehadiran untuk ikut UAS', 1, '2026-01-07 06:50:00', '2026-01-07 06:50:00', NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `pertemuan`
--

CREATE TABLE `pertemuan` (
  `id` int(11) NOT NULL,
  `kelas_id` int(11) NOT NULL,
  `pertemuan_ke` tinyint(4) NOT NULL COMMENT 'Pertemuan ke-1 sampai ke-16',
  `tanggal` date NOT NULL,
  `waktu_mulai` time NOT NULL,
  `waktu_selesai` time NOT NULL,
  `materi` varchar(255) DEFAULT NULL,
  `metode` enum('Tatap Muka','Daring','Hybrid') DEFAULT 'Tatap Muka',
  `ruangan_id` int(11) DEFAULT NULL,
  `status_pertemuan` enum('Dijadwalkan','Berlangsung','Selesai','Dibatalkan') DEFAULT 'Dijadwalkan',
  `link_daring` varchar(255) DEFAULT NULL COMMENT 'Link Zoom/GMeet untuk daring',
  `catatan` text DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL COMMENT 'ID dosen yang membuat pertemuan',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Tabel master pertemuan/sesi kuliah per kelas';

--
-- Dumping data untuk tabel `pertemuan`
--

INSERT INTO `pertemuan` (`id`, `kelas_id`, `pertemuan_ke`, `tanggal`, `waktu_mulai`, `waktu_selesai`, `materi`, `metode`, `ruangan_id`, `status_pertemuan`, `link_daring`, `catatan`, `created_by`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 1, 1, '2024-09-02', '08:00:00', '10:30:00', 'Pengenalan Teknologi Informasi', 'Tatap Muka', 1, 'Selesai', NULL, 'Pertemuan pembuka semester', 2, '2026-01-07 06:49:59', '2026-01-07 06:49:59', NULL),
(2, 1, 2, '2024-09-09', '08:00:00', '10:30:00', 'Komponen Sistem Komputer', 'Tatap Muka', 1, 'Selesai', NULL, NULL, 2, '2026-01-07 06:49:59', '2026-01-07 06:49:59', NULL),
(3, 1, 3, '2024-09-16', '08:00:00', '10:30:00', 'Sistem Operasi', 'Tatap Muka', 1, 'Selesai', NULL, NULL, 2, '2026-01-07 06:49:59', '2026-01-07 06:49:59', NULL),
(4, 1, 4, '2024-09-23', '08:00:00', '10:30:00', 'Jaringan Komputer Dasar', 'Tatap Muka', 1, 'Selesai', NULL, NULL, 2, '2026-01-07 06:49:59', '2026-01-07 06:49:59', NULL),
(5, 1, 5, '2024-09-30', '08:00:00', '10:30:00', 'Database Fundamental', 'Tatap Muka', 1, 'Selesai', NULL, NULL, 2, '2026-01-07 06:49:59', '2026-01-07 06:49:59', NULL),
(6, 1, 6, '2024-10-07', '08:00:00', '10:30:00', 'Pemrograman Dasar', 'Tatap Muka', 1, 'Selesai', NULL, NULL, 2, '2026-01-07 06:49:59', '2026-01-07 06:49:59', NULL),
(7, 1, 7, '2024-10-14', '08:00:00', '10:30:00', 'Web Technology', 'Tatap Muka', 1, 'Selesai', NULL, NULL, 2, '2026-01-07 06:49:59', '2026-01-07 06:49:59', NULL),
(8, 1, 8, '2024-10-21', '08:00:00', '10:30:00', 'UTS - Ujian Tengah Semester', 'Tatap Muka', 1, 'Selesai', NULL, 'Ujian tertulis', 2, '2026-01-07 06:49:59', '2026-01-07 06:49:59', NULL),
(9, 1, 9, '2024-10-28', '08:00:00', '10:30:00', 'Keamanan Informasi', 'Tatap Muka', 1, 'Selesai', NULL, NULL, 2, '2026-01-07 06:49:59', '2026-01-07 06:49:59', NULL),
(10, 1, 10, '2024-11-04', '08:00:00', '10:30:00', 'Cloud Computing', 'Daring', 1, 'Selesai', NULL, 'Kuliah online via Zoom', 2, '2026-01-07 06:49:59', '2026-01-07 06:49:59', NULL),
(11, 1, 11, '2024-11-11', '08:00:00', '10:30:00', 'Big Data Introduction', 'Tatap Muka', 1, 'Selesai', NULL, NULL, 2, '2026-01-07 06:49:59', '2026-01-07 06:49:59', NULL),
(12, 1, 12, '2024-11-18', '08:00:00', '10:30:00', 'Internet of Things', 'Tatap Muka', 1, 'Selesai', NULL, NULL, 2, '2026-01-07 06:49:59', '2026-01-07 06:49:59', NULL),
(13, 1, 13, '2024-11-25', '08:00:00', '10:30:00', 'Mobile Technology', 'Tatap Muka', 1, 'Selesai', NULL, NULL, 2, '2026-01-07 06:49:59', '2026-01-07 06:49:59', NULL),
(14, 1, 14, '2024-12-02', '08:00:00', '10:30:00', 'AI & Machine Learning Intro', 'Tatap Muka', 1, 'Selesai', NULL, NULL, 2, '2026-01-07 06:49:59', '2026-01-07 06:49:59', NULL),
(15, 1, 15, '2024-12-09', '08:00:00', '10:30:00', 'Review & Diskusi', 'Tatap Muka', 1, 'Selesai', NULL, NULL, 2, '2026-01-07 06:49:59', '2026-01-07 06:49:59', NULL),
(16, 1, 16, '2024-12-16', '08:00:00', '10:30:00', 'UAS - Ujian Akhir Semester', 'Tatap Muka', 1, 'Dijadwalkan', NULL, 'Ujian tertulis', 2, '2026-01-07 06:49:59', '2026-01-07 06:49:59', NULL),
(17, 2, 1, '2024-09-02', '10:45:00', '13:15:00', 'Pengenalan Algoritma & Flowchart', 'Tatap Muka', 4, 'Selesai', NULL, NULL, 4, '2026-01-07 06:49:59', '2026-01-07 06:49:59', NULL),
(18, 2, 2, '2024-09-09', '10:45:00', '13:15:00', 'Tipe Data & Variable', 'Tatap Muka', 4, 'Selesai', NULL, NULL, 4, '2026-01-07 06:49:59', '2026-01-07 06:49:59', NULL),
(19, 2, 3, '2024-09-16', '10:45:00', '13:15:00', 'Operator & Ekspresi', 'Tatap Muka', 4, 'Selesai', NULL, NULL, 4, '2026-01-07 06:49:59', '2026-01-07 06:49:59', NULL),
(20, 2, 4, '2024-09-23', '10:45:00', '13:15:00', 'Percabangan (IF-ELSE)', 'Tatap Muka', 4, 'Selesai', NULL, NULL, 4, '2026-01-07 06:49:59', '2026-01-07 06:49:59', NULL),
(21, 2, 5, '2024-09-30', '10:45:00', '13:15:00', 'Perulangan (FOR, WHILE)', 'Tatap Muka', 4, 'Selesai', NULL, NULL, 4, '2026-01-07 06:49:59', '2026-01-07 06:49:59', NULL),
(22, 2, 6, '2024-10-07', '10:45:00', '13:15:00', 'Array & Matrix', 'Tatap Muka', 4, 'Selesai', NULL, NULL, 4, '2026-01-07 06:49:59', '2026-01-07 06:49:59', NULL),
(23, 2, 7, '2024-10-14', '10:45:00', '13:15:00', 'Function & Procedure', 'Tatap Muka', 4, 'Selesai', NULL, NULL, 4, '2026-01-07 06:49:59', '2026-01-07 06:49:59', NULL),
(24, 2, 8, '2024-10-21', '10:45:00', '13:15:00', 'UTS - Ujian Tengah Semester', 'Tatap Muka', 4, 'Selesai', NULL, 'Ujian praktikum', 4, '2026-01-07 06:49:59', '2026-01-07 06:49:59', NULL),
(25, 2, 9, '2024-10-28', '10:45:00', '13:15:00', 'Searching Algorithm', 'Tatap Muka', 4, 'Selesai', NULL, NULL, 4, '2026-01-07 06:49:59', '2026-01-07 06:49:59', NULL),
(26, 2, 10, '2024-11-04', '10:45:00', '13:15:00', 'Sorting Algorithm', 'Tatap Muka', 4, 'Selesai', NULL, NULL, 4, '2026-01-07 06:49:59', '2026-01-07 06:49:59', NULL),
(27, 2, 11, '2024-11-11', '10:45:00', '13:15:00', 'Recursion', 'Tatap Muka', 4, 'Selesai', NULL, NULL, 4, '2026-01-07 06:49:59', '2026-01-07 06:49:59', NULL),
(28, 2, 12, '2024-11-18', '10:45:00', '13:15:00', 'String Processing', 'Tatap Muka', 4, 'Selesai', NULL, NULL, 4, '2026-01-07 06:49:59', '2026-01-07 06:49:59', NULL),
(29, 2, 13, '2024-11-25', '10:45:00', '13:15:00', 'File Handling', 'Tatap Muka', 4, 'Selesai', NULL, NULL, 4, '2026-01-07 06:49:59', '2026-01-07 06:49:59', NULL),
(30, 2, 14, '2024-12-02', '10:45:00', '13:15:00', 'Dynamic Programming', 'Tatap Muka', 4, 'Selesai', NULL, NULL, 4, '2026-01-07 06:49:59', '2026-01-07 06:49:59', NULL),
(31, 2, 15, '2024-12-09', '10:45:00', '13:15:00', 'Review & Latihan Soal', 'Tatap Muka', 4, 'Selesai', NULL, NULL, 4, '2026-01-07 06:49:59', '2026-01-07 06:49:59', NULL),
(32, 2, 16, '2024-12-16', '10:45:00', '13:15:00', 'UAS - Project Akhir', 'Tatap Muka', 4, 'Dijadwalkan', NULL, 'Presentasi project', 4, '2026-01-07 06:49:59', '2026-01-07 06:49:59', NULL),
(33, 3, 1, '2024-09-03', '08:00:00', '10:30:00', 'Logika Matematika', 'Tatap Muka', 2, 'Selesai', NULL, NULL, 2, '2026-01-07 06:49:59', '2026-01-07 06:49:59', NULL),
(34, 3, 2, '2024-09-10', '08:00:00', '10:30:00', 'Himpunan & Operasi Himpunan', 'Tatap Muka', 2, 'Selesai', NULL, NULL, 2, '2026-01-07 06:49:59', '2026-01-07 06:49:59', NULL),
(35, 3, 3, '2024-09-17', '08:00:00', '10:30:00', 'Relasi & Fungsi', 'Tatap Muka', 2, 'Selesai', NULL, NULL, 2, '2026-01-07 06:49:59', '2026-01-07 06:49:59', NULL),
(36, 3, 4, '2024-09-24', '08:00:00', '10:30:00', 'Kombinatorika Dasar', 'Tatap Muka', 2, 'Selesai', NULL, NULL, 2, '2026-01-07 06:49:59', '2026-01-07 06:49:59', NULL),
(37, 3, 5, '2024-10-01', '08:00:00', '10:30:00', 'Permutasi & Kombinasi', 'Tatap Muka', 2, 'Selesai', NULL, NULL, 2, '2026-01-07 06:49:59', '2026-01-07 06:49:59', NULL),
(38, 3, 6, '2024-10-08', '08:00:00', '10:30:00', 'Prinsip Inklusi-Eksklusi', 'Tatap Muka', 2, 'Selesai', NULL, NULL, 2, '2026-01-07 06:49:59', '2026-01-07 06:49:59', NULL),
(39, 3, 7, '2024-10-15', '08:00:00', '10:30:00', 'Teori Graf Dasar', 'Tatap Muka', 2, 'Selesai', NULL, NULL, 2, '2026-01-07 06:49:59', '2026-01-07 06:49:59', NULL),
(40, 3, 8, '2024-10-22', '08:00:00', '10:30:00', 'UTS', 'Tatap Muka', 2, 'Selesai', NULL, NULL, 2, '2026-01-07 06:49:59', '2026-01-07 06:49:59', NULL),
(41, 3, 9, '2024-10-29', '08:00:00', '10:30:00', 'Graf Berarah & Tidak Berarah', 'Tatap Muka', 2, 'Selesai', NULL, NULL, 2, '2026-01-07 06:49:59', '2026-01-07 06:49:59', NULL),
(42, 3, 10, '2024-11-05', '08:00:00', '10:30:00', 'Pohon (Tree)', 'Tatap Muka', 2, 'Selesai', NULL, NULL, 2, '2026-01-07 06:49:59', '2026-01-07 06:49:59', NULL),
(43, 3, 11, '2024-11-12', '08:00:00', '10:30:00', 'Algoritma Pohon Rentang', 'Tatap Muka', 2, 'Selesai', NULL, NULL, 2, '2026-01-07 06:49:59', '2026-01-07 06:49:59', NULL),
(44, 3, 12, '2024-11-19', '08:00:00', '10:30:00', 'Teori Bilangan', 'Tatap Muka', 2, 'Selesai', NULL, NULL, 2, '2026-01-07 06:49:59', '2026-01-07 06:49:59', NULL),
(45, 3, 13, '2024-11-26', '08:00:00', '10:30:00', 'Algoritma Euclidean', 'Tatap Muka', 2, 'Selesai', NULL, NULL, 2, '2026-01-07 06:49:59', '2026-01-07 06:49:59', NULL),
(46, 3, 14, '2024-12-03', '08:00:00', '10:30:00', 'Induksi Matematika', 'Tatap Muka', 2, 'Selesai', NULL, NULL, 2, '2026-01-07 06:49:59', '2026-01-07 06:49:59', NULL),
(47, 3, 15, '2024-12-10', '08:00:00', '10:30:00', 'Review Materi', 'Tatap Muka', 2, 'Selesai', NULL, NULL, 2, '2026-01-07 06:49:59', '2026-01-07 06:49:59', NULL),
(48, 3, 16, '2024-12-17', '08:00:00', '10:30:00', 'UAS', 'Tatap Muka', 2, 'Dijadwalkan', NULL, NULL, 2, '2026-01-07 06:49:59', '2026-01-07 06:49:59', NULL),
(49, 7, 5, '2026-01-07', '08:00:00', '10:30:00', 'a', 'Tatap Muka', 6, 'Dijadwalkan', NULL, NULL, 1, '2026-01-07 06:55:00', '2026-01-07 06:55:00', NULL),
(50, 2, 1, '2026-01-10', '08:00:00', '10:00:00', 'Pengenalan Algoritma', 'Tatap Muka', 1, 'Dijadwalkan', NULL, NULL, 4, '2026-01-07 08:05:35', '2026-01-07 08:05:35', NULL);

--
-- Trigger `pertemuan`
--
DELIMITER $$
CREATE TRIGGER `after_pertemuan_insert` AFTER INSERT ON `pertemuan` FOR EACH ROW BEGIN
    -- Insert kehadiran untuk semua mahasiswa yang terdaftar di kelas
    INSERT INTO `kehadiran` (
        `pertemuan_id`,
        `mahasiswa_id`,
        `krs_detail_id`,
        `status_kehadiran`,
        `keterangan`,
        `diinput_oleh`,
        `waktu_input`
    )
    SELECT 
        NEW.id AS pertemuan_id,
        krs.mahasiswa_id,
        kd.id AS krs_detail_id,
        'Alpa' AS status_kehadiran,
        'Auto generated - belum diisi dosen' AS keterangan,
        NEW.created_by AS diinput_oleh,
        NOW() AS waktu_input
    FROM 
        `krs_detail` kd
    INNER JOIN 
        `krs` ON kd.krs_id = krs.id
    WHERE 
        kd.kelas_id = NEW.kelas_id
        AND krs.status = 'Disetujui'
        AND kd.deleted_at IS NULL
        AND krs.deleted_at IS NULL;
        
    -- Optional: Update status pertemuan menjadi 'Berlangsung' jika hari ini
    IF NEW.tanggal = CURDATE() THEN
        UPDATE `pertemuan` 
        SET `status_pertemuan` = 'Berlangsung'
        WHERE id = NEW.id;
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Struktur dari tabel `rekap_kehadiran`
--

CREATE TABLE `rekap_kehadiran` (
  `id` int(11) NOT NULL,
  `mahasiswa_id` int(11) NOT NULL,
  `kelas_id` int(11) NOT NULL,
  `semester_id` int(11) NOT NULL,
  `total_pertemuan` int(11) DEFAULT 0,
  `total_hadir` int(11) DEFAULT 0,
  `total_izin` int(11) DEFAULT 0,
  `total_sakit` int(11) DEFAULT 0,
  `total_alpa` int(11) DEFAULT 0,
  `persentase_kehadiran` decimal(5,2) GENERATED ALWAYS AS (case when `total_pertemuan` > 0 then (`total_hadir` + `total_izin` + `total_sakit`) / `total_pertemuan` * 100 else 0 end) STORED COMMENT 'Persentase kehadiran (Hadir + Izin + Sakit)',
  `nilai_kehadiran` decimal(5,2) DEFAULT NULL COMMENT 'Nilai kehadiran 0-100',
  `status_minimal` enum('Memenuhi','Tidak Memenuhi') DEFAULT 'Memenuhi' COMMENT 'Status memenuhi minimal kehadiran 75%',
  `keterangan` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Tabel rekap kehadiran mahasiswa per kelas (auto-calculated)';

--
-- Dumping data untuk tabel `rekap_kehadiran`
--

INSERT INTO `rekap_kehadiran` (`id`, `mahasiswa_id`, `kelas_id`, `semester_id`, `total_pertemuan`, `total_hadir`, `total_izin`, `total_sakit`, `total_alpa`, `nilai_kehadiran`, `status_minimal`, `keterangan`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 1, 2, 1, 15, 12, 2, 1, 0, 95.00, 'Memenuhi', 'Kehadiran sangat baik', '2026-01-07 06:49:59', '2026-01-07 06:49:59', NULL),
(2, 1, 3, 1, 15, 13, 1, 0, 1, 85.00, 'Memenuhi', 'Kehadiran baik', '2026-01-07 06:49:59', '2026-01-07 06:49:59', NULL),
(3, 2, 8, 1, 15, 15, 0, 0, 0, 100.00, 'Memenuhi', 'Kehadiran sempurna', '2026-01-07 06:49:59', '2026-01-07 06:49:59', NULL),
(4, 2, 6, 1, 15, 13, 0, 2, 0, 90.00, 'Memenuhi', 'Kehadiran baik', '2026-01-07 06:49:59', '2026-01-07 06:49:59', NULL),
(5, 2, 2, 1, 15, 13, 1, 0, 1, 87.00, 'Memenuhi', 'Kehadiran baik', '2026-01-07 06:49:59', '2026-01-07 06:49:59', NULL),
(6, 1, 2, 1, 16, 12, 2, 1, 1, 75.00, 'Memenuhi', 'Auto update - persentase: 93.75%', '2026-01-07 08:05:35', '2026-01-07 08:05:35', NULL),
(7, 2, 2, 1, 16, 13, 1, 0, 2, 81.25, 'Memenuhi', 'Auto update - persentase: 87.50%', '2026-01-07 08:05:35', '2026-01-07 08:05:35', NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `roles`
--

CREATE TABLE `roles` (
  `id` int(11) NOT NULL,
  `nama_role` varchar(50) NOT NULL,
  `deskripsi` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `roles`
--

INSERT INTO `roles` (`id`, `nama_role`, `deskripsi`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Admin', 'Administrator sistem', '2025-09-18 01:43:59', '2025-10-22 20:45:09', NULL),
(2, 'Dosen', 'Dosen/Pengajar', '2025-09-18 01:43:59', '2025-10-23 03:05:57', NULL),
(3, 'Mahasiswa', 'Mahasiswa', '2025-09-18 01:43:59', '2025-10-23 03:05:57', NULL),
(4, 'Akademik', 'Staff Akademik', '2025-09-18 01:43:59', '2025-10-07 18:22:31', '2025-10-07 18:22:31'),
(5, 'Test', NULL, '2025-10-01 23:41:47', '2025-10-07 18:22:34', '2025-10-07 18:22:34'),
(6, 'test 2', NULL, '2025-10-01 23:42:41', '2025-10-07 18:22:37', '2025-10-07 18:22:37'),
(7, 'deskripsi', 'ini adalah deskripsi', '2025-10-01 23:54:01', '2025-10-03 00:38:03', NULL),
(9, 'test', 'test', '2025-10-02 21:28:42', '2025-11-12 18:44:06', NULL),
(10, 'test4', 'test4', '2025-11-12 19:58:49', '2025-11-12 19:58:49', NULL),
(11, 'test6', 'test6', '2025-11-12 19:59:15', '2025-11-12 20:01:19', NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `ruangan`
--

CREATE TABLE `ruangan` (
  `id` int(11) NOT NULL,
  `kode_ruangan` varchar(15) NOT NULL,
  `nama_ruangan` varchar(50) NOT NULL,
  `gedung` varchar(50) DEFAULT NULL,
  `lantai` tinyint(4) DEFAULT 1,
  `kapasitas` int(11) NOT NULL DEFAULT 40,
  `jenis_ruangan` enum('Kelas','Lab','Aula','Seminar') DEFAULT 'Kelas',
  `fasilitas` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `ruangan`
--

INSERT INTO `ruangan` (`id`, `kode_ruangan`, `nama_ruangan`, `gedung`, `lantai`, `kapasitas`, `jenis_ruangan`, `fasilitas`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'test edit', 'Ruang Kuliah A101', 'Gedung A', 1, 40, 'Kelas', 'Proyektor, AC, Whiteboard', '2025-09-18 01:43:59', '2025-10-12 20:53:46', NULL),
(2, 'A102', 'Ruang Kuliah A102', 'Gedung A', 1, 50, 'Kelas', 'Proyektor, AC, Whiteboard, Sound System', '2025-09-18 01:43:59', '2025-09-18 01:43:59', NULL),
(3, 'A201', 'Ruang Kuliah A201', 'Gedung A', 2, 35, 'Kelas', 'Proyektor, AC, Whiteboard', '2025-09-18 01:43:59', '2025-09-18 01:43:59', NULL),
(4, 'B101', 'Lab Komputer 1', 'Gedung B', 1, 30, 'Lab', 'PC 30 unit, AC, Proyektor', '2025-09-18 01:43:59', '2025-09-18 01:43:59', NULL),
(5, 'B102', 'Lab Komputer 2', 'Gedung B', 1, 25, 'Lab', 'PC 25 unit, AC, Proyektor', '2025-09-18 01:43:59', '2025-09-18 01:43:59', NULL),
(6, 'C101', 'Aula Utama', 'Gedung C', 1, 200, 'Aula', 'Sound System, AC, Proyektor, Panggung', '2025-09-18 01:43:59', '2025-09-18 01:43:59', NULL),
(7, 'test', 'test', 'test', 1, 1, 'Kelas', 'test', '2025-10-12 20:45:13', '2025-10-12 20:45:13', NULL),
(8, 'aw', 'aw', 'aw', 1, 1, 'Kelas', 'aw', '2025-10-12 20:49:35', '2025-10-12 20:49:35', NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `semester`
--

CREATE TABLE `semester` (
  `id` int(11) NOT NULL,
  `kode_semester` varchar(10) NOT NULL,
  `nama_semester` varchar(50) NOT NULL,
  `tahun_ajaran` varchar(9) NOT NULL,
  `periode` enum('Ganjil','Genap') NOT NULL,
  `tanggal_mulai` date NOT NULL,
  `tanggal_selesai` date NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `semester`
--

INSERT INTO `semester` (`id`, `kode_semester`, `nama_semester`, `tahun_ajaran`, `periode`, `tanggal_mulai`, `tanggal_selesai`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, '20241a', 'Semester Ganjil 2024/2025', '2024/2025', 'Genap', '2024-09-02', '2025-01-31', '2025-09-18 01:43:59', '2025-10-12 19:43:00', NULL),
(2, '20242', 'Semester Genap 2024/2025', '2024/2025', 'Genap', '2025-02-03', '2025-06-30', '2025-09-18 01:43:59', '2025-09-18 01:43:59', NULL),
(3, '20231', 'Semester Ganjil 2023/2024', '2023/2024', 'Ganjil', '2023-09-04', '2024-01-31', '2025-09-18 01:43:59', '2025-09-18 01:43:59', NULL),
(4, 'test', 'test', 'test', 'Ganjil', '2025-09-01', '2025-10-01', '2025-10-12 19:22:55', '2025-10-12 19:22:55', NULL),
(5, 'a', 'a', '2025', 'Genap', '2025-10-14', '2025-10-29', '2025-10-12 19:30:48', '2025-10-12 19:30:48', NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `sks_limits`
--

CREATE TABLE `sks_limits` (
  `id` int(11) NOT NULL,
  `min_ipk` decimal(3,2) DEFAULT NULL,
  `max_ipk` decimal(3,2) DEFAULT NULL,
  `max_sks` int(11) DEFAULT NULL,
  `keterangan` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `sks_limits`
--

INSERT INTO `sks_limits` (`id`, `min_ipk`, `max_ipk`, `max_sks`, `keterangan`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 3.00, 4.00, 24, 'IPK Sangat Baik', '2026-01-08 02:44:46', '2026-01-08 02:51:51', NULL),
(2, 2.50, 2.99, 21, 'IPK Baik', '2026-01-08 02:44:46', '2026-01-08 02:51:51', NULL),
(3, 2.00, 2.49, 18, 'IPK Cukup', '2026-01-08 02:44:46', '2026-01-08 02:51:51', NULL),
(4, 0.00, 1.99, 15, 'IPK Kurang', '2026-01-08 02:44:46', '2026-01-08 02:51:51', NULL),
(5, 1.00, 1.00, 12, 'a', '2026-01-07 21:04:47', '2026-01-07 21:04:47', NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role_id` int(11) DEFAULT 2,
  `status` enum('aktif','nonaktif') DEFAULT 'aktif',
  `last_login` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `role_id`, `status`, `last_login`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'admin', 'admin@univ.ac.id', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 1, 'aktif', NULL, '2025-09-18 01:43:59', '2025-09-17 19:55:40', '2025-09-17 19:55:40'),
(2, 'test edit dosen', 'dosen1@univ.ac.id', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 1, 'aktif', NULL, '2025-09-18 01:43:59', '2025-11-12 20:58:25', NULL),
(3, 'dosen2', 'dosen2@univ.ac.id', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 2, 'aktif', NULL, '2025-09-18 01:43:59', '2025-09-18 01:43:59', NULL),
(4, 'dosen3', 'dosen3@univ.ac.id', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 2, 'aktif', NULL, '2025-09-18 01:43:59', '2025-09-18 01:43:59', NULL),
(5, 'mahasiswa1', 'mahasiswa1@student.univ.ac.id', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 9, 'aktif', NULL, '2025-09-18 01:43:59', '2025-12-05 00:30:30', NULL),
(6, 'mahasiswa2', 'mahasiswa2@student.univ.ac.id', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 3, 'aktif', NULL, '2025-09-18 01:43:59', '2025-09-18 01:43:59', NULL),
(7, 'mahasiswa3', 'mahasiswa3@student.univ.ac.id', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 3, 'aktif', NULL, '2025-09-18 01:43:59', '2025-09-18 01:43:59', NULL),
(8, 'mahasiswa4', 'mahasiswa4@student.univ.ac.id', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 3, 'aktif', NULL, '2025-09-18 01:43:59', '2025-09-18 01:43:59', NULL),
(9, 'test', 'test@test', '$2y$12$ePz0q4Yk6QWCw3gKLSYZCOyjNjY9Z95l/ZkcWCYye3CsF3xqWXNeu', 1, 'aktif', NULL, '2025-09-30 23:37:44', '2025-10-02 02:14:17', NULL),
(10, 'shiro', 'shiro@shiro', '$2y$12$ePz0q4Yk6QWCw3gKLSYZCOyjNjY9Z95l/ZkcWCYye3CsF3xqWXNeu', 3, 'aktif', NULL, '2025-10-24 01:26:29', '2025-10-24 02:16:35', NULL),
(12, 'test2', 'neko@neko.com', '$2y$12$VL9U8K8X7B7QJDzpDNPGCeF.hXwWmDhXtHLKMML2i6aMBcF1hKiWW', 1, 'aktif', NULL, '2025-11-12 18:41:02', '2025-11-12 18:41:02', NULL),
(13, 'nekoneko', 'shironeko6351@gmail.com', '$2y$12$QfoRbm0nydeYVGmel/C4iOzxfrNGNnCEVT0Dd5FhXVhgdXLnmSh9G', 1, 'aktif', NULL, '2025-11-12 18:53:57', '2025-11-12 18:53:57', NULL),
(14, 'test3', 'test@test3.com', '$2y$12$SX.7cLBglvXYKzePuFD3su4cy2tJIcN.hDtv8dRENuc93is8arne2', 1, 'aktif', NULL, '2025-11-12 19:50:33', '2025-11-12 19:50:33', NULL),
(16, 'test4', 'test4@test', '$2y$12$7fNaY1QmFxCCOdh2F/RY..4Oh0UqBiL/MbiN7nBwXuAGLEKtvFN1a', 1, 'aktif', NULL, '2025-11-12 19:52:30', '2025-11-12 19:52:30', NULL),
(17, 'test5', 'test5@test', '$2y$12$uiokz3Cth3n0UXK.zAcvq.Cwjs7Q7pw7rHk78P6HWz7.Y45INe7fK', 2, 'aktif', NULL, '2025-11-12 19:55:14', '2025-11-12 19:55:14', NULL),
(18, 'test6', 'test6@test.com', '$2y$12$Nk3EwRMwcmx73c402bDot.4i5Lmsm.NZeZnZgilVjjalvMYAMTTSm', 1, 'aktif', NULL, '2025-11-12 20:55:48', '2025-11-12 20:55:48', NULL);

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `bobot_nilai`
--
ALTER TABLE `bobot_nilai`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_mk_semester` (`mata_kuliah_id`,`semester_id`),
  ADD KEY `idx_mata_kuliah` (`mata_kuliah_id`),
  ADD KEY `idx_semester` (`semester_id`),
  ADD KEY `idx_deleted` (`deleted_at`);

--
-- Indeks untuk tabel `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indeks untuk tabel `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indeks untuk tabel `dosen`
--
ALTER TABLE `dosen`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_nidn` (`nidn`),
  ADD UNIQUE KEY `uk_user_id` (`user_id`),
  ADD KEY `idx_jurusan` (`jurusan_id`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_dosen_status` (`status`),
  ADD KEY `idx_dosen_deleted` (`deleted_at`);

--
-- Indeks untuk tabel `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indeks untuk tabel `fakultas`
--
ALTER TABLE `fakultas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_kode_fakultas` (`kode_fakultas`),
  ADD KEY `idx_fakultas_deleted` (`deleted_at`);

--
-- Indeks untuk tabel `grade_system`
--
ALTER TABLE `grade_system`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_grade` (`grade`),
  ADD KEY `idx_score_range` (`min_score`,`max_score`),
  ADD KEY `idx_deleted` (`deleted_at`);

--
-- Indeks untuk tabel `jadwal_kuliah`
--
ALTER TABLE `jadwal_kuliah`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_kelas` (`kelas_id`),
  ADD KEY `idx_ruangan` (`ruangan_id`),
  ADD KEY `idx_jadwal_waktu` (`hari`,`jam_mulai`),
  ADD KEY `idx_ruangan_waktu` (`ruangan_id`,`hari`,`jam_mulai`,`jam_selesai`),
  ADD KEY `idx_jadwal_waktu_ruangan` (`hari`,`jam_mulai`,`jam_selesai`,`ruangan_id`),
  ADD KEY `idx_jadwal_deleted` (`deleted_at`);

--
-- Indeks untuk tabel `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indeks untuk tabel `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `jurusan`
--
ALTER TABLE `jurusan`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_kode_jurusan` (`kode_jurusan`),
  ADD KEY `idx_fakultas` (`fakultas_id`),
  ADD KEY `idx_jurusan_deleted` (`deleted_at`),
  ADD KEY `idx_nama_jurusan` (`nama_jurusan`),
  ADD KEY `idx_kode_jurusan` (`kode_jurusan`),
  ADD KEY `idx_fakultas_id` (`fakultas_id`),
  ADD KEY `idx_deleted_at` (`deleted_at`),
  ADD KEY `idx_deleted_nama` (`deleted_at`,`nama_jurusan`),
  ADD KEY `idx_deleted_kode` (`deleted_at`,`kode_jurusan`);

--
-- Indeks untuk tabel `kehadiran`
--
ALTER TABLE `kehadiran`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_pertemuan_mahasiswa` (`pertemuan_id`,`mahasiswa_id`,`deleted_at`),
  ADD KEY `idx_mahasiswa` (`mahasiswa_id`),
  ADD KEY `idx_krs_detail` (`krs_detail_id`),
  ADD KEY `idx_status` (`status_kehadiran`),
  ADD KEY `idx_input_by` (`diinput_oleh`),
  ADD KEY `idx_deleted` (`deleted_at`),
  ADD KEY `idx_mahasiswa_status` (`mahasiswa_id`,`status_kehadiran`),
  ADD KEY `idx_pertemuan_status` (`pertemuan_id`,`status_kehadiran`),
  ADD KEY `idx_waktu_input` (`waktu_input`);

--
-- Indeks untuk tabel `kelas`
--
ALTER TABLE `kelas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_kode_kelas` (`kode_kelas`),
  ADD KEY `idx_mata_kuliah` (`mata_kuliah_id`),
  ADD KEY `idx_dosen` (`dosen_id`),
  ADD KEY `idx_semester` (`semester_id`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_kelas_semester_status` (`semester_id`,`status`),
  ADD KEY `idx_kelas_deleted` (`deleted_at`);

--
-- Indeks untuk tabel `khs`
--
ALTER TABLE `khs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_mahasiswa_semester` (`mahasiswa_id`,`semester_id`),
  ADD KEY `idx_semester` (`semester_id`);

--
-- Indeks untuk tabel `krs`
--
ALTER TABLE `krs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_mahasiswa_semester` (`mahasiswa_id`,`semester_id`),
  ADD KEY `idx_semester` (`semester_id`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_dosen_pa` (`dosen_pa_id`),
  ADD KEY `idx_krs_status` (`status`),
  ADD KEY `idx_krs_deleted` (`deleted_at`),
  ADD KEY `idx_krs_mahasiswa_semester_status` (`mahasiswa_id`,`semester_id`,`status`);

--
-- Indeks untuk tabel `krs_detail`
--
ALTER TABLE `krs_detail`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_krs_kelas` (`krs_id`,`kelas_id`),
  ADD KEY `idx_kelas` (`kelas_id`),
  ADD KEY `idx_mata_kuliah` (`mata_kuliah_id`),
  ADD KEY `idx_krs_detail_deleted` (`deleted_at`),
  ADD KEY `idx_krs_mk_status` (`krs_id`,`mata_kuliah_id`);

--
-- Indeks untuk tabel `mahasiswa`
--
ALTER TABLE `mahasiswa`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_nim` (`nim`),
  ADD UNIQUE KEY `uk_user_id` (`user_id`),
  ADD KEY `idx_jurusan` (`jurusan_id`),
  ADD KEY `idx_angkatan` (`angkatan`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_mahasiswa_angkatan_status` (`angkatan`,`status`),
  ADD KEY `idx_mahasiswa_deleted` (`deleted_at`);

--
-- Indeks untuk tabel `mata_kuliah`
--
ALTER TABLE `mata_kuliah`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_kode_mk` (`kode_mk`),
  ADD KEY `idx_jurusan` (`jurusan_id`),
  ADD KEY `idx_semester_rekomendasi` (`semester_rekomendasi`),
  ADD KEY `idx_mata_kuliah_deleted` (`deleted_at`);

--
-- Indeks untuk tabel `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `nilai`
--
ALTER TABLE `nilai`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_krs_detail` (`krs_detail_id`),
  ADD KEY `idx_nilai_huruf` (`nilai_huruf`),
  ADD KEY `idx_nilai_status` (`status`),
  ADD KEY `idx_nilai_deleted` (`deleted_at`),
  ADD KEY `idx_bobot` (`bobot_nilai_id`),
  ADD KEY `idx_input_by` (`input_by`),
  ADD KEY `idx_nilai_status_deleted` (`status`,`deleted_at`),
  ADD KEY `idx_nilai_akhir` (`nilai_akhir`);

--
-- Indeks untuk tabel `pengaturan_kehadiran`
--
ALTER TABLE `pengaturan_kehadiran`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_kelas` (`kelas_id`,`deleted_at`),
  ADD KEY `idx_deleted` (`deleted_at`);

--
-- Indeks untuk tabel `pertemuan`
--
ALTER TABLE `pertemuan`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_kelas_pertemuan` (`kelas_id`,`pertemuan_ke`,`deleted_at`),
  ADD KEY `idx_kelas` (`kelas_id`),
  ADD KEY `idx_tanggal` (`tanggal`),
  ADD KEY `idx_ruangan` (`ruangan_id`),
  ADD KEY `idx_status` (`status_pertemuan`),
  ADD KEY `idx_deleted` (`deleted_at`),
  ADD KEY `fk_pertemuan_created_by` (`created_by`),
  ADD KEY `idx_kelas_tanggal` (`kelas_id`,`tanggal`),
  ADD KEY `idx_status_tanggal` (`status_pertemuan`,`tanggal`);

--
-- Indeks untuk tabel `rekap_kehadiran`
--
ALTER TABLE `rekap_kehadiran`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_mahasiswa_kelas_semester` (`mahasiswa_id`,`kelas_id`,`semester_id`,`deleted_at`),
  ADD KEY `idx_mahasiswa` (`mahasiswa_id`),
  ADD KEY `idx_kelas` (`kelas_id`),
  ADD KEY `idx_semester` (`semester_id`),
  ADD KEY `idx_persentase` (`persentase_kehadiran`),
  ADD KEY `idx_status` (`status_minimal`),
  ADD KEY `idx_deleted` (`deleted_at`);

--
-- Indeks untuk tabel `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_roles_deleted` (`deleted_at`);

--
-- Indeks untuk tabel `ruangan`
--
ALTER TABLE `ruangan`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_kode_ruang` (`kode_ruangan`),
  ADD KEY `idx_jenis_ruang` (`jenis_ruangan`);

--
-- Indeks untuk tabel `semester`
--
ALTER TABLE `semester`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_kode_semester` (`kode_semester`),
  ADD KEY `idx_tahun_ajaran` (`tahun_ajaran`);

--
-- Indeks untuk tabel `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indeks untuk tabel `sks_limits`
--
ALTER TABLE `sks_limits`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_username` (`username`),
  ADD UNIQUE KEY `uk_email` (`email`),
  ADD KEY `idx_role` (`role_id`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_users_deleted` (`deleted_at`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `bobot_nilai`
--
ALTER TABLE `bobot_nilai`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `dosen`
--
ALTER TABLE `dosen`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `fakultas`
--
ALTER TABLE `fakultas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT untuk tabel `grade_system`
--
ALTER TABLE `grade_system`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT untuk tabel `jadwal_kuliah`
--
ALTER TABLE `jadwal_kuliah`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT untuk tabel `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `jurusan`
--
ALTER TABLE `jurusan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT untuk tabel `kehadiran`
--
ALTER TABLE `kehadiran`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT untuk tabel `kelas`
--
ALTER TABLE `kelas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT untuk tabel `khs`
--
ALTER TABLE `khs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `krs`
--
ALTER TABLE `krs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT untuk tabel `krs_detail`
--
ALTER TABLE `krs_detail`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT untuk tabel `mahasiswa`
--
ALTER TABLE `mahasiswa`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `mata_kuliah`
--
ALTER TABLE `mata_kuliah`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT untuk tabel `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `nilai`
--
ALTER TABLE `nilai`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `pengaturan_kehadiran`
--
ALTER TABLE `pengaturan_kehadiran`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT untuk tabel `pertemuan`
--
ALTER TABLE `pertemuan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT untuk tabel `rekap_kehadiran`
--
ALTER TABLE `rekap_kehadiran`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT untuk tabel `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT untuk tabel `ruangan`
--
ALTER TABLE `ruangan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT untuk tabel `semester`
--
ALTER TABLE `semester`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `sks_limits`
--
ALTER TABLE `sks_limits`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `bobot_nilai`
--
ALTER TABLE `bobot_nilai`
  ADD CONSTRAINT `fk_bobot_mk` FOREIGN KEY (`mata_kuliah_id`) REFERENCES `mata_kuliah` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_bobot_semester` FOREIGN KEY (`semester_id`) REFERENCES `semester` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `dosen`
--
ALTER TABLE `dosen`
  ADD CONSTRAINT `fk_dosen_jurusan` FOREIGN KEY (`jurusan_id`) REFERENCES `jurusan` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_dosen_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `jadwal_kuliah`
--
ALTER TABLE `jadwal_kuliah`
  ADD CONSTRAINT `fk_jadwal_kelas` FOREIGN KEY (`kelas_id`) REFERENCES `kelas` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_jadwal_ruangan` FOREIGN KEY (`ruangan_id`) REFERENCES `ruangan` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `jurusan`
--
ALTER TABLE `jurusan`
  ADD CONSTRAINT `fk_jurusan_fakultas` FOREIGN KEY (`fakultas_id`) REFERENCES `fakultas` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `kehadiran`
--
ALTER TABLE `kehadiran`
  ADD CONSTRAINT `fk_kehadiran_input_by` FOREIGN KEY (`diinput_oleh`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_kehadiran_krs_detail` FOREIGN KEY (`krs_detail_id`) REFERENCES `krs_detail` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_kehadiran_mahasiswa` FOREIGN KEY (`mahasiswa_id`) REFERENCES `mahasiswa` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_kehadiran_pertemuan` FOREIGN KEY (`pertemuan_id`) REFERENCES `pertemuan` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `kelas`
--
ALTER TABLE `kelas`
  ADD CONSTRAINT `fk_kelas_dosen` FOREIGN KEY (`dosen_id`) REFERENCES `dosen` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_kelas_mk` FOREIGN KEY (`mata_kuliah_id`) REFERENCES `mata_kuliah` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_kelas_semester` FOREIGN KEY (`semester_id`) REFERENCES `semester` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `khs`
--
ALTER TABLE `khs`
  ADD CONSTRAINT `fk_khs_mahasiswa` FOREIGN KEY (`mahasiswa_id`) REFERENCES `mahasiswa` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_khs_semester` FOREIGN KEY (`semester_id`) REFERENCES `semester` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `krs`
--
ALTER TABLE `krs`
  ADD CONSTRAINT `fk_krs_dosen_pa` FOREIGN KEY (`dosen_pa_id`) REFERENCES `dosen` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_krs_mahasiswa` FOREIGN KEY (`mahasiswa_id`) REFERENCES `mahasiswa` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_krs_semester` FOREIGN KEY (`semester_id`) REFERENCES `semester` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `krs_detail`
--
ALTER TABLE `krs_detail`
  ADD CONSTRAINT `fk_krs_detail_kelas` FOREIGN KEY (`kelas_id`) REFERENCES `kelas` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_krs_detail_krs` FOREIGN KEY (`krs_id`) REFERENCES `krs` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_krs_detail_mk` FOREIGN KEY (`mata_kuliah_id`) REFERENCES `mata_kuliah` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `mahasiswa`
--
ALTER TABLE `mahasiswa`
  ADD CONSTRAINT `fk_mahasiswa_jurusan` FOREIGN KEY (`jurusan_id`) REFERENCES `jurusan` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_mahasiswa_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `mata_kuliah`
--
ALTER TABLE `mata_kuliah`
  ADD CONSTRAINT `fk_mk_jurusan` FOREIGN KEY (`jurusan_id`) REFERENCES `jurusan` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `nilai`
--
ALTER TABLE `nilai`
  ADD CONSTRAINT `fk_nilai_bobot` FOREIGN KEY (`bobot_nilai_id`) REFERENCES `bobot_nilai` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_nilai_input_by` FOREIGN KEY (`input_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_nilai_krs_detail` FOREIGN KEY (`krs_detail_id`) REFERENCES `krs_detail` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `pengaturan_kehadiran`
--
ALTER TABLE `pengaturan_kehadiran`
  ADD CONSTRAINT `fk_pengaturan_kelas` FOREIGN KEY (`kelas_id`) REFERENCES `kelas` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `pertemuan`
--
ALTER TABLE `pertemuan`
  ADD CONSTRAINT `fk_pertemuan_created_by` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_pertemuan_kelas` FOREIGN KEY (`kelas_id`) REFERENCES `kelas` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_pertemuan_ruangan` FOREIGN KEY (`ruangan_id`) REFERENCES `ruangan` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `rekap_kehadiran`
--
ALTER TABLE `rekap_kehadiran`
  ADD CONSTRAINT `fk_rekap_kelas` FOREIGN KEY (`kelas_id`) REFERENCES `kelas` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_rekap_mahasiswa` FOREIGN KEY (`mahasiswa_id`) REFERENCES `mahasiswa` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_rekap_semester` FOREIGN KEY (`semester_id`) REFERENCES `semester` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `fk_users_role` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
