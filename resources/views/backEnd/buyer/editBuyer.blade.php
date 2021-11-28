<div class="modal-content">
    <div class="modal-header clone-modal-header bg-secondary">
        <h4 class="modal-title" id="exampleModalLabel"><i class="fa fa-edit"></i> {!!__('lang.EDIT_BUYER')!!}</h4>
    </div>
    <div class="modal-body">
        {!!Form::open(['id'=>'editFormData','class'=>'form-horizontal'])!!}
        <div class="card-body">
            {!!Form::hidden('id',$target->id)!!}
            <div class="form-group row">
                <label for="name" class="col-sm-4 col-form-label">@lang('lang.BUYER_NAME') :</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" id="name" placeholder="Enter Name" name="name" value="{{$target->name}}"/>
                    <span class="text-danger" id="name_error"></span>
                </div>
            </div>
            <div class="form-group row">
                <label for="phone" class="col-sm-4 col-form-label">@lang('lang.PHONE') :</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" id="phone" placeholder="Enter Phone" name="phone" value="{{$target->phone}}"/>
                    <span class="text-danger" id="phone_error"></span>
                </div>
            </div>
            <div class="form-group row">
                <label for="email" class="col-sm-4 col-form-label">@lang('lang.EMAIL') :</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" id="email" placeholder="Enter Email" name="email" value="{{$target->email}}"/>
                    <span class="text-danger" id="email_error"></span>
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



