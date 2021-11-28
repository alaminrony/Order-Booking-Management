<?php

namespace App\Http\Controllers\API;

use App\Helpers\OBAValidation;
use App\Helpers\UploadFile;
use App\Http\Controllers\Controller;
use App\Models\OrderBooking;
use App\Models\OrderBookingAttachment;
use App\Models\RoleToAccess;
use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AddOBAController extends Controller
{
    use UploadFile;
    use OBAValidation;

    public function store(Request $request)
    {
        $validation = $this->validationRequest($request);

        if ( $validation->fails() ) return response()->json([
            'message' => 'Validation Error',
            'errors' => $validation->errors()
        ], Response::HTTP_NOT_ACCEPTABLE);

        try {
            $order = OrderBooking::create(
                $request->except([
                    'picture_1', 'picture_2', 'cost_sheet', 'smv'
                ])
            );

            if ( ! empty($order) ){

                $attachments = $this->getAttachedFileInfos($request);

                foreach ($attachments as $ind => $attachment){
                    $attachment['order_book_id'] = $order->id;

                    OrderBookingAttachment::create($attachment);
                }
            }

        } catch (\Throwable $exception){
            return response()->json([
                'message' => $exception->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        return response()->json([
            'message' => 'Request added successfully'
        ], Response::HTTP_OK);
    }


    public function requestList(Request $request)
    {
        $orders = OrderBooking::with([
            'buyer', 'department', 'attachments', 'user'
        ])->whereHas('user', function ($query) use ($request) {
            $query->where('id', $request->user('api')->id);
        })->get();

        return response()->json(
            ['message' => $orders]
        , Response::HTTP_OK);
    }


    public function rejectOrder(Request $request)
    {
        $validation = $this->rejectValidation($request, true);

        if ( $validation->fails() ) return response()->json([
            'message' => 'Validation Error',
            'errors' => $validation->errors()
        ], Response::HTTP_NOT_ACCEPTABLE);


        $this->setRequestValues($request);

        $request->request->add(['status' => 2]);

        return $this->createAndReturnResponse($request, 'Booking Rejected');
    }


    public function acceptOrders(Request $request)
    {
        $validation = $this->rejectValidation($request);

        if ( $validation->fails() ) return response()->json([
            'message' => 'Validation Error',
            'errors' => $validation->errors()
        ], Response::HTTP_NOT_ACCEPTABLE);

        $this->setRequestValues($request);

        $request->request->add(['status' => 1]);

        return $this->createAndReturnResponse($request, 'Booking Accepted');
    }


    private function createAndReturnResponse(Request $request, $message){
        if ( RoleToAccess::create($request->all()) ) return response()->json([
            'message' => $message
        ], Response::HTTP_OK);

        return response()->json([
            'message' => 'Error',
            'errors' => 'Something went wrong'
        ], Response::HTTP_BAD_REQUEST);
    }


    private function setRequestValues(Request $request)
    {
        $user_info = $this->getUserInfo($request);

        $request->request->add([
            'user_id' => $user_info['user_id'],
            'department_id' => $user_info['department_id'],
            'role_id' => $request->user('api')->role_id
        ]);
    }


    private function getUserInfo(Request $request){
        $user_id = $request->user('api')->id;

        $userDeparment = User::with('departments')->find($user_id);

        return ( count($userDeparment->departments) > 0 ) ?
            ['department_id' => $userDeparment->departments[0]->id, 'user_id' => $user_id] :
            response()->json([
                'message' => 'Error',
                'errors' => 'User dont have any department yet'
            ], Response::HTTP_NOT_ACCEPTABLE);
    }


    private function getAttachedFileInfos(Request $request)
    {
        $attachmentData = [];

        if ($request->hasFile('picture_1')){
            $attachmentData[0]['file_name'] = $this->upload($request, 'picture_1');
            $attachmentData[0]['attach_type'] = 'image';
            $attachmentData[0]['file_type'] = 'p_photo1';
        }

        if ($request->hasFile('picture_2')){
            $attachmentData[1]['file_name'] = $this->upload($request, 'picture_2');
            $attachmentData[1]['attach_type'] = 'image';
            $attachmentData[1]['file_type'] = 'p_photo2';
        }

        if ($request->hasFile('cost_sheet')){
            $attachmentData[2]['file_name'] = $this->upload($request, 'cost_sheet');
            $attachmentData[2]['attach_type'] = 'file';
            $attachmentData[2]['file_type'] = 'cost_sheet';
        }

        if ($request->hasFile('smv')){
            $attachmentData[3]['file_name'] = $this->upload($request, 'smv');
            $attachmentData[3]['attach_type'] = 'file';
            $attachmentData[3]['file_type'] = 'smv';
        }

        return $attachmentData;
    }
}
