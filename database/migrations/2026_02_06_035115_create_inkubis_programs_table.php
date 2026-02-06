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
        Schema::create('inkubis_programs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tahapan_inkubasi_id')->constrained('inkubis_stages')->onDelete('cascade');
            $table->dateTime('tanggal_penyelenggaraan');
            $table->string('nama_program');
            $table->text('deskripsi');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inkubis_programs');
    }
};
