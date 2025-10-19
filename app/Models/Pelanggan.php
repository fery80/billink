<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pelanggan extends Model
{
    use HasFactory;

    protected $table = 'pelanggan';
    protected $primaryKey = 'id_pelanggan';

    protected $fillable = [
        'kode_pelanggan',
        'nama_pelanggan',
        'user_pppoe',
        'foto',
        'latitude',
        'longitude',
        'alamat',
        'nomer_hp',
        'id_paket',
        'tanggal_bulan_pertama',
        'tanggal_tagihan',
        'status_pembayaran',
        'status_langganan',
        'tanggal_aktivasi',
    ];

    public $timestamps = true;

    // relasi ke tabel paket
    public function paket()
    {
        return $this->belongsTo(Paket::class, 'id_paket', 'id_paket');
    }
    public function pembayaran()
{
    return $this->hasMany(Pembayaran::class, 'id_pelanggan');
}
}
