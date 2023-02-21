@extends('layouts.master')
@section('content')
    <div class="container">
        {{-- <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('user.dashboard') }}">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Library</li>
            </ol>
        </nav> --}}
        <div class="row justify-content-between">
            <div class="col-6">
                <h1> Dashboard </h1>
            </div>
            <div class="col-6 justify-content-end d-flex mt-3">
                <p>{{ $date->format('l, j F Y') }}</p>
            </div>
        </div>
        <br>
        @foreach ($berita as $b)
            <div class="alert alert-info">
                <marquee behavior="" direction="">{{ $b->isi }}</marquee>
            </div>
        @endforeach
        <div class="alert alert-secondary">
            @if ($hour >= 0 && $hour <= 11)
                Selamat Pagi
            @elseif ($hour >= 12 && $hour <= 14)
                Selamat Siang
            @elseif ($hour >= 15 && $hour <= 17)
                Selamat Sore
            @elseif ($hour >= 17 && $hour <= 18)
                Selamat Petang
            @else($hour>=19 && $hour<=23) 
                Selamat Malam 
            @endif

        {{ $hour }}, Selamat Datang <b>{{ Auth::user()->fullname }}</b> di E-Perpus LSP
        </div>
    </div>

    {{-- data sekolah --}}
    <div class="row">
        <center><img src="{{ asset('/assets/images/Untitled.png') }}" alt=""
                style="width: 210px; padding-top:15px; margin-bottom: 30px">
            @foreach ($identitas as $id)
                <h1>{{ $id->nama_app }}</h1>
                <span>{{ $id->alamat_app }}</span>
            @endforeach
        </center>
    </div>
@endsection