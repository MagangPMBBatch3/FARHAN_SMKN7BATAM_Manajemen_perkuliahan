<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     * File: 2024_01_01_000001_create_master_tables.php
     */
    public function up(): void
    {
        // 1. Tabel roles
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('nama_role', 50);
            $table->string('deskripsi', 255)->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('deleted_at', 'idx_roles_deleted');
        });

        // 2. Tabel users
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('username', 50)->unique('uk_username');
            $table->string('email', 100)->unique('uk_email');
            $table->string('password', 255);
            $table->foreignId('role_id')->default(2)->constrained('roles')->onUpdate('cascade')->onDelete('no action');
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
            $table->timestamp('last_login')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('role_id', 'idx_role');
            $table->index('status', 'idx_status');
            $table->index('deleted_at', 'idx_users_deleted');
        });

        // 3. Tabel fakultas
        Schema::create('fakultas', function (Blueprint $table) {
            $table->id();
            $table->string('kode_fakultas', 10)->unique('uk_kode_fakultas');
            $table->string('nama_fakultas', 100);
            $table->string('dekan', 100)->nullable();
            $table->text('alamat')->nullable();
            $table->decimal('telepon', 20, 0)->nullable();
            $table->string('email', 100)->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('deleted_at', 'idx_fakultas_deleted');
        });

        // 4. Tabel jurusan
        Schema::create('jurusan', function (Blueprint $table) {
            $table->id();
            $table->string('kode_jurusan', 10)->unique('uk_kode_jurusan');
            $table->string('nama_jurusan', 100);
            $table->foreignId('fakultas_id')->constrained('fakultas')->onUpdate('cascade')->onDelete('no action');
            $table->enum('jenjang', ['D3', 'S1', 'S2', 'S3'])->default('S1');
            $table->enum('akreditasi', ['A', 'B', 'C', 'Unggul', 'Baik Sekali', 'Baik'])->nullable();
            $table->string('kaprodi', 100)->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('fakultas_id', 'idx_fakultas');
            $table->index('deleted_at', 'idx_jurusan_deleted');
            $table->index('nama_jurusan', 'idx_nama_jurusan');
            $table->index('kode_jurusan', 'idx_kode_jurusan');
            $table->index(['deleted_at', 'nama_jurusan'], 'idx_deleted_nama');
            $table->index(['deleted_at', 'kode_jurusan'], 'idx_deleted_kode');
        });

        // 5. Tabel semester
        Schema::create('semester', function (Blueprint $table) {
            $table->id();
            $table->string('kode_semester', 10)->unique('uk_kode_semester');
            $table->string('nama_semester', 50);
            $table->string('tahun_ajaran', 9);
            $table->enum('periode', ['Ganjil', 'Genap']);
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            $table->enum('status', ['Aktif', 'Nonaktif'])->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('tahun_ajaran', 'idx_tahun_ajaran');
        });

        // 6. Tabel ruangan
        Schema::create('ruangan', function (Blueprint $table) {
            $table->id();
            $table->string('kode_ruangan', 15)->unique('uk_kode_ruang');
            $table->string('nama_ruangan', 50);
            $table->string('gedung', 50)->nullable();
            $table->tinyInteger('lantai')->default(1);
            $table->integer('kapasitas')->default(40);
            $table->enum('jenis_ruangan', ['Kelas', 'Lab', 'Aula', 'Seminar'])->default('Kelas');
            $table->text('fasilitas')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('jenis_ruangan', 'idx_jenis_ruang');
        });

        // 7. Tabel mata_kuliah
        Schema::create('mata_kuliah', function (Blueprint $table) {
            $table->id();
            $table->string('kode_mk', 15)->unique('uk_kode_mk');
            $table->string('nama_mk', 100);
            $table->foreignId('jurusan_id')->constrained('jurusan')->onUpdate('cascade')->onDelete('no action');
            $table->tinyInteger('sks')->default(3);
            $table->tinyInteger('semester_rekomendasi')->nullable();
            $table->enum('jenis', ['Wajib', 'Pilihan'])->default('Wajib');
            $table->text('deskripsi')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('jurusan_id', 'idx_jurusan');
            $table->index('semester_rekomendasi', 'idx_semester_rekomendasi');
            $table->index('deleted_at', 'idx_mata_kuliah_deleted');
        });

        // 8. Tabel sks_limits
        Schema::create('sks_limits', function (Blueprint $table) {
            $table->id();
            $table->decimal('min_ipk', 3, 2)->nullable();
            $table->decimal('max_ipk', 3, 2)->nullable();
            $table->integer('max_sks')->nullable();
            $table->string('keterangan', 100)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // 9. Tabel grade_system
        Schema::create('grade_system', function (Blueprint $table) {
            $table->id();
            $table->string('grade', 3)->unique('uk_grade')->comment('Nilai huruf: A, A-, B+, B, B-, C+, C, D, E');
            $table->decimal('min_score', 5, 2)->comment('Nilai minimum untuk grade ini');
            $table->decimal('max_score', 5, 2)->comment('Nilai maksimum untuk grade ini');
            $table->decimal('grade_point', 3, 2)->comment('Bobot nilai (0.00 - 4.00)');
            $table->enum('status_kelulusan', ['Lulus', 'Tidak Lulus'])->default('Lulus');
            $table->string('keterangan', 100)->nullable();
            $table->string('Status', 10)->default('Aktif');
            $table->timestamps();
            $table->softDeletes();

            $table->index(['min_score', 'max_score'], 'idx_score_range');
            $table->index('deleted_at', 'idx_deleted');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grade_system');
        Schema::dropIfExists('sks_limits');
        Schema::dropIfExists('mata_kuliah');
        Schema::dropIfExists('ruangan');
        Schema::dropIfExists('semester');
        Schema::dropIfExists('jurusan');
        Schema::dropIfExists('fakultas');
        Schema::dropIfExists('users');
        Schema::dropIfExists('roles');
    }
};