<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\OrderBooking;
use App\Models\OrderBookingAttachment;
use App\Models\User;
use App\Models\Buyer;
use App\Models\UserRole;
use App\Models\DepartmentVsUser;
use App\Models\RoleToAccess;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Validator;
use DB;
use Auth;

class OrderBookingController extends Controller {

    public function index(Request $request) {
        $targets = OrderBooking::orderBy('id', 'desc');

        if (!empty($request->search_value)) {
            $searchText = $request->search_value;
            $targets->where(function ($query) use ($searchText) {
                $query->where('dep_name', 'like', "%{$searchText}%");
            });
        }

        $targets = $targets->paginate(10);
        
         $orders = OrderBooking::with(['attachments'])->get();
        
//        echo "<pre>";print_r($orders->toArray());exit;

        $buyers = ['' => '--Select Buyer--'] + Buyer::pluck('name', 'id')->toArray();
        $users = ['' => '--Select Users--'] + User::join('roles', 'roles.id', '=', 'users.role_id')->select(DB::raw("CONCAT(users.name,' (',roles.role_name,')') as name"), 'users.id as user_id')->pluck('name', 'user_id')->toArray();

        $data['title'] = 'OrderBooking List';
        $data['meta_tag'] = 'OrderBooking page';
        $data['meta_description'] = 'OrderBooking';
        return view('backEnd.order_booking.index')->with(compact('targets', 'data', 'buyers', 'users'));
//        echo "<pre>";print_r($target->toArray());exit;
    }

    public function create(Request $request) {

        $buyers = ['' => '--Select Buyer--'] + Buyer::pluck('name', 'id')->toArray();
        $departments = ['' => '--Select Department--'] + Department::pluck('dep_name', 'id')->toArray();
        $users = ['' => '--Select Users--'] + User::join('roles', 'roles.id', '=', 'users.role_id')->select(DB::raw("CONCAT(users.name,' (',roles.role_name,')') as name"), 'users.id as user_id')->pluck('name', 'user_id')->toArray();
        $buyerDepArr = ['' => '--Select Buyer Department--', 'Men' => 'Men', 'Women' => 'Women', 'Boy' => 'Boy', 'Girl' => 'Girl', 'Infant' => 'Infant'];
        $view = view('backEnd.order_booking.createOrderBooking')->with(compact('departments', 'users', 'buyers', 'buyerDepArr'))->render();
        return response()->json(['data' => $view]);
    }

    public function store(Request $request) {

        $rules = [
            'buyer_id' => 'required',
            'picture1' => 'required',
            'style_number' => 'required',
            'buyer_dept' => 'required',
            'quantity' => 'required',
            'delivery_date' => 'required',
            'fabric_supplier' => 'required',
            'wash_price' => 'required',
            'fob' => 'required',
            'cm' => 'required',
            'cmp' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }

        $target = new OrderBooking;
        $target->user_id = Auth::user()->id;
        $target->buyer_id = $request->buyer_id;
        $target->buyer_dept = $request->buyer_dept;
        $target->style_number = $request->style_number;
        $target->quantity = $request->quantity;
        $target->delivery_date = $request->delivery_date;
        $target->fabric_supplier = $request->fabric_supplier;
        $target->wash_price = $request->wash_price;
        $target->fob = $request->fob;
        $target->cm = $request->cm;
        $target->cmp = $request->cmp;
        if ($target->save()) {
//            echo "<pre>";print_r($target->id);exit;
            if ($files = $request->file('picture1')) {
                $target2 = new OrderBookingAttachment();
                $target2->order_book_id = $target->id;

                $imagePath = 'uploads/order_booking/';
                $fileName = uniqid() . "." . date('Ymd') . "." . $files->getClientOriginalExtension();
                $dbName = $imagePath . '' . $fileName;
                $files->move(public_path($imagePath), $fileName);
                $target2->file_name = $dbName;

                $target2->attach_type = 'image';
                $target2->file_type = 'p_photo1';
                $target2->save();
            }
            if ($files = $request->file('picture2')) {
                $target3 = new OrderBookingAttachment();
                $target3->order_book_id = $target->id;

                $imagePath = 'uploads/order_booking/';
                $fileName = uniqid() . "." . date('Ymd') . "." . $files->getClientOriginalExtension();
                $dbName = $imagePath . '' . $fileName;
                $files->move(public_path($imagePath), $fileName);
                $target3->file_name = $dbName;

                $target3->attach_type = 'image';
                $target3->file_type = 'p_photo2';
                $target3->save();
            }
            if ($files = $request->file('cost_sheet')) {
                $target4 = new OrderBookingAttachment();
                $target4->order_book_id = $target->id;

                $imagePath = 'uploads/order_booking/';
                $fileName = uniqid() . "." . date('Ymd') . "." . $files->getClientOriginalExtension();
                $dbName = $imagePath . '' . $fileName;
                $files->move(public_path($imagePath), $fileName);
                $target4->file_name = $dbName;

                $target4->attach_type = 'file';
                $target4->file_type = 'cost_sheet';
                $target4->save();
            }
            if ($files = $request->file('smv')) {
                $target5 = new OrderBookingAttachment();
                $target5->order_book_id = $target->id;

                $imagePath = 'uploads/order_booking/';
                $fileName = uniqid() . "." . date('Ymd') . "." . $files->getClientOriginalExtension();
                $dbName = $imagePath . '' . $fileName;
                $files->move(public_path($imagePath), $fileName);
                $target5->file_name = $dbName;

                $target5->attach_type = 'file';
                $target5->file_type = 'smv';
                $target5->save();
            }
            $this->roleVsApprove($target->id);

            return response()->json(['response' => 'success']);
        }
    }

    public function roleVsApprove($orderBookingId) {

        $orderBookingByDepartmentId = DepartmentVsUser::where('user_id', Auth::user()->id)->first()->department_id;
        if (!empty($orderBookingByDepartmentId)) {
            $allUserThisDepartment = DepartmentVsUser::where('department_id', $orderBookingByDepartmentId)->whereNotIn('user_id', [3, 4, Auth::user()->id])->get();
        }

        $roleArr = User::pluck('role_id', 'id')->toArray();
        $roleVsApproveData = [];
        if ($allUserThisDepartment->isNotEmpty()) {
            $i = 0;
            foreach ($allUserThisDepartment as $data) {
                $roleVsApproveData[$i]['role_id'] = $roleArr[$data->user_id];
                $roleVsApproveData[$i]['user_id'] = $data->user_id;
                $roleVsApproveData[$i]['department_id'] = $data->department_id;
                $roleVsApproveData[$i]['order_booking_id'] = $orderBookingId;
                $roleVsApproveData[$i]['status'] = '0';
                $i++;
            }
        }

        $insertRow = RoleToAccess::insert($roleVsApproveData);
        if ($insertRow) {
            return true;
        } else {
            return false;
        }
    }

    public function edit(Request $request) {
        $target = OrderBooking::findOrFail($request->id);
        $buyers = ['' => '--Select Buyer--'] + Buyer::pluck('name', 'id')->toArray();
        $users = ['' => '--Select Users--'] + User::join('roles', 'roles.id', '=', 'users.role_id')->select(DB::raw("CONCAT(users.name,' (',roles.role_name,')') as name"), 'users.id as user_id')->pluck('name', 'user_id')->toArray();
        $view = view('backEnd.buyer_vs_users.editOrderBooking')->with(compact('target', 'buyers', 'users'))->render();
        return response()->json(['data' => $view]);
    }

    public function update(Request $request) {
//        echo "<pre>";print_r($request->all());exit;
        $rules = [
            'buyer_id' => 'required',
            'user_id' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }

        $target = OrderBooking::findOrFail($request->id);
        $target->buyer_id = $request->buyer_id;
        $target->user_id = $request->user_id;
        if ($target->save()) {
            return response()->json(['response' => 'success']);
        }
    }

    public function destroy(Request $request) {
        $target = OrderBooking::findOrFail($request->id);
//        echo "<pre>";print_r($target->toArray());exit;
        if ($target->delete()) {
            Session::flash('success', __('lang.BUYER_VS_USERS_DELETED_SUCCESSFULLY'));
//            return redirect()->route('head.index', ['page' => $request->get('page', 1)]);
            return redirect()->route('buyerVsUsers.index');
        }
    }

    public function filter(Request $request) {
        $url = '&search_value=' . $request->search_value;
        return redirect('admin/buyerVsUsers-list?' . $url);
    }

}
