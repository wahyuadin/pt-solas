<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Pinjam;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

class PeminjamanController extends Controller
{
    public function peminjamanbukuAdmin() {
        confirmDelete('Hapus Data', 'Apakah Anda Yakin Menghapus Data ?');
        return view('admin.peminjaman' , ['data' => Pinjam::showAll()]);
    }

    public function tambahpeminjamanbukuAdmin(Request $request) {
        $this->validate($request, [
            'user_id'           => 'required',
            'buku_id'           => 'required|unique:pinjams,buku_id',
            'tgl_pinjam'        => 'date|required',
            'tgl_pengembalian'  => 'date|required'
        ], [
            'buku_id.unique'    => 'Buku sudah dipinjam, kembalikan terlebih dahulu',
            'buku_id.required'  => 'Buku Wajib Diisi',
            'user_id.required'  => 'User Wajib Diisi',
            'tgl_pinjam.required'   => 'Tanggal Pinjam Wajib Diisi',
            'tgl_pengembalian.required' => 'Tanggal Pengembalian Wajib Diisi'
        ]);

        if (Pinjam::create($request->except('_token'))) {
            Alert::success('Berhasil', 'Data Berhasil Ditambah!');
            return redirect()->back();
        }
    }

    public function editpeminjamanbukuAdmin(Request $request, $id) {
        $this->validate($request, [
            'user_id'           => 'required',
            'buku_id'           => 'required',
            'tgl_pinjam'        => 'date|required',
            'tgl_pengembalian'  => 'date|required',
            'status'            => 'required',
            'status_buku'       => 'nullable'
        ], [
            'buku_id.required'  => 'Buku Wajib Diisi',
            'user_id.required'  => 'User Wajib Diisi',
            'tgl_pinjam.required'   => 'Tanggal Pinjam Wajib Diisi',
            'tgl_pengembalian.required' => 'Tanggal Pengembalian Wajib Diisi',
            'status.required'   => 'Status Wajib Diisi'
        ]);
        $data = $request->except('_token');

        if ($request->input('status') == 'accept') {
            if ($request->filled('status_buku')) {
                if ($request->input('status_buku') == 'dipinjam') {
                    $data['status_buku'] = 'dipinjam';
                    $jumlah = Buku::find($request->buku_id)->value('jumlah');
                } elseif ($request->input('status_buku') == 'dikembalikan') {
                    $pengurangan = Buku::find($request->buku_id)->value('jumlah');
                    $jumlah = $pengurangan + 1;
                    $data['status_buku'] = 'dikembalikan';
                } else {
                    $data['status_buku'] = 'telat';
                    $jumlah = Buku::find($request->buku_id)->value('jumlah');
                }
            } else {
                $data['status_buku'] = 'dipinjam';
                $pengurangan = Buku::find($request->buku_id)->value('jumlah');
                $jumlah = $pengurangan - 1;
                if ($jumlah < 1) {
                    Alert::error('Gagal', 'Buku Habis Terpinjam!');
                    return redirect()->back();
                }
            }
            Buku::find($request->buku_id)->update(['jumlah' => $jumlah]);
        }

        if (Pinjam::find($id)->update($data)) {
            Alert::success('Berhasil', 'Data Berhasil Diupdate!');
            return redirect()->back();
        }
    }

    public function hapuspeminjamanbukuAdmin($id) {
        if (Pinjam::find($id)->delete()) {
            Alert::success('Berhasil', 'Data Berhasil Dihapus!');
            return redirect()->back();
        }
    }

    public function peminjamanbukuUser() {
        return view('user.peminjaman', ['data' => Pinjam::showByid(Auth::user()->id)]);
    }

    public function tambahpeminjamanbukuUser(Request $request) {
        $this->validate($request, [
            'user_id'           => 'required',
            'buku_id'           => 'required',
            'tgl_pinjam'        => 'date|required',
            'tgl_pengembalian'  => 'date|required'
        ], [
            'buku_id.unique'    => 'Buku sudah dipinjam, kembalikan terlebih dahulu'
        ]);

        if (Pinjam::create($request->except('_token'))) {
            Alert::success('Berhasil', 'Data Berhasil Ditambah!');
            return redirect()->route('peminjamanbuku.user');
        }
    }

    public function status(Request $request) {
        $this->validate($request, [
            'id_pinjam'         => 'required',
            'id_buku'           => 'required',
            'tgl_pinjam'        => 'required|date',
            'tgl_pengembalian'  => 'required|date',
        ]);
        $pinjam = Pinjam::find($request->id_pinjam);

        if (!$pinjam) {
            return redirect()->back()->withErrors(['error' => 'Data peminjaman tidak ditemukan.']);
        }
        $tgl_sekarang = new DateTime();
        $tglPengembalian = new DateTime($request->tglPengembalian);

        if ($tgl_sekarang < $tglPengembalian) {
            $pinjam->update(['status_buku' => 'dikembalikan']);
        } else {
            $pinjam->update(['status_buku' => 'telat']);
        }

        $pengurangan = Buku::find($request->id_buku)->value('jumlah');
        $jumlah = $pengurangan + 1;
        if (Buku::find($request->id_buku)->update(['jumlah' => $jumlah])) {
            Alert::success('Berhasil', 'Buku Berhasil Dikembalikan');
            return redirect()->back();
        }
    }
}
