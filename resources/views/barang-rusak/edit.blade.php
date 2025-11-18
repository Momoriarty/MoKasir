@extends('layouts.app')

@section('content')
<div class="container mt-4">

    <div class="shadow-sm card">
        <div class="card-header">
            <h5 class="mb-0">Tambah Barang Rusak</h5>
        </div>

        <div class="card-body">

            <form action="{{ route('barang-rusak.store') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label>Barang</label>
                    <select name="id_barang" class="form-control" required>
                        <option value="">-- Pilih Barang --</option>
                        @foreach ($barangs as $b)
                        <option value="{{ $b->id_barang }}">{{ $b->nama_barang }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label>Jumlah Kardus</label>
                    <input type="number" name="jumlah_kardus" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label>Jumlah Ecer</label>
                    <input type="number" name="jumlah_ecer" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label>Keterangan</label>
                    <textarea name="keterangan" class="form-control"></textarea>
                </div>

                <div class="mb-3">
                    <label>Tanggal Rusak</label>
                    <input type="date" name="tanggal_rusak" class="form-control" required>
                </div>

                <button class="btn btn-success">Simpan</button>
                <a href="{{ route('barang-rusak.index') }}" class="btn btn-secondary">Kembali</a>

            </form>

        </div>
    </div>

</div>
@endsection
