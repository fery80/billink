<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Paket extends Model
{
    use HasFactory;

    protected $table = 'paket';
    protected $primaryKey = 'id_paket';

    protected $fillable = [
        'nama_paket',
        'kecepatan',
        'harga',
        'deskripsi',
    ];

    public $timestamps = true;
}
