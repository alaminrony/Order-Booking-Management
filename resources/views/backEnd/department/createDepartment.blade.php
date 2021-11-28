<div class="modal-content">
    <div class="modal-header clone-modal-header bg-secondary">
        <h4 class="modal-title" id="exampleModalLabel"><i class="fa fa-plus-square"></i> {!!__('lang.CREATE_DEPARTMENT')!!}</h4>
    </div>
    <div class="modal-body">
        <form class="form-horizontal" id="createFormData" method="POST">
            @csrf
            <div class="card-body">
                <div class="form-group row">
                    <label for="dep_name" class="col-sm-4 col-form-label">@lang('lang.DEPARTMENT') :</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" id="dep_name" placeholder="Enter Department" name="dep_name" required/>
                        <span class="text-danger" id="dep_name_error"></span>
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



