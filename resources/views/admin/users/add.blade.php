@extends('layouts.admin')

@section('content')

<style>
  /* for fix microsoft edge unwanted password eye */
  input[type="password"]::-ms-reveal,
  input[type="password"]::-ms-clear {
      display: none;
  }
</style>

<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">

            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active">Add User</li>
                </ol>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">Add User</h3>
                    </div>

                    <form method="POST" action="{{ route('admin.users.store') }}">
                        @csrf

                        <div class="card-body">
                            <div class="form-group">
                                <label for="companyName">Company Name</label>
                                <select class="form-control required @error('company_id') is-invalid @enderror" name="company_id">
                                    <option value="">Select Any</option>
                                    @foreach($companies as $company)
                                        <option value="{{ $company->id }}">{{ $company->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="userName">Name</label>
                                <input type="text" name="name" id="userName"
                                    class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}"
                                    placeholder="Enter name" required>

                            </div>

                            <div class="form-group">
                                <label for="userEmail">Email</label>
                                <input type="email" name="email" id="userEmail"
                                    class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}"
                                    placeholder="Enter email" required>

                            </div>

                            <div class="form-group position-relative">
                                <label for="userPassword">Password</label>
                                <div class="input-group">
                                    <input type="password" name="password" id="userPassword"
                                        class="form-control @error('password') is-invalid @enderror"
                                        placeholder="Enter password" required>
                                    <div class="input-group-append">
                                        <span class="input-group-text toggle-password" data-target="#userPassword"
                                            style="cursor: pointer;">
                                            👁️
                                        </span>
                                    </div>
                                </div>

                            </div>

                            <div class="form-group position-relative">
                                <label for="password_confirmation">Confirm Password</label>
                                <div class="input-group">
                                    <input type="password" name="password_confirmation" id="password_confirmation"
                                        class="form-control" placeholder="Confirm password" required>
                                    <div class="input-group-append">
                                        <span class="input-group-text toggle-password"
                                            data-target="#password_confirmation" style="cursor: pointer;">
                                            👁️
                                        </span>
                                    </div>
                                </div>
                            </div>


                            <div class="form-group">
                                <label>Assign Roles</label>
                                <div class="row">
                                    @foreach($roles as $role)
                                    <div class="col-md-4">
                                        <div class="form-check">
                                            <input class="form-check-input @error('roles') is-invalid @enderror"
                                                type="checkbox" name="roles[]" value="{{ $role->id }}"
                                                id="role{{ $role->id }}"
                                                {{ in_array($role->id, old('roles', [])) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="role{{ $role->id }}">
                                                {{ $role->title }}
                                            </label>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>

                            </div>

                        </div>

                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">Submit</button>
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
<script>
$(function() {
    // Toggle password visibility
    $('.toggle-password').on('click', function() {
        const targetInput = $($(this).data('target'));
        const type = targetInput.attr('type') === 'password' ? 'text' : 'password';
        targetInput.attr('type', type);

        // Optional: toggle icon text
        $(this).text(type === 'password' ? '👁️' : '🙈');
    });
});
</script>
@parent
@endsection