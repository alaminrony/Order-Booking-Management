<?php

namespace App\Http\Controllers\API;

use App\Helpers\OBAValidation;
use App\Helpers\UploadFile;
use App\Http\Controllers\Controller;
use App\Http\Controllers\OrderBookingController;
use App\Models\BuyerDepartment;
use App\Models\OrderBooking;
use App\Models\OrderBookingAttachment;
use App\Models\RoleToAccess;
use App\Models\BuyerVsUser;
use App\Models\UserRole;
use App\Models\DepartmentVsUser;
use App\Models\OrderVsCustomField;
use App\Models\CustomField;
use App\Models\FieldHeading;
use App\Models\FieldHeadingNote;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Jetstream\Role;
use Validator;
use App\Helpers\Helper;

class AddOBAController extends Controller {

    use UploadFile;
    use OBAValidation;

    public function store(Request $request) {
        $validation = $this->validationRequest($request);

        if ($validation->fails())
            return response()->json([
                'status' => 'Error',
                'message' => 'Validation Error',
                'errors' => $validation->errors()
            ], Response::HTTP_NOT_ACCEPTABLE);


        try {
            $order = OrderBooking::create(
                $request->except([
                    'picture1', 'cost_sheet', 'smv', 'cad_consumption', 'level', 'lot_quantity', 'lot_delivery_date', 'picture2'
                ])
            );

            if (!empty($order)) {
                $attachments = $this->getAttachedFileInfos($request);

                foreach ($attachments as $ind => $attachment) {
                    $attachment['order_book_id'] = $order->id;

                    OrderBookingAttachment::create($attachment);
                }

                $code = "OBA" . date('Ym') . $order->id;
                OrderBooking::where('id', $order->id)->update(['booking_code' => $code]);

                $this->orderInsertForManager($request->user('api')->id, $order->id);

                $quantitySegmentData = [
                    'orderBookingId' => $order->id,
                    'level' => explode(',', $request->level),
                    'lot_quantity' => explode(',', $request->lot_quantity),
                    'delivery_date' => explode(',', $request->lot_delivery_date)
                ];

                $othrt = new OrderBookingController();
                $othrt->quantitySegment($quantitySegmentData);

                $othrt->sendNotification($request->user('api')->id, $code);
            }
        } catch (\Throwable $exception) {
            return response()->json([
                'status' => 'Error',
                'message' => 'Internal Server Error',
                'errors' => [
                    'server_error' => $exception->getMessage()
                ]
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        return response()->json([
            'status' => 'Success',
            'message' => 'Request added successfully'
        ], Response::HTTP_OK);
    }

    public function orderInsertForManager($user_id, $orderBookingId) {

        $orderBookingByDepartmentId = DepartmentVsUser::where('user_id', $user_id)->first()->department_id;

        if (!empty($orderBookingByDepartmentId)) {
            $allUserThisDepartment = DepartmentVsUser::where('department_id', $orderBookingByDepartmentId)->where('user_role_id', '9')->get();
        }


        $roleVsApproveData = [];
        if ($allUserThisDepartment->isNotEmpty()) {
            $i = 0;
            foreach ($allUserThisDepartment as $data) {
                $roleVsApproveData[$i]['role_id'] = $data->user_role_id;
                $roleVsApproveData[$i]['user_id'] = $data->user_id;
                $roleVsApproveData[$i]['department_id'] = $data->department_id;
                $roleVsApproveData[$i]['order_booking_id'] = $orderBookingId;
                $roleVsApproveData[$i]['status'] = '0';
                $i++;
            }
        }

        if (RoleToAccess::insert($roleVsApproveData)) {
            return true;
        } else {
            return false;
        }
    }

    public function requestList(Request $request) {
        $orders = OrderBooking::with([
            'buyer', 'department', 'attachments', 'user'
        ])->whereHas('user', function ($query) use ($request) {
            $query->where('id', $request->user('api')->id);
        })->unTouchedOBA()->get();

        return response()->json(
            [
                'status' => 'Success',
                'message' => 'Data Retrieved Successfully',
                'data' => $orders
            ]
            , Response::HTTP_OK);
    }

    public function rejectOrder(Request $request) {
        $validation = $this->rejectValidation($request, true);

        if ($validation->fails())
            return response()->json([
                'status' => 'Error',
                'message' => 'Validation Error',
                'errors' => $validation->errors()
            ], Response::HTTP_NOT_ACCEPTABLE);


        $users = $this->setRequestValues($request);

        if ($users == false) {
            return response()->json([
                'status' => 'Error',
                'message' => 'Error',
                'errors' => [
                    'error' => 'User dont have any department yet'
                ]
            ], Response::HTTP_NOT_ACCEPTABLE);
        }

        $request->request->add(['status' => 2]);

        return $this->createAndReturnResponse($request, 'Booking Rejected');
    }

    public function acceptOrders(Request $request) {

        $rules = [
            'order_booking_id' => 'required|numeric',
            'note' => 'nullable'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }

        $order = OrderBooking::where('id', $request->order_booking_id)->first();

        if ($request->user('api')->role_id == 1 || $request->user('api')->role_id == 2) {
            $order->status = 1;
            $order->save();
        }

        RoleToAccess::where(['user_id' => Auth::id(), 'order_booking_id' => $request->order_booking_id])->update(
            ['status' => '1', 'accept_note' => $request->note]
        );

        if (Auth::user()->role_id == 9) {
            return $this->roleVsApprove($request->order_booking_id, $order);
        }

        Helper::insertMDandDMD($request->order_booking_id);

        return response()->json([
            'status' => 'Success',
            'message' => 'Order Booking No. ' . $order->booking_code . " Accepted",
        ], Response::HTTP_OK);
    }

    public function roleVsApprove($orderBookingId, $order) {
        $orderBookingByDepartmentId = DepartmentVsUser::where('user_id', $order->user_id)->first()->department_id;

        if (!empty($orderBookingByDepartmentId)) {
            $allUserThisDepartment = DepartmentVsUser::where('department_id', $orderBookingByDepartmentId)->whereIn('user_role_id', [3, 4, 5])->get();
        }

        $roleVsApproveData = [];
        if ($allUserThisDepartment->isNotEmpty()) {
            $i = 0;
            foreach ($allUserThisDepartment as $data) {

                $buyerVsUser = BuyerVsUser::where([
                    'user_id' => $data->user_id,
                    'buyer_id' => $order->buyer_id
                ])->first();

                if ($buyerVsUser) {
                    $roleVsApproveData[$i]['role_id'] = $data->user_role_id;
                    $roleVsApproveData[$i]['user_id'] = $data->user_id;
                    $roleVsApproveData[$i]['department_id'] = $orderBookingByDepartmentId;
                    $roleVsApproveData[$i]['order_booking_id'] = $orderBookingId;
                    $roleVsApproveData[$i]['status'] = '0';
                    $i++;
                }
            }
        }

        if (RoleToAccess::insert($roleVsApproveData)) {
            return response()->json([
                'status' => 'Success',
                'message' => 'Order Booking No. ' . $orderBookingId . " Accepted",
            ], Response::HTTP_OK);
        }

        return response()->json([
            'status' => 'Success',
            'message' => "Not Accepted",
        ], Response::HTTP_NOT_ACCEPTABLE);
    }

    public function unFinishedOBA(Request $request) {
        $orders = OrderBooking::with([
            'buyer', 'department', 'attachments', 'user', 'segments', 'approvals.user.role'
        ])->whereHas('approvals', function ($query) use ($request) {
            $query->where([
                'user_id' => $request->user('api')->id,
                'status' => '0'
            ]);
        })->orderBy('id', 'DESC')->get();

        return response()->json(
            [
                'status' => 'Success',
                'message' => 'Data Retrieved Successfully',
                'data' => $this->addDataToOrder($orders)
            ]
            , Response::HTTP_OK);
    }

    public function finishedOBA(Request $request, $dept_id, $buyer_id, $month, $year) {
        $orders = OrderBooking::with([
            'buyer', 'attachments', 'approvals', 'segments', 'approvals.user.role'
        ])->whereHas('approvals', function ($query) use ($request, $dept_id) {
            $query->where(
                [
                    'department_id' => $dept_id,
                    'user_id' => $request->user('api')->id
                ]
            );
        })->where('buyer_id', $buyer_id)->whereMonth('created_at', $month)
            ->whereYear('created_at', $year)
            ->orderBy('id', 'DESC')
            ->get();

        return response()->json(
            [
                'status' => 'Success',
                'message' => 'Data Retrieved Successfully',
                'data' => $this->addDataToOrder($orders)
            ]
            , Response::HTTP_OK);
    }

    public function userOrders(Request $request) {
        $orders = OrderBooking::where([
            'user_id' => $request->user('api')->id
        ])->with('approvals.user.role', 'segments', 'attachments', 'buyer')
        ->orderBy('id', 'DESC')->get();
        
//        echo "<pre>";print_r($orders->toArray());exit;

        return response()->json([
            'status' => 'Success',
            'message' => 'Data Retrieved successfully',
            'data' => $this->addDataToOrder($orders)
        ]);
    }
    
    public function ordersForAssistantManager(Request $request) {
        $assistantManagerDept = DepartmentVsUser::where('user_id',$request->user('api')->id)->first();
        if(!empty($assistantManagerDept->department_id)){
             $assistantManagerDeptEmployee = DepartmentVsUser::where('department_id',$assistantManagerDept->department_id)->where('user_role_id',6)->pluck('user_id')->toArray();
        }
        $orders = OrderBooking::whereIn('user_id',$assistantManagerDeptEmployee)->with('approvals.user.role', 'segments', 'attachments', 'buyer')
        ->orderBy('id', 'DESC')->get();

        return response()->json([
            'status' => 'Success',
            'message' => 'Data Retrieved successfully',
            'data' => $this->addDataToOrder($orders)
        ]);
    }

    public function customFieldList() {
        $targets = CustomField::join('field_heading', 'field_heading.id', '=', 'custom_fields.field_heading_id')
            ->select('custom_fields.*', 'field_heading.name as field_heading_name')
            ->get();

//        echo "<pre>";print_r($targets->toArray());exit;
        return response()->json([
            'status' => 'Success',
            'message' => 'Data Retrieved successfully',
            'data' => $targets
        ]);
    }

    public function customFieldByOrderId(Request $request) {

        $targets = OrderVsCustomField::where('order_id', $request->order_id)->get();

       
        return response()->json([
            'status' => 'Success',
            'message' => 'Data Retrieved successfully',
            'data' => $targets
        ]);
    }

    public function storeCustomField(Request $request) {
        $dynamicKeyArr = explode(',', $request->fieldKey);
        $dynamicValArr = explode(',', $request->fieldVal);
//        $dynamicNoteArr = explode(',', $request->fieldNote);
        $groupKeyArr = explode(',', $request->groupKey);
        $groupNoteArr = explode(',', $request->groupNote);

        $customFieldArr = CustomField::pluck('field_heading_id', 'id')->toArray();



        $data = [];
        if (!empty($request->orderBookingId)) {
            $i = 1;
            foreach ($dynamicKeyArr as $keyIndex => $key) {
                foreach ($dynamicValArr as $valueIndex => $val) {
                    if ($keyIndex == $valueIndex) {
                        $data[$i]['order_id'] = $request->orderBookingId;
                        $data[$i]['custom_field_id'] = $key;
                        $data[$i]['value'] = $val ?? '';
                        $data[$i]['field_heading_id'] = $customFieldArr[$key] ?? '';
                        $data[$i]['created_at'] = date('Y-m-d H:i:s');
                        $data[$i]['updated_at'] = date('Y-m-d H:i:s');
                        $i++;
                    }
                }
            }
        }


        $notesData = [];
        if (!empty($request->orderBookingId)) {
            $i = 1;
            foreach ($groupKeyArr as $keyIndex => $groupId) {
                foreach ($groupNoteArr as $noteIndex => $note) {
                    if ($keyIndex == $noteIndex) {
                        $notesData[$i]['order_id'] = $request->orderBookingId;
                        $notesData[$i]['field_heading_id'] = $groupId;
                        $notesData[$i]['note'] = $note ?? '';
                        $notesData[$i]['created_at'] = date('Y-m-d H:i:s');
                        $notesData[$i]['updated_at'] = date('Y-m-d H:i:s');
                        $i++;
                    }
                }
            }
        }

//        echo "<pre>";print_r($notesData);exit;

        if (OrderVsCustomField::insert($data) && FieldHeadingNote::insert($notesData)) {
            return response()->json(['response' => 'success', 'message' => 'Successfully added']);
        } else {
            return response()->json(['response' => 'error', 'message' => 'Do Not added Successfully']);
        }
    }

    public function destroy(OrderBooking $orderBooking) {
        if ($orderBooking->delete()) {

            QuantitySegment::where('order_booking_id', $orderBooking->id)->delete();

            return response()->json([
                'status' => 'Success',
                'message' => "Order deleted"
            ]);
        }

        return response()->json([
            'status' => 'Error',
            'message' => "Something went wrong"
        ]);
    }

}
