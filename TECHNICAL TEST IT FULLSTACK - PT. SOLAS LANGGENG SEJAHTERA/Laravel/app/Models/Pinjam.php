<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pinjam extends Model
{
    use HasFactory, HasUuids;
    protected $guarded = [];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function buku() {
        return $this->belongsTo(Buku::class);
    }

    public static function showAll() {
        return Self::with('user','buku')->latest()->get();
    }

    public static function showByid($id) {
        return Self::with('user','buku')->where('user_id',$id)->latest()->get();
    }
}
