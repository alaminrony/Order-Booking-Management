@extends('backEnd.layouts.master')
@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>@lang('lang.SETTING')</h1>
                </div>
                <div class="col-sm-6">
                    <div class="float-right mr-2">
                        <a type="button" class="btn btn-success openCreateModal" data-toggle="modal" title="@lang('lang.VIEW_ISSUE')" data-target="#viewCreateModal"><i class="fa fa-plus-square"></i> @lang('lang.CREATE_SETTING')</a>
                    </div>
                </div>
            </div>
            @include('backEnd.layouts.message')
        </div><!-- /.container-fluid -->
    </section>

    <section class="content">
        <div class="container-fluid">
            {!!Form::open(['route'=>'setting.filter','method'=>'GET'])!!}
            <input type="hidden" name='filter' value="true">
            <div class="row">
                <div class="col-md-10 offset-md-1">
                    <div class="row">
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
                            <h3 class="card-title">@lang('lang.SETTING_LIST')</h3>
                        </div>

                        <!-- /.card-header -->
                        <div class="card-body">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>SL</th>
                                        <th>@lang('lang.KEY')</th>
                                        <th>@lang('lang.VALUE')</th>
                                        <th>@lang('lang.IMAGE')</th>
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
                                        <td>{{$target->key_name}}</td>
                                        <td>{{$target->key_value}}</td>
                                        <td width='10%'>
                                            @if(!empty($target->image))
                                             <img src="{{asset($target->image)}}" class="img-fluid" alt="no image"/>
                                            @else
                                            {{''}}
                                             @endif
                                           
                                        </td>
                                        <td>{{Helper::dateFormat($target->created_at)}}</td>
                                        <td>
                                            <div style="float: left;margin-right:4px;">
                                                <a type="button" class="btn btn-warning openEditModal" data-toggle="modal" title="@lang('lang.EDIT_ISSUE')" data-target="#viewEditModal" data-id="{{$target->id}}"><i class="fa fa-edit"></i></a>
                                            </div>
                                            <div style="float: left;">
                                                {!!Form::open(['route'=>['setting.destroy',$target->id]])!!}
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger deleteBtn"><i class="fa fa-trash"></i></button>
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
                                {!!$targets->links('pagination::bootstrap-4')!!}
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
    </section>
</div>

<!--view contact Number Modal -->
<div class="modal fade" id="viewCreateModal" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog">
        <div id="CreateModalShow">
        </div>
    </div>
</div>
<!--end view Modal -->
<!--view contact Number Modal -->
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
                url: "{{route('setting.create')}}",
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
                    url: "{{route('setting.store')}}",
                    data: data,
                    cache: false,
                    contentType: false,
                    processData: false,
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (data) {
                        $("#key_name_error").text('');
                        $("#key_value_error").text('');
                        if (data.errors) {
                            $("#key_name_error").text(data.errors.key_name);
                            $("#key_value_error").text(data.errors.key_value);
                        }
                        if (data.response == "success") {
                            setTimeout(function () {
                                location.reload();
                            }, 1000);
                            $('#viewCreateModal').modal('hide');
                            toastr.success("@lang('lang.SETTING_CREATED_SUCCESSFULLY')", 'Success', {timeOut: 5000});
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
                    url: "{{route('setting.edit')}}",
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
                    url: "{{route('setting.update')}}",
                    data: data,
                    cache: false,
                    contentType: false,
                    processData: false,
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (data) {
                        $("#key_name_error").text('');
                        $("#key_value_error").text('');
                        if (data.errors) {
                            $("#key_name_error").text(data.errors.key_name);
                            $("#key_value_error").text(data.errors.key_value);
                        }
                        if (data.response == "success") {
                            setTimeout(function () {
                                location.reload();
                            }, 1000);
                            $('#viewEditModal').modal('hide');
                            toastr.success("@lang('lang.SETTING_UPDATED_SUCCESSFULLY')", 'Success', {timeOut: 5000});
//                            toastr["success"]("@lang('label.MEET_UP_HAS_BEEN_UPDATED_SUCCESSFULLY')");
                        }
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
