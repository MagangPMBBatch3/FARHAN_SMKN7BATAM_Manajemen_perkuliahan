<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     * File: 2024_01_01_000004_create_krs_tables.php
     */
    public function up(): void
    {
        // Tabel krs
        Schema::create('krs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mahasiswa_id')->constrained('mahasiswa')->onUpdate('cascade')->onDelete('no action');
            $table->foreignId('semester_id')->constrained('semester')->onUpdate('cascade')->onDelete('no action');
            $table->dateTime('tanggal_pengisian')->useCurrent();
            $table->dateTime('tanggal_persetujuan')->nullable();
            $table->enum('status', ['Draft', 'Diajukan', 'Disetujui', 'Ditolak'])->default('Draft');
            $table->integer('total_sks')->default(0);
            $table->text('catatan')->nullable();
            $table->foreignId('dosen_pa_id')->nullable()->constrained('dosen')->onUpdate('cascade')->onDelete('set null');
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['mahasiswa_id', 'semester_id'], 'uk_mahasiswa_semester');
            $table->index('semester_id', 'idx_semester');
            $table->index('status', 'idx_krs_status');
            $table->index('dosen_pa_id', 'idx_dosen_pa');
            $table->index('deleted_at', 'idx_krs_deleted');
            $table->index(['mahasiswa_id', 'semester_id', 'status'], 'idx_krs_mahasiswa_semester_status');
        });

        // Tabel krs_detail
        Schema::create('krs_detail', function (Blueprint $table) {
            $table->id();
            $table->foreignId('krs_id')->constrained('krs')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('kelas_id')->constrained('kelas')->onUpdate('cascade')->onDelete('no action');
            $table->foreignId('mata_kuliah_id')->constrained('mata_kuliah')->onUpdate('cascade')->onDelete('no action');
            $table->integer('sks');
            $table->enum('status_ambil', ['Baru', 'Mengulang', 'Perbaikan'])->default('Baru');
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['krs_id', 'kelas_id'], 'uk_krs_kelas');
            $table->index('kelas_id', 'idx_kelas');
            $table->index('mata_kuliah_id', 'idx_mata_kuliah');
            $table->index('deleted_at', 'idx_krs_detail_deleted');
            $table->index(['krs_id', 'mata_kuliah_id'], 'idx_krs_mk_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('krs_detail');
        Schema::dropIfExists('krs');
    }
};