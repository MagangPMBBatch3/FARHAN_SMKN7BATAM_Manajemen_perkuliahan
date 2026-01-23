<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     * File: 2024_01_01_000007_create_database_triggers.php
     */
    public function up(): void
    {
        // Trigger: after_kehadiran_insert
        DB::unprepared('
            CREATE TRIGGER after_kehadiran_insert AFTER INSERT ON kehadiran
            FOR EACH ROW
            BEGIN
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
                    SUM(CASE WHEN status_kehadiran = "Hadir" THEN 1 ELSE 0 END) AS total_hadir,
                    SUM(CASE WHEN status_kehadiran = "Izin" THEN 1 ELSE 0 END) AS total_izin,
                    SUM(CASE WHEN status_kehadiran = "Sakit" THEN 1 ELSE 0 END) AS total_sakit,
                    SUM(CASE WHEN status_kehadiran = "Alpa" THEN 1 ELSE 0 END) AS total_alpa
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
                    SET v_status_minimal = "Memenuhi";
                ELSE
                    SET v_status_minimal = "Tidak Memenuhi";
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
                    keterangan,
                    created_at,
                    updated_at
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
                    CONCAT("Auto update - persentase: ", ROUND(v_persentase, 2), "%"),
                    NOW(),
                    NOW()
                )
                ON DUPLICATE KEY UPDATE
                    total_pertemuan = v_total_pertemuan,
                    total_hadir = v_total_hadir,
                    total_izin = v_total_izin,
                    total_sakit = v_total_sakit,
                    total_alpa = v_total_alpa,
                    nilai_kehadiran = v_nilai_kehadiran,
                    status_minimal = v_status_minimal,
                    keterangan = CONCAT("Auto update - persentase: ", ROUND(v_persentase, 2), "%"),
                    updated_at = NOW();
            END
        ');

        // Trigger: after_kehadiran_update
        DB::unprepared('
            CREATE TRIGGER after_kehadiran_update AFTER UPDATE ON kehadiran
            FOR EACH ROW
            BEGIN
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
                    SUM(CASE WHEN status_kehadiran = "Hadir" THEN 1 ELSE 0 END) AS total_hadir,
                    SUM(CASE WHEN status_kehadiran = "Izin" THEN 1 ELSE 0 END) AS total_izin,
                    SUM(CASE WHEN status_kehadiran = "Sakit" THEN 1 ELSE 0 END) AS total_sakit,
                    SUM(CASE WHEN status_kehadiran = "Alpa" THEN 1 ELSE 0 END) AS total_alpa
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
                    SET v_status_minimal = "Memenuhi";
                ELSE
                    SET v_status_minimal = "Tidak Memenuhi";
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
                    keterangan,
                    created_at,
                    updated_at
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
                    CONCAT("Auto update - persentase: ", ROUND(v_persentase, 2), "%"),
                    NOW(),
                    NOW()
                )
                ON DUPLICATE KEY UPDATE
                    total_pertemuan = v_total_pertemuan,
                    total_hadir = v_total_hadir,
                    total_izin = v_total_izin,
                    total_sakit = v_total_sakit,
                    total_alpa = v_total_alpa,
                    nilai_kehadiran = v_nilai_kehadiran,
                    status_minimal = v_status_minimal,
                    keterangan = CONCAT("Auto update - persentase: ", ROUND(v_persentase, 2), "%"),
                    updated_at = NOW();
            END
        ');

        // Trigger: after_pertemuan_insert
        DB::unprepared('
            CREATE TRIGGER after_pertemuan_insert AFTER INSERT ON pertemuan
            FOR EACH ROW
            BEGIN
                -- Insert kehadiran untuk semua mahasiswa yang terdaftar di kelas
                INSERT INTO kehadiran (
                    pertemuan_id,
                    mahasiswa_id,
                    krs_detail_id,
                    status_kehadiran,
                    keterangan,
                    diinput_oleh,
                    waktu_input,
                    created_at,
                    updated_at
                )
                SELECT 
                    NEW.id AS pertemuan_id,
                    krs.mahasiswa_id,
                    kd.id AS krs_detail_id,
                    "Alpa" AS status_kehadiran,
                    "Auto generated - belum diisi dosen" AS keterangan,
                    NEW.created_by AS diinput_oleh,
                    NOW() AS waktu_input,
                    NOW() AS created_at,
                    NOW() AS updated_at
                FROM 
                    krs_detail kd
                INNER JOIN 
                    krs ON kd.krs_id = krs.id
                WHERE 
                    kd.kelas_id = NEW.kelas_id
                    AND krs.status = "Disetujui"
                    AND kd.deleted_at IS NULL
                    AND krs.deleted_at IS NULL;
            END
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared('DROP TRIGGER IF EXISTS after_pertemuan_insert');
        DB::unprepared('DROP TRIGGER IF EXISTS after_kehadiran_update');
        DB::unprepared('DROP TRIGGER IF EXISTS after_kehadiran_insert');
    }
};