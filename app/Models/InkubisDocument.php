<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InkubisDocument extends Model
{
    /** @use HasFactory<\Database\Factories\InkubisDocumentFactory> */
    use HasFactory;

    // Siapa yang mengunggah dokumen
    public function uploader()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Dokumen ini milik program mana
    public function program()
    {
        return $this->belongsTo(InkubisProgram::class, 'inkubis_program_id');
    }

    public function accessibleTenants()
    {
        return $this->belongsToMany(InkubisTenant::class, 'inkubis_document_accesses')
                    ->withTimestamps();
    }
}
