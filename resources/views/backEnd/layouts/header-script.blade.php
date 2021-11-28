<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
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
        <!-- Ionicons -->
        <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
        <!-- Tempusdominus Bootstrap 4 -->
        <link rel="stylesheet" href="{{asset('backend/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css')}}">
        <!-- iCheck -->
        <link rel="stylesheet" href="{{asset('backend/plugins/icheck-bootstrap/icheck-bootstrap.min.css')}}">
        <!-- JQVMap -->
        <link rel="stylesheet" href="{{asset('backend/plugins/jqvmap/jqvmap.min.css')}}">
        <!-- Theme style -->
        <link rel="stylesheet" href="{{asset('backend/dist/css/adminlte.min.css')}}">
        <!-- overlayScrollbars -->
        <link rel="stylesheet" href="{{asset('backend/plugins/overlayScrollbars/css/OverlayScrollbars.min.css')}}">
        <!-- Daterange picker -->
        <link rel="stylesheet" href="{{asset('backend/plugins/daterangepicker/daterangepicker.css')}}">
        <!-- summernote -->
        <link rel="stylesheet" href="{{asset('backend/plugins/summernote/summernote-bs4.min.css')}}">
        <link rel="stylesheet" href="{{asset('backend/dist/css/sweetalert.min.css')}}">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.0.1/css/toastr.css" rel="stylesheet"/>
        <link rel="stylesheet" href="{{asset('backend/plugins/select2/css/select2.min.css')}}">
        <!--<link rel="stylesheet" href="{{asset('backend/dist/css/light-main.css')}}">-->
        <link rel="stylesheet" href="{{asset('backend/dist/css/lightbox.min.css')}}">
        @stack('css')
    </head>
