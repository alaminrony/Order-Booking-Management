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
            <div class="card card-outline card-primary">
                <div class="card-header text-center">
                    <a href="{{route('admin.dashboard')}}" class="h1"><b>{{$settingArr['company_name']??''}}</a>
                </div>
                @if ($message = Session::get('error'))
                <div class="alert alert-danger alert-block text-center ml-2 mr-2">
                    <button type="button" class="close" data-dismiss="alert">×</button>
                    <strong>{{ $message }}</strong>
                </div>
                @endif
                @if ($message = Session::get('success'))
                <div class="alert alert-success alert-block text-center ml-2 mr-2">
                    <button type="button" class="close" data-dismiss="alert">×</button>
                    <strong>{{ $message }}</strong>
                </div>
                @endif
                <div class="card-body">
                    <p class="login-box-msg">You forgot your password? Here you can easily retrieve a new password.</p>
                    <form action="{{route('recoveryPassword')}}" method="post">
                        @csrf
                        <div class="input-group mb-3">
                            <input type="email" class="form-control" placeholder="Email" name="email">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-envelope"></span>
                                </div>
                            </div>
                        </div>
                        @if($errors->has('email'))
                        <span class="text-danger">{{$errors->first('email')}}</span>
                        @endif
                        <div class="row">
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary btn-block">Request new password</button>
                            </div>
                            <!-- /.col -->
                        </div>
                    </form>
                    <p class="mt-3 mb-1">
                        <a href="{{route('login')}}">Login</a>
                    </p>
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
