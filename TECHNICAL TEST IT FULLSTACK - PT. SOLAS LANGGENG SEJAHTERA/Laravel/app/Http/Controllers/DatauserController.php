<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class DatauserController extends Controller
{
    public function datauserAdmin() {
        confirmDelete('Hapus Data', 'Apakah Anda Yakin Menghapus Data ?');
        return view('admin.datauser', ['data' => User::all()]);
    }

    public function tambahdatauserAdmin(Request $request) {
        $this->validate($request, [
            'nama'          => 'required|min:5',
            'username'      => 'required|min:5|unique:users,username',
            'email'         => 'required|min:6|unique:users,email',
            'password'      => 'nullable',
        ], [
            'username.unique'   => 'Username Sudah Ada',
            'email.unique'      => 'Email Sudah Ada',
            'password.required' => 'Password Wajib Diisi'
        ]);

        $data = $request->except('_token');

        if (!$request->filled('password')) {
            $data['password'] = bcrypt('user123');
        } else {
            $data['password'] = bcrypt($request->password);
        }

        if (User::create($data)) {
            Alert::success('Berhasil', 'Data Berhasil Ditambah!');
            return redirect()->back();
        }
    }

    public function editdatauserAdmin(Request $request, $id) {
        $this->validate($request, [
            'nama'          => 'required|min:5',
            'username'      => 'required|min:5',
            'email'         => 'required|min:6',
        ]);

        $data = $request->except('_token');

        if ($request->filled('password')) {
            $data['password'] = bcrypt($request->password);
        } else {
            unset($data['password']);
        }
        if (User::find($id)->update($data)) {
            Alert::success('Berhasil', 'Data Berhasil Diedit!');
            return redirect()->back();
        }
    }

    public function hapusdatauserAdmin($id) {
        if (User::find($id)->delete()) {
            Alert::success('Berhasil', 'Data Berhasil Dihapus!');
            return redirect()->back();
        }
    }
}
