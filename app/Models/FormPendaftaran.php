<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FormPendaftaran extends Model
{
    /** @use HasFactory<\Database\Factories\FormPendaftaranFactory> */
    use HasFactory;

    // relasi ke user yang mengisi form pendaftaran
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // relasi ke program inkubis yang dipilih
    public function inkubis()
    {
        return $this->belongsTo(InkubisProgram::class, 'inkubis_program_id');
    }

}
