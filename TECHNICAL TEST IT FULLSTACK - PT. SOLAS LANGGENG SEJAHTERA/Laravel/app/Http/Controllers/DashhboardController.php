<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use Illuminate\Http\Request;

class DashhboardController extends Controller
{
    public function dashboardAdmin() {
        $kategoris = Kategori::withCount('buku')->get();
        $labels = $kategoris->pluck('nama');
        $data = $kategoris->pluck('buku_count');
        return view('admin.dashboard', compact('labels','data'));
    }

    public function dashboardUser() {
        $kategoris = Kategori::withCount('buku')->get();
        $labels = $kategoris->pluck('nama');
        $data = $kategoris->pluck('buku_count');
        return view('user.dashboard',  compact('labels','data'));
    }
}
