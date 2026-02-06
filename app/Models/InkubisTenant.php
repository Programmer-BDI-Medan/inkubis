<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InkubisTenant extends Model
{
    /** @use HasFactory<\Database\Factories\InkubisTenantFactory> */
    use HasFactory;

    // Kebalikan dari hasMany di Program
    public function inkubis()
    {
        return $this->belongsTo(InkubisProgram::class, 'inkubis_program_id');
    }

    // Relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function accessibleDocuments()
    {
        // Relasi ke Model InkubisDocument
        return $this->belongsToMany(
            InkubisDocument::class,      // Model tujuan
            'inkubis_document_accesses',   // Nama tabel pivot
            'tenant_id',                 // FK di tabel pivot yang merujuk ke Tenant
            'inkubis_document_id'        // FK di tabel pivot yang merujuk ke Dokumen
        )->withTimestamps();
    }

}
