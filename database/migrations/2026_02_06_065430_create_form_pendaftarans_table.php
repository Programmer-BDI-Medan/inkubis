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
        Schema::create('form_pendaftarans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('inkubis_program_id')->constrained('inkubis_programs')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('nama_tenant');
            $table->string('jenis_produk');
            $table->text('deskripsi');
            $table->string('dokumen_pendukungan');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('form_pendaftarans');
    }
};
