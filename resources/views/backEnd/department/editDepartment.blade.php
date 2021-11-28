<div class="modal-content">
    <div class="modal-header clone-modal-header bg-secondary">
        <h4 class="modal-title" id="exampleModalLabel"><i class="fa fa-edit"></i> {!!__('lang.EDIT_DEPARTMENT')!!}</h4>
    </div>
    <div class="modal-body">
        {!!Form::open(['id'=>'editFormData','class'=>'form-horizontal'])!!}
        <div class="card-body">
            {!!Form::hidden('id',$target->id)!!}
            <div class="form-group row">
                <label for="dep_name" class="col-sm-4 col-form-label">@lang('lang.DEPARTMENT') :</label>
                <div class="col-sm-8">
                    {!!Form::text('dep_name',$target->dep_name,['class'=>'form-control'])!!}
                    <span class="text-danger" id="dep_name_error"></span>
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



