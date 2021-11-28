<div class="modal-content">
    <div class="modal-header clone-modal-header bg-secondary">
        <h4 class="modal-title" id="exampleModalLabel"><i class="fa fa-plus-square"></i> {!!__('lang.CREATE_BUYER_VS_USERS')!!}</h4>
    </div>
    <div class="modal-body">
        <form class="form-horizontal" id="createFormData" method="POST">
            @csrf
            <div class="card-body">

                <div class="form-group row">
                    <label for="role_id" class="col-sm-4 col-form-label">@lang('lang.USER') :</label>
                    <div class="col-sm-8">
                        {!!Form::select('user_id',$users,'',['class'=>'select2 form-control','id'=>'user_id','width'=>'100%'])!!}
                        <span class="text-danger" id="user_id_error"></span>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="role_id" class="col-sm-4 col-form-label">@lang('lang.BUYER') :</label>
                    <div class="col-sm-8">
                        {!!Form::select('buyer_id',$buyers,'',['class'=>'select2 form-control','id'=>'buyer_id','width'=>'100%'])!!}
                        <span class="text-danger" id="buyer_id_error"></span>
                    </div>
                </div>

            </div>
    </div>
    <div class="modal-footer">
        <fieldset class="w-100">
            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            <button type="button" id="create" class="btn btn-secondary float-right" >Save</button>
        </fieldset>
    </div>
</form>
</div>



