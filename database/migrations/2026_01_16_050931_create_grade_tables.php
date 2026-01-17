<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     * File: 2024_01_01_000006_create_grade_tables.php
     */
    public function up(): void
    {
        // Tabel nilai
        Schema::create('nilai', function (Blueprint $table) {
            $table->id();
            $table->foreignId('krs_detail_id')->unique('uk_krs_detail')->constrained('krs_detail')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('bobot_nilai_id')->nullable()->constrained('bobot_nilai')->onUpdate('cascade')->onDelete('set null');
            $table->decimal('tugas', 5, 2)->nullable();
            $table->decimal('quiz', 5, 2)->nullable();
            $table->decimal('uts', 5, 2)->nullable();
            $table->decimal('uas', 5, 2)->nullable();
            $table->decimal('kehadiran', 5, 2)->nullable();
            $table->decimal('praktikum', 5, 2)->nullable();
            $table->decimal('nilai_akhir', 5, 2)->nullable();
            $table->enum('nilai_huruf', ['A', 'B', 'C', 'D', 'E'])->nullable();
            $table->decimal('nilai_mutu', 3, 2)->nullable();
            $table->enum('status', ['Draft', 'Final'])->default('Draft');
            $table->dateTime('tanggal_input')->useCurrent();
            $table->foreignId('input_by')->nullable()->constrained('users')->onUpdate('cascade')->onDelete('set null')->comment('ID dosen yang menginput nilai');
            $table->timestamps();
            $table->softDeletes();

            $table->index('bobot_nilai_id', 'idx_bobot');
            $table->index('nilai_huruf', 'idx_nilai_huruf');
            $table->index('status', 'idx_nilai_status');
            $table->index('input_by', 'idx_input_by');
            $table->index('deleted_at', 'idx_nilai_deleted');
            $table->index(['status', 'deleted_at'], 'idx_nilai_status_deleted');
            $table->index('nilai_akhir', 'idx_nilai_akhir');
        });

        // Tabel khs
        Schema::create('khs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mahasiswa_id')->constrained('mahasiswa')->onUpdate('cascade')->onDelete('no action');
            $table->foreignId('semester_id')->constrained('semester')->onUpdate('cascade')->onDelete('no action');
            $table->integer('sks_semester')->default(0);
            $table->integer('sks_kumulatif')->default(0);
            $table->decimal('ip_semester', 3, 2)->default(0.00);
            $table->decimal('ipk', 3, 2)->default(0.00);
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['mahasiswa_id', 'semester_id'], 'uk_mahasiswa_semester');
            $table->index('semester_id', 'idx_semester');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('khs');
        Schema::dropIfExists('nilai');
    }
};