<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     * File: 2024_01_01_000002_create_user_related_tables.php
     */
    public function up(): void
    {
        // Tabel dosen
        Schema::create('dosen', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique('uk_user_id')->constrained('users')->onUpdate('cascade')->onDelete('no action');
            $table->string('nidn', 20)->unique('uk_nidn');
            $table->string('nip', 30)->nullable();
            $table->string('nama_lengkap', 100);
            $table->string('gelar_depan', 20)->nullable();
            $table->string('gelar_belakang', 50)->nullable();
            $table->foreignId('jurusan_id')->constrained('jurusan')->onUpdate('cascade')->onDelete('no action');
            $table->enum('jenis_kelamin', ['L', 'P']);
            $table->string('tempat_lahir', 50)->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->text('alamat')->nullable();
            $table->string('no_hp', 20)->nullable();
            $table->string('email_pribadi', 100)->nullable();
            $table->enum('status_kepegawaian', ['Tetap', 'Kontrak', 'Honorer'])->default('Tetap');
            $table->enum('jabatan', ['Asisten Ahli', 'Lektor', 'Lektor Kepala', 'Profesor'])->nullable();
            $table->enum('status', ['Aktif', 'Nonaktif', 'Pensiun', 'Cuti'])->default('Aktif');
            $table->timestamps();
            $table->softDeletes();

            $table->index('jurusan_id', 'idx_jurusan');
            $table->index('status', 'idx_dosen_status');
            $table->index('deleted_at', 'idx_dosen_deleted');
        });

        // Tabel mahasiswa
        Schema::create('mahasiswa', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique('uk_user_id')->constrained('users')->onUpdate('cascade')->onDelete('no action');
            $table->string('nim', 20)->unique('uk_nim');
            $table->string('nama_lengkap', 100);
            $table->foreignId('jurusan_id')->constrained('jurusan')->onUpdate('cascade')->onDelete('no action');
            $table->year('angkatan');
            $table->enum('jenis_kelamin', ['L', 'P']);
            $table->string('tempat_lahir', 50)->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->text('alamat')->nullable();
            $table->string('no_hp', 20)->nullable();
            $table->string('email_pribadi', 100)->nullable();
            $table->string('nama_ayah', 100)->nullable();
            $table->string('nama_ibu', 100)->nullable();
            $table->string('no_hp_ortu', 20)->nullable();
            $table->enum('status', ['Aktif', 'Cuti', 'Lulus', 'DO', 'Mengundurkan Diri'])->default('Aktif');
            $table->tinyInteger('semester_saat_ini')->default(1);
            $table->decimal('ip_semester', 3, 2)->default(0.00);
            $table->decimal('ipk', 3, 2)->default(0.00);
            $table->integer('total_sks')->default(0);
            $table->timestamps();
            $table->softDeletes();

            $table->index('jurusan_id', 'idx_jurusan');
            $table->index('angkatan', 'idx_angkatan');
            $table->index('status', 'idx_status');
            $table->index(['angkatan', 'status'], 'idx_mahasiswa_angkatan_status');
            $table->index('deleted_at', 'idx_mahasiswa_deleted');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mahasiswa');
        Schema::dropIfExists('dosen');
    }
};