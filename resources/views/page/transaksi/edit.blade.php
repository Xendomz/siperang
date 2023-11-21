@extends('layouts.app')

@section('title', 'Transaksi')

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
                <h1>Transaksi</h1>
            </div>

            <div class="section-body">
                @if (Session::get('error'))
                    <div class="alert alert-danger alert-dismissible show fade">
                        <div class="alert-body">
                            <button class="close" data-dismiss="alert">
                                <span>×</span>
                            </button>
                            {{ Session::get('error') }}
                        </div>
                    </div>
                @endif
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <form action="{{ route('transaksi.update', $transaksi->kode_transaksi) }}"
                                class="needs-validation" novalidate="" id="form" method="POST">
                                @csrf
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label>Kode Transaksi</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control" id="kode_transaksi"
                                                placeholder="Kode Transaksi" name="kode_transaksi"
                                                value="{{ $transaksi->kode_transaksi }}" readonly>
                                        </div>
                                    </div>
                                    <div class="def-barang">
                                        <div class="row mb-2">
                                            <div class="col-6">
                                            </div>
                                            <div class="col-6">
                                                <button class="btn btn-outline-primary btn-sm float-right"
                                                    id="btn-add-barang" type="button" data-repeater-create><i
                                                        class="fas fa-plus mr-2"></i> Tambah barang</button>
                                            </div>
                                        </div>

                                        <div data-repeater-list="barangs">
                                            @if (old('barangs'))
                                                @foreach (old('barangs') as $old_barang)
                                                    <div data-repeater-item>
                                                        <div class="row mb-4">
                                                            <div class="col-md-8 my-2">
                                                                <label>Nama Barang | Stok</label>
                                                                <div class="input-group">
                                                                    <select name="barang" class="form-control select2"
                                                                        required>
                                                                        @foreach ($barangs as $barang)
                                                                            <option value="{{ $barang->id }}"
                                                                                {{ $old_barang['barang'] == $barang->id ? 'selected' : '' }}>
                                                                                {{ $barang->name }} | {{ $barang->stok }}
                                                                            </option>
                                                                        @endforeach
                                                                    </select>
                                                                    <div class="invalid-feedback">
                                                                        Please fill Barang
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-3 my-2">
                                                                <label>Stok Barang</label>
                                                                <div class="input-group">
                                                                    <input type="number"
                                                                        value="{{ intval($old_barang['qty']) }}"
                                                                        class="form-control" min="0" id="qty"
                                                                        placeholder="0" name="qty" required>
                                                                    <div class="invalid-feedback">
                                                                        Please fill Quantity
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-1 my-2 d-flex align-items-center">
                                                                <button class="btn btn-danger btn-block"
                                                                    data-repeater-delete type="button"><i
                                                                        class="fas fa-trash"></i></button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            @else
                                                @foreach ($transaksi->transaksiBarangs as $transaksi_barang)
                                                    <div data-repeater-item>
                                                        <div class="row mb-4">
                                                            <div class="col-md-8 my-2">
                                                                <label>Nama Barang | Stok</label>
                                                                <div class="input-group">
                                                                    <select name="barang" class="form-control select2"
                                                                        required>
                                                                        @foreach ($barangs as $barang)
                                                                            <option value="{{ $barang->id }}"
                                                                                {{ $transaksi_barang->barang_id == $barang->id ? 'selected' : '' }}>
                                                                                {{ $barang->name }} | {{ $barang->stok }}
                                                                            </option>
                                                                        @endforeach
                                                                    </select>
                                                                    <div class="invalid-feedback">
                                                                        Please fill Barang
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-3 my-2">
                                                                <label>Stok Barang</label>
                                                                <div class="input-group">
                                                                    <input type="number" class="form-control"
                                                                        min="0" id="qty" placeholder="0"
                                                                        name="qty" value="{{ $transaksi_barang->qty }}"
                                                                        required>
                                                                    <div class="invalid-feedback">
                                                                        Please fill Quantity
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-1 my-2 d-flex align-items-center">
                                                                <button class="btn btn-danger btn-block"
                                                                    data-repeater-delete type="button"><i
                                                                        class="fas fa-trash"></i></button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            @endif
                                        </div>
                                    </div>

                                    <div class="mb-2">
                                        <label>Diskon (*optional)</label>
                                        <div class="input-group">
                                            <input type="number" class="form-control" id="diskon" placeholder="0"
                                                name="diskon" min="0">
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer text-right">
                                    <button class="btn btn-info" onclick="showInvoice()" type="button">Generate
                                        Invoice</button>
                                    <button class="btn btn-danger" name="is_draft" value="true">Bayar nanti</button>
                                    <button class="btn btn-primary">Bayar</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="invoice-section">

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
    <script src="{{ asset('library/sweetalert/dist/sweetalert.min.js') }}"></script>
    <script src="{{ asset('library/select2/dist/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('js/page/jquery.repeater.min.js') }}"></script>
    <script>
        $(".select2").select2({
            placeholder: "Pilih Barang",
        });
        $('.def-barang').repeater({
            show: function() {
                $(this).slideDown();
                $('.select2-container').remove();
                $('.select2').select2({
                    placeholder: "Pilih Barang",
                });
                $('form').removeClass('was-validated');
            },
            hide: function(deleteElement) {
                $(this).slideUp(deleteElement);
            },
        });

        function showInvoice() {
            if ($('#form')[0].checkValidity()) {
                $.ajax({
                    url: '/transaksi/show-invoice',
                    type: "post",
                    data: new FormData($('#form')[0]),
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function(res) {
                        $('.invoice-section').html(res)
                        console.log(res);
                    },
                });
            } else {
                $('#form').addClass('was-validated')
            }
        }
    </script>
@endpush
