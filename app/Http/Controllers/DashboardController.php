<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Identitas;
use App\Models\Kategori;
use App\Models\Pemberitahuan;
use App\Models\Penerbit;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function indexUs()
    {
        $identitas = Identitas::get();
        $pemberitahuans = Pemberitahuan::where('status', 'aktif')->get();
        $bukus = Buku::all();
        $date = Carbon::now()->locale('id');
        $date->settings(['formatFunction' => 'translatedFormat']);
        $b = time();
        $hour = date("G");

        return view('user.dashboard', compact('date','hour',"pemberitahuans", "bukus", "identitas"));
    }

    public function indexAd()
    {
        $buku = Buku::count();
        $kategori = Kategori::count();
        $user = User::where('role', 'user')->count();
        $penerbit = Penerbit::count();
        
        return view('admin.dashboard', compact('buku', 'kategori', 'user', 'penerbit'));
    }
}
