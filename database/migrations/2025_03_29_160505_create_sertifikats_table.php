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
        Schema::create('sertifikats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('nama');
            $table->string('lembaga_penerbit');
            $table->date('tanggal_terbit');
            $table->date('tanggal_kadaluwarsa')->nullable(); // nullable untuk sertifikat tanpa masa berlaku
            $table->string('nomor_sertifikat')->nullable(); // Nomor sertifikat
            $table->string('url_sertifikat')->nullable(); // Link sertifikat jika ada
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sertifikats');
    }
};
