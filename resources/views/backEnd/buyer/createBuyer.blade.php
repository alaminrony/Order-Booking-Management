<div class="modal-content">
    <div class="modal-header clone-modal-header bg-secondary">
        <h4 class="modal-title" id="exampleModalLabel"><i class="fa fa-plus-square"></i> {!!__('lang.CREATE_BUYER')!!}</h4>
    </div>
    <div class="modal-body">
        <form class="form-horizontal" id="createFormData" method="POST">
            @csrf
            <div class="card-body">
                <div class="form-group row">
                    <label for="name" class="col-sm-4 col-form-label">@lang('lang.BUYER_NAME') :</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" id="name" placeholder="Enter Name" name="name" required/>
                        <span class="text-danger" id="name_error"></span>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="phone" class="col-sm-4 col-form-label">@lang('lang.PHONE') :</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" id="phone" placeholder="Enter Phone" name="phone"/>
                        <span class="text-danger" id="phone_error"></span>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="email" class="col-sm-4 col-form-label">@lang('lang.EMAIL') :</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" id="email" placeholder="Enter Email" name="email"/>
                        <span class="text-danger" id="email_error"></span>
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



