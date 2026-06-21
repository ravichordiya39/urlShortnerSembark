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
                <h1>Users</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active">Users</li>
                </ol>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">

                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Users List</h3>
                    </div>
                    <div class="card-body">
                        <table id="example1" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Roles</th>
                                  
                                    <th>Created At</th>
                                    <th>Updated At</th>
                                    <th>Status</th>
                                    <th>Action</th>
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
<div class="modal fade" id="userModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">

        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">User Details</h5>

                <button type="button" 
                        class="close" 
                        data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>


            <div class="modal-body">

                <form id="userForm">
                    <div class="form-group">
                        <label>Name & Email </label>
                        <div id="userDataDiv">

                        </div>
                        <input type="hidden" name = "userId" id = "userId" value="">
                    </div>    
                    <div class="form-group">
                        <label>Select Company</label>
                        <select class="form-control" id="companyId" name = 'companyId'>
                            <option value = "">Select Any</option>
                            @foreach($companies as $comp)
                                <option value = "{{ $comp->id }}">{{ $comp->title }}</option>
                            @endforeach 
                        </select>
                    </div>
                </form>

            </div>


            <div class="modal-footer">

                <button type="button"
                        class="btn btn-secondary"
                        data-dismiss="modal">
                    Close
                </button>

                <button type="button"
                        class="btn btn-primary submitInvite">
                    Save
                </button>

            </div>

        </div>

    </div>
</div>

<!-- Control Sidebar -->
<aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
</aside>
<!-- /.control-sidebar -->
</div>
@endsection

@section('scripts')
@parent
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
    getUsers();
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

function getUsers() {
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
            url: "{{ route('admin.users.userListAjax') }}",
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}'
            }
        },
        columns: [{
                data: 'id',
                name: 'id',
                searchable: true,
                orderable: false,
                render: function(data, type, full) {
                    return '<span class="datefont">' + data + '</span>';
                },
            },
            {
                data: 'name',
                name: 'name',
                searchable: true,
                orderable: false,
                render: function(data, type, full) {
                    //console.log("datadatadata",data);
                    return data;
                }
            },
            {
                data: 'email',
                name: 'email',
                searchable: true,
                orderable: false,
                render: function(data, type, full) {
                    //console.log("datadatadata",data);
                    return data;
                }
            },
            {
                data: 'roles',
                name: 'roles',
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
                data: 'updated_at_dt',
                name: 'updated_at_dt',
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
                            ' data-status="N" class="userStatusUpdate" name="user-status-checkbox" checked data-bootstrap-switch data-off-color="danger" data-on-color="success">';
                    } else {
                        return '<input type="checkbox" value="' + data + '" data-id=' + full.id +
                            ' data-status="Y" class="userStatusUpdate" name="user-status-checkbox" checked data-bootstrap-switch data-off-color="danger" data-on-color="success">';
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
$(document).on('switchChange.bootstrapSwitch', '.userStatusUpdate', function(event, state) {
    event.preventDefault();

    var $switch = $(this);
    var id = $switch.data('id');
    var status = state ? 'Y' : 'N';

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
        url: "{{ route('admin.users.changeStatusUser') }}",
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
            $switch.bootstrapSwitch('disabled', false);
        },
        error: function() {
            toastr.error('Server error.');
            $switch.bootstrapSwitch('disabled', false);
        }
    });
});

// Delete
$(document).on('click', '.deleteUser', function() {
    let id = $(this).data('id');
    if (!confirm("Are you sure you want to delete this user?")) {
        return false;
    }
    $.ajax({
        type: 'POST',
        url: "{{ route('admin.users.deleteUser') }}",
        data: {
            _token: "{{ csrf_token() }}",
            id: id
        },
        success: function(response) {
            if (response.status === 'success') {
                $('table#example1').DataTable().ajax.reload(null, false);
                updateSwitchUI(1000);
                toastr.success(response.message);
            } else {
                toastr.error(response.message);
            }
        },
        error: function() {
            toastr.error('Server error.');
        }
    });
});
$(document).on('click','.openUserModal',function(){

    let id = $(this).data('id');
    let name = $(this).data('name');
    let email = $(this).data('email');
    $("#userDataDiv").empty(); 
    $("#userDataDiv").html(name +' ('+email+')'); 
    $("#userId").val(id);
    $('#userModal').modal('show');

    // optional ajax load
    $.ajax({
        url: "/admin/users/"+id,
        type:"GET",
        success:function(response){

            $('input[name=name]').val(response.name);
            $('input[name=email]').val(response.email);

        }
    });

});

$(document).on('click','.submitInvite',function(){

    let userId = $("#userId").val();
    let companyId = $("#companyId").val();
    // optional ajax load
    $.ajax({
        type: 'POST',
        url: "{{ route('admin.users.inviteUser') }}",
        data: {
            _token: "{{ csrf_token() }}",
            id: userId,
            company_id: companyId
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
                    'Cannot deactivate permission because it is assigned to role.');
            } else {
                toastr.error('Server error.');
                $switch.bootstrapSwitch('state', previousState, true);
            }
        }
    });

});
</script>
@endsection