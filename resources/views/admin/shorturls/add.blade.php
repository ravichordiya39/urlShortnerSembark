@extends('layouts.admin')

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
                    <li class="breadcrumb-item active">Add ShortUrl</li>
                </ol>
            </div>
        </div>
    </div>
</section>

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <!-- left column -->
            <div class="col-md-12">
                <!-- general form elements -->
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">Add ShortUrl</h3>
                    </div>
                    <!-- /.card-header -->

                    <!-- form start -->
                    <form method="POST" action="{{ route('admin.shortUrls.store') }}">
                        @csrf
                        <div class="card-body">
                            <div class="form-group">
                                <label for="shortUrlTitle">Original URL</label>
                                <input type="url" name="original_url" class="form-control" id="shortUrlTitle"
                                    placeholder="Enter Original Url" required>
                            </div>
                            <div class="form-group">
                                <label for="companyName">Company Name</label>
                                <select class="form-control required @error('company_id') is-invalid @enderror" name="company_id">
                                    <option value="">Select Any</option>
                                    @foreach($companies as $company)
                                        <option value="{{ $company->id }}">{{ $company->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <!-- /.card-body -->

                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">Submit</button>

                        </div>
                    </form>
                </div>
                <!-- /.card -->
            </div>
            <!--/.col (left)-->
        </div>
        <!-- /.row -->
    </div><!-- /.container-fluid -->
</section>
<!-- /.content -->
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
@parent
@endsection