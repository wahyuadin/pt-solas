@extends('template.app')
@section('peminjaman', 'active')
@section('title', 'Data Peminjam')

@section('content')

<div class="card w-100">
    <div class="card-body">
        <div id="adobe-dc-view"></div>
        <div class="row">
            <div class="col-6 col-md-8">
                <h5 class="card-title">Halaman @yield('title')</h5>
            </div>
        </div>
        @if ($errors->any())
        <div class="alert alert-danger mt-3">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
        @endif
        <div class="table-responsive mt-5">
            <table id="example" class="table table-striped table-bordered" style="width:100%">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Peminjam</th>
                        <th>Judul Buku</th>
                        <th>Penulis</th>
                        <th>Penerbit</th>
                        <th>ISBN</th>
                        <th>Jumlah Buku Tersedia</th>
                        <th>Tanggal Pinjam</th>
                        <th>Tanggal Kembali</th>
                        <th>Status Pinjam</th>
                        <th>Status Buku</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                @php
                    $no = 1;
                @endphp
                @foreach ($data as $dataitem)
                <!-- Modal -->
                <div class="modal fade" id="ModalEdit{{ $dataitem->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">{{ config('app.name') }}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <form action="{{ route('status.peminjamanbuku.user') }}" method="POST">
                                @csrf
                                <input type="text" name="id_pinjam" value="{{ $dataitem->id }}" hidden>
                                <input type="text" name="id_buku" value="{{ $dataitem->buku->id }}" hidden>
                                <input type="text" name="tgl_pinjam" value="{{ $dataitem->tgl_pinjam }}" hidden>
                                <input type="text" name="tgl_pengembalian" value="{{ $dataitem->tgl_pengembalian }}" hidden>
                                <div class="modal-body">
                                    Apakah Anda Yakin Untuk Mengembalikan buku ?
                                </div>
                                <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Kembalikan</button>
                            </form>
                        </div>
                    </div>
                </div>
                <tr>
                    <td>{{ $no++ }}</td>
                    <td>{{ $dataitem->user->nama }}</td>
                    <td>{{ $dataitem->buku->judul }}</td>
                    <td>{{ $dataitem->buku->penulis }}</td>
                    <td>{{ $dataitem->buku->penerbit }} - {{ $dataitem->buku->thn_terbit }}</td>
                    <td>{{ $dataitem->buku->isbn }}</td>
                    <td>{{ $dataitem->buku->jumlah }} Buku</td>
                    <td>{{ $dataitem->tgl_pinjam }}</td>
                    <td>{{ $dataitem->tgl_pengembalian }}</td>
                    <td>{{ $dataitem->status }}</td>
                    <td>{{ $dataitem->status == 'accept' ? $dataitem->status_buku : '-' }}</td>
                    @if ($dataitem->status == 'accept' && $dataitem->status_buku == 'dipinjam')
                    <td class="d-flex flex-column flex-sm-row">
                        <button data-bs-toggle="modal" data-bs-target="#ModalEdit{{ $dataitem->id }}" class="btn btn-primary btn-sm mb-2 mb-sm-0 me-sm-2">Kembalikan</button>
                    </td>
                    @else
                    <td>-</td>
                    @endif
                </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
  </div>
@endsection
