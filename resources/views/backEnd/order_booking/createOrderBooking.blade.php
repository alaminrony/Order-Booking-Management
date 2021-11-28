<div class="modal-content">
    <div class="modal-header clone-modal-header bg-secondary">
        <h4 class="modal-title" id="exampleModalLabel"><i class="fa fa-plus-square"></i> Create Order Booking</h4>
    </div>
    <div class="modal-body">
        {!!Form::open(['id'=>'createFormData','class'=>'form-horizontal','files'=>'true'])!!}
        @csrf
        <div class="card-body">
            <div class="form-group row">
                <label for="profile_photo" class="col-sm-4 col-form-label">Picture 1 :</label>
                <div class="col-sm-8">
                    <input type="file" class="form-control" id="picture1" name="picture1"/>
                    <span class="text-danger" id="picture1_error"></span>
                </div>
            </div>
            <div class="form-group row">
                <label for="profile_photo" class="col-sm-4 col-form-label">Picture 2 :</label>
                <div class="col-sm-8">
                    <input type="file" class="form-control" id="picture2" name="picture2"/>
                    <span class="text-danger" id="picture2_error"></span>
                </div>
            </div>
            <div class="form-group row">
                <label for="name" class="col-sm-4 col-form-label">Style No :</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" id="style_number" placeholder="Enter Style No"
                           name="style_number"/>
                    <span class="text-danger" id="style_number_error"></span>
                </div>
            </div>
            <div class="form-group row">
                <label for="role_id" class="col-sm-4 col-form-label">@lang('lang.BUYER') :</label>
                <div class="col-sm-8">
                    {!!Form::select('buyer_id',$buyers,'',['class'=>'select2 form-control','id'=>'buyer_id','width'=>'100%'])!!}
                    <span class="text-danger" id="buyer_id_error"></span>
                </div>
            </div>
            <div class="form-group row">
                <label for="role_id" class="col-sm-4 col-form-label">Buyer Dept. :</label>
                <div class="col-sm-8">
                    {!!Form::select('buyer_dept',$buyerDepArr,'',['class'=>'select2 form-control','id'=>'buyer_dept','width'=>'100%'])!!}
                    <span class="text-danger" id="buyer_dept_error"></span>
                </div>
            </div>
            <div class="form-group row">
                <label for="name" class="col-sm-4 col-form-label">Quantity :</label>
                <div class="col-sm-8">
                    <input type="number" class="form-control" id="quantity" placeholder="Enter Quantity"
                           name="quantity"/>
                    <span class="text-danger" id="quantity_error"></span>
                </div>
            </div>
            <div class="form-group row">
                <label for="name" class="col-sm-4 col-form-label">Delivery Date :</label>
                <div class="col-sm-8">
                    <input type="date" class="form-control" id="delivery_date" placeholder="Enter Delivery Date" name="delivery_date"/>
                    <span class="text-danger" id="delivery_date_error"></span>
                </div>
            </div>
            <div class="form-group row">
                <label for="name" class="col-sm-4 col-form-label">Fabric Supplier :</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" id="fabric_supplier" placeholder="Enter Fabric Supplier"
                           name="fabric_supplier"/>
                    <span class="text-danger" id="fabric_supplier_error"></span>
                </div>
            </div>
            <div class="form-group row">
                <label for="name" class="col-sm-4 col-form-label">Wash Price :</label>
                <div class="col-sm-8">
                    <input type="number" class="form-control" id="wash_price" placeholder="Enter Wash Price"
                           name="wash_price"/>
                    <span class="text-danger" id="wash_price_error"></span>
                </div>
            </div>
            <div class="form-group row">
                <label for="name" class="col-sm-4 col-form-label">FOB :</label>
                <div class="col-sm-8">
                    <input type="number" class="form-control" id="fob" placeholder="Enter FOB" name="fob"/>
                    <span class="text-danger" id="fob_error"></span>
                </div>
            </div>
            <div class="form-group row">
                <label for="profile_photo" class="col-sm-4 col-form-label">Cost Sheet :</label>
                <div class="col-sm-8">
                    <input type="file" class="form-control" id="cost_sheet" name="cost_sheet"/>
                    <span class="text-danger" id="cost_sheet_error"></span>
                </div>
            </div>
            <div class="form-group row">
                <label for="name" class="col-sm-4 col-form-label">CM :</label>
                <div class="col-sm-8">
                    <input type="number" class="form-control" id="cm" placeholder="Enter CM" name="cm"/>
                    <span class="text-danger" id="cm_error"></span>
                </div>
            </div>
            <div class="form-group row">
                <label for="name" class="col-sm-4 col-form-label">CMP :</label>
                <div class="col-sm-8">
                    <input type="number" class="form-control" id="cmp" placeholder="Enter CMP" name="cmp"/>
                    <span class="text-danger" id="cmp_error"></span>
                </div>
            </div>
            <div class="form-group row">
                <label for="profile_photo" class="col-sm-4 col-form-label">SMV :</label>
                <div class="col-sm-8">
                    <input type="file" class="form-control" id="smv" name="smv"/>
                    <span class="text-danger" id="smv_error"></span>
                </div>
            </div>


            {{--<div class="form-group row">
            <label for="role_id" class="col-sm-4 col-form-label">@lang('lang.USER') :</label>
            <div class="col-sm-8">
                {!!Form::select('user_id',$users,'',['class'=>'select2 form-control','id'=>'user_id','width'=>'100%'])!!}
                    <span class="text-danger" id="user_id_error"></span>
                    </div>
                </div>--}}

                </div>
                <div class="modal-footer">
                    <fieldset class="w-100">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                        <button type="button" id="create" class="btn btn-secondary float-right">Save</button>
                    </fieldset>
                </div>
                </form>
                {!!Form::close()!!}
            </div>
        </div>



