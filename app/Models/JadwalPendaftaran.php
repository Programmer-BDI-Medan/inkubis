<?php

namespace App\Models;

use App\Models\InkubisProgram;
use Illuminate\Database\Eloquent\Model;

class JadwalPendaftaran extends Model
{
    protected $table = 'jadwal_pendaftaran';
    public function inkubis()
    {
        return $this->belongsTo(InkubisProgram::class, 'inkubis_program_id');
    }
}
