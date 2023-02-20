<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect(route('login'));
});

Auth::routes();

Route::get('/home', function () {
    if (Auth::user()->role == 'admin') {
        return redirect()->route('admin.dashboard');
    }
    if (Auth::user()->role == 'user') {
        return redirect()->route('user.dashboard');
    }})->middleware('auth');

Route::prefix('/user')->middleware('auth', 'role:user')->group(function(){
    Route::get('/dashboard', [\App\Http\Controllers\DashboardController::class, 'indexUs'])->name('user.dashboard');
        Route::controller(\App\Http\Controllers\User\PeminjamanController::class)->prefix('/peminjaman')->group(function(){
            Route::get('/', 'index')->name('user.peminjaman');
            Route::get('/form', 'indexForm')->name('user.peminjaman.form');
            Route::post('/form', 'store')->name('user.peminjaman.store');
        });
        Route::controller(\App\Http\Controllers\User\PengembalianController::class)->prefix('/pengembalian')->group(function(){
            Route::get('/', 'index')->name('user.pengembalian');
            Route::get('/form', 'indexForm')->name('user.pengembalian.form');
            Route::post('/form', 'store')->name('user.pengembalian.store');
        });
        Route::controller(\App\Http\Controllers\User\PesanController::class)->prefix('/pesan')->group(function(){
            Route::get('/', 'indexTerkirim')->name('user.kirim.pesan.index');
            Route::post('/form', 'kirim')->name('user.kirim.pesan');
            Route::get('/masuk', 'indexMasuk')->name('user.masuk.pesan.index');
            Route::put('/update', 'updateStatus')->name('user.update.pesan');
        });
            Route::controller(\App\Http\Controllers\User\ProfileController::class)->prefix('/profile')->group(function(){
            Route::get('/', 'index')->name('user.profile');
            Route::put('/photo', 'photo')->name('user.photo');
        });
});

Route::prefix('/admin')->middleware('auth', 'role:admin')->group(function(){
    Route::get('/dashboard', [\App\Http\Controllers\DashboardController::class, 'indexAd'])->name('admin.dashboard');
        Route::controller(\App\Http\Controllers\Admin\AnggotaController::class)->prefix('/anggota')->group(function(){
            Route::get('/','indexAnggota')->name('admin.anggota');
            Route::post('/tambah','storeAnggota')->name('admin.tambah.anggota');
            Route::put('/edit/anggota/{id}','updateAnggota')->name('admin.update.anggota');
            Route::delete('/hapus/anggota/{id}','deleteAnggota')->name('admin.delete.anggota');
            Route::put('/update/{id}','updateStatus')->name('admin.update.status');
        });

        Route::controller(\App\Http\Controllers\Admin\PenerbitController::class)->prefix('/penerbit')->group(function(){
            Route::get('/','indexPenerbit')->name('admin.penerbit');
            Route::post('/tambah','storePenerbit')->name('admin.tambah.penerbit');
            Route::put('/edit/penerbit/{id}','updatePenerbit')->name('admin.update.penerbit');
            Route::post('/update_status/{id}','updateStatus')->name('admin.update.status.penerbit');
            Route::delete('/hapus/penerbit/{id}','deletePenerbit')->name('admin.delete.penerbit');
        });
 
        Route::controller(\App\Http\Controllers\Admin\AdministratorController::class)->prefix('/administrator')->group(function(){
            Route::get('/','indexAdministrator')->name('admin.administrator');
            Route::post('/tambah','storeAdministrator')->name('admin.tambah.admin');
            Route::put('/edit/admin/{id}','updateAdmin')->name('admin.update.admin');
            Route::delete('/hapus/admin/{id}','deleteAdmin')->name('admin.delete.admin');
        });

    Route::get('/data-peminjaman', [\App\Http\Controllers\Admin\PeminjamanController::class, 'indexPeminjaman'])->name('admin.peminjaman');

        Route::controller(\App\Http\Controllers\Admin\DatabukuController::class)->prefix('/buku')->group(function(){
            Route::get('/buku', 'indexBuku')->name('admin.buku');
            Route::post('/tambah-buku', 'storeBuku')->name('admin.tambah.buku');
            Route::put('/edit/buku/{id}', 'updateBuku')->name('admin.update.buku');
            Route::delete('/hapus/buku/{id}', 'deleteBuku')->name('admin.delete.buku');
        });

        Route::controller(\App\Http\Controllers\Admin\KategoriController::class)->prefix('/kategori')->group(function(){
            Route::get('/kategori', 'indexKategori')->name('admin.kategori');
            Route::post('/tambah-kategori', 'storeKategori')->name('admin.tambah.kategori');
            Route::put('/edit/kategori/{id}', 'updateKategori')->name('admin.update.kategori');
            Route::delete('/hapus/kategori/{id}', 'deleteKategori')->name('admin.delete.kategori');
        });


    //- - - - - - - - - Cetak Laporan- - - - - - - - - -
    //                [ Laporan PDF]
    Route::get('/index', [LaporanController::class, 'index'])->name('admin.index');
    Route::post('/laporan-pdf', [LaporanController::class, 'laporan_pdf'])->name('admin.lap_pdf');

    Route::post('/peminjaman', [LaporanController::class, 'laporan_pdf'])->name('admin.laporan_peminjaman');
    Route::post('/pengembalian', [LaporanController::class, 'laporan_pdf'])->name('admin.laporan_pengembalian');
    Route::post('/laporan_user', [LaporanController::class, 'laporan_pdf'])->name('admin.laporan_user');
    //                [ Laporan Excel]
    Route::post('laporan-excel', [LaporanController::class, 'laporan_excel'])->name('admin.laporan_excel');
    Route::post('/excel-pengembalian', [LaporanController::class, 'excelPengembalian'])->name('admin.excel_pengembalian');
    Route::post('/excel-user', [LaporanController::class, 'excelUser'])->name('admin.excel_user');

    //                [ Identitas Applikasi]
    Route::get('/indexIdentitas', [IdentitasController::class, 'indexIdentitas'])->name('admin.identitas');
    Route::put('/edit/identitas', [IdentitasController::class, 'updateIdentitas'])->name('admin.update_identitas');

    //- - - - - - - - - Pesan - - - - - - - - - -
    Route::get('/pesan-masuk', [AdminPesanController::class, 'pesanMasuk'])->name('admin.pesan_masuk');
    Route::post('/admin-status', [AdminPesanController::class, 'admin_status'])->name('admin.ubah_status');

    Route::get('/pesan-terkirim', [AdminPesanController::class, 'pesanTerkirim'])->name('admin.pesan_terkirim');
    Route::post('/kirim-pesan', [AdminPesanController::class, 'kirimPesan'])->name('admin.kirim_pesan');
});