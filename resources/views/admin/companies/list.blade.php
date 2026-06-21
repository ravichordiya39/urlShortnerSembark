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
                <h1>Companies</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active">Companies</li>
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
                        <h3 class="card-title">Companies List</h3>
                    </div>
                    <div class="card-body">
                        <table id="example1" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Sr. No.</th>
                                    <th>Title</th>
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
<!-- /.content -->
 
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
    getPermissions();
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

function getPermissions() {
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
            url: "{{ route('admin.companies.companyListAjax') }}",
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
                data: 'title',
                name: 'title',
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
           
            {data: 'status', name: 'status', searchable: true, orderable: false,
          render: function ( data, type, full ) {
           
            if(data == 'Y'){
              return '<input type="checkbox" value="'+data+'" data-id='+full.id+' data-status="N" class="permissionStatusUpdate" name="permission-status-checkbox" checked data-bootstrap-switch data-off-color="danger" data-on-color="success">';
            }else{
              return '<input type="checkbox" value="'+data+'" data-id='+full.id+' data-status="Y" class="permissionStatusUpdate" name="permission-status-checkbox" checked data-bootstrap-switch data-off-color="danger" data-on-color="success">';
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

$(document).on('switchChange.bootstrapSwitch', '.permissionStatusUpdate', function(event, state) {
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
        url: "{{ route('admin.companies.changeStatusCompany') }}",
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
                    'Cannot deactivate permission because it is assigned to role.');
            } else {
                toastr.error('Server error.');
                $switch.bootstrapSwitch('state', previousState, true);
            }
        }
    });
});


$(document).on('click', '.deletePermission', function() {
    let $button = $(this); 
    let id = $(this).data('id');
    if (!confirm("Are you sure you want to delete this permission?")) {
        return false;
    }
    $.ajax({
        type: 'POST',
        url: "{{ route('admin.companies.deleteCompany') }}",
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
                    'Permission is assigned to role and cannot be deleted.');
            } else {
                toastr.error('Server error.');
            }
        }
    });
});
</script>


@parent
@endsection