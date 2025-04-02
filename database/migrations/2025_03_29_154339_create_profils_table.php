<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('profils', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('gelar_awalan');
            $table->string('nama_lengkap');
            $table->string('gelar_akhiran');
            $table->string('slug'); // slug menggunakan spatie slugs
            $table->string('nomor_ktp')->nullable(); // KTP atau identitas lainnya
            $table->text('alamat');
            $table->integer('kelurahan_id')->nullable(); // Kelurahan
            $table->integer('kecamatan_id')->nullable(); // Kecamatan
            $table->integer('kota_id')->nullable();
            $table->integer('propinsi_id')->nullable();
            $table->integer('negara_id');
            $table->date('tanggal_lahir')->nullable();
            $table->enum('jenis_kelamin', ['laki-laki', 'perempuan']);
            $table->enum('status_perkawinan', ['singel', 'menikah', 'cerai', 'janda']);
            $table->enum('pendidikan_terakhir', ['SD', 'SMP', 'SMA', 'D3', 'S1', 'S2', 'S3', 'Lainnya']); // SD, SMP, SMA, D3, S1, etc
            $table->string('pas_foto')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profils');
    }
};
