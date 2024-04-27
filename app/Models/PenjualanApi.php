<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenjualanApi extends Model
{
    use HasFactory;

    /**
     * fillable
     *
     * @var array
     */
    protected $fillable = [
        'nama_pelanggan',
        'tanggal',
        'jam',
        'bayar_tunai',
        'kembali',
        'total',
        'persen_pajak'
    ];
}
