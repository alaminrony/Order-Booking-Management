<div class="modal-content">
    <div class="modal-header clone-modal-header bg-secondary">
        <h4 class="modal-title" id="exampleModalLabel"><i class="fa fa-eye"></i> {!!__('lang.VIEW_USER')!!}</h4>
    </div>
    <div class="modal-body">
        <div class="card-body">
            <div>
                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <td><strong>@lang('lang.NAME')</strong> </td>
                            <td>{{$target->name}}</td>
                        </tr>
                        <tr>
                            <td><strong>@lang('lang.ROLE')</strong> </td>
                            <td>{{$roles[$target->role_id]}}</td>
                        </tr>
                        <tr>
                            <td><strong>@lang('lang.EMAIL')</strong> </td>
                            <td>{{$target->email}}</td>
                        </tr>
                        <tr>
                            <td><strong>@lang('lang.PHONE')</strong> </td>
                            <td>{{$target->phone}}</td>
                        </tr>
                        <tr>
                            <td><strong>@lang('lang.PHOTO')</strong> </td>
                            <td><img src="{{asset($target->profile_photo)}}" class="img-fluid" style="height: 100px;"></td>
                        </tr>
                        
                        <tr>
                            <td><strong>@lang('lang.ADDRESS')</strong> </td>
                            <td>{{$target->address}}</td>
                        </tr>
                        <tr>
                            <td><strong>@lang('lang.CREATED_AT')</strong> </td>
                            <td>{{Helper::dateFormat($target->created_at)}}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <fieldset class="w-100">
            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        </fieldset>
    </div>
    {!!Form::close()!!}
</div>



