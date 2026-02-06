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
        Schema::create('inkubis_document_accesses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('inkubis_document_id')->constrained('inkubis_documents')->onDelete('cascade');
            $table->foreignId('tenant_id')->constrained('inkubis_tenants')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inkubis_document_accesses');
    }
};
