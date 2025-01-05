@extends('template.app')
@section('menu', 'open active')
@section('buku', 'active')
@section('title', 'Data Buku')

@section('content')
    <!-- Modal -->
    <div class="modal fade" id="add" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">{{ config('app.name') }} || Tambah Data</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('tambah.databuku.admin') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="exampleFormControlInput1" class="form-label">Penanggung Jawab</label>
                            <input type="text" class="form-control" value="{{ Auth::user()->nama }}" required readonly>
                        </div>
                        <div class="mb-3">
                            <label for="exampleFormControlTextarea1" class="form-label">Judul Buku</label>
                            <input type="text" name="judul" class="form-control" value="{{ old('judul') }}" required
                                placeholder="Judul Buku"></input>
                        </div>
                        <div class="mb-3">
                            <label for="exampleFormControlTextarea1" class="form-label">Kategori</label>
                            <select name="kategori_id" class="form-control">
                                <option selected>none</option>
                                @php
                                    $kategori = App\Models\Kategori::all();
                                @endphp
                                @foreach ($kategori as $kategoriitem)
                                    <option value="{{ $kategoriitem->id }}">{{ $kategoriitem->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="exampleFormControlTextarea1" class="form-label">Penulis</label>
                            <input type="text" name="penulis" class="form-control" value="{{ old('penulis') }}" required
                                placeholder="penulis"></input>
                        </div>
                        <div class="mb-3">
                            <label for="exampleFormControlTextarea1" class="form-label">Penerbit</label>
                            <input type="text" name="penerbit" class="form-control" value="{{ old('penerbit') }}"
                                required placeholder="penerbit"></input>
                        </div>
                        <div class="mb-3">
                            <label for="exampleFormControlTextarea1" class="form-label">Tahun Terbit</label>
                            <input type="number" name="thn_terbit" class="form-control" value="{{ old('thn_terbit') }}"
                                required placeholder="Tahun Terbit"></input>
                        </div>
                        <div class="mb-3">
                            <label for="exampleFormControlTextarea1" class="form-label">ISBN</label>
                            <input type="text" name="isbn" class="form-control" value="{{ old('isbn') }}" required
                                placeholder="ISBN"></input>
                        </div>
                        <div class="mb-3">
                            <label for="exampleFormControlTextarea1" class="form-label">Jumlah Buku</label>
                            <input type="number" name="jumlah" class="form-control" value="{{ old('jumlah') }}" required
                                placeholder="Jumlah Buku"></input>
                        </div>
                        <div class="mb-3">
                            <label for="exampleFormControlTextarea1" class="form-label">Cover Buku</label>
                            <input type="file" name="gambar" class="form-control" value="{{ old('gambar') }}"
                                accept="image/*" required></input>
                            <p><span class="text-danger">Format : jpg, jpeg, png, max ukuran 2 MB</span></p>
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
                            <th>Judul Buku</th>
                            <th>Penulis</th>
                            <th>Penerbit</th>
                            <th>Kategori</th>
                            <th>Tahun Terbit</th>
                            <th>ISBN</th>
                            <th>Cover Buku</th>
                            <th>Jumlah Buku</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $no = 1;
                        @endphp
                        @foreach ($data as $dataitem)
                            {{-- Edit Modal --}}
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
                                        <form action="{{ route('edit.databuku.admin', ['id' => $dataitem->id]) }}"
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
                                                    <label for="exampleFormControlTextarea1" class="form-label">Judul
                                                        Buku</label>
                                                    <input type="text" name="judul" class="form-control"
                                                        value="{{ $dataitem->judul }}" required
                                                        placeholder="Judul Buku"></input>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="exampleFormControlTextarea1"
                                                        class="form-label">Kategori</label>
                                                    <select name="kategori_id" class="form-control">
                                                        @isset($dataitem->kategori_id)
                                                            <option selected value="{{ $dataitem->kategori_id }}">
                                                                {{ $dataitem->kategori->nama }}</option>
                                                        @else
                                                            <option selected>none</option>
                                                        @endisset
                                                        @php
                                                            $kategori = App\Models\Kategori::all();
                                                        @endphp
                                                        @foreach ($kategori as $kategoriitem)
                                                            <option value="{{ $kategoriitem->id }}">
                                                                {{ $kategoriitem->nama }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="exampleFormControlTextarea1"
                                                        class="form-label">Penulis</label>
                                                    <input type="text" name="penulis" class="form-control"
                                                        value="{{ $dataitem->penulis }}" required
                                                        placeholder="penulis"></input>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="exampleFormControlTextarea1"
                                                        class="form-label">Penerbit</label>
                                                    <input type="text" name="penerbit" class="form-control"
                                                        value="{{ $dataitem->penerbit }}" required
                                                        placeholder="penerbit"></input>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="exampleFormControlTextarea1" class="form-label">Tahun
                                                        Terbit</label>
                                                    <input type="number" name="thn_terbit" class="form-control"
                                                        value="{{ $dataitem->thn_terbit }}" required
                                                        placeholder="Tahun Terbit"></input>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="exampleFormControlTextarea1"
                                                        class="form-label">ISBN</label>
                                                    <input type="text" name="isbn" class="form-control"
                                                        value="{{ $dataitem->isbn }}" required
                                                        placeholder="ISBN"></input>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="exampleFormControlTextarea1" class="form-label">Jumlah
                                                        Buku</label>
                                                    <input type="number" name="jumlah" class="form-control"
                                                        value="{{ $dataitem->jumlah }}" required
                                                        placeholder="Jumlah Buku"></input>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="exampleFormControlTextarea1"
                                                        class="form-label">Gambar</label>
                                                    <br>
                                                    <img src="{{ asset('assets/gambar/buku/' . $dataitem->gambar) }}"
                                                        alt="foto-formal" class="img-fluid"
                                                        style="max-width: 20%; height: auto;">
                                                    <input type="file" class="form-control" name="gambar"
                                                        id="gambar" value="{{ old('gambar') }}" accept="image/*"
                                                        size="2048">
                                                    <p><span class="text-success">Format : jpg, jpeg, png, max ukuran 2
                                                            MB</span></p>
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
                            {{-- End modal --}}
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
                                <td>
                                    <img src="{{ asset('assets/gambar/buku/' . $dataitem->gambar) }}" class="img-fluid"
                                        width="200" alt="">
                                </td>
                                <td>{{ $dataitem->jumlah }}</td>
                                <td class="d-flex flex-column flex-sm-row">
                                    <button data-bs-toggle="modal" data-bs-target="#ModalEdit{{ $dataitem->id }}"
                                        class="btn btn-warning btn-sm mb-2 mb-sm-0 me-sm-2 bx bx-edit"></button>
                                    <a href="{{ route('hapus.databuku.admin', ['id' => $dataitem->id]) }}"
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
