<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Buku;
use Illuminate\Support\Facades\Validator;

class BukuController extends Controller
{

    public function __construct()
    {
        $this->middleware('jwtAuth', ['except' => ['login','register']]);
    }
    public function show() {
        return response()->json([
            'error' => false,
            'massage' => Buku::showAll(),
        ], 200);
    }

    public function showbyid($id) {
        $id = Buku::showAll($id);
        if ($id) {
            return response()->json([
                'error' => false,
                'massage' => $id,
            ], 200);
        }

        return response()->json([
            'error' => true,
            'massage' => 'Data Tidak Ada',
        ], 404);
    }

    public function post() {
        $validator = Validator::make(request()->all(), [
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

        if(!$validator->fails()) {
            $data = request()->except('_token');
            if (request()->input('kategori_id') == 'none') {
                $data['kategori_id'] = null;
            }
            if (request()->hasFile('gambar')) {
                $hasName        = request()->file('gambar')->hashName();
                request()->file('gambar')->move(public_path('assets/gambar/buku'), $hasName);
                $data['gambar']   = $hasName;
            }

            if (Buku::create($data)) {
                return response()->json([
                    'error' => false,
                    'massage' => 'Data Berhasil Ditambahkan'
                ], 200);
            }
        }

        return response()->json([
            'error' => true,
            'massage' => $validator->messages()
        ], 500);
    }

    public function put($id) {
        $validator = Validator::make(request()->all(), [
            'kategori_id'       => 'nullable',
            'judul'             => 'required|unique:bukus,judul,'.$id,
            'penulis'           => 'required|min:3',
            'penerbit'          => 'required',
            'thn_terbit'        => 'required',
            'isbn'              => 'required|unique:bukus,isbn,'.$id,
            'jumlah'            => 'required',
            'gambar'            => 'mimes:jpg,jpeg,png|max:2048'
        ], [
            'judul.unique'      => 'Judul Sudah Ada',
            'isbn.unique'       => 'ISBN Sudah Ada',
            'gambar.mimes'      => 'Format Gambar Harus jpg,jpeg,png',
            'gambar.max'        => 'Ukuran Gambar Maksimal 2MB'
        ]);

        $id = Buku::find($id);
        if ($id == null) {
            return response()->json([
                'error' => true,
                'massage' => 'ID Tidak Ditemukan'
            ], 404);
        }

        if(!$validator->fails()) {
            $data = request()->except('_token', '_method');
            if (request()->input('kategori_id') == 'none') {
                $data['kategori_id'] = null;
            }
            if (request()->hasFile('gambar')) {
                $hasName        = request()->file('gambar')->hashName();
                request()->file('gambar')->move(public_path('assets/gambar/buku'), $hasName);
                $data['gambar']   = $hasName;
            }

            if ($id->update($data)) {
                return response()->json([
                    'error' => false,
                    'massage' => 'Data Berhasil Diubah'
                ], 200);
            }
        }

        return response()->json([
            'error' => true,
            'massage' => $validator->messages() ?? 'ID Tidak Ditemukan'
        ], 500);
    }

    public function delete($id) {
        $id = Buku::find($id);
        if ($id == null) {
            return response()->json([
                'error' => true,
                'massage' => 'ID Tidak Ditemukan'
            ], 404);
        }
        if ($id->delete()) {
            return response()->json([
                'error' => false,
                'massage' => 'Data Berhasil Dihapus'
            ], 200);
        }

        return response()->json([
            'error' => true,
            'massage' => 'Data Gagal Dihapus'
        ], 500);
    }
}
