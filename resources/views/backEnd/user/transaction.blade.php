@extends('backEnd.layouts.master')
@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>@lang('lang.TRANSACTION_LIST') of {{$users[$user_id ]}}</h1>
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
                            <h3 class="card-title">@lang('lang.TRANSACTION_LIST')</h3>
                            <div class="float-right">
                                <a href="{{url('admin/user/'.$user_id.'/transaction?view=print')}}" class="btn btn-primary"  title="@lang('lang.PRINT')"><i class="fa fa-print"></i></a>
                                <a href="{{url('admin/user/'.$user_id.'/transaction?view=pdf')}}" class="btn btn-warning"  title="@lang('lang.PDF')"><i class="fa fa-file-pdf"></i></a>
                                <a href="{{url('admin/user/'.$user_id.'/transaction?view=excel')}}" class="btn btn-success"  title="@lang('lang.EXCEL')"><i class="fa fa-file-excel"></i></a>
                            </div>
                        </div>

                        <!-- /.card-header -->
                        <div class="card-body">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>SL</th>
                                        <th>@lang('lang.CREATED_AT')</th>
                                        <th>@lang('lang.TRANSACTION_TYPE')</th>
                                        <th>@lang('lang.PAYMENT_MODE')</th>
                                        <th>@lang('lang.CHEQUE_NO')</th>
                                        <th>@lang('lang.ISSUE')</th>
                                        <th>@lang('lang.BANK_INFO')</th>
                                        <th>@lang('lang.AMOUNT')</th>
                                        <th>@lang('lang.BALANCE')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if($targets->isNotEmpty())
                                    <?php
                                    $i = 0;
                                    $balance = 0;
                                    ?>
                                    @foreach($targets as $target)
                                    <?php $i++;
                                    ?>
                                    <tr>
                                        <td>{{$i}}</td>
                                        <td>{{Helper::dateFormat($target->created_at)}}</td>
                                        <td>Cash {{$target->transaction_type}}</td>
                                        <td>{{$target->payment_mode}}</td>
                                        <td>{{$target->cheque_no}}</td>
                                        <td>{{$issues[$target->issue_id]}}</td>
                                        <td width='15%'>{{$bankAccountArr[$target->bank_account_id]??''}}</td>
                                        <td>{{$target->amount}}</td>
                                        <?php
                                        if ($target->transaction_type == 'out') {
                                            $balance += $target->amount;
                                        }else{
                                            $balance -= $target->amount;
                                        }
                                        ?>
                                        <td>{{$balance}}</td>
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
                            <ul class="pagination pagination-sm m-0 float-left">
                                <a href="{{route('user.index')}}" class="btn btn-primary" data-dismiss="modal">Back</a>
                            </ul>
                            <ul class="pagination pagination-sm m-0 float-right">
                                {!!$targets->links('pagination::bootstrap-4')!!}
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
    </section>
</div>
@endsection
@push('script')
<script type="text/javascript">
    $(document).ready(function () {
        $('.select2').select2();
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
</script>
@endpush
