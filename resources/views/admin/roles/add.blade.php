@extends('layouts.admin')
@section('styles')
  <link rel="stylesheet" href="{{ asset('assets/vendor/plugins/select2/css/select2.min.css') }}">
  <style>
    /* Ensure selected values and dropdown options have dark text */
    .select2-container--default .select2-selection--multiple,
    .select2-container--default .select2-selection--single {
      color: #212529 !important;
      background-color: #ffffff !important;
      border: 1px solid #ced4da;
    }

    .select2-container--default .select2-selection--multiple .select2-selection__choice {
      color: #212529 !important;
      background-color: #e9ecef !important;
    }

    .select2-container--default .select2-results__option {
      color: #212529;
    }

    .select2-container--default .select2-selection__rendered {
      color: #212529 !important;
    }
  </style>
@endsection

@section('content')


    <!-- Content Header -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Create Role</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Create Role</li>
            </ol>
          </div>
        </div>
      </div>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <!-- Left column -->
          <div class="col-md-12">
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Add New Role & Assign Permissions</h3>
              </div>

              <!-- Form start -->
              <form method="POST" action="{{ route('admin.roles.store') }}">
                @csrf
                <div class="card-body">
                  <div class="form-group">
                    <label for="roleTitle">Role Title</label>
                    <input type="text" name="title" id="roleTitle" class="form-control" placeholder="Enter role title" required>
                  </div>

                  <div class="form-group">
                    <label>Assign Permissions</label>
                    <div class="form-check mb-2">
                        <input type="checkbox" class="form-check-input" id="toggleAllPermissions">
                        <label class="form-check-label" for="toggleAllPermissions">Select All</label>
                      </div>
                                        <div class="row">
                                        <select name="permissions[]" id="permissions" class="select2" multiple="multiple" data-placeholder="Select Permissions" style="width: 100%;" required>
                        @foreach($permissions as $permission)
                          <option value="{{ $permission->id }}"
                            {{ in_array($permission->id, old('permissions', $selectedPermissions ?? [])) ? 'selected' : '' }}>
                            {{ $permission->title }}
                          </option>
                        @endforeach
                      </select>
                    </div>
                  </div>
                </div>

                <div class="card-footer">
                  <button type="submit" class="btn btn-primary">Submit</button>
             
                </div>
              </form> 
            </div>
          </div>
          <!-- /.col -->
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
<script src="{{ asset('assets/vendor/plugins/select2/js/select2.full.min.js') }}"></script>
<script>
$(function () {
  const $permissions = $('#permissions');
  const $toggleAll = $('#toggleAllPermissions');

  $permissions.select2({
    closeOnSelect: false
  });

  $toggleAll.on('change', function () {
    if ($(this).is(':checked')) {
      const allValues = $permissions.find('option').map(function () {
        return $(this).val();
      }).get();
      $permissions.val(allValues).trigger('change');
    } else {
      $permissions.val([]).trigger('change');
    }
  });

  $permissions.on('change', function () {
    const total = $permissions.find('option').length;
    const selected = ($permissions.val() || []).length;
    $toggleAll.prop('checked', selected === total);
  });

  $permissions.on('select2:open', function () {
    const searchField = $('.select2-search__field');
    searchField.off('keydown.preventDelete').on('keydown.preventDelete', function (e) {
      if (e.key === 'Backspace' && $(this).val() === '') {
        e.stopPropagation();
        e.preventDefault();
      }
    });
  });

  $permissions.on('select2:open', function () {
    const searchField = $('.select2-search__field');
    searchField.off('keyup.closeDropdown').on('keyup.closeDropdown', function (e) {
      if (e.key === 'Backspace' && !$(this).val()) {
        $permissions.select2('close');
      }
    });
  });
});
</script>
@parent
@endsection



