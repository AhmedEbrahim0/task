<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="{{asset('css/bootstrap.css')}}">
    <link rel="shortcut icon" type="image/png" href="img/logo-1-1.png" />

    <style>

.login,
.image {
  min-height: 100vh;
}

.bg-image {
  background-image: url('https://res.cloudinary.com/mhmd/image/upload/v1555917661/art-colorful-contemporary-2047905_dxtao7.jpg');
  background-size: cover;
  background-position: center center;
}
    </style>
</head>
<body>
<div class="container-fluid">
    <div class="row no-gutter">
        <!-- The image half -->
        <div class="col-md-6 col-xs-12 d-md-flex bg-image"></div>


        <!-- The content half -->
        <div class="col-md-6  col-xs-12 bg-light">
            <div class="login d-flex align-items-center py-5">

                <!-- Demo content-->
                <div class="container">
                    <div class="row">
                        <div class="col-lg-10 col-xl-7 mx-auto">
                            @if(Session::has('success'))
								<p  style="background: green; color:white;padding: 10px;">{{ Session::get('success') }}</p>
							@endif

                            @if(Session::has('error'))
								<p  style="background: red; color:white;padding: 10px;">{{ Session::get('error') }}</p>
							@endif
							@if($errors->any())
								@foreach ($errors->all() as $error)
									<div style="background-color: red; color:white; margin: 10px 0;padding: 10px;"> {{$error}}</div>
								@endforeach
							@endif
                            <h3 class="display-4">Welcome Back!</h3>

                            <form method="post" action="/login">
                                @csrf
                                <div class="form-group mb-3">
                                    <input name="email" id="inputEmail" type="email" placeholder="Email address" required autofocus="" class="form-control rounded-pill border-0 shadow-sm px-4">
                                </div>
                                <div class="form-group mb-3">
                                    <input name="password" id="inputPassword" type="password" placeholder="Password" required class="form-control rounded-pill border-0 shadow-sm px-4 text-primary">
                                </div>
                                <div class="custom-control custom-checkbox mb-3">
                                    <input id="customCheck1" type="checkbox" checked class="custom-control-input">
                                    <label for="customCheck1" class="custom-control-label">Remember password</label>
                                </div>
                                <button type="submit" class="btn btn-primary btn-block text-uppercase mb-2 rounded-pill shadow-sm">Sign in</button>
                            </form>

                        </div>
                    </div>
                </div><!-- End -->

            </div>
        </div><!-- End -->

    </div>
</div>

</body>
</html>