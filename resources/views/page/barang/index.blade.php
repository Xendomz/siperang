@extends('layouts.app')

@section('title', 'Barang')

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
                <h1>Barang</h1>
            </div>

            <div class="section-body">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-end">
                                <a href="{{ route('barang.historisBarang') }}" class="btn btn-info"><i class="fa-solid fa-box-archive"></i> Historis Barang</a>&nbsp;
                                <button class="btn btn-primary" data-toggle="modal" data-target="#modalBarang"
                                    onclick="addBarang()"><i class="fa-solid fa-plus"></i> Add Barang</button>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table-striped table w-100" id="table">
                                        <thead>
                                            <tr>
                                                <th class="text-center">
                                                    #
                                                </th>
                                                <th>Name</th>
                                                <th>Price</th>
                                                <th>Stok</th>
                                                <th>Kategori</th>
                                                <th>Suplier</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>

                                        <tfoot>
                                            <tr>
                                                <th class="text-center">
                                                    #
                                                </th>
                                                <th>Name</th>
                                                <th>Price</th>
                                                <th>Stok</th>
                                                <th>Kategori</th>
                                                <th>Suplier</th>
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
        <div class="modal fade" role="dialog" id="modalBarang">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="title">Add Barang</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form id="form" class="needs-validation" novalidate="">
                        <div class="modal-body">
                            @csrf
                            <div class="form-group">
                                <label>Name</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="name" placeholder="Name"
                                        name="name" required>
                                    <div class="invalid-feedback">
                                        Please fill name
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Price</label>
                                <div class="input-group">
                                    <input type="number" class="form-control" id="price" placeholder="0" name="price"
                                        required>
                                    <div class="invalid-feedback">
                                        Please fill price
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Stok</label>
                                <div class="input-group">
                                    <input type="number" class="form-control" id="stok" placeholder="0" name="stock"
                                        required>
                                    <div class="invalid-feedback">
                                        Please fill stock
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label>Kategori</label>
                                        <div class="input-group">
                                            <select name="kategori" id="kategori" class="form-control select2" required>
                                                @foreach ($kategories as $kategori)
                                                    <option value="{{ $kategori->id }}">{{ $kategori->name }}</option>
                                                @endforeach
                                            </select>
                                            <div class="invalid-feedback">
                                                Please fill kategori
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label>Supplier</label>
                                        <div class="input-group">
                                            <select name="supplier" id="supplier" class="form-control select2" required>
                                                @foreach ($suppliers as $supplier)
                                                    <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                                                @endforeach
                                            </select>
                                            <div class="invalid-feedback">
                                                Please fill supplier
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Tanggal Input</label>
                                <div class="input-group">
                                    <input type="date" class="form-control" id="barang-masuk" placeholder="0"
                                        name="tanggal_input" required>
                                    <div class="invalid-feedback">
                                        Please fill tanggal input
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer bg-whitesmoke br">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Save changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
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
        $(document).ready(function(){
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
                        }, error: function (res) {
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
                'url': '/barang/get-data',
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
                    data: 'name'
                },
                {
                    data: 'price'
                },
                {
                    data: 'stok'
                },
                {
                    data: 'kategori.name'
                },
                {
                    data: 'supplier.name'
                },
                {
                    data: null,
                    render: function(data, type, row, meta) {
                        return `<button class="btn btn-warning text-white" data-toggle="modal" data-target="#modalBarang" onclick="editBarang(${row.id})"><i class="fa fa-edit"></i> Edit</button> <button class="btn btn-danger text-white" onclick="deleteBarang(${row.id})"><i class="fa fa-trash"></i> Delete</button>`;
                    }
                }
            ],
            dom: '<"d-flex justify-content-between align-items-center mx-0 row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>t<"d-flex justify-content-between mx-0 row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
            initComplete: function () {
                this.api().column(4).every( function () {
                var column = this;
                var select = $('<select class="form-control"><option value=""></option></select>')
                    .appendTo( $(column.header()).empty() )
                    .on( 'change', function () {
                        var val = $.fn.dataTable.util.escapeRegex($(this).val());
                        column
                            .search( val ? '^'+val+'$' : '', true, false )
                            .draw();
                    } );
                    column.data().unique().sort().each( function ( d, j ) {
                        select.append( '<option value="'+d+'">'+d+'</option>' )
                    } );
                } );
            }
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

        function deleteBarang(id) {
            swal({
                title: 'Are you sure?',
                text: 'Once deleted, you will not be able to recover this data!',
                icon: 'warning',
                buttons: true,
                dangerMode: true,
            }).then((willDelete) => {
                if (willDelete) {
                    $.ajax({
                        url: `/barang/${id}/delete`,
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
