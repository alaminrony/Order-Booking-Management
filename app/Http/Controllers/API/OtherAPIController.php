<?php

namespace App\Http\Controllers\API;

use App\Helpers\UploadFile;
use App\Http\Controllers\OrderBookingController;
use App\Models\CustomField;
use App\Models\FieldHeading;
use App\Models\OrderBookingAttachment;
use Illuminate\Support\Facades\Auth;
use PDF;
use App\Http\Controllers\Controller;
use App\Http\Controllers\OtherController;
use App\Models\Buyer;
use App\Models\BuyerDepartment;
use App\Models\Department;
use App\Models\OrderBooking;
use App\Models\User;
use App\Models\CV;
use App\Models\BuyerVsUser;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Validator;
use Mail;
use DB;

class OtherAPIController extends Controller {

    use UploadFile;

    public function userBuyer(Request $request) {
        $user = User::with('buyers')->where('id', $request->user('api')->id)->get();

        return response()->json([
                    'status' => 'Success',
                    'message' => 'Data Retrieved Successfully',
                    'data' => $user
        ]);
    }

    public function buyerByDept(Request $request, $dept_id) {

        $buyers = Buyer::join('buyer_vs_users', 'buyer_vs_users.buyer_id', 'buyers.id')->where([
                    'dept_id' => $dept_id,
                    'user_id' => $request->user('api')->id
                ])->get();

        return response()->json([
                    'status' => 'Success',
                    'message' => 'Data Retrieved Successfully',
                    'data' => $buyers
        ]);
    }

    public function userDepartments(Request $request) {
        $user = User::with('departments')->where('id', $request->user('api')->id)->get();

        return response()->json([
                    'status' => 'Success',
                    'message' => 'Data Retrieved Successfully',
                    'data' => $user
        ]);
    }

    public function userDepartmentsHistory(Request $request) {
        $departments = Department::join('department_vs_users', 'department_vs_users.department_id', 'departments.id')->where('department_vs_users.user_id', $request->user('api')->id)->get();

        return response()->json([
                    'status' => 'Success',
                    'message' => 'Data Retrieved Successfully',
                    'data' => $departments
        ]);
    }

    public function userBuyerAndDep(Request $request) {
        $user = User::with('buyers')->where('id', $request->user('api')->id)->get()->toArray();

        $buyerDep = BuyerDepartment::all()->toArray();
        return response()->json([
                    'status' => 'Success',
                    'message' => 'Data Retrieved Successfully',
                    'data' => array_merge(['user' => $user], ['buyer_dep' => $buyerDep])
        ]);
    }

    public function monthlySummery(Request $request, $value) {
        $otherController = new OtherController();
        $from = date('Y-m-d', strtotime($request->year . '-01-01'));
        $to = date('Y-m-d', strtotime($request->year . '-12-31'));

        return response()->json(
                        [
                            'Status' => 'Success',
                            'Message' => "Data Retrieved",
                            'data' => $otherController->countTotalByMonth($value, $from, $to, $request->user('api')->id)[0]
                        ]
                        , Response::HTTP_OK);
    }

    public function summery(Request $request, $year) {
        $form = date('Y-m-d H:i:s', strtotime($year . '-01-01'));
        $to = date('Y-m-d H:i:s', strtotime($year . '-12-31'));

        $authUserAllBuyers = BuyerVsUser::where('user_id', Auth::id())->pluck('buyer_id')->toArray();

        $orders = OrderBooking::with('segments')->whereBetween('created_at', [$form, $to]);
        if ($request->user('api')->role_id != 7) {
            $orders = $orders->whereIn('buyer_id', $authUserAllBuyers);
        }
        if ($request->user('api')->role_id == 4 || $request->user('api')->role_id == 5 || $request->user('api')->role_id == 3) {

            $orders->whereHas('approvals', function ($query) use ($request) {
                $query->where('user_id', $request->user('api')->id);
            });
        }
        $orders->where('status', '1');
        return response()->json(
                        [
                            'Status' => 'Success',
                            'Message' => "Data Retrieved",
                            'data' => $orders->get()
                        ]
                        , Response::HTTP_OK);
    }

    public function summeryPDFLocal(Request $request, $year) {
        $form = date('Y-m-d H:i:s', strtotime($year . '-01-01'));
        $to = date('Y-m-d H:i:s', strtotime($year . '-12-31'));

        $authUserAllBuyers = BuyerVsUser::where('user_id', Auth::id())->pluck('buyer_id')->toArray();
        $orders = OrderBooking::with('segments')->whereBetween('created_at', [$form, $to]);
        if (Auth::user()->role_id  != 7) {
            $orders = $orders->whereIn('buyer_id', $authUserAllBuyers);
        }
        if (Auth::user()->role_id == 4 || Auth::user()->role_id == 5 || Auth::user()->role_id == 3) {
            $orders->whereHas('approvals', function ($query) use ($request) {
                $query->where('user_id', Auth::id());
            });
        }

        $orders->where('status', '1');

        $pdf = PDF::loadView('backEnd.layouts.list_pdf', [
                    'targets' => $orders->get()
        ]);

        $now = time() . '_summery_listing_' . Auth::id() . '.pdf';

        $pdf->setPaper('legal', 'landscape');
        $path = public_path('uploads/pdf/' . $now);
        $pdf->save($path);

        return redirect(url(asset('uploads/pdf') . '/' . $now));
    }

    public function createPDF($value, $year, Request $request) {
        $from = date('Y-m-d', strtotime($year . '-01-01'));
        $to = date('Y-m-d', strtotime($year . '-12-31'));
        // retreive all records from db
        $otherController = new OtherController();
        $data = $otherController->countTotalByMonth($value, $from, $to, $request->user('api')->id);

        $monthNameArr = [
            '01' => "Jan",
            '02' => "Feb",
            '03' => "March",
            '04' => "April",
            '05' => "May",
            '06' => "June",
            '07' => "July",
            '08' => "Aug",
            '09' => "Sep",
            '10' => "Oct",
            '11' => "Nov",
            '12' => "Dec",
        ];

        $pdf = PDF::loadView('backEnd.layouts.summerypdf', [
                    'reports' => $data[0],
                    'type' => $value,
                    //'monthTotal' => $data[1],
                    'monthArr' => $monthNameArr,
        ]);

        $now = time() . '_summery_' . $value . '_' . $request->user('api')->id . '.pdf';

        $pdf->setPaper('legal', 'landscape');
        $path = public_path('uploads/pdf/' . $now);
        $pdf->save($path);

        return response()->json(
                        [
                            'Status' => 'Success',
                            'Message' => "Data Retrieved",
                            'data' => url(asset('uploads/pdf') . '/' . $now)
                        ]
                        , Response::HTTP_OK);
    }

    public function summeryByDep($year, Request $request) {
        $from = date('Y-m-d', strtotime($year . '-01-01'));
        $to = date('Y-m-d', strtotime($year . '-12-31'));
        // retreive all records from db
        $otherController = new OtherController();
        $data = $otherController->departmentSummery($from, $to);

        $monthNameArr = [
            '01' => "Jan",
            '02' => "Feb",
            '03' => "March",
            '04' => "April",
            '05' => "May",
            '06' => "June",
            '07' => "July",
            '08' => "Aug",
            '09' => "Sep",
            '10' => "Oct",
            '11' => "Nov",
            '12' => "Dec",
        ];

        $pdf = PDF::loadView('backEnd.layouts.by_department', [
                    'reports' => $data[0],
                    'monthTotal' => $data[1],
                    'monthArr' => $monthNameArr
        ]);

        $now = time() . '_by_department_summery' . $request->user('api')->id . '.pdf';

        $pdf->setPaper('legal', 'landscape');
        $path = public_path('uploads/pdf/' . $now);
        $pdf->save($path);

        return response()->json(
                        [
                            'Status' => 'Success',
                            'Message' => "Data Retrieved",
                            'data' => url(asset('uploads/pdf') . '/' . $now)
                        ]
                        , Response::HTTP_OK);
    }

    public function summeryPDF(Request $request, $year) {
        $form = date('Y-m-d H:i:s', strtotime($year . '-01-01'));
        $to = date('Y-m-d H:i:s', strtotime($year . '-12-31'));
        
        $authUserAllBuyers = BuyerVsUser::where('user_id', Auth::id())->pluck('buyer_id')->toArray();

        $orders = OrderBooking::with('segments')->whereBetween('created_at', [$form, $to]);

        if ($request->user('api')->role_id != 7) {
            $orders = $orders->whereIn('buyer_id', $authUserAllBuyers);
        }
        if ($request->user('api')->role_id == 4 || $request->user('api')->role_id == 5 || $request->user('api')->role_id == 3) {
            $orders->whereHas('approvals', function ($query) use ($request) {
                $query->where('user_id', $request->user('api')->id);
            });
        }

        $orders->where('status', '1');

        $pdf = PDF::loadView('backEnd.layouts.list_pdf', [
                    'targets' => $orders->get()
        ]);

        $now = time() . '_summery_listing_' . $request->user('api')->id . '.pdf';

        $pdf->setPaper('legal', 'landscape');
        $path = public_path('uploads/pdf/' . $now);
        $pdf->save($path);

        return response()->json(
                        [
                            'Status' => 'Success',
                            'Message' => "Data Retrieved",
                            'data' => url(asset('uploads/pdf') . '/' . $now)
                        ]
                        , Response::HTTP_OK);
    }

    public function editOrder(Request $request, OrderBooking $orderBooking) {

        if ($request->buyer_id != null)
            $orderBooking->buyer_id = $request->buyer_id;

        if ($request->buyer_dept != null) {
            if (is_numeric($request->buyer_dept)) {
                $buyer = BuyerDepartment::find($request->buyer_dept)->name;
                $orderBooking->buyer_dept = $buyer;
            } else
                $orderBooking->buyer_dept = $request->buyer_dept;
        }


        if ($request->fabric_supplier != null)
            $orderBooking->fabric_supplier = $request->fabric_supplier;
        if ($request->wash_price != null)
            $orderBooking->wash_price = $request->wash_price;
        if ($request->fob != null)
            $orderBooking->fob = $request->fob;
        if ($request->cm != null)
            $orderBooking->cm = $request->cm;
        if ($request->cmp != null)
            $orderBooking->cmp = $request->cmp;
        if ($request->smv_value != null)
            $orderBooking->smv_value = $request->smv_value;
        if ($request->style_number != null)
            $orderBooking->style_number = $request->style_number;

        if ($orderBooking->save()) {
            if ($files = $request->file('picture1')) {
                $target2 = OrderBookingAttachment::where([
                            'order_book_id' => $orderBooking->id,
                            'attach_type' => 'image',
                            'file_type' => 'p_photo1'
                        ])->first();

                if ($target2) {
                    $target2->file_name = $this->upload($request, 'picture1');

                    $target2->save();
                } else
                    $this->createAttachment($orderBooking->id, 'image', 'p_photo', $request, 'picture1');
            }
            if ($files = $request->file('picture2')) {
                $target3 = OrderBookingAttachment::where([
                            'order_book_id' => $orderBooking->id,
                            'attach_type' => 'image',
                            'file_type' => 'p_photo2'
                        ])->first();

                if ($target3) {
                    $target3->file_name = $this->upload($request, 'picture2');

                    $target3->save();
                } else
                    $this->createAttachment($orderBooking->id, 'image', 'p_photo2', $request, 'picture2');
            }
            if ($files = $request->file('cost_sheet')) {
                $target4 = OrderBookingAttachment::where([
                            'order_book_id' => $orderBooking->id,
                            'attach_type' => 'file',
                            'file_type' => 'cost_sheet'
                        ])->first();

                if ($target4) {
                    $target4->file_name = $this->upload($request, 'cost_sheet');

                    $target4->save();
                } else
                    $this->createAttachment($orderBooking->id, 'file', 'cost_sheet', $request, 'cost_sheet');
            }
            if ($files = $request->file('smv')) {
                $target5 = OrderBookingAttachment::where([
                            'order_book_id' => $orderBooking->id,
                            'attach_type' => 'file',
                            'file_type' => 'smv'
                        ])->first();

                if ($target5) {
                    $target5->file_name = $this->upload($request, 'smv');

                    $target5->save();
                } else
                    $this->createAttachment($orderBooking->id, 'file', 'smv', $request, 'smv');
            }

            if ($files = $request->file('cad_consumption')) {
                $target5 = OrderBookingAttachment::where([
                            'order_book_id' => $orderBooking->id,
                            'attach_type' => 'file',
                            'file_type' => 'cad_consumption'
                        ])->first();

                if ($target5) {
                    $target5->file_name = $this->upload($request, 'cad_consumption');
                    $target5->save();
                } else
                    $this->createAttachment($orderBooking->id, 'file', 'cad_consumption', $request, 'cad_consumption');
            }

            if (!empty($request->level) && !empty($request->lot_quantity) && !empty($request->lot_delivery_date)) {
                $quantitySegmentData = [
                    'orderBookingId' => $orderBooking->id,
                    'level' => explode(',', $request->level),
                    'lot_quantity' => explode(',', $request->lot_quantity),
                    'delivery_date' => explode(',', $request->lot_delivery_date)
                ];

                $othrt = new OrderBookingController();

                if ($othrt->quantitySegment($quantitySegmentData))
                    return response()->json(['response' => 'success', 'message' => 'Successfully Updates']);

                return response()->json(['response' => 'Error', 'message' => 'Something went wrong .'], Response::HTTP_INTERNAL_SERVER_ERROR);
            }

            return response()->json(['response' => 'success', 'message' => 'Successfully Updates']);
        }

        return response()->json([
                    'Status' => 'Error',
                    'Message' => 'Something went wrong'
                        ], Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    private function createAttachment($order_id, $attach_type, $p_photo1, Request $request, $image_name) {
        $target2 = new OrderBookingAttachment();
        $target2->order_book_id = $order_id;

        $target2->file_name = $this->upload($request, $image_name);

        $target2->attach_type = $attach_type;
        $target2->file_type = $p_photo1;
        $target2->save();
    }

    public function sendCV(Request $request) {
        $rules = [
            'subject' => 'required|string',
            'message' => 'required|string',
        ];

        if (!empty($request->file('attachment'))) {
            $rules['attachment'] = ['mimes:docx,pdf'];
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }

        $target = new CV;
        $target->subject = $request->subject;
        $target->message = $request->message;
        if ($files = $request->file('attachment')) {
            $fileName = uniqid() . "." . date('Ymd') . "." . $files->getClientOriginalExtension();
            $filePath = 'uploads/cv/';
            $files->move($filePath, $fileName);
        }
        $target->attachment = $fileName;

        if ($target->save()) {
            $toEmail = 'ids@goldeninfotech.com.bd';
            $subject = $target->subject;
            $data = [
                'toEmail' => $toEmail,
                'subject' => $subject,
            ];

            $file = public_path('/uploads/cv/' . $target->attachment);

            Mail::send('email-template.cv', $data, function ($message) use ($toEmail, $subject, $file) {
                $message->to($toEmail)->subject($subject);
                $message->attach($file);
            });
            return response()->json([
                        'status' => 'Success',
                        'message' => 'Mail Send Successfully',
                            ], Response::HTTP_OK);
        }
    }

    public function privacyPolicy(Request $request) {
        $data = DB::table('pages')->where('id', 1)->first();
        if (!empty($data)) {
            return response()->json([
                        'status' => 'Success',
                        'message' => 'Data found',
                        'data' => $data
                            ], Response::HTTP_OK);
        }
    }

    public function customFieldsByOrder(Request $request, $id) {
        $fields = CustomField::join('field_heading', 'field_heading.id', '=', 'custom_fields.field_heading_id')
                ->select('custom_fields.*', 'field_heading.name')
                ->get();

        $groupWiseFieldArr = [];
        if ($fields->isNotEmpty()) {
            foreach ($fields as $field) {
                $groupWiseFieldArr[$field->field_heading_id][$field->id]['id'] = $field->id;
                $groupWiseFieldArr[$field->field_heading_id][$field->id]['fkey'] = $field->fkey;
                $groupWiseFieldArr[$field->field_heading_id][$field->id]['type'] = $field->type;
                $groupWiseFieldArr[$field->field_heading_id][$field->id]['created_at'] = date('Y-m-d H:i:s', strtotime($field->created_at));
                $groupWiseFieldArr[$field->field_heading_id][$field->id]['updated_at'] = date('Y-m-d H:i:s', strtotime($field->updated_at));
                $groupWiseFieldArr[$field->field_heading_id][$field->id]['field_heading_id'] = $field->field_heading_id;
                $groupWiseFieldArr[$field->field_heading_id][$field->id]['field_heading_name'] = $field->name;
            }
        }

        $fieldHeadingArr = FieldHeading::pluck('name', 'id')->toArray();

        $dynamicData = DB::table('order_vs_custom_fields')->where('order_id', $id)->get();
        $targetData = [];
        if ($dynamicData->isNotEmpty()) {
            foreach ($dynamicData as $data) {
                $targetData[$data->custom_field_id][] = $data->value;
            }
        }



        $indexCountArr = [];
        if ($fields->isNotEmpty()) {
            foreach ($fields as $field) {
                $indexCountArr[$field->field_heading_id][] = $field->id;
            }
        }

        $indexCount = [];
        if (!empty($indexCountArr)) {
            foreach ($indexCountArr as $headingId => $fiendNumber) {
                $indexCount[$headingId] = count($fiendNumber);
            }
        }

        $customFieldCountByGroupId = [];
        if (!empty($indexCount)) {
            foreach ($indexCount as $headingId => $fiendNumber) {
                $totalNumber = DB::table('order_vs_custom_fields')->where('order_id', $id)->where('field_heading_id', $headingId)->count();
                $customFieldCountByGroupId[$headingId] = $totalNumber / $indexCount[$headingId];
            }
        }

        $noteData = DB::table('field_heading_note')->where('order_id', $request->id)->get();
        $noteDataArr = [];
        if (!empty($noteData)) {
            foreach ($noteData as $headingId => $note) {
                $noteDataArr[$note->field_heading_id][$note->index_no] = $note->note;
            }
        }

        $supplierData = DB::table('field_heading_suppliers')->where('order_id', $request->id)->get();
        $supplierDataArr = [];
        if (!empty($supplierData)) {
            foreach ($supplierData as $headingId => $suppliers) {
                $supplierDataArr[$suppliers->field_heading_id][$suppliers->index_no] = $suppliers->supplier_name;
            }
        }
        $orderId = $id;
        //$view = view('backEnd.layouts.dynamic_fields_pdf', compact('groupWiseFieldArr', 'fieldHeadingArr', 'orderId', 'targetData', 'customFieldCountByGroupId'));

        $pdf = PDF::loadView('backEnd.layouts.dynamic_fields_pdf', [
                    'orderId' => $orderId,
                    'groupWiseFieldArr' => $groupWiseFieldArr,
                    'fieldHeadingArr' => $fieldHeadingArr,
                    'targetData' => $targetData,
                    'customFieldCountByGroupId' => $customFieldCountByGroupId,
                    'noteDataArr' => $noteDataArr,
                    'supplierDataArr' => $supplierDataArr,
        ]);

        $now = time() . '_summery_listing_' . $request->user('api')->id . '.pdf';

        $pdf->setPaper('legal', 'landscape');
        $path = public_path('uploads/pdf/' . $now);
        $pdf->save($path);

        return response()->json(
                        [
                            'Status' => 'Success',
                            'Message' => "Data Retrieved",
                            'data' => url(asset('uploads/pdf') . '/' . $now)
                        ]
                        , Response::HTTP_OK);
    }

}
