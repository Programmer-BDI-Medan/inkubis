<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InkubisProgram extends Model
{
    /** @use HasFactory<\Database\Factories\InkubisProgramFactory> */
    use HasFactory;

    // Satu program memiliki banyak tenant
    public function tenants()
    {
        return $this->hasMany(InkubisTenant::class);
    }

    // Satu program memiliki banyak dokumen
    public function documents()
    {
        return $this->hasMany(InkubisDocument::class);
    }

    // Satu program memiliki banyak form pendaftaran
    public function registrationForms()
    {
        return $this->hasMany(FormPendaftaran::class);
    }

    // program ini sedang di tahap apa
    public function stage()
    {
        return $this->belongsTo(InkubisStage::class, 'tahapan_inkubasi_id');
    }
}
