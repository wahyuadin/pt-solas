<?php

use App\Http\Controllers\BukuController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\DashhboardController;
use App\Http\Controllers\DatauserController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\PeminjamanController;
use App\Http\Middleware\SesiFalse;
use Illuminate\Support\Facades\Route;

Route::middleware([SesiFalse::class])->group(function () {
    Route::get('/', [Controller::class, 'login'])->name('login');
    Route::get('/register', [Controller::class, 'register'])->name('register');
    Route::prefix('auth')->group(function () {
        Route::post('login', [Controller::class, 'loginAuth'])->name('login_auth');
        Route::post('register', [Controller::class, 'registerAuth'])->name('register_auth');
    });
});
Route::middleware(['role:admin'])->group(function () {
    Route::get('home', [DashhboardController::class, 'dashboardAdmin'])->name('dashboard.admin');
    Route::prefix('data-buku')->group(function () {
        Route::get('/', [BukuController::class, 'databukuAdmin'])->name('databuku.admin');
        Route::post('/', [BukuController::class, 'tambahdatabukuAdmin'])->name('tambah.databuku.admin');
        Route::put('{id}', [BukuController::class, 'editdatabukuAdmin'])->name('edit.databuku.admin');
        Route::delete('{id}', [BukuController::class, 'hapusdatabukuAdmin'])->name('hapus.databuku.admin');
    });
    Route::prefix('kategori-buku')->group(function () {
        Route::get('/', [KategoriController::class, 'kategoribukuAdmin'])->name('kategoribuku.admin');
        Route::post('/', [KategoriController::class, 'tambahkategoribukuAdmin'])->name('tambah.kategoribuku.admin');
        Route::put('{id}', [KategoriController::class, 'editkategoribukuAdmin'])->name('edit.kategoribuku.admin');
        Route::delete('{id}', [KategoriController::class, 'hapuskategoribukuAdmin'])->name('hapus.kategoribuku.admin');
    });
    Route::prefix('peminjaman-buku')->group(function () {
        Route::get('/', [PeminjamanController::class, 'peminjamanbukuAdmin'])->name('peminjamanbuku.admin');
        Route::post('/', [PeminjamanController::class, 'tambahpeminjamanbukuAdmin'])->name('tambah.peminjamanbuku.admin');
        Route::put('{id}', [PeminjamanController::class, 'editpeminjamanbukuAdmin'])->name('edit.peminjamanbuku.admin');
        Route::delete('{id}', [PeminjamanController::class, 'hapuspeminjamanbukuAdmin'])->name('hapus.peminjamanbuku.admin');
    });
    Route::prefix('data-user')->group(function () {
        Route::get('/', [DatauserController::class, 'datauserAdmin'])->name('datauser.admin');
        Route::post('/', [DatauserController::class, 'tambahdatauserAdmin'])->name('tambah.datauser.admin');
        Route::put('{id}', [DatauserController::class, 'editdatauserAdmin'])->name('edit.datauser.admin');
        Route::delete('{id}', [DatauserController::class, 'hapusdatauserAdmin'])->name('hapus.datauser.admin');
    });
});
Route::middleware(['role:user'])->group(function () {
    Route::get('dashboard', [DashhboardController::class,'dashboardUser'])->name('dashboard.user');
    Route::prefix('list-buku')->group(function () {
        Route::get('/', [BukuController::class,'databukuUser'])->name('databuku.user');
        Route::post('/', [PeminjamanController::class, 'tambahpeminjamanbukuUser'])->name('tambah.peminjamanbuku.user');
    });
    Route::prefix('pinjam-buku')->group(function () {
        Route::get('/', [PeminjamanController::class, 'peminjamanbukuUser'])->name('peminjamanbuku.user');
        Route::post('/', [PeminjamanController::class, 'status'])->name('status.peminjamanbuku.user');
    });
});
Route::get('logout',[Controller::class, 'logout'])->name('logout');
