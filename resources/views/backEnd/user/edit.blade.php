<div class="modal-content">
    <div class="modal-header clone-modal-header bg-secondary">
        <h4 class="modal-title" id="exampleModalLabel"><i class="fa fa-edit"></i> {!!__('lang.EDIT_USER')!!}</h4>
    </div>
    <div class="modal-body">
        {!!Form::open(['id'=>'editFormData','class'=>'form-horizontal'])!!}
        <div class="card-body">
            {!!Form::hidden('id',$target->id)!!}
            <div class="form-group row">
                <label for="name" class="col-sm-4 col-form-label">@lang('lang.NAME') :</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" id="name" placeholder="Enter name" name="name" value="{{$target->name}}" />
                    <span class="text-danger" id="name_error"></span>
                </div>
            </div>

            <div class="form-group row">
                <label for="role_id" class="col-sm-4 col-form-label">@lang('lang.ROLE') :</label>
                <div class="col-sm-8">
                    {!!Form::select('role_id',$roles,$target->role_id,['class'=>'form-control'])!!}
                    <span class="text-danger" id="role_id_error"></span>
                </div>
            </div>

            <div class="form-group row">
                <label for="email" class="col-sm-4 col-form-label">@lang('lang.EMAIL') :</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" id="email" value="{{$target->email}}" placeholder="Enter email" name="email" />
                    <span class="text-danger" id="email_error"></span>
                </div>
            </div>
            <div class="form-group row">
                <label for="phone" class="col-sm-4 col-form-label">@lang('lang.PHONE') :</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" id="phone" value="{{$target->phone}}" placeholder="Enter phone" name="phone" />
                    <span class="text-danger" id="phone_error"></span>
                </div>
            </div>
            <div class="form-group row">
                <label for="password" class="col-sm-4 col-form-label">@lang('lang.PASSWORD') :</label>
                <div class="col-sm-8">
                    <input type="password" class="form-control" id="password" placeholder="Enter password" name="password" />
                    <span class="text-danger" id="password_error"></span>
                </div>
            </div>
            <div class="form-group row">
                <label for="profile_photo" class="col-sm-4 col-form-label">@lang('lang.PHOTO') :</label>
                <div class="col-sm-8">
                    <input type="file" class="form-control" id="profile_photo"  name="profile_photo" />
                    <span class="text-danger" id="profile_photo_error"></span>
                </div>
            </div>
            <div class="form-group row">
                <label for="profile_photo" class="col-sm-4 col-form-label"></label>
                <div class="col-sm-6">
                    <img src="{{asset($target->profile_photo)}}" class="img-fluid" style="height: 100px;">
                </div>
            </div>
            
           

            <div class="form-group row">
                <label for="address" class="col-sm-4 col-form-label">@lang('lang.ADDRESS') :</label>
                <div class="col-sm-8">
                    {!!Form::textarea('address',$target->address,['class'=>'form-control','id'=>'address','style'=>'height:100px;'])!!}
                    <span class="text-danger" id="address_error"></span>
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



