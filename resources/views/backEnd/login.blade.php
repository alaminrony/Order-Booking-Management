<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>
            @if(!empty($data['title']))
            {{ $settingArr['company_name']??'Rafiq & Sons' }} | {{ $data['title'] }}
            @else
            {{ $settingArr['company_name']??'Rafiq & Sons' }}
            @endif
        </title>

        <!-- Google Font: Source Sans Pro -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
        <!-- Font Awesome -->
        <link rel="stylesheet" href="{{asset('backend/plugins/fontawesome-free/css/all.min.css')}}">
        <!-- icheck bootstrap -->
        <link rel="stylesheet" href="{{asset('backend/plugins/icheck-bootstrap/icheck-bootstrap.min.css')}}">
        <!-- Theme style -->
        <link rel="stylesheet" href="{{asset('backend/dist/css/adminlte.min.css')}}">
    </head>
    <body class="hold-transition login-page">
        <div class="login-box">
            <div class="login-logo">
                <a href="{{route('admin.dashboard')}}"><b>{{$settingArr['company_name']??''}}</b></a>
            </div>
            <!-- /.login-logo -->
            <div class="card">
                <div class="card-body login-card-body">
                    <p class="login-box-msg">Sign in to start your session</p>
                    @if ($message = Session::get('error'))
                    <div class="alert alert-danger alert-block">
                        <button type="button" class="close" data-dismiss="alert">×</button>
                        <strong>{{ $message }}</strong>
                    </div>
                    @endif
                    {!!Form::open(['route'=>['login']])!!}
                    <div class="input-group mb-3">
                        {!!Form::text('email','',['class'=>'form-control','placeholder'=>'Enter your email'])!!}
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-envelope"></span>
                            </div>
                        </div>
                    </div>
                    @if($errors->has('email'))
                    <span class="text-danger">{{$errors->first('email')}}</span>
                    @endif
                    <div class="input-group mb-3">
                        {!!Form::password('password',['class'=>'form-control','placeholder'=>'Enter your password'])!!}
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                    </div>
                    @if($errors->has('password'))
                    <span class="text-danger">{{$errors->first('password')}}</span>
                    @endif
                    <div class="row">
                        <div class="col-8">
                            <div class="icheck-primary">
                                <input type="checkbox" id="remember">
                                <label for="remember">
                                    Remember Me
                                </label>
                            </div>
                        </div>
                        <!-- /.col -->
                        <div class="col-4">
                            <button type="submit" class="btn btn-primary btn-block">Sign In</button>
                        </div>
                        <!-- /.col -->
                    </div>
                    {!!Form::close()!!}

                    <!--                    <div class="social-auth-links text-center mb-3">
                                            <p>- OR -</p>
                                            <a href="#" class="btn btn-block btn-primary">
                                                <i class="fab fa-facebook mr-2"></i> Sign in using Facebook
                                            </a>
                                            <a href="#" class="btn btn-block btn-danger">
                                                <i class="fab fa-google-plus mr-2"></i> Sign in using Google+
                                            </a>
                                        </div>-->
                    <!-- /.social-auth-links -->

                    <p class="mb-1">
                        <a href="{{route('forgetPass')}}">I forgot my password</a>
                    </p>
                    <!--<p class="mb-0">-->
                    <!--    <a href="register.html" class="text-center">Register a new membership</a>-->
                    <!--</p>-->
                </div>
                <!-- /.login-card-body -->
            </div>
        </div>
        <!-- /.login-box -->

        <!-- jQuery -->
        <script src="{{asset('backend/plugins/jquery/jquery.min.js')}}"></script>
        <!-- Bootstrap 4 -->
        <script src="{{asset('backend/plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
        <!-- AdminLTE App -->
        <script src="{{asset('backend/dist/js/adminlte.min.js')}}"></script>
    </body>
</html>
