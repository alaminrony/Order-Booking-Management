<div class="modal-content">
    <div class="modal-header clone-modal-header bg-secondary">
        <h4 class="modal-title" id="exampleModalLabel"><i class="fa fa-plus-square"></i> {!!__('lang.CREATE_SETTING')!!}</h4>
    </div>
    <div class="modal-body">
        <form class="form-horizontal" id="createFormData" method="POST">
            @csrf
            <div class="card-body">
                <div class="form-group row">
                    <label for="role_name" class="col-sm-4 col-form-label">@lang('lang.KEY') :</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" id="key_name" placeholder="Enter Key" name="key_name" />
                        <span class="text-danger" id="key_name_error"></span>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="role_name" class="col-sm-4 col-form-label">@lang('lang.VALUE') :</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" id="key_value" placeholder="Enter value" name="key_value" />
                        <span class="text-danger" id="key_value_error"></span>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="image" class="col-sm-4 col-form-label">@lang('lang.PHOTO') :</label>
                    <div class="col-sm-8">
                        <input type="file" class="form-control" id="image"  name="image" />
                        <span class="text-danger" id="image_error"></span>
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



