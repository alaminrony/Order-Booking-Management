@extends('backEnd.layouts.master')
@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>@lang('lang.USER')</h1>
                </div>

                <div class="col-sm-6">
                    <div class="float-right mr-2">
                        <a type="button" class="btn btn-success openCreateModal" data-toggle="modal" title="@lang('lang.VIEW_ISSUE')" data-target="#viewCreateModal"><i class="fa fa-plus-square"></i> Create user</a>
                    </div>
                </div>
            </div>
            @include('backEnd.layouts.message')
        </div><!-- /.container-fluid -->
    </section>

    <section class="content">
        <div class="container-fluid">
            {!!Form::open(['route'=>'user.filter','method'=>'GET'])!!}
            <input type="hidden" name='filter' value="true">
            <div class="row">
                <div class="col-md-10 offset-md-1">
                    <div class="row">
                        <div class="col-3">
                            <div class="form-group">
                                <label>@lang('lang.USER'):</label>
                                {!!Form::select('user_id',$users,Request::get('user_id'),['class'=>'select2 form-control','id'=>'user_id','width'=>'100%']) !!}
                                @if($errors->has('user_id'))
                                <span class="text-danger">{{$errors->first('user_id')}}</span>
                                @endif
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="form-group">
                                <label>@lang('lang.ROLE'):</label>
                                {!!Form::select('role_id',$roles,Request::get('role_id'),['class'=>'select2 form-control','id'=>'role_id','width'=>'100%']) !!}
                                @if($errors->has('role_id'))
                                <span class="text-danger">{{$errors->first('role_id')}}</span>
                                @endif
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="form-group">
                                <label>@lang('lang.TEXT'):</label>
                                {!!Form::text('search_value',Request::get('search_value'),['class'=>'form-control','id'=>'search_value','width'=>'100%']) !!}
                                @if($errors->has('search_value'))
                                <span class="text-danger">{{$errors->first('search_value')}}</span>
                                @endif
                            </div>
                        </div>


                        <div class="col-3">
                            <div class="form-group">
                                <label></label>
                                <div class="input-group">
                                    <div class="float-right mt-2">
                                        <button type="submit" class="btn btn-warning" title="submit" ><i class="fa fa fa-search"></i> @lang('lang.SUBMIT')</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {!!Form::close()!!}
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">@lang('lang.USER_LIST')</h3>
                        </div>

                        <!-- /.card-header -->
                        <div class="card-body">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>SL</th>
                                        <th>@lang('lang.NAME')</th>
                                        <th>@lang('lang.EMAIL')</th>
                                        <th>@lang('lang.PHONE')</th>
                                        <th>@lang('lang.ADDRESS')</th>
                                        <th>@lang('lang.PHOTO')</th>
                                        <th>@lang('lang.ROLE')</th>
                                        <th>@lang('lang.CREATED_AT')</th>
                                        <th>@lang('lang.ACTION')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if($targets->isNotEmpty())
                                    <?php $i = 0; ?>
                                    @foreach($targets as $target)
                                    <?php $i++; ?>
                                    <tr>
                                        <td>{{$i}}</td>
                                        <td>{{$target->name}}</td>
                                        <td>{{$target->email}}</td>
                                        <td>{{$target->phone}}</td>
                                        <td>{{$target->address}}</td>
                                        <td width='10%'><img src="{{asset(!empty($target->profile_photo) ? $target->profile_photo :'backend/dist/img/img_avatar.png')}}" class="img-fluid"/></td>
                                        <td>{{$roles[$target->role_id]??''}}</td>
                                        <td>{{Helper::dateFormat($target->created_at)}}</td>
                                        <td width='18%'>
                                            <div style="float: left;margin-right:4px;">
                                                <a type="button" class="btn btn-success btn-sm openViewModal" data-toggle="modal" title="@lang('lang.VIEW_USER')" data-target="#viewModal" data-id="{{$target->id}}"><i class="fa fa-eye"></i></a>
                                                <a type="button" class="btn btn-warning btn-sm openEditModal" data-toggle="modal" title="@lang('lang.EDIT_ISSUE')" data-target="#viewEditModal" data-id="{{$target->id}}"><i class="fa fa-edit"></i></a>
                                            </div>
                                            <div style="float: left;">
                                                {!!Form::open(['route'=>['user.destroy',$target->id]])!!}
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm deleteBtn"><i class="fa fa-trash"></i></button>
                                                {!!Form::close()!!}
                                            </div> 
                                        </td>
                                    </tr>
                                    @endforeach
                                    @else
                                    <tr>No Data Found</tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                        <!-- /.card-body -->
                        <div class="card-footer clearfix">
                            <ul class="pagination pagination-sm m-0 float-right">
                                {!!$targets->withQueryString()->links('pagination::bootstrap-4')!!}
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
    </section>
</div>

<!--view  Modal -->
<div class="modal fade" id="viewModal" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog">
        <div id="viewModalShow">
        </div>
    </div>
</div>
<!--end view Modal -->
<!--create  Modal -->
<div class="modal fade" id="viewCreateModal" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog">
        <div id="CreateModalShow">
        </div>
    </div>
</div>
<!--end create Modal -->
<!--edit  Modal -->
<div class="modal fade" id="viewEditModal" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog">
        <div id="editModalShow">
        </div>
    </div>
</div>
<!--end view Modal -->
@endsection
@push('script')
<script type="text/javascript">
    $(document).ready(function () {
        $('.select2').select2();

        $(document).on('click', '.openCreateModal', function () {
            $.ajax({
                url: "{{route('user.create')}}",
                type: "post",
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (data) {
                    $('#CreateModalShow').html(data.data);
                }
            });
        });

        $(document).on('click', '#create', function () {
            var data = new FormData($('#createFormData')[0]);
            if (data != '') {
                $.ajax({
                    url: "{{route('user.store')}}",
                    data: data,
                    cache: false,
                    contentType: false,
                    processData: false,
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (data) {
                        $("#name_error").text('');
                        $("#role_id_error").text('');
                        $("#email_error").text('');
                        $("#phone_error").text('');
                        $("#password_error").text('');
                        $("#balance_error").text('');
                        $("#profile_photo_error").text('');
                        $("#address_error").text('');
                        if (data.errors) {
                            $("#name_error").text(data.errors.name);
                            $("#role_id_error").text(data.errors.role_id);
                            $("#email_error").text(data.errors.email);
                            $("#phone_error").text(data.errors.phone);
                            $("#password_error").text(data.errors.password);
                            $("#balance_error").text(data.errors.balance);
                            $("#profile_photo_error").text(data.errors.profile_photo);
                            $("#address_error").text(data.errors.address);
                        }
                        if (data.response == "success") {
                            setTimeout(function () {
                                location.reload();
                            }, 1000);
                            $('#viewCreateModal').modal('hide');
                            toastr.success("@lang('lang.USER_CREATED_SUCCESSFULLY')", 'Success', {timeOut: 5000});
//                            toastr["success"]("@lang('label.MEET_UP_HAS_BEEN_UPDATED_SUCCESSFULLY')");
                        }
                    }
                });
            }
        });

        $(document).on('click', '.openEditModal', function () {
            var id = $(this).attr('data-id');
            if (id != '') {
                $.ajax({
                    url: "{{route('user.edit')}}",
                    type: "post",
                    data: {id: id},
                    dataType: "json",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (data) {
                        $('#editModalShow').html(data.data);
                    }
                });
            }
        });

        $(document).on('click', '#update', function () {
            var data = new FormData($('#editFormData')[0]);
            if (data != '') {
                $.ajax({
                    url: "{{route('user.update')}}",
                    data: data,
                    cache: false,
                    contentType: false,
                    processData: false,
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (data) {
                        $("#name_error").text('');
                        $("#role_id_error").text('');
                        $("#email_error").text('');
                        $("#phone_error").text('');
                        $("#password_error").text('');
                        $("#profile_photo_error").text('');
                        $("#address_error").text('');
                        if (data.errors) {
                            $("#name_error").text(data.errors.name);
                            $("#role_id_error").text(data.errors.role_id);
                            $("#email_error").text(data.errors.email);
                            $("#phone_error").text(data.errors.phone);
                            $("#password_error").text(data.errors.password);
                            $("#profile_photo_error").text(data.errors.profile_photo);
                            $("#address_error").text(data.errors.address);
                        }
                        if (data.response == "success") {
                            setTimeout(function () {
                                location.reload();
                            }, 1000);
                            $('#viewEditModal').modal('hide');
                            toastr.success("@lang('lang.USER_UPDATED_SUCCESSFULLY')", 'Success', {timeOut: 5000});
//                            toastr["success"]("@lang('label.MEET_UP_HAS_BEEN_UPDATED_SUCCESSFULLY')");
                        }
                    }
                });
            }
        });
        
        $(document).on('click', '.openViewModal', function () {
            var id = $(this).attr('data-id');
            if (id != '') {
                $.ajax({
                    url: "{{route('user.view')}}",
                    type: "post",
                    data: {id: id},
                    dataType: "json",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (data) {
                        $('#viewModalShow').html(data.data);
                    }
                });
            }
        });


        $('.deleteBtn').on('click', function (e) {
            event.preventDefault();
            var form = $(this).closest('form');
            swal({
                title: "Are you sure?",
                text: "You want to delete this, you can't recover this data again.",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes, DELETE it!",
                cancelButtonText: "No, cancel please!",
                closeOnConfirm: false,
                closeOnCancel: false
            },
                    function (isConfirm) {
                        if (isConfirm) {
                            form.submit();
                        } else {
                            swal("Cancelled", "Your Record is safe :)", "error");

                        }
                    });
        });


    });
</script>
@endpush
