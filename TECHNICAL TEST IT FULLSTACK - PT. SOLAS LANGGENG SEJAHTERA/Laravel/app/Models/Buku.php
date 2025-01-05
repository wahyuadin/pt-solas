<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Buku extends Model
{
    use HasFactory, HasUuids;
    protected $guarded = [];

    public function kategori() {
        return $this->belongsTo(Kategori::class);
    }

    public function pinjam() {
        return $this->hasMany(Pinjam::class);

    }

    public static function showAll($id = null) {
        return $id ? Self::with('kategori','pinjam')->find($id) : Self::with('kategori','pinjam')->latest()->get();
    }
}
