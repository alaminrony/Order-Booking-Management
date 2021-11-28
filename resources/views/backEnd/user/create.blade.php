<div class="modal-content">
    <div class="modal-header clone-modal-header bg-secondary">
        <h4 class="modal-title" id="exampleModalLabel"><i class="fa fa-plus-square"></i> {!!__('lang.CREATE_USER')!!}</h4>
    </div>
    <div class="modal-body">
        {!!Form::open(['id'=>'createFormData','class'=>'form-horizontal','files'=>'true'])!!}
        <div class="card-body">
            <div class="form-group row">
                <label for="name" class="col-sm-4 col-form-label">@lang('lang.NAME') :</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" id="name" placeholder="Enter name" name="name" />
                    <span class="text-danger" id="name_error"></span>
                </div>
            </div>

            <div class="form-group row">
                <label for="role_id" class="col-sm-4 col-form-label">@lang('lang.ROLE') :</label>
                <div class="col-sm-8">
                    {!!Form::select('role_id',$roles,'',['class'=>'form-control'])!!}
                    <span class="text-danger" id="role_id_error"></span>
                </div>
            </div>

            <div class="form-group row">
                <label for="email" class="col-sm-4 col-form-label">@lang('lang.EMAIL') :</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" id="email" placeholder="Enter email" name="email" />
                    <span class="text-danger" id="email_error"></span>
                </div>
            </div>
            <div class="form-group row">
                <label for="phone" class="col-sm-4 col-form-label">@lang('lang.PHONE') :</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" id="phone" placeholder="Enter phone" name="phone" />
                    <span class="text-danger" id="phone_error"></span>
                </div>
            </div>
            <div class="form-group row">
                <label for="password" class="col-sm-4 col-form-label">@lang('lang.PASSWORD') :</label>
                <div class="input-group col-sm-8">
                    <input type="password" class="form-control" id="password" placeholder="Enter password" name="password" value="12345678"/>
                    <div class="input-group-append">
                        <!--<span  class="input-group-text bg-secondary fa fa-fw fa-eye field_icon toggle-password"></span>-->
                        <span toggle="#password-field"  class="input-group-text bg-secondary toggle-password" title="@lang('lang.HIDE_SHOW_PASS')"><i class="fa fa-eye-slash" id="hideShowIcon"></i></span>
                    </div> 
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
                <label for="address" class="col-sm-4 col-form-label">@lang('lang.ADDRESS') :</label>
                <div class="col-sm-8">
                    {!!Form::textarea('address','',['class'=>'form-control','id'=>'address','style'=>'height:100px;'])!!}
                    <span class="text-danger" id="address_error"></span>
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
    {!!Form::close()!!}
</div>

<script type="text/javascript">

    $(document).on('click', '.toggle-password', function () {
        $('#hideShowIcon').toggleClass("fa-eye fa-eye-slash");
        var passType = $("#password");
        passType.attr('type') === 'password' ? passType.attr('type', 'text') : passType.attr('type', 'password');
    });
</script>



