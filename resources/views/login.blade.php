<!DOCTYPE html>
<html lang="en">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Munchbox | Login</title>

  <!-- Bootstrap -->
  <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet">
  <!-- Fontawesome -->
  <link href="{{ asset('assets/css/font-awesome.css') }}" rel="stylesheet">
  <!-- Custom Stylesheet -->
  <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/css/responsive.css') }}" rel="stylesheet">
</head>

<body>
  <div class="inner-wrapper">
    <div class="container-fluid no-padding">
      <div class="row no-gutters overflow-auto">
        <div class="col-md-6">
          <div class="main-banner">
            <img src="{{ asset('assets/img/banner/banner-1.jpg') }}" class="img-fluid full-width main-img" alt="banner">
            <div class="overlay-2 main-padding">
              <img src="{{ asset('assets/img/logo-2.jpg') }}" class="img-fluid" alt="logo">
            </div>
            <img src="{{ asset('assets/img/banner/burger.png') }}" class="footer-img" alt="footer-img">
          </div>
        </div>
        <div class="col-md-6" style="margin-top: 30px">
          <div class="section-2 user-page main-padding">
            <div class="login-sec">
              <div class="login-box">

                @if (session('success'))
                  <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                @if ($errors->any())
                  <div class="alert alert-danger">
                    <ul>
                      @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                      @endforeach
                    </ul>
                  </div>
                @endif

                <form id="loginForm">
                  <h4 class="text-light-black fw-600">Sign in with your Munchbox account</h4>
                  <div class="row">
                    <div class="col-12">
                      <p class="text-light-black">Have a corporate username? <a href="#">Click here</a></p>

                      <div class="form-group">
                        <label class="text-light-white fs-14">Email</label>
                        <input type="email" name="email" id="email" class="form-control form-control-submit" placeholder="Email" required>
                      </div>

                      <div class="form-group">
                        <label class="text-light-white fs-14">Password</label>
                        <input type="password" id="password-field" name="password" class="form-control form-control-submit" placeholder="Password" required>
                        <div data-name="#password-field" class="fa fa-fw fa-eye field-icon toggle-password"></div>
                      </div>

                      <div class="form-group checkbox-reset">
                        <label class="custom-checkbox mb-0">
                          <input type="checkbox" name="#"> 
                          <span class="checkmark"></span> Keep me signed in
                        </label>
                        <a href="#">Reset password</a>
                      </div>

                      <div class="form-group">
                        <button type="submit" class="btn-second btn-submit full-width" id="loginBtn">
                          <img src="{{ asset('assets/img/M.png') }}" alt="btn logo"> Sign in
                        </button>
                      </div>

                      <div class="form-group text-center"><span>or</span></div>

                      <div class="form-group">
                        <a href="{{ route('register') }}" class="btn-second btn-submit full-width">
                          <img src="{{ asset('assets/img/create-account.png') }}" alt="btn logo"> Create your account
                        </a>
                      </div>

                      <div class="form-group">
                        <button type="button" class="btn-second btn-facebook full-width">
                          <img src="{{ asset('assets/img/facebook-logo.svg') }}" alt="btn logo"> Continue with Facebook
                        </button>
                      </div>

                      <div class="form-group">
                        <button type="button" class="btn-second btn-google full-width">
                          <img src="{{ asset('assets/img/google-logo.png') }}" alt="btn logo"> Continue with Google
                        </button>
                      </div>

                    </div>
                  </div>
                </form>

              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- jQuery (only once) -->
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

  <script>
  $(document).ready(function() {
    // Setup CSRF for all Ajax requests
    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': '{{ csrf_token() }}'
      }
    });

    $("#loginForm").on("submit", function(e) {
      e.preventDefault();

      var email = $("#email").val();
      var password = $("#password-field").val();

      $.ajax({
        url: '/api/login',
        type: 'POST',
        contentType: 'application/json',
        data:JSON.stringify({ email: email, password: password }) ,
        success: function(response) {
          console.log('Login successful:', response);
        
           // redirect after login

          // If API returns token, store it & redirect
         
            localStorage.setItem('auth_token', response.token);
            window.location.href = "/index"; // redirect after login
          
        },
        error: function(xhr) {
          console.error('Login failed:', xhr.responseText);
          alert("Invalid login. Please check your email and password.");
         }
      });
    });
  });
  </script>

  <!-- Bootstrap JS -->
  <script src="{{ asset('assets/js/bootstrap.min.js') }}"></script>
</body>
</html>