@extends('backEnd.layouts.master')
@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>@lang('lang.DEPARTMENT')</h1>
                </div>
                <div class="col-sm-6">
                    <div class="float-right mr-2">
                        <a type="button" class="btn btn-success openCreateModal" data-toggle="modal" title="@lang('lang.CREATE_DEPARTMENT')" data-target="#viewCreateModal"><i class="fa fa-plus-square"></i> @lang('lang.CREATE_DEPARTMENT')</a>
                    </div>
                </div>
            </div>
            @include('backEnd.layouts.message')
        </div><!-- /.container-fluid -->
    </section>

    <section class="content">
        <div class="container-fluid">
            {!!Form::open(['route'=>'department.filter','method'=>'GET'])!!}
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
                    <div class="card card-secondary">
                        <div class="card-header">
                            <h3 class="card-title">@lang('lang.DEPARTMENT_LIST')</h3>
                        </div>

                        <!-- /.card-header -->
                        <div class="card-body">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>SL</th>
                                        <th>@lang('lang.DEPARTMENT')</th>
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
                                        <td>{{$target->dep_name}}</td>
                                        <td>{{Helper::dateFormat($target->created_at)}}</td>
                                        <td>
                                            <div style="float: left;margin-right:4px;">
                                                <a type="button" class="btn btn-warning openEditModal" data-toggle="modal" title="@lang('lang.EDIT_DEPARTMENT')" data-target="#viewEditModal" data-id="{{$target->id}}"><i class="fa fa-edit"></i></a>
                                            </div>
                                            <div style="float: left;">
                                                {!!Form::open(['route'=>['department.destroy',$target->id]])!!}
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
        $(document).on('click', '.openCreateModal', function () {
            $.ajax({
                url: "{{route('department.create')}}",
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
                    url: "{{route('department.store')}}",
                    data: data,
                    cache: false,
                    contentType: false,
                    processData: false,
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (data) {
                        $("#dep_name_error").text('');
                        if (data.errors) {
                            $("#dep_name_error").text(data.errors.issue_title);
                        }
                        if (data.response == "success") {
                            setTimeout(function () {
                                location.reload();
                            }, 1000);
                            $('#viewCreateModal').modal('hide');
                            toastr.success("@lang('lang.DEPARTMENT_CREATED_SUCCESSFULLY')", 'Success', {timeOut: 5000});
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
                    url: "{{route('department.edit')}}",
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
                    url: "{{route('department.update')}}",
                    data: data,
                    cache: false,
                    contentType: false,
                    processData: false,
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (data) {
                        $("#issue_title_error").text('');
                        $("#transaction_type_error").text('');
                        if (data.errors) {
                            $("#issue_title_error").text(data.errors.issue_title);
                            $("#transaction_type_error").text(data.errors.transaction_type);
                        }
                        if (data.response == "success") {
                            setTimeout(function () {
                                location.reload();
                            }, 1000);
                            $('#viewEditModal').modal('hide');
                            toastr.success("@lang('lang.ISSUE_UPDATED_SUCCESSFULLY')", 'Success', {timeOut: 5000});
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
