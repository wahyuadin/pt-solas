@extends('template.app')
@section('buku', 'active')
@section('title', 'Data Buku')

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
                        <th>Judul Buku</th>
                        <th>Penulis</th>
                        <th>Penerbit</th>
                        <th>Kategori</th>
                        <th>Tahun Terbit</th>
                        <th>ISBN</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                @php
                    $no = 1;
                @endphp
                @foreach ($data as $dataitem)
                <!-- Modal -->
                <div class="modal fade" id="add{{ $dataitem->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-xl">
                    <div class="modal-content">
                        <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">@yield('title') || Pinjam Buku</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form action="{{ route('tambah.peminjamanbuku.user') }}" method="POST">
                        @csrf
                        <input type="text" value="{{ Auth::user()->id }}" name="user_id" hidden>
                        <input type="text" value="{{ $dataitem->id }}" name="buku_id" hidden>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="exampleFormControlTextarea1" class="form-label">Nama Peminjam</label>
                                <input type="text" class="form-control" value="{{ ucwords(Auth::user()->nama) }}" readonly>
                            </div>
                            <div class="mb-3">
                                <label for="exampleFormControlTextarea1" class="form-label">Rak Buku</label>
                                <input type="text" class="form-control" value="Judul: {{ ucwords($dataitem->judul) }}, Penulis: {{ $dataitem->penulis }}, Penerbit: {{ $dataitem->penerbit }}" readonly>
                            </div>
                            <div class="mb-3">
                                <label for="exampleFormControlTextarea1" class="form-label">Tanggal Pinjam</label>
                                <input type="date" name="tgl_pinjam" class="form-control" value="{{ old('tgl_pinjam') }}" required placeholder="Tanggal Pinjam"></input>
                            </div>
                            <div class="mb-3">
                                <label for="exampleFormControlTextarea1" class="form-label">Tanggal Pengembalian</label>
                                <input type="date" name="tgl_pengembalian" class="form-control" value="{{ old('tgl_pengembalian') }}" required placeholder="Tanggal Pengembalian"></input>
                            </div>
                        </div>
                        <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save</button>
                        </div>
                        </form>
                    </div>
                    </div>
                </div>
                {{-- end modal --}}
                <tr>
                    <td>{{ $no++ }}</td>
                    <td>{{ $dataitem->judul }}</td>
                    <td>{{ $dataitem->penulis }}</td>
                    <td>{{ $dataitem->penerbit }}</td>
                    <td>
                        @isset($dataitem->kategori)
                            {{ $dataitem->kategori->nama }}
                        @else
                        -
                        @endisset
                    </td>
                    <td>{{ $dataitem->thn_terbit }}</td>
                    <td>{{ $dataitem->isbn }}</td>
                    <td class="d-flex flex-column flex-sm-row">
                        <button data-bs-toggle="modal" data-bs-target="#add{{ $dataitem->id }}" class="btn btn-primary btn-sm mb-2 mb-sm-0 me-sm-2">Pinjam</button>
                    </td>
                </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
  </div>
@endsection
