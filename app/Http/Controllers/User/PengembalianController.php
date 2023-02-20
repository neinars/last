<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Buku;
use App\Models\Pemberitahuan;
use App\Models\Peminjaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PengembalianController extends Controller
{
    public function index()
    {
        $judul = Peminjaman::where('user_id', Auth::user()->id)
                                    ->where('tgl_pengembalian', '!=', null)
                                    ->get();
        return view('user.pengembalian.index', compact('judul'));
    }

    public function indexForm()
    {
        $judul = Peminjaman::where('user_id', Auth::user()->id)
                                    ->where('tgl_pengembalian', null)
                                    ->get();
        return view('user.pengembalian.form', compact('judul'));
    }

    public function store(Request $request)
    {
        $pengembalian = Peminjaman::where('user_id', Auth::user()->id)
                                    ->where('tgl_pengembalian', null)
                                    ->where('buku_id', $request->buku_id)
                                    ->first();
        
        $pengembalian->update([
            'tgl_pengembalian' => $request->tgl_pengembalian,
            'kondisi_buku_saat_dikembalikan' => $request->kondisi_buku_saat_dikembalikan,
        ]);

        $buku = Buku::where('id', $request->buku_id )->first();

        if ($request->kondisi_buku_saat_dikembalikan == 'baik')
        {
            $buku->update([
                'j_buku_baik' => $request->j_buku_baik  + 1,
            ]);
        }

        if ($pengembalian->kondisi_buku_saat_dipinjam == 'rusak' && $request->kondisi_buku_saat_dikembalikan == 'rusak')
        {
            $buku->update([
                'j_buku_rusak' => $request->j_buku_rusak + 1,
            ]);

            $pengembalian->update([
                'denda' => 0
            ]);
        }

        if ($pengembalian->kondisi_buku_saat_dipinjam != 'rusak' && $request->kondisi_buku_saat_dikembalikan == 'rusak')
        {
            $buku->update([
                'j_buku_rusak' => $request->j_buku_rusak + 1,
            ]);

            $pengembalian->update([
                'denda' => 20000
            ]);
        }

        if ($request->kondisi_buku_saat_dikembalikan == 'hilang')
        {
            $pengembalian->update([
                'denda' => 50000
            ]);
        }
           // Update Pemberitahuan
           Pemberitahuan::create([
            "isi" => Auth::user()->username . " Berhasil Mengembalikan Buku " . $buku->judul,
            "status" => "pengembalian"
        ]);

        return redirect()->route('user.pengembalian');
    }
}
