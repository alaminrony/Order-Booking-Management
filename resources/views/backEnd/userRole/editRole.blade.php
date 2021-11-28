<div class="modal-content">
    <div class="modal-header clone-modal-header bg-secondary">
        <h4 class="modal-title" id="exampleModalLabel"><i class="fa fa-edit"></i> {!!__('lang.EDIT_ROLE')!!}</h4>
    </div>
    <div class="modal-body">
        {!!Form::open(['id'=>'editFormData','class'=>'form-horizontal'])!!}
        <div class="card-body">
            {!!Form::hidden('id',$target->id)!!}
            <div class="form-group row">
                <label for="roleName" class="col-sm-4 col-form-label">@lang('lang.ROLE_NAME') :</label>
                <div class="col-sm-8">
                    {!!Form::text('role_name',$target->role_name,['class'=>'form-control','id'=>'roleName'])!!}
                    <span class="text-danger" id="issue_title_error"></span>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <fieldset class="w-100">
            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            <button type="button" id="update" class="btn btn-secondary float-right" >Save</button>
        </fieldset>
    </div>
    {!!Form::close()!!}
</div>



