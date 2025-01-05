@extends('template.app')
@section('peminjaman', 'active')
@section('title', 'Data Peminjam')

@section('content')
    <!-- Modal -->
    <div class="modal fade" id="add" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">@yield('title') || Tambah Data</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('tambah.peminjamanbuku.admin') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="exampleFormControlInput1" class="form-label">Penanggung Jawab</label>
                            <input type="text" class="form-control" value="{{ Auth::user()->nama }}" required readonly>
                        </div>
                        <div class="mb-3">
                            <label for="exampleFormControlTextarea1" class="form-label">Peminjam</label>
                            <select name="user_id" id="select" class="form-select">
                                <option selected disabled>== Pilih Salah Satu ==</option>
                                @php
                                    $user = App\Models\User::where('role', 'user')->get();
                                @endphp
                                @foreach ($user as $useritem)
                                    <option value="{{ $useritem->id }}">Member: {{ $useritem->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="exampleFormControlTextarea1" class="form-label">Rak Buku</label>
                            <select name="buku_id" class="form-select">
                                <option selected disabled>== Pilih Salah Satu ==</option>
                                @php
                                    $buku = App\Models\Buku::showAll();
                                @endphp
                                @foreach ($buku as $bukuitem)
                                    <option value="{{ $bukuitem->id }}">
                                        Judul: {{ $bukuitem->judul }},
                                        Penulis: {{ $bukuitem->penulis }},
                                        Penerbit: {{ $bukuitem->penerbit }},
                                        ISBN: {{ $bukuitem->isbn }},
                                        Jumlah Buku: {{ $bukuitem->jumlah }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="exampleFormControlTextarea1" class="form-label">Tanggal Pinjam</label>
                            <input type="date" name="tgl_pinjam" class="form-control" value="{{ old('tgl_pinjam') }}"
                                required placeholder="Tanggal Pinjam"></input>
                        </div>
                        <div class="mb-3">
                            <label for="exampleFormControlTextarea1" class="form-label">Tanggal Pengembalian</label>
                            <input type="date" name="tgl_pengembalian" class="form-control"
                                value="{{ old('tgl_pengembalian') }}" required placeholder="Tanggal Pengembalian"></input>
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

    <div class="card w-100">
        <div class="card-body">
            <div id="adobe-dc-view"></div>
            <div class="row">
                <div class="col-6 col-md-8">
                    <h5 class="card-title">Halaman @yield('title')</h5>
                </div>
                <div class="col-6 col-md-4">
                    <div class="d-flex justify-content-end">
                        <button class="btn btn-primary" style="margin-right: 5px" data-bs-toggle="modal"
                            data-bs-target="#add"><i class='bx bx-plus'></i></button>
                    </div>
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
                            <th>Cover Buku</th>
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
                            <div class="modal fade" id="ModalEdit{{ $dataitem->id }}" tabindex="-1"
                                aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-xl">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">@yield('title') || Edit Data
                                            </h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <form action="{{ route('edit.peminjamanbuku.admin', ['id' => $dataitem->id]) }}"
                                            method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label for="exampleFormControlInput1" class="form-label">Penanggung
                                                        Jawab</label>
                                                    <input type="text" class="form-control"
                                                        value="{{ Auth::user()->nama }}" required readonly>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="exampleFormControlTextarea1"
                                                        class="form-label">Peminjam</label>
                                                    <select name="user_id" id="select" class="form-select">
                                                        <option selected value="{{ $dataitem->user_id }}">Member:
                                                            {{ $dataitem->user->nama }}</option>
                                                        @php
                                                            $user = App\Models\User::where('role', 'user')->get();
                                                        @endphp
                                                        @foreach ($user as $useritem)
                                                            <option value="{{ $useritem->id }}">Member:
                                                                {{ $useritem->nama }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="exampleFormControlTextarea1" class="form-label">Rak
                                                        Buku</label>
                                                    <select name="buku_id" class="form-select">
                                                        <option selected value="{{ $dataitem->buku_id }}">
                                                            {{ $dataitem->buku->judul }}</option>
                                                        @php
                                                            $buku = App\Models\Buku::showAll();
                                                        @endphp
                                                        @foreach ($buku as $bukuitem)
                                                            <option value="{{ $bukuitem->id }}">
                                                                Judul: {{ $bukuitem->judul }},
                                                                Penulis: {{ $bukuitem->penulis }},
                                                                Penerbit: {{ $bukuitem->penerbit }},
                                                                ISBN: {{ $bukuitem->isbn }},
                                                                Jumlah Buku: {{ $bukuitem->jumlah }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="exampleFormControlTextarea1"
                                                        class="form-label">Status</label>
                                                    <select name="status" class="form-select">
                                                        <option value="pending"
                                                            {{ $dataitem->status == 'pending' ? 'selected' : '' }}>Pending
                                                        </option>
                                                        <option value="accept"
                                                            {{ $dataitem->status == 'accept' ? 'selected' : '' }}>Accept
                                                        </option>
                                                        <option value="reject"
                                                            {{ $dataitem->status == 'reject' ? 'selected' : '' }}>Reject
                                                        </option>
                                                    </select>
                                                </div>
                                                @if ($dataitem->status == 'accept')
                                                    <div class="mb-3">
                                                        <label for="exampleFormControlTextarea1"
                                                            class="form-label">Status</label>
                                                        <select name="status_buku" class="form-select">
                                                            <option value="dikembalikan"
                                                                {{ $dataitem->status_buku == 'dikembalikan' ? 'selected' : '' }}>
                                                                Dikembalikan</option>
                                                            <option value="dipinjam"
                                                                {{ $dataitem->status_buku == 'dipinjam' ? 'selected' : '' }}>
                                                                Dipinjam</option>
                                                            <option value="telat"
                                                                {{ $dataitem->status_buku == 'telat' ? 'selected' : '' }}>
                                                                Telat Pengembalian</option>
                                                        </select>
                                                    </div>
                                                @endif
                                                <div class="mb-3">
                                                    <label for="exampleFormControlTextarea1" class="form-label">Tanggal
                                                        Pinjam</label>
                                                    <input type="date" name="tgl_pinjam" class="form-control"
                                                        value="{{ $dataitem->tgl_pinjam }}" required
                                                        placeholder="Tanggal Pinjam"></input>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="exampleFormControlTextarea1" class="form-label">Tanggal
                                                        Pengembalian</label>
                                                    <input type="date" name="tgl_pengembalian" class="form-control"
                                                        value="{{ $dataitem->tgl_pengembalian }}" required
                                                        placeholder="Tanggal Pengembalian"></input>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-bs-dismiss="modal">Close</button>
                                                <button type="submit" class="btn btn-primary">Save</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            {{-- end modal --}}
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
                                <td>
                                    <img src="{{ asset('assets/gambar/buku/' . $dataitem->buku->gambar) }}"
                                        alt="{{ $dataitem->buku->gambar }}" width="100">
                                </td>
                                <td>{{ $dataitem->status }}</td>
                                <td>{{ $dataitem->status == 'accept' ? $dataitem->status_buku : '-' }}</td>
                                <td class="d-flex flex-column flex-sm-row">
                                    <button data-bs-toggle="modal" data-bs-target="#ModalEdit{{ $dataitem->id }}"
                                        class="btn btn-warning btn-sm mb-2 mb-sm-0 me-sm-2 bx bx-edit"></button>
                                    <a href="{{ route('hapus.peminjamanbuku.admin', ['id' => $dataitem->id]) }}"
                                        class="btn btn-danger btn-sm bx bx-trash" data-confirm-delete="true"></a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
