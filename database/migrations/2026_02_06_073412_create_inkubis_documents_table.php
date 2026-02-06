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
        Schema::create('inkubis_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('inkubis_program_id')->constrained('inkubis_programs')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('tahapan_inkubasi_id')->constrained('inkubis_stages')->onDelete('cascade');
            $table->foreignId('jenis_dokumen_id')->constrained('document_types')->onDelete('cascade');
            $table->string('path_dokumen');
            $table->enum('visibility', ['internal', 'tenant_specific']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inkubis_documents');
    }
};
