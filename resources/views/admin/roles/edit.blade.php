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


 
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Edit Role</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

  <section class="content">
    <div class="container-fluid">
      <div class="row"> 
        <div class="col-md-12">
          <div class="card card-primary">
            <div class="card-header">
              <h3 class="card-title">Edit Role</h3>
            </div>

            <form method="POST" action="{{ route('admin.roles.update', $role->id) }}">
              @csrf
              @method('PUT')
              <div class="card-body">
                <div class="form-group">
                  <label for="roleTitle">Role Title</label>
                  <input type="text" name="title" class="form-control" id="roleTitle" value="{{ $role->title }}" required>
                </div>

                <div class="form-group">
                  <label for="permissions">Permissions</label>
                  <div class="form-check mb-2">
    <input type="checkbox" class="form-check-input" id="toggleAllPermissions">
    <label class="form-check-label" for="toggleAllPermissions">Select All</label>
  </div>
                  <select name="permissions[]" id="permissions" class="select2" multiple="multiple" data-placeholder="Select Permissions" style="width: 100%;" required>
                    @foreach($permissions as $permission)
                      <option value="{{ $permission->id }}"
                        {{ in_array($permission->id, $role->permissions->pluck('id')->toArray()) ? 'selected' : '' }}>
                        {{ $permission->title }}
                      </option>
                    @endforeach
                  </select>
                </div>
              </div>

              <div class="card-footer">
                <button type="submit" class="btn btn-primary">Update</button>
         
              </div>
            </form>

          </div>
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


