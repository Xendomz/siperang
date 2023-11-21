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
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-end">
                                <a href="{{ route('transaksi.create') }}" class="btn btn-primary"><i
                                        class="fa-solid fa-plus"></i> Add Transaksi</a>
                            </div>
                            <div class="card-body">
                                @if (Session::get('success'))
                                <div class="alert alert-success alert-dismissible show fade">
                                    <div class="alert-body">
                                        <button class="close" data-dismiss="alert">
                                            <span>×</span>
                                        </button>
                                        {{ Session::get('success') }}
                                    </div>
                                </div>
                                @endif
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
                                <div class="table-responsive">
                                    <table class="table-striped table w-100" id="table">
                                        <thead>
                                            <tr>
                                                <th class="text-center">
                                                    #
                                                </th>
                                                <th>Kode Transaksi</th>
                                                <th>Created By</th>
                                                <th>Diskon (%)</th>
                                                <th>Total Harga</th>
                                                <th>Status</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>

                                        <tfoot>
                                            <tr>
                                                <th class="text-center">
                                                    #
                                                </th>
                                                <th>Kode Transaksi</th>
                                                <th>Created By</th>
                                                <th>Diskon (%)</th>
                                                <th>Total Harga</th>
                                                <th>Status</th>
                                                <th>Action</th>
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
    <script src="{{ asset('library/select2/dist/js/select2.full.min.js') }}"></script>

    <script>
        $(document).ready(function() {
            form.on('submit', function(e) {
                if (this.checkValidity()) {
                    e.preventDefault();
                    $.ajax({
                        url: url,
                        type: "post",
                        data: new FormData(this),
                        processData: false,
                        contentType: false,
                        success: function(res) {
                            $("#modalBarang").modal("hide")
                            table.ajax.reload()
                            swal('Good Job', res.message, 'success');
                        },
                        error: function(res) {
                            $("#modalBarang").modal("hide")
                            swal('Errors!', res.responseJSON.message, 'error');
                        }
                    });
                }
            })
        });

        var table = $('#table').DataTable({
            responsive: true,
            ajax: {
                'url': '/transaksi/get-data',
                'type': 'GET',
                'dataSrc': ''
            },
            fnRowCallback: function(nRow, aData, iDisplayIndex, iDisplayIndexFull) {
                $('td:eq(0)', nRow).html(iDisplayIndexFull + 1);
            },
            columns: [{
                    data: null
                },
                {
                    data: 'kode_transaksi'
                },
                {
                    data: 'user.name'
                },
                {
                    data: 'diskon'
                },
                {
                    data: 'total_price'
                },
                {
                    data: 'status'
                },
                {
                    data: null,
                    render: function(data, type, row, meta) {
                        var act = '';
                        if (row.status == 'Belum Bayar') {
                            act += `<a href="/transaksi/${row.id}" class="btn btn-success"><i class="fa-solid fa-money-bill-1-wave"></i> Bayar</a>`
                        }
                        act +=` <a href="/transaksi/${row.id}/detail" class="btn btn-info"><i class="fa fa-info"></i> Detail</a> <button class="btn btn-danger text-white" onclick="deleteTransaksi(${row.id})"><i class="fa fa-trash"></i> Delete</button>`;
                        return act;
                    }
                }
            ],
            dom: '<"d-flex justify-content-between align-items-center mx-0 row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>t<"d-flex justify-content-between mx-0 row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
        });

        var url = "";
        var form = $("#form");

        function addBarang() {
            $("#title").text("Add Barang")
            url = "/barang/store"
            form.trigger("reset")
            form.removeClass('was-validated')
        }

        function editBarang(id) {
            $("#title").text("Edit Barang")
            url = `/barang/${id}/update`
            form.trigger("reset")
            form.removeClass('was-validated')
            $.ajax({
                url: `/barang/${id}`,
                type: "get",
                success: function(res) {
                    $("#name").val(res.name)
                    $('#price').val(res.price)
                    $('#stok').val(res.stok)
                    $('#expired-date').val(res.expired_date)
                    $('#kategori').val(res.kategori_id)
                    $('#supplier').val(res.supplier_id)
                }
            })
        }

        function deleteTransaksi(id) {
            swal({
                title: 'Are you sure?',
                text: 'Once deleted, you will not be able to recover this data!',
                icon: 'warning',
                buttons: true,
                dangerMode: true,
            }).then((willDelete) => {
                if (willDelete) {
                    $.ajax({
                        url: `/transaksi/${id}/delete`,
                        type: "post",
                        success: function(res) {
                            table.ajax.reload();
                            swal('Good Job', res.message, 'success');
                        }
                    })
                }
            });
        }
    </script>

    <!-- Page Specific JS File -->
    <script src="{{ asset('js/page/modules-datatables.js') }}"></script>
@endpush
