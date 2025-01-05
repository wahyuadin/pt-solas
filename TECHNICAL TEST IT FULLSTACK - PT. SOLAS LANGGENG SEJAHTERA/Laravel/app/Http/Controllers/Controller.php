<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use RealRashid\SweetAlert\Facades\Alert;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function login() {
        $rememberDevice = Cookie::get('remember_device', false);
        return view('login', compact('rememberDevice'));
    }

    public function loginAuth(Request $request) {
        $this->validate($request, [
            'username'  => 'required',
            'password'  => 'required'
        ]);

        if ($request->has('remember_device')) {
            Cookie::queue(Cookie::forever('remember_device', json_encode($request->all())));
        } else {
            Cookie::queue(Cookie::forget('remember_device'));
        }

        if (Auth::attempt($request->only(['username','password']))) {
            Alert::success('Berhasil', 'Login Berhasil!');
            return redirect()->route('dashboard.'. Auth::user()->role);
        } else {
            Alert::error('Gagal', 'Username Atau Password Salah!');
            return redirect()->back();
        }
    }

    public function register() {
        return view('register');
    }

    public function registerAuth(Request $request) {
        $request->validate([
            'nama'          => 'required|min:5',
            'username'      => 'required|min:5|unique:users,username',
            'email'         => 'required|min:6|unique:users,email',
            'password'      => 'required',
            'repassword'    => 'same:password|min:6'
        ]);
        $data = $request->except('_token','repassword');
        $data['password'] = bcrypt($request->password);

        if (User::create($data)) {
            Alert::success('Berhasil', 'Data Berhasil Register');
            return redirect()->route('login');
        }
    }

    public function logout() {
        Alert::success('Success', 'logout Berhasil !');
        Auth::logout();
        return redirect(route('login'));
    }
}
