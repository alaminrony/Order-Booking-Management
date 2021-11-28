<div class="modal-content">
    <div class="modal-header clone-modal-header bg-secondary">
        <h4 class="modal-title" id="exampleModalLabel"><i class="fa fa-edit"></i> {!!__('lang.EDIT_BUYER_VS_USERS')!!}</h4>
    </div>
    <div class="modal-body">
        {!!Form::open(['id'=>'editFormData','class'=>'form-horizontal'])!!}
        <div class="card-body">
            {!!Form::hidden('id',$target->id)!!}
            <div class="form-group row">
                <label for="role_id" class="col-sm-4 col-form-label">@lang('lang.USER') :</label>
                <div class="col-sm-8">
                    {!!Form::select('user_id',$users,$target->user_id,['class'=>'select2 form-control','id'=>'user_id','width'=>'100%'])!!}
                    <span class="text-danger" id="user_id_error"></span>
                </div>
            </div>

            <div class="form-group row">
                <label for="role_id" class="col-sm-4 col-form-label">@lang('lang.BUYER') :</label>
                <div class="col-sm-8">
                    {!!Form::select('buyer_id',$buyers,$target->buyer_id,['class'=>'select2 form-control','id'=>'buyer_id','width'=>'100%'])!!}
                    <span class="text-danger" id="buyer_id_error"></span>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <fieldset class="w-100">
            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            <button type="button" id="update" class="btn btn-secondary float-right" >Update</button>
        </fieldset>
    </div>
    {!!Form::close()!!}
</div>



