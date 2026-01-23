<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     * File: 2026_01_16_060000_create_auto_update_mahasiswa_dosen_triggers.php
     */
    public function up(): void
    {
        // ========================================
        // TRIGGER 1: Update IPK dan Total SKS Mahasiswa setelah KHS diupdate
        // ========================================
        DB::unprepared('
            CREATE TRIGGER after_khs_insert_update_mahasiswa AFTER INSERT ON khs
            FOR EACH ROW
            BEGIN
                UPDATE mahasiswa
                SET 
                    ip_semester = NEW.ip_semester,
                    ipk = NEW.ipk,
                    total_sks = NEW.sks_kumulatif,
                    updated_at = NOW()
                WHERE id = NEW.mahasiswa_id;
            END
        ');

        DB::unprepared('
            CREATE TRIGGER after_khs_update_update_mahasiswa AFTER UPDATE ON khs
            FOR EACH ROW
            BEGIN
                UPDATE mahasiswa
                SET 
                    ip_semester = NEW.ip_semester,
                    ipk = NEW.ipk,
                    total_sks = NEW.sks_kumulatif,
                    updated_at = NOW()
                WHERE id = NEW.mahasiswa_id;
            END
        ');

        // ========================================
        // TRIGGER 2: Update semester_saat_ini mahasiswa berdasarkan KRS terakhir yang disetujui
        // ========================================
        DB::unprepared('
            CREATE TRIGGER after_krs_approved_update_semester AFTER UPDATE ON krs
            FOR EACH ROW
            BEGIN
                DECLARE v_semester_ke INT;
                
                -- Hanya jalan ketika status berubah menjadi Disetujui
                IF NEW.status = "Disetujui" AND OLD.status != "Disetujui" THEN
                    -- Hitung semester ke berapa mahasiswa ini (dari total KRS yang disetujui)
                    SELECT COUNT(DISTINCT k.semester_id) + 1
                    INTO v_semester_ke
                    FROM krs k
                    WHERE k.mahasiswa_id = NEW.mahasiswa_id
                      AND k.status = "Disetujui"
                      AND k.id < NEW.id
                      AND k.deleted_at IS NULL;
                    
                    -- Update semester_saat_ini mahasiswa
                    UPDATE mahasiswa
                    SET 
                        semester_saat_ini = v_semester_ke,
                        updated_at = NOW()
                    WHERE id = NEW.mahasiswa_id;
                END IF;
            END
        ');

        // ========================================
        // TRIGGER 3: Update total_sks di KRS saat KRS detail ditambah/diubah
        // ========================================
        DB::unprepared('
            CREATE TRIGGER after_krs_detail_insert_update_total_sks AFTER INSERT ON krs_detail
            FOR EACH ROW
            BEGIN
                DECLARE v_total_sks INT;
                
                -- Hitung total SKS dari semua mata kuliah yang diambil
                SELECT COALESCE(SUM(sks), 0)
                INTO v_total_sks
                FROM krs_detail
                WHERE krs_id = NEW.krs_id
                  AND deleted_at IS NULL;
                
                -- Update total_sks di tabel KRS
                UPDATE krs
                SET 
                    total_sks = v_total_sks,
                    updated_at = NOW()
                WHERE id = NEW.krs_id;
            END
        ');

        DB::unprepared('
            CREATE TRIGGER after_krs_detail_update_update_total_sks AFTER UPDATE ON krs_detail
            FOR EACH ROW
            BEGIN
                DECLARE v_total_sks INT;
                
                -- Hitung total SKS dari semua mata kuliah yang diambil
                SELECT COALESCE(SUM(sks), 0)
                INTO v_total_sks
                FROM krs_detail
                WHERE krs_id = NEW.krs_id
                  AND deleted_at IS NULL;
                
                -- Update total_sks di tabel KRS
                UPDATE krs
                SET 
                    total_sks = v_total_sks,
                    updated_at = NOW()
                WHERE id = NEW.krs_id;
            END
        ');

        DB::unprepared('
            CREATE TRIGGER after_krs_detail_delete_update_total_sks AFTER DELETE ON krs_detail
            FOR EACH ROW
            BEGIN
                DECLARE v_total_sks INT;
                
                -- Hitung total SKS dari semua mata kuliah yang diambil
                SELECT COALESCE(SUM(sks), 0)
                INTO v_total_sks
                FROM krs_detail
                WHERE krs_id = OLD.krs_id
                  AND deleted_at IS NULL;
                
                -- Update total_sks di tabel KRS
                UPDATE krs
                SET 
                    total_sks = v_total_sks,
                    updated_at = NOW()
                WHERE id = OLD.krs_id;
            END
        ');

        // ========================================
        // TRIGGER 4: Update kuota_terisi di kelas saat KRS detail ditambah/dihapus
        // ========================================
        DB::unprepared('
            CREATE TRIGGER after_krs_detail_insert_update_kuota AFTER INSERT ON krs_detail
            FOR EACH ROW
            BEGIN
                DECLARE v_kuota_terisi INT;
                
                -- Hitung jumlah mahasiswa yang mengambil kelas ini dari KRS yang disetujui
                SELECT COUNT(DISTINCT kd.krs_id)
                INTO v_kuota_terisi
                FROM krs_detail kd
                INNER JOIN krs k ON kd.krs_id = k.id
                WHERE kd.kelas_id = NEW.kelas_id
                  AND k.status = "Disetujui"
                  AND kd.deleted_at IS NULL
                  AND k.deleted_at IS NULL;
                
                -- Update kuota_terisi di tabel kelas
                UPDATE kelas
                SET 
                    kuota_terisi = v_kuota_terisi,
                    updated_at = NOW()
                WHERE id = NEW.kelas_id;
            END
        ');

        DB::unprepared('
            CREATE TRIGGER after_krs_detail_delete_update_kuota AFTER DELETE ON krs_detail
            FOR EACH ROW
            BEGIN
                DECLARE v_kuota_terisi INT;
                
                -- Hitung jumlah mahasiswa yang mengambil kelas ini dari KRS yang disetujui
                SELECT COUNT(DISTINCT kd.krs_id)
                INTO v_kuota_terisi
                FROM krs_detail kd
                INNER JOIN krs k ON kd.krs_id = k.id
                WHERE kd.kelas_id = OLD.kelas_id
                  AND k.status = "Disetujui"
                  AND kd.deleted_at IS NULL
                  AND k.deleted_at IS NULL;
                
                -- Update kuota_terisi di tabel kelas
                UPDATE kelas
                SET 
                    kuota_terisi = v_kuota_terisi,
                    updated_at = NOW()
                WHERE id = OLD.kelas_id;
            END
        ');

        // ========================================
        // TRIGGER 5: Update kuota saat status KRS berubah
        // ========================================
        DB::unprepared('
            CREATE TRIGGER after_krs_status_update_kuota AFTER UPDATE ON krs
            FOR EACH ROW
            BEGIN
                DECLARE v_kelas_id INT;
                DECLARE v_kuota_terisi INT;
                DECLARE done INT DEFAULT FALSE;
                
                DECLARE kelas_cursor CURSOR FOR
                    SELECT DISTINCT kelas_id
                    FROM krs_detail
                    WHERE krs_id = NEW.id
                      AND deleted_at IS NULL;
                
                DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = TRUE;
                
                -- Hanya proses jika status berubah dari/ke Disetujui
                IF (NEW.status = "Disetujui" AND OLD.status != "Disetujui") OR
                   (NEW.status != "Disetujui" AND OLD.status = "Disetujui") THEN
                    
                    OPEN kelas_cursor;
                    
                    read_loop: LOOP
                        FETCH kelas_cursor INTO v_kelas_id;
                        IF done THEN
                            LEAVE read_loop;
                        END IF;
                        
                        -- Hitung ulang kuota untuk setiap kelas
                        SELECT COUNT(DISTINCT kd.krs_id)
                        INTO v_kuota_terisi
                        FROM krs_detail kd
                        INNER JOIN krs k ON kd.krs_id = k.id
                        WHERE kd.kelas_id = v_kelas_id
                          AND k.status = "Disetujui"
                          AND kd.deleted_at IS NULL
                          AND k.deleted_at IS NULL;
                        
                        UPDATE kelas
                        SET 
                            kuota_terisi = v_kuota_terisi,
                            updated_at = NOW()
                        WHERE id = v_kelas_id;
                    END LOOP;
                    
                    CLOSE kelas_cursor;
                END IF;
            END
        ');

        // ========================================
        // TRIGGER 6: Sinkronisasi email user dengan email dosen/mahasiswa
        // ========================================
        DB::unprepared('
            CREATE TRIGGER after_user_update_sync_email_dosen AFTER UPDATE ON users
            FOR EACH ROW
            BEGIN
                -- Update email di tabel dosen jika email user berubah
                IF NEW.email != OLD.email THEN
                    UPDATE dosen
                    SET updated_at = NOW()
                    WHERE user_id = NEW.id;
                END IF;
            END
        ');

        DB::unprepared('
            CREATE TRIGGER after_user_update_sync_email_mahasiswa AFTER UPDATE ON users
            FOR EACH ROW
            BEGIN
                -- Update timestamp di tabel mahasiswa jika email user berubah
                IF NEW.email != OLD.email THEN
                    UPDATE mahasiswa
                    SET updated_at = NOW()
                    WHERE user_id = NEW.id;
                END IF;
            END
        ');

        // ========================================
        // TRIGGER 7: Auto update status mahasiswa berdasarkan kondisi tertentu
        // ========================================
        DB::unprepared('
            CREATE TRIGGER check_mahasiswa_status_on_khs_update AFTER UPDATE ON khs
            FOR EACH ROW
            BEGIN
                DECLARE v_current_status VARCHAR(50);
                DECLARE v_semester_count INT;
                
                -- Ambil status mahasiswa saat ini
                SELECT status INTO v_current_status
                FROM mahasiswa
                WHERE id = NEW.mahasiswa_id;
                
                -- Hitung berapa semester sudah dijalani
                SELECT COUNT(DISTINCT semester_id)
                INTO v_semester_count
                FROM khs
                WHERE mahasiswa_id = NEW.mahasiswa_id
                  AND deleted_at IS NULL;
                
                -- Jika mahasiswa sudah menyelesaikan >= 8 semester dan IPK >= 2.75, 
                -- bisa dipertimbangkan untuk status lulus (tapi ini harus manual approval)
                -- Trigger ini hanya memberi informasi via keterangan di KHS
                
                -- Contoh: Update keterangan otomatis di KHS
                IF v_semester_count >= 8 AND NEW.ipk >= 2.75 THEN
                    UPDATE khs
                    SET updated_at = NOW()
                    WHERE id = NEW.id;
                END IF;
            END
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared('DROP TRIGGER IF EXISTS check_mahasiswa_status_on_khs_update');
        DB::unprepared('DROP TRIGGER IF EXISTS after_user_update_sync_email_mahasiswa');
        DB::unprepared('DROP TRIGGER IF EXISTS after_user_update_sync_email_dosen');
        DB::unprepared('DROP TRIGGER IF EXISTS after_krs_status_update_kuota');
        DB::unprepared('DROP TRIGGER IF EXISTS after_krs_detail_delete_update_kuota');
        DB::unprepared('DROP TRIGGER IF EXISTS after_krs_detail_insert_update_kuota');
        DB::unprepared('DROP TRIGGER IF EXISTS after_krs_detail_delete_update_total_sks');
        DB::unprepared('DROP TRIGGER IF EXISTS after_krs_detail_update_update_total_sks');
        DB::unprepared('DROP TRIGGER IF EXISTS after_krs_detail_insert_update_total_sks');
        DB::unprepared('DROP TRIGGER IF EXISTS after_krs_approved_update_semester');
        DB::unprepared('DROP TRIGGER IF EXISTS after_khs_update_update_mahasiswa');
        DB::unprepared('DROP TRIGGER IF EXISTS after_khs_insert_update_mahasiswa');
    }
};