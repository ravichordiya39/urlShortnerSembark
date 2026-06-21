<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Url Shortner | Log in</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">

  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{ asset('assets/vendor/plugins/fontawesome-free/css/all.min.css') }}">

  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="{{ asset('assets/vendor/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">

  <!-- Theme style -->
  <link rel="stylesheet" href="{{ asset('assets/vendor/dist/css/adminlte.min.css') }}">

<style>
  input[type="password"]::-ms-reveal,
  input[type="password"]::-ms-clear {
      display: none;
  }
</style>

</head>
<body class="hold-transition login-page">
<div class="login-box">
  
  
  <!-- /.login-logo -->
  <div class="card">
    
    <div class="card-body login-card-body">
    @if ($errors->any())
  <div id="error-alert" class="alert alert-danger" style="font-size: 16px; margin-bottom: 0; text-align: center;">
    <ul class="mb-0" style="list-style: none; padding-left: 0; margin: 0;">
      @foreach ($errors->all() as $error)
        <li class="mb-0">{{ $error }}</li>
      @endforeach
    </ul>
  </div>
@endif



      <p class="login-box-msg">Sign in to start your session</p>

      <form action="{{ route('login.submit') }}" method="POST">
        @csrf
        <div class="input-group mb-3">
          <input type="email" name="email" class="form-control" placeholder="Email" required>
          <div class="input-group-append">
            <div class="input-group-text"> 
              <span class="fas fa-envelope"></span>
            </div>
          </div>
        </div>
        <div class="input-group mb-3">
  <input type="password" id="password" name="password" class="form-control" placeholder="Password" required>
  <div class="input-group-append">
    <div class="input-group-text" style="cursor: pointer;" onclick="togglePassword()">
      <span id="toggleEye" class="fas fa-eye"></span>
    </div>
  </div>
</div>
        <div class="row">
          <div class="col-8"> 
            
          
          </div>
        </div>
        <div class ="row">
          <div class="col-12">
            <button type="submit" class="btn btn-primary btn-block">Sign In</button>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Scripts -->
<script src="{{ asset('assets/vendor/plugins/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('assets/vendor/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('assets/vendor/dist/js/adminlte.min.js') }}"></script>


<script>
  function togglePassword() {
    const passwordInput = document.getElementById('password');
    const eyeIcon = document.getElementById('toggleEye');
    
    if (passwordInput.type === 'password') {
      passwordInput.type = 'text';
      eyeIcon.classList.remove('fa-eye');
      eyeIcon.classList.add('fa-eye-slash');
    } else {
      passwordInput.type = 'password';
      eyeIcon.classList.remove('fa-eye-slash');
      eyeIcon.classList.add('fa-eye');
    }
  }
  
</script>
<script>
  // Hide error alert after 5 seconds
  setTimeout(function() {
    const alertBox = document.getElementById('error-alert');
    if (alertBox) {
      alertBox.style.display = 'none';
    }
  }, 4000); // 5000ms = 5 seconds
</script>

</body>
</html>
