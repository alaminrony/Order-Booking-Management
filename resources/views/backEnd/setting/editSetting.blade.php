<div class="modal-content">
    <div class="modal-header clone-modal-header bg-secondary">
        <h4 class="modal-title" id="exampleModalLabel"><i class="fa fa-edit"></i> {!!__('lang.EDIT_SETTING')!!}</h4>
    </div>
    <div class="modal-body">
        {!!Form::open(['id'=>'editFormData','class'=>'form-horizontal'])!!}
        <div class="card-body">
            {!!Form::hidden('id',$target->id)!!}
            <div class="form-group row">
                <label for="role_name" class="col-sm-4 col-form-label">@lang('lang.KEY') :</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" id="key_name" placeholder="Enter Key" name="key_name" value="{{$target->key_name}}"/>
                    <span class="text-danger" id="key_name_error"></span>
                </div>
            </div>

            <div class="form-group row">
                <label for="role_name" class="col-sm-4 col-form-label">@lang('lang.VALUE') :</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" id="key_value" placeholder="Enter value" name="key_value" value="{{$target->key_value}}"/>
                    <span class="text-danger" id="key_value_error"></span>
                </div>
            </div>
            
            <div class="form-group row">
                <label for="profile_photo" class="col-sm-4 col-form-label">@lang('lang.PHOTO') :</label>
                <div class="col-sm-8">
                    <input type="file" class="form-control" id="image"  name="image" />
                    <span class="text-danger" id="image_error"></span>
                </div>
            </div>
            <div class="form-group row">
                <label for="profile_photo" class="col-sm-4 col-form-label"></label>
                <div class="col-sm-6">
                    <img src="{{asset($target->image)}}" class="img-fluid" style="height: 100px;">
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



