<?php


namespace App\Helpers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

trait OBAValidation
{
    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Validation\Validator
     */
    private function validationRequest(Request $request): \Illuminate\Contracts\Validation\Validator
    {
        return Validator::make($request->all(), [
            'smv' => 'required',
            'cost_sheet' => 'required',
            'picture_2' => 'required',
            'picture_1' => 'required',
            'user_id' => 'required',
            'buyer_id' => 'required',
            'buyer_dept' => 'required',
            'style_number' => 'required',
            'quantity' => 'required',
            'delivery_date' => 'required',
            'fabric_supplier' => 'required',
            'wash_price' => 'required',
            'fob' => 'required',
            'cmp' => 'required',
            'cm' => 'required',
        ]);
    }


    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Validation\Validator
     */
    private function rejectValidation(Request $request, $checkReject = false)
    {
        return Validator::make($request->all(), [
            'order_booking_id' => 'required',
            'reject_note' => ($checkReject) ? 'required' : 'nullable'
        ]);
    }
}
