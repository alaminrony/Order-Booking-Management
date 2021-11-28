@extends('backEnd.layouts.master')
@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Order Booking</h1>
                </div>
                <div class="col-sm-6">
                    <div class="float-right mr-2">
                        <a type="button" class="btn btn-success openCreateModal" data-toggle="modal" title="@lang('lang.CREATE_DEPARTMENT_VS_USERS')" data-target="#viewCreateModal"><i class="fa fa-plus-square"></i> Create Order Booking</a>
                    </div>
                </div>
            </div>
            @include('backEnd.layouts.message')
        </div><!-- /.container-fluid -->
    </section>



    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-secondary">
                        <div class="card-header">
                            <h3 class="card-title">Order Booking</h3>
                        </div>

                        <!-- /.card-header -->
                        <div class="card-body">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>SL</th>
                                        <th>@lang('lang.USER')</th>
                                        <th>@lang('lang.BUYER')</th>
                                        <th>@lang('lang.BUYER_DEPT')</th>
                                        <th>@lang('lang.STYLE_NUM')</th>
                                        <th>@lang('lang.QTY')</th>
                                        <th>@lang('lang.DELIVERY_DATE')</th>
                                        <th>@lang('lang.FAB_SUPP')</th>
                                        <th>@lang('lang.WASH_PRICE')</th>
                                        <th>@lang('lang.FOB')</th>
                                        <th>@lang('lang.CM')</th>
                                        <th>@lang('lang.CMP')</th>
                                        <th>@lang('lang.CREATED_AT')</th>
                                        <th>@lang('lang.PHOTO_1')</th>
                                        <th>@lang('lang.PHOTO_2')</th>
                                        <th>@lang('lang.COST_SHEET')</th>
                                        <th>@lang('lang.SMV')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if($targets->isNotEmpty())
                                    <?php $i = 0; ?>
                                    @foreach($targets as $target)
                                    <?php
                                    $i++;
                                    ?>
                                    <tr>
                                        <td>{{$i}}</td>
                                        <td>{{$users[$target->user_id]?? ''}}</td>
                                        <td>{{$buyers[$target->buyer_id]?? ''}}</td>
                                        <td>{{$target->buyer_dept?? ''}}</td>
                                        <td>{{$target->style_number?? ''}}</td>
                                        <td>{{$target->quantity?? ''}}</td>
                                        <td>{{date('d F Y',strtotime($target->delivery_date))}}</td>
                                        <td>{{$target->fabric_supplier?? ''}}</td>
                                        <td>{{$target->wash_price?? ''}}</td>
                                        <td>{{$target->fob ?? ''}}</td>
                                        <td>{{$target->cm ?? ''}}</td>
                                        <td>{{$target->cmp ?? ''}}</td>
                                        <td>{{Helper::dateFormat($target->created_at)}}</td>
                                        <td width='5%'>
                                            <?php
                                            foreach ($target->attachments as $attachments) {
                                                if ($attachments->attach_type == 'image' && $attachments->file_type == 'p_photo1') {
                                                    ?>
                                                    <a class="link-photo img-fluid" data-lightbox="img" href="{{asset('public/'.$attachments->file_name) }}" title="view photo one">
                                                        <img class="img-fluid" src="{{asset('public/'.$attachments->file_name) }}" alt="demo">
                                                    </a>
                                                    <?php
                                                }
                                            }
                                            ?>
                                        </td>
                                        <td width='5%'>
                                            <?php
                                            foreach ($target->attachments as $attachments) {
                                                if ($attachments->attach_type == 'image' && $attachments->file_type == 'p_photo2') {
                                                    ?>
                                                    <a class="link-photo img-fluid" data-lightbox="img" href="{{asset('public/'.$attachments->file_name) }}" title="view photo two" >
                                                        <img class="img-fluid" src="{{asset('public/'.$attachments->file_name) }}" alt="demo">
                                                    </a>
                                                    <?php
                                                }
                                            }
                                            ?>
                                        </td>
                                        <td width='5%'>
                                            <?php
                                            foreach ($target->attachments as $attachments) {
                                                if ($attachments->attach_type == 'file' && $attachments->file_type == 'cost_sheet') {
                                                    ?>
                                                    <a class="btn btn-warning" data-lightbox="img" href="{{asset('public/'.$attachments->file_name) }}" title="Download Cost sheet" download>
                                                        <i class="fa fa-file"></i>
                                                    </a>
                                                    <?php
                                                }
                                            }
                                            ?>
                                        </td>
                                        <td width='5%'> 
                                            <?php
                                            foreach ($target->attachments as $attachments) {
                                                if ($attachments->attach_type == 'file' && $attachments->file_type == 'smv') {
                                                    ?>
                                                    <a class="btn btn-warning" data-lightbox="img" href="{{asset('public/'.$attachments->file_name) }}" title="Download SMV" download>
                                                        <i class="fa fa-file"></i>
                                                    </a>
                                                    <?php
                                                }
                                            }
                                            ?>
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
                url: "{{route('orderBooking.create')}}",
                type: "post",
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (data) {
                    $('#CreateModalShow').html(data.data);
                    $('.select2').select2();
                }
            });
        });

        $(document).on('click', '#create', function () {
            var data = new FormData($('#createFormData')[0]);
            if (data != '') {
                $.ajax({
                    url: "{{route('orderBooking.store')}}",
                    data: data,
                    cache: false,
                    contentType: false,
                    processData: false,
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (data) {
                        $("#picture1_error").text('');
                        $("#buyer_id_error").text('');
                        $("#buyer_dept_error").text('');
                        $("#style_number_error").text('');
                        $("#quantity_error").text('');
                        $("#delivery_date_error").text('');
                        $("#fabric_supplier_error").text('');
                        $("#wash_price_error").text('');
                        $("#fob_error").text('');
                        $("#cm_error").text('');
                        $("#cmp_error").text('');
                        if (data.errors) {
                            $("#picture1_error").text(data.errors.picture1);
                            $("#buyer_id_error").text(data.errors.buyer_id);
                            $("#buyer_dept_error").text(data.errors.buyer_dept);
                            $("#style_number_error").text(data.errors.style_number);
                            $("#quantity_error").text(data.errors.quantity);
                            $("#delivery_date_error").text(data.errors.delivery_date);
                            $("#fabric_supplier_error").text(data.errors.fabric_supplier);
                            $("#wash_price_error").text(data.errors.wash_price);
                            $("#fob_error").text(data.errors.fob);
                            $("#cm_error").text(data.errors.cm);
                            $("#cmp_error").text(data.errors.cmp);
                        }
                        if (data.response == "success") {
                            setTimeout(function () {
                                location.reload();
                            }, 1000);
                            $('#viewCreateModal').modal('hide');
                            toastr.success("@lang('lang.DEPARTMENT_VS_USERS_CREATED_SUCCESSFULLY')", 'Success', {timeOut: 5000});
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
                    url: "{{route('departmentVsUsers.edit')}}",
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
                    url: "{{route('departmentVsUsers.update')}}",
                    data: data,
                    cache: false,
                    contentType: false,
                    processData: false,
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (data) {
                        $("#department_id_error").text('');
                        $("#user_id_error").text('');
                        if (data.errors) {
                            $("#department_id_error").text(data.errors.department_id);
                            $("#user_id_error").text(data.errors.user_id);
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

    $(document).ready(function () {

        const lb = lightbox();

    });
</script>
@endpush
