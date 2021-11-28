<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Device;
use App\Models\Notification;
use App\Models\NotificationSend;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class NotificationController extends Controller
{
    public function allNotifications(Request $request)
    {
        $notifications = Notification::whereHas('notificationsends', function($query) use ($request) {
                $query->where('user_id', $request->user('api')->id);
            })->orderBy('id', 'DESC')->paginate(10);

        return response()->json([
            'status' => 'Success',
            'message' => 'Data Retrieved Successfully',
            'data' => $notifications
        ], Response::HTTP_OK);
    }

    public function store(Request $request)
    {
        $validator = $this->validationNotification($request);

        if ( $validator->fails() ) return response()->json([
            'status' => 'Error',
            'error' => $validator->errors()
        ], Response::HTTP_NOT_ACCEPTABLE);

       $create = Device::updateOrCreate(
            ['user_id' => $request->user_id, 'device_type' => $request->device_type],
            $request->all()
        );

        if ( $create ) {

            NotificationSend::create([
                'user_id' => $request->user_id,
                'notification_id' => $create->id,
                'read_at' => '0'
            ]);

            return response()->json([
                'status' => 'Success',
                'message' => 'Stored'
            ], Response::HTTP_OK);
        }

        return response()->json([
            'status' => 'Success',
            'error' => 'Internal Server Error'
        ], Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    private function validationNotification(Request $request)
    {
        $request->request->add(['user_id' => $request->user('api')->id]);
        return Validator::make($request->all(), [
            'user_id' => 'required',
            'device_type' => 'required',
            'role' => 'required',
            'token' => 'required',
        ]);
    }
}
