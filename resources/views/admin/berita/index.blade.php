@extends('layouts.master')
@section('content')
    @if (session('status'))
        <div class="alert alert-{{ session('status') }}">
            {{ session('message') }}
        </div>
    @endif
    <div class="row">
    <div class="col-9">
            <h3>Berita</h3>
            <p class="text-subtitle text-muted">Kirim Pesan Ke Anggota</p>
        </div>
    </div>
    <section class="section">
        <div class="card">
            <div class="card-body">
                <button type="button" class="btn shadow btn-primary mb-3" data-bs-toggle="modal"
                    data-bs-target="#exampleModal"><i class="bi bi-send"></i>
                    Kirim Pesan
                </button>
                <!-- Modal ADD DATA -->
                <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Tambah Berita</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <form method="POST" action={{ route('admin.kirim.berita') }} enctype="multipart/form-data">
                                @csrf
                                @method('POST')
                                <div class="modal-body">
                                    <div class="form-group mb-3">
                                        <label for="exampleFormControlTextarea1" class="form-label">Isi Berita</label>
                                        <textarea class="form-control" id="exampleFormControlTextarea1" rows="3" name="isi" required></textarea>
                                    </div>

                                    <div class="mb-3">
                                        <label for="formGroupExampleInput" class="form-label">Status</label>
                                        <select class="form-select" required name="status">
                                            <option value="" disabled selected>--Pilih Opsi--</option>
                                            <option value="aktif">Aktif</option>
                                            <option value="nonaktif">Nonaktif</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                    <button type="submit" class="btn btn-primary">Kirim Pesan</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <!-- Modal ADD DATA -->

                <table class="table table-striped" id="table1">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Isi Pesan</th>
                            <th>Status Pesan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($berita as $key => $p)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $p->isi }}</td>
                                <td>{{ $p->status }}</td>
                                <td>
                                   <button type="button" class="btn btn-success" data-bs-toggle="modal"
                                    data-bs-target="#update-berita{{ $p->id }}"><i class="bi bi-pencil-square"></i></button>
                                    <button type="button" class="btn btn-danger" data-bs-toggle="modal"
                                    data-bs-target="#hapus-berita{{ $p->id }}"><i class="bi bi-trash"></i></button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>
    </div>

    @foreach ($berita as $p)
    <div class="modal fade" id="update-berita{{ $p->id }}" tabindex="-1"
        aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <form action="{{ route('admin.update.berita', $p->id) }}" method="post">
                    @csrf
                    @method('put')
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="formGroupExampleInput" class="form-label">Status</label>
                            <select class="form-select" required name="status">
                                <option value="" disabled selected>--Pilih Opsi--</option>
                                <option value="aktif">Aktif</option>
                                <option value="nonaktif">Nonaktif</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary"
                            data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endforeach
{{-- Modal EDIT --}}


{{-- Modal DELETE --}}
@foreach ($berita as $p)
    <div class="modal fade modal-borderless" id="hapus-berita{{ $p->id }}" tabindex="-1"
        aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action={{ route('admin.delete.berita' , $p->id) }} method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    @method('DELETE')
                    <div class="modal-body">
                        <center class="mt-3">
                            <p>
                                apakah anda yakin ingin menghapus data ini?
                            </p>

                        </center>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary"
                            data-bs-dismiss="modal">Tidak</button>
                        <button type="submit" class="btn btn-danger">Hapus!</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endforeach
@endsection
