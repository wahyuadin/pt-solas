<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class BukuController extends Controller
{
    public function databukuAdmin() {
        confirmDelete('Hapus Data', 'Apakah Anda Yakin Menghapus Data ?');
        return view('admin.databuku', ['data' => Buku::showAll()]);
    }

    public function tambahdatabukuAdmin(Request $request) {
        $this->validate($request, [
            'kategori_id'       => 'nullable',
            'judul'             => 'required|unique:bukus,judul',
            'penulis'           => 'required|min:3',
            'penerbit'          => 'required',
            'thn_terbit'        => 'required',
            'isbn'              => 'required|unique:bukus,isbn',
            'jumlah'            => 'required',
            'gambar'            => 'required|mimes:jpg,jpeg,png|max:2048'
        ], [
            'judul.unique'      => 'Judul Sudah Ada',
            'isbn.unique'       => 'ISBN Sudah Ada',
            'gambar.required'   => 'Gambar Wajib Diisi',
            'gambar.mimes'      => 'Format Gambar Harus jpg,jpeg,png',
            'gambar.max'        => 'Ukuran Gambar Maksimal 2MB'
        ]);
        $data = $request->all();
        if ($request->input('kategori_id') == 'none') {
            $data['kategori_id'] = null;
        }

        if ($request->hasFile('gambar')) {
            $hasName        = $request->file('gambar')->hashName();
            $request->file('gambar')->move(public_path('assets/gambar/buku'), $hasName);
            $data['gambar']   = $hasName;
        }

        if (Buku::create($data)) {
            Alert::success('Berhasil', 'Data Berhasil Ditambah!');
            return redirect()->back();
        }
    }

    public function editdatabukuAdmin(Request $request, $id) {
        $this->validate($request, [
            'kategori_id'       => 'nullable',
            'judul'             => 'required',
            'penulis'           => 'required|min:3',
            'penerbit'          => 'required',
            'thn_terbit'        => 'required',
            'isbn'              => 'required',
            'jumlah'            => 'required'
        ]);
        $data = $request->all();

        if ($request->input('kategori_id') == 'none') {
            $data['kategori_id'] = null;
        }

        if (Buku::find($id)->update($data)) {
            Alert::success('Berhasil', 'Data Berhasil Diedit!');
            return redirect()->back();
        }
    }

    public function hapusdatabukuAdmin($id) {
        if (Buku::find($id)->delete()) {
            Alert::success('Berhasil', 'Data Berhasil Dihapus!');
            return redirect()->back();
        }
    }

    public function databukuUser() {
        return view('user.databuku', ['data' => Buku::showAll()]);
    }
}
