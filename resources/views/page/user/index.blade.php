@extends('layouts.app')

@section('title', 'User Management')

@push('style')
    <!-- CSS Libraries -->
    <link rel="stylesheet" href="{{ asset('library/datatables/media/css/jquery.dataTables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('library/datatables/media/css/jquery.dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('library/datatables/media/css/jquery.dataTables.select.bootstrap4.min.css') }}">
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>User Management</h1>
            </div>

            <div class="section-body">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-end">
                                <button class="btn btn-primary" data-toggle="modal" data-target="#modalUser"
                                    onclick="addUser()"><i class="fa-solid fa-plus"></i> Add User</button>
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
                                                <th>Email</th>
                                                <th>Role</th>
                                                <th>Status</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <div class="modal fade" tabindex="-1" role="dialog" id="modalUser">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="title">Add Kategori</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form id="form" class="needs-validation" novalidate="">
                        <div class="modal-body">
                            @csrf
                            <div class="form-group">
                                <label for="name">Name</label>
                                <input id="name" type="text" class="form-control" name="name" placeholder="Name" required>
                                <div class="invalid-feedback">
                                    please fill in your name
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="email">Email</label>
                                <input id="email" type="email" class="form-control" name="email" placeholder="Email" required>
                                <div class="invalid-feedback">
                                    please fill in your email
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="password">Password</label>
                                <input id="password" type="password" class="form-control" name="password" placeholder="Password" required>
                                <div class="invalid-feedback">
                                    please fill in your password
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
                            $("#modalUser").modal("hide")
                            table.ajax.reload()
                            swal('Good Job', res.message, 'success');
                        }
                    });
                }
            })
        });

        var table = $('#table').DataTable({
            responsive: true,
            ajax: {
                'url': '/user/get-data',
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
                    data: 'email'
                },
                {
                    data: 'role'
                },
                {
                    data: null,
                    render: function(data, type, row, meta) {
                        return `<button type="button" class="btn btn-icon btn-${row.is_active == 1 ? 'success' : 'danger'}" onclick="toggleActivePrograms(${row.id})"><i class="fa fa-toggle-${row.is_active == 1 ? 'on' : 'off'}"></i></button>`;
                    }
                },
                {
                    data: null,
                    render: function(data, type, row, meta) {
                        return `<button class="btn btn-warning text-white" data-toggle="modal" data-target="#modalUser" onclick="editUser(${row.id})"><i class="fa fa-edit"></i> Edit</button> <button class="btn btn-danger text-white" onclick="deleteUser(${row.id})"><i class="fa fa-trash"></i> Delete</button>`;
                    }
                }
            ],
            dom: '<"d-flex justify-content-between align-items-center mx-0 row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>t<"d-flex justify-content-between mx-0 row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
        });

        var url = "";
        var form = $("#form");

        function addUser() {
            $("#title").text("Add User")
            $('#password')[0].setAttribute('required', '');
            url = "/user/store"
            form.trigger("reset")
            form.removeClass('was-validated')
        }

        function toggleActivePrograms(id) {
            swal({
                title: 'Change Status',
                text: "Are you sure? you won't be able to revert this!",
                icon: 'warning',
                buttons: true,
            }).then(function(result) {
                if (result) {
                    $.ajax({
                        url: `/user/${id}/toggle-status`,
                        type: 'post',
                        success: function(res) {
                            swal('Good Job', res.message, 'success');
                            table.ajax.reload();
                        },
                    });
                }
            });
        }

        function editUser(id) {
            $("#title").text("Edit Kategori")
            $('#password')[0].removeAttribute('required')
            url = `/user/${id}/update`
            form.trigger("reset")
            form.removeClass('was-validated')
            $.ajax({
                url: `/user/${id}`,
                type: "get",
                success: function(res) {
                    $("#name").val(res.name)
                    $("#email").val(res.email)
                }
            })
        }

        function deleteUser(id) {
            swal({
                title: 'Are you sure?',
                text: 'Once deleted, you will not be able to recover this data!',
                icon: 'warning',
                buttons: true,
                dangerMode: true,
            }).then((willDelete) => {
                if (willDelete) {
                    $.ajax({
                        url: `/user/${id}/delete`,
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
