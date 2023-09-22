<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Surat extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $table = 'surat';
    protected $primaryKey = 'id';
    protected $keyType = 'int';
    protected $guarded = ['id'];

    public function jenis()
    {
        return $this->belongsTo(JenisSurat::class, 'id_jenis_surat');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
}
