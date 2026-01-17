<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     * File: 2024_01_01_000005_create_attendance_tables.php
     */
    public function up(): void
    {
        // Tabel pertemuan
        Schema::create('pertemuan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kelas_id')->constrained('kelas')->onUpdate('cascade')->onDelete('cascade');
            $table->tinyInteger('pertemuan_ke')->comment('Pertemuan ke-1 sampai ke-16');
            $table->date('tanggal');
            $table->time('waktu_mulai');
            $table->time('waktu_selesai');
            $table->string('materi', 255)->nullable();
            $table->enum('metode', ['Tatap Muka', 'Daring', 'Hybrid'])->default('Tatap Muka');
            $table->foreignId('ruangan_id')->nullable()->constrained('ruangan')->onUpdate('cascade')->onDelete('set null');
            $table->enum('status_pertemuan', ['Dijadwalkan', 'Berlangsung', 'Selesai', 'Dibatalkan'])->default('Dijadwalkan');
            $table->string('link_daring', 255)->nullable()->comment('Link Zoom/GMeet untuk daring');
            $table->text('catatan')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->onUpdate('cascade')->onDelete('set null')->comment('ID dosen yang membuat pertemuan');
            $table->timestamps();
            $table->softDeletes();

            $table->index('kelas_id', 'idx_kelas');
            $table->index('tanggal', 'idx_tanggal');
            $table->index('ruangan_id', 'idx_ruangan');
            $table->index('status_pertemuan', 'idx_status');
            $table->index('deleted_at', 'idx_deleted');
            $table->index(['kelas_id', 'tanggal'], 'idx_kelas_tanggal');
            $table->index(['status_pertemuan', 'tanggal'], 'idx_status_tanggal');
        });

        // Add unique constraint with deleted_at
        DB::statement('ALTER TABLE pertemuan ADD UNIQUE KEY uk_kelas_pertemuan (kelas_id, pertemuan_ke, deleted_at)');

        // Tabel pengaturan_kehadiran
        Schema::create('pengaturan_kehadiran', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kelas_id')->constrained('kelas')->onUpdate('cascade')->onDelete('cascade');
            $table->decimal('minimal_kehadiran', 5, 2)->default(75.00)->comment('Minimal kehadiran dalam persen untuk bisa ikut ujian');
            $table->boolean('auto_generate_pertemuan')->default(true)->comment('Auto generate pertemuan sesuai jadwal');
            $table->text('keterangan')->nullable();
            $table->boolean('aktif')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->index('deleted_at', 'idx_deleted');
        });

        // Add unique constraint with deleted_at
        DB::statement('ALTER TABLE pengaturan_kehadiran ADD UNIQUE KEY uk_kelas (kelas_id, deleted_at)');

        // Tabel kehadiran
        Schema::create('kehadiran', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pertemuan_id')->constrained('pertemuan')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('mahasiswa_id')->constrained('mahasiswa')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('krs_detail_id')->constrained('krs_detail')->onUpdate('cascade')->onDelete('cascade')->comment('Relasi ke KRS detail untuk validasi');
            $table->enum('status_kehadiran', ['Hadir', 'Izin', 'Sakit', 'Alpa'])->default('Alpa');
            $table->dateTime('waktu_input')->useCurrent()->comment('Waktu dosen menginput kehadiran');
            $table->text('keterangan')->nullable();
            $table->foreignId('diinput_oleh')->nullable()->constrained('users')->onUpdate('cascade')->onDelete('set null')->comment('ID dosen yang menginput');
            $table->timestamps();
            $table->softDeletes();

            $table->index('mahasiswa_id', 'idx_mahasiswa');
            $table->index('krs_detail_id', 'idx_krs_detail');
            $table->index('status_kehadiran', 'idx_status');
            $table->index('diinput_oleh', 'idx_input_by');
            $table->index('deleted_at', 'idx_deleted');
            $table->index(['mahasiswa_id', 'status_kehadiran'], 'idx_mahasiswa_status');
            $table->index(['pertemuan_id', 'status_kehadiran'], 'idx_pertemuan_status');
            $table->index('waktu_input', 'idx_waktu_input');
        });

        // Add unique constraint with deleted_at
        DB::statement('ALTER TABLE kehadiran ADD UNIQUE KEY uk_pertemuan_mahasiswa (pertemuan_id, mahasiswa_id, deleted_at)');

        // Tabel rekap_kehadiran
        Schema::create('rekap_kehadiran', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mahasiswa_id')->constrained('mahasiswa')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('kelas_id')->constrained('kelas')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('semester_id')->constrained('semester')->onUpdate('cascade')->onDelete('cascade');
            $table->integer('total_pertemuan')->default(0);
            $table->integer('total_hadir')->default(0);
            $table->integer('total_izin')->default(0);
            $table->integer('total_sakit')->default(0);
            $table->integer('total_alpa')->default(0);
            $table->decimal('nilai_kehadiran', 5, 2)->nullable()->comment('Nilai kehadiran 0-100');
            $table->enum('status_minimal', ['Memenuhi', 'Tidak Memenuhi'])->default('Memenuhi')->comment('Status memenuhi minimal kehadiran 75%');
            $table->text('keterangan')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('mahasiswa_id', 'idx_mahasiswa');
            $table->index('kelas_id', 'idx_kelas');
            $table->index('semester_id', 'idx_semester');
            $table->index('status_minimal', 'idx_status');
            $table->index('deleted_at', 'idx_deleted');
        });

        // Add computed column and unique constraint
        DB::statement('ALTER TABLE rekap_kehadiran ADD persentase_kehadiran DECIMAL(5,2) AS (CASE WHEN total_pertemuan > 0 THEN (total_hadir + total_izin + total_sakit) / total_pertemuan * 100 ELSE 0 END) STORED COMMENT "Persentase kehadiran (Hadir + Izin + Sakit)"');
        DB::statement('ALTER TABLE rekap_kehadiran ADD UNIQUE KEY uk_mahasiswa_kelas_semester (mahasiswa_id, kelas_id, semester_id, deleted_at)');
        DB::statement('ALTER TABLE rekap_kehadiran ADD INDEX idx_persentase (persentase_kehadiran)');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rekap_kehadiran');
        Schema::dropIfExists('kehadiran');
        Schema::dropIfExists('pengaturan_kehadiran');
        Schema::dropIfExists('pertemuan');
    }
};