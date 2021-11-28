<div class="modal-content">
    <div class="modal-header clone-modal-header bg-secondary">
        <h4 class="modal-title" id="exampleModalLabel"><i class="fa fa-plus-square"></i> {!!__('lang.CREATE_ROLE')!!}</h4>
    </div>
    <div class="modal-body">
        <form class="form-horizontal" id="createFormData" method="POST">
            @csrf
            <div class="card-body">
                <div class="form-group row">
                    <label for="role_name" class="col-sm-4 col-form-label">@lang('lang.ROLE_NAME') :</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" id="role_name" placeholder="Enter Role" name="role_name" />
                        <span class="text-danger" id="role_name_error"></span>
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



