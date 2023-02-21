<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Pesan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PesanController extends Controller
{
    //KIRIM PESAN
    public function indexTerkirim(){
        $pesan = Pesan::where('penerima_id', '!=', Auth::user()->id )
        ->where('pengirim_id', Auth::user()->id)
        ->get();
        $penerimas = User::where('role', 'admin')
        ->get();
        return view('user.pesan.terkirim', compact('pesan', 'penerimas'));
    }

    public function kirim(Request $request)
    {
        $pesan = Pesan::create($request->all());
        $admin = User::where('id', $request->penerima_id)->first();
        return redirect()
            ->back()
            ->with('status', 'success')
            ->with('message', "Berhasil mengirim pesan ke $admin->fullname");
    }   

    //MASUK PESAN
    public function indexMasuk(Request $request)
    {
        $masuk = Pesan::where('pengirim_id', '!=', Auth::user()->id)
        ->where('penerima_id', Auth::user()->id)
        ->get();

        $notif = Pesan::where('id', $request->status)->first();
        if ($request->status == 'terkirim') {
            $notif->update([
                'terkirim'=> $notif->terkirim + 1
            ]);
        }

        return view('user.pesan.masuk', compact('masuk'));
    }

    public function updateStatus(Request $request)
    {
        $status = Pesan::where('id', $request->id)->first();
        $status->update([
            'status' => 'dibaca'
        ]);
        return redirect()->route('user.masuk.pesan.index');
    }
}
