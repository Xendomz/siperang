@extends('layouts.app')

@section('title', 'Barang Keluar')

@push('style')
    <!-- CSS Libraries -->
    <link rel="stylesheet" href="{{ asset('library/datatables/media/css/jquery.dataTables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('library/datatables/media/css/jquery.dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('library/datatables/media/css/jquery.dataTables.select.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('library/select2/dist/css/select2.min.css') }}">
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Barang Keluar</h1>
            </div>

            <div class="section-body">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table-striped table w-100" id="table">
                                        <thead>
                                            <tr>
                                                <th class="text-center">
                                                    #
                                                </th>
                                                <th>Nama Barang</th>
                                                <th>Nama Pengguna</th>
                                                <th>Stok</th>
                                                <th>Keterangan</th>
                                                <th>Tanggal Input</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($barang_keluars as $barang_keluar)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $barang_keluar->barang->name }}</td>
                                                <td>{{ $barang_keluar->user->name }}</td>
                                                <td>{{ $barang_keluar->stock }}</td>
                                                <td>{{ $barang_keluar->keterangan }}</td>
                                                <td>{{ $barang_keluar->tanggal_input->toFormattedDateString() }}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>

                                        <tfoot>
                                            <tr>
                                                <th class="text-center">
                                                    #
                                                </th>
                                                <th>Nama Barang</th>
                                                <th>Nama Pengguna</th>
                                                <th>Stok</th>
                                                <th>Keterangan</th>
                                                <th>Tanggal Input</th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@push('scripts')
    <!-- JS Libraies -->
    <script src="{{ asset('library/datatables/media/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('library/datatables/media/js/jquery.dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('library/datatables/media/js/jquery.dataTables.select.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('library/jquery-ui-dist/jquery-ui.min.js') }}"></script>
    <script>
        var table = $('#table').DataTable();
    </script>
    <!-- Page Specific JS File -->
@endpush
