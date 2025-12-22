<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KategoriBarang extends Model
{
    protected $table = 'kategoris';
    protected $primaryKey = 'kategori_id';
    protected $fillable = [
        'nama_kategori',
        'deskripsi_kategori',
    ];
}
