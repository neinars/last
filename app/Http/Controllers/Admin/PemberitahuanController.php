<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Berita;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PemberitahuanController extends Controller
{
    public function index()
    {
        $penerimas = User::where('role', 'user')
        ->get();
        $berita = Berita::all();
        return view('admin.berita.index', compact('penerimas','berita'));
    }

    public function sendNews(Request $request){
        $berita = Berita::create([
            'isi' => $request->isi,
            'status' => $request->status
        ]);
        $user = User::where('role', 'user')->first();
        return redirect()
            ->back()
            ->with('status', 'success')
            ->with('message', "Berhasil mengirim berita ke semua user");
    }

    public function updateBerita(Request $request, $id) {
        $berita = Berita::findOrFail($id);
        $berita->update([
            'status' => $request->status,
        ]);
        return redirect()->back();
    }

    public function delete($id){
        $berita = Berita::findOrFail($id);
        $berita->delete();

        return redirect()->back();
    }

    public function indexMasuk(Request $request)
    {
        $berita = Berita::where('status', 'aktif')->get();
        return view('user.dashboard', compact('berita'));
    }
}
