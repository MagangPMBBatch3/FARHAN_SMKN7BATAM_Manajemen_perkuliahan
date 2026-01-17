<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     * File: 2024_01_01_000003_create_academic_tables.php
     */
    public function up(): void
    {
        // Tabel kelas
        Schema::create('kelas', function (Blueprint $table) {
            $table->id();
            $table->string('kode_kelas', 20)->unique('uk_kode_kelas');
            $table->string('nama_kelas', 50);
            $table->foreignId('mata_kuliah_id')->constrained('mata_kuliah')->onUpdate('cascade')->onDelete('no action');
            $table->foreignId('dosen_id')->constrained('dosen')->onUpdate('cascade')->onDelete('no action');
            $table->foreignId('semester_id')->constrained('semester')->onUpdate('cascade')->onDelete('no action');
            $table->integer('kapasitas')->default(40);
            $table->integer('kuota_terisi')->default(0);
            $table->enum('status', ['Aktif', 'Nonaktif', 'Selesai'])->default('Aktif');
            $table->timestamps();
            $table->softDeletes();

            $table->index('mata_kuliah_id', 'idx_mata_kuliah');
            $table->index('dosen_id', 'idx_dosen');
            $table->index('semester_id', 'idx_semester');
            $table->index('status', 'idx_status');
            $table->index(['semester_id', 'status'], 'idx_kelas_semester_status');
            $table->index('deleted_at', 'idx_kelas_deleted');
        });

        // Tabel jadwal_kuliah
        Schema::create('jadwal_kuliah', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kelas_id')->constrained('kelas')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('ruangan_id')->constrained('ruangan')->onUpdate('cascade')->onDelete('no action');
            $table->enum('hari', ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu']);
            $table->time('jam_mulai');
            $table->time('jam_selesai');
            $table->string('keterangan', 255)->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('kelas_id', 'idx_kelas');
            $table->index('ruangan_id', 'idx_ruangan');
            $table->index(['hari', 'jam_mulai'], 'idx_jadwal_waktu');
            $table->index(['ruangan_id', 'hari', 'jam_mulai', 'jam_selesai'], 'idx_ruangan_waktu');
            $table->index(['hari', 'jam_mulai', 'jam_selesai', 'ruangan_id'], 'idx_jadwal_waktu_ruangan');
            $table->index('deleted_at', 'idx_jadwal_deleted');
        });

        // Tabel bobot_nilai
        Schema::create('bobot_nilai', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mata_kuliah_id')->constrained('mata_kuliah')->onUpdate('cascade')->onDelete('cascade');
            $table->decimal('tugas', 5, 2)->default(20.00)->comment('Bobot tugas dalam persen');
            $table->decimal('quiz', 5, 2)->default(20.00)->comment('Bobot quiz dalam persen');
            $table->decimal('uts', 5, 2)->default(30.00)->comment('Bobot UTS dalam persen');
            $table->decimal('uas', 5, 2)->default(30.00)->comment('Bobot UAS dalam persen');
            $table->decimal('kehadiran', 5, 2)->default(0.00)->comment('Bobot kehadiran dalam persen');
            $table->decimal('praktikum', 5, 2)->default(0.00)->comment('Bobot praktikum dalam persen');
            $table->foreignId('semester_id')->constrained('semester')->onUpdate('cascade')->onDelete('cascade');
            $table->text('keterangan')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['mata_kuliah_id', 'semester_id'], 'uk_mk_semester');
            $table->index('mata_kuliah_id', 'idx_mata_kuliah');
            $table->index('semester_id', 'idx_semester');
            $table->index('deleted_at', 'idx_deleted');
        });

        // Add computed column for total_bobot
        DB::statement('ALTER TABLE bobot_nilai ADD total_bobot DECIMAL(5,2) AS (tugas + quiz + uts + uas + kehadiran + praktikum) STORED');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bobot_nilai');
        Schema::dropIfExists('jadwal_kuliah');
        Schema::dropIfExists('kelas');
    }
};