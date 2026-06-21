@extends('layouts.admin')
@section('styles')
<link rel="stylesheet" href="{{ asset('assets/vendor/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet"
    href="{{ asset('assets/vendor/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/vendor/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
@endsection
@section('content')


<!-- Content Header -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Roles</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active">Roles</li>
                </ol>
            </div>
        </div>
    </div>
</section>

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">

                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Roles List</h3>
                    </div>
                    <div class="card-body">
                        <table id="example1" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Sr. No.</th>
                                    <th style="width: 150px;">Role</th>
                                    <th style="width: 700px;">Permissions</th>
                                    <th>Created At</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>

                    </div>

                </div>
                <!-- /.card -->

            </div>
        </div>
    </div>
</section>

</div>

<!-- Control Sidebar -->
<aside class="control-sidebar control-sidebar-dark">
  <!-- Control sidebar content goes here -->
</aside>
<!-- /.control-sidebar -->
</div>
@endsection

@section('scripts')
<script src="{{ asset('assets/vendor/plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/vendor/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('assets/vendor/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('assets/vendor/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
<script src="{{ asset('assets/vendor/plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('assets/vendor/plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>
<script src="{{ asset('assets/vendor/plugins/jszip/jszip.min.js') }}"></script>
<script src="{{ asset('assets/vendor/plugins/pdfmake/pdfmake.min.js') }}"></script>
<script src="{{ asset('assets/vendor/plugins/pdfmake/vfs_fonts.js') }}"></script>
<script src="{{ asset('assets/vendor/plugins/bootstrap-switch/js/bootstrap-switch.min.js') }}"></script>
<script src="{{ asset('assets/vendor/plugins/datatables-buttons/js/buttons.html5.min.js') }}"></script>
<script src="{{ asset('assets/vendor/plugins/datatables-buttons/js/buttons.print.min.js') }}"></script>
<script src="{{ asset('assets/vendor/plugins/datatables-buttons/js/buttons.colVis.min.js') }}"></script>

<script>
$(function() {
    getRoles();
    // updateSwitchUI(1000);
});

function updateSwitchUI(timeout) {
    setTimeout(() => {
        $("input[data-bootstrap-switch]").each(function() {
            let $this = $(this);
            let value = $this.val();

            $this.prop('checked', value === 'Y');

            $this.bootstrapSwitch({
                state: $this.prop('checked'),
                onText: 'A',
                offText: 'D',
                onColor: 'success',
                offColor: 'danger'
            });
        });
    }, timeout);
}

function getRoles() {
    $('table#example1').DataTable({
        "pageLength": 10,
        "dom": '<"top"f<"clear">>rt<"bottom"ip<"clear">>',
        processing: true,
        "ordering": false,
        "language": {
            paginate: {
                first: '',
                previous: '<',
                next: '>',
                last: ''
            },
            aria: {
                paginate: {
                    first: '',
                    previous: '',
                    next: '',
                    last: ''
                }
            },
            "sSearch": "_INPUT_",
            "sSearchPlaceholder": "Search",
            "sLengthMenu": "Rows _MENU_",
            "sProcessing": "<div id='overlay'><h2>Loading .. Please wait</h2></div>",
        },
        "serverSide": true,
        "bDestroy": true,
        "bLengthChange": false,
        "bFilter": true,
        ajax: {
            url: "{{ route('admin.roles.roleListAjax') }}",
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}'
            }
        },
        columns: [{
                data: 'no',
                name: 'no',
                searchable: true,
                orderable: false,
                render: function(data, type, full) {
                    return '<span class="datefont">' + data + '</span>';
                },
            },
            {
                data: 'role',
                name: 'role',
                searchable: true,
                orderable: false,
                render: function(data, type, full) {
                    //console.log("datadatadata",data);
                    return data;
                }
            },
            {
                data: 'permissions',
                name: 'permissions',
                searchable: true,
                orderable: false,
                render: function(data, type, full) {
                    //console.log("datadatadata",data);
                    return data;
                }
            },
            {
                data: 'created_at_dt',
                name: 'created_at_dt',
                searchable: true,
                orderable: false,
                render: function(data, type, full) {
                    //console.log("datadatadata",data);
                    return '<span class="datefont" >' + data + '</span>';
                }
            },
            {
                data: 'status',
                name: 'status',
                searchable: true,
                orderable: false,
                render: function(data, type, full) {

                    if (data == 'Y') {
                        return '<input type="checkbox" value="' + data + '" data-id=' + full.id +
                            ' data-status="N" class="roleStatusUpdate" name="role-status-checkbox" checked data-bootstrap-switch data-off-color="danger" data-on-color="success">';
                    } else {
                        return '<input type="checkbox" value="' + data + '" data-id=' + full.id +
                            ' data-status="Y" class="roleStatusUpdate" name="role-status-checkbox"  data-bootstrap-switch data-off-color="danger" data-on-color="success">';
                    }

                }
            },
            {
                data: 'action',
                name: 'action',
                searchable: true,
                orderable: false,
                render: function(data, type, full) {
                    //console.log("datadatadata",data);
                    return data;
                }
            },
        ],
        language: {
            searchPlaceholder: "Search...",
            sSearch: "",
            paginate: {
                previous: "<",
                next: ">"
            },
            sProcessing: "<div id='overlay'><h2>Loading .. Please wait</h2></div>"
        },
        dom: '<"top"f<"clear">>rt<"bottom"ip<"clear">>',
        pageLength: 10,
        responsive: true,
        lengthChange: false,
        autoWidth: false,
        bDestroy: true,
        drawCallback: function(settings) {
            // Initialize switches after each table draw
            $("input[data-bootstrap-switch]").each(function() {
                let $this = $(this);
                let value = $this.val();

                $this.prop('checked', value === 'Y');

                $this.bootstrapSwitch({
                    state: $this.prop('checked'),
                    onText: 'A',
                    offText: 'D',
                    onColor: 'success',
                    offColor: 'danger'
                });
            });
        }
    });
}
$(document).on('switchChange.bootstrapSwitch', '.roleStatusUpdate', function(event, state) {
    event.preventDefault();

    var $switch = $(this);
    var id = $switch.data('id');
    var status = state ? 'Y' : 'N';
    var previousState = !state; // store the opposite state to revert if needed

    if (state && !confirm('Are you sure to activate?')) {
        $switch.bootstrapSwitch('state', false, true);
        return;
    }

    if (!state && !confirm('Are you sure to deactivate?')) {
        $switch.bootstrapSwitch('state', true, true);
        return;
    }
    $.ajax({
        type: 'POST',
        url: "{{ route('admin.roles.changeStatusRole') }}",
        data: {
            _token: "{{ csrf_token() }}",
            id: id,
            status: status
        },
        success: function(data) {
            if (!data.error) {
                toastr.success(data.message);
            } else {
                toastr.error(data.message);
            }
        },
        error: function(xhr) {
            if (xhr.status === 403) {
                // If deactivation is blocked because the role is assigned to users,
                // revert the switch back to activated (ON)
                $switch.bootstrapSwitch('state', true, true);
                toastr.error(xhr.responseJSON.message ||
                    'Cannot deactivate role assigned to users.');
            } else {
                toastr.error('Server error.');
                $switch.bootstrapSwitch('state', previousState, true);
            }
        },
        complete: function() {
            // Re-enable the switch for further interactions

        }
    });
});



$(document).on('click', '.deleteRole', function() {
    let id = $(this).data('id');
    let $button = $(this); // reference for disabling

    if (!confirm("Are you sure you want to delete this role?")) {
        return false;
    }

    $.ajax({
        type: 'POST',
        url: "{{ route('admin.roles.deleteRole') }}",
        data: {
            _token: "{{ csrf_token() }}",
            id: id
        },
        success: function(response) {
            if (response.status === 'success') {
                $('table#example1').DataTable().ajax.reload(null, false);
                toastr.success(response.message);
            } else {
                toastr.error(response.message);
            }
        },
        error: function(xhr) {
            if (xhr.status === 403) {
                // Disable the button if role is in use
                // $button.prop('disabled', true);
                toastr.error(xhr.responseJSON.message ||
                    'Role is assigned to users and cannot be deleted.');
            } else {
                toastr.error('Server error.');
            }
        }
    });
});



</script>

@parent
@endsection