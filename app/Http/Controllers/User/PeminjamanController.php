<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Buku;
use App\Models\Pemberitahuan;
use App\Models\Peminjaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PeminjamanController extends Controller
{
    public function index()
    {
        $peminjaman = Peminjaman::where('user_id', Auth::user()->id)->get();
        return view('user.peminjaman.index', compact('peminjaman'));
    }

    public function indexForm()
    {
        $buku = Buku::all();
        return view('user.peminjaman.form', compact('buku'));
    }

    public function store(Request $request)
    {
        // CEK TOTAL
        $total = Peminjaman::where('user_id', Auth::user()->id)
                        ->where('tgl_pengembalian', null)->count();
        if ($total >= 5){
            return redirect()->back()->withResponse(['status' => 'danger','message' => 'Tidak Dapat Meminjam Buku > 5 Item']);
        }

        //CEK BUKU
        $cek_buku = Peminjaman::where('buku_id', $request->buku_id)
                                ->where('user_id', Auth::user()->id)
                                ->where('tgl_pengembalian', null)
                                ->first();
        
        if ($cek_buku) {
            return redirect()->route('user.peminjaman.form')
                             ->with('status', 'danger')
                             ->with('message', 'Tidak Boleh Meminjam Buku dengan Judul Yang Sama');

        }

        //ADD PEMINJAMAN
        $peminjaman = Peminjaman::create($request->all());

        //PENGURANGAN JUMLAH BUKU
        $buku = Buku::where('id', $request->buku_id)->first();
        if  ($request->kondisi_buku_saat_dipinjam == 'baik') {
            $buku->update([
                'j_buku_baik' => $buku->j_buku_baik -1,
            ]);
        }
        if  ($request->kondisi_buku_saat_dipinjam == 'rusak') {
            $buku->update([
                'j_buku_rusak' => $buku->j_buku_rusak -1,
            ]);
        }

        //ALERT
        Pemberitahuan::create([
            "isi" => Auth::user()->fullname . " Meminjam Buku Yang Berjudul " . $buku->judul,
            "status" => "peminjaman"
        ]);

        if($peminjaman)
        {
            return redirect()->route('user.peminjaman')
                             ->with('status','success')
                             ->with('message', 'Data berhasil ditambahkan');
        }
        return redirect()->route('user.peminjaman')
                             ->with('status','danger')
                             ->with('message', 'Data gagal ditambahkan');
    }
}
